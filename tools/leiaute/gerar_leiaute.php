<?php

/**
 * Gera a pasta de JSONs de um leiaute a partir da referência extraída dos PDFs
 * oficiais e dos JSONs de um leiaute anterior da biblioteca.
 *
 * uso: php tools/leiaute/gerar_leiaute.php <referencia.json> <pasta-base> <pasta-destino> <ajustes.json> [relatorio.md]
 *
 * Para cada registro do leiaute:
 * - a lista e a ordem dos campos vêm da referência; o nome do JSON-base é
 *   mantido quando o do PDF só está cortado na coluna;
 * - a definição do campo existente é aproveitada e corrigida onde diverge do
 *   leiaute (tipo inválido, campo C como número, casas decimais, regex
 *   quebrada ou de tamanho errado, regex que recusa valor válido);
 * - campo novo nasce da referência (tipo, tamanho, decimais, valores válidos);
 * - por fim valem os ajustes manuais (ajustes.json), para o que o PDF não
 *   resolve sozinho: nome cortado, obrigatoriedade, registro que o extrator não leu.
 *
 * ajustes.json:
 * {
 *   "referencia": { "C185": [ {"nome": "...", "tipo": "C", "tam": 60, "fixo": false, "dec": null}, ... ] },
 *   "sem_auditoria": { "B035": "motivo" },
 *   "campos": { "D700": { "07": { "ref": {"tipo": "C"}, "json": {"required": true} } } }
 * }
 * Em "referencia" a lista não inclui o campo 01 REG. Em "campos", "ref" corrige
 * a referência antes da geração e "json" sobrescreve a definição gerada.
 * Chaves que começam com "_" são comentário.
 */

declare(strict_types=1);

require __DIR__ . '/funcoes.php';

if ($argc < 5) {
    fwrite(STDERR, "uso: php gerar_leiaute.php <referencia.json> <pasta-base> <pasta-destino> <ajustes.json> [relatorio.md]\n");
    exit(1);
}
$referencia = json_decode((string) file_get_contents($argv[1]), true);
$pastaBase = rtrim($argv[2], '/\\');
$pastaDestino = rtrim($argv[3], '/\\');
$ajustes = json_decode((string) file_get_contents($argv[4]), true);
$relatorio = $argv[5] ?? null;
if (!is_array($referencia) || !is_array($ajustes)) {
    fwrite(STDERR, "referência ou ajustes com JSON inválido\n");
    exit(1);
}

/**
 * @param array<string, mixed> $campo
 * @param array<string, mixed>|null $definicao
 * @return array{0: array<string, mixed>, 1: list<string>}
 */
function gerarDefinicao(array $campo, ?array $definicao): array
{
    if ($definicao === null) {
        return [[
            'type' => $campo['tipo'] === 'N' ? 'numeric' : 'string',
            'regex' => regexDoCampo($campo),
            'required' => false,
            'info' => '',
            'format' => formatoDoCampo($campo),
        ], ['novo']];
    }
    $d = $definicao;
    $mudou = [];
    $d['type'] = (string) ($d['type'] ?? '');
    $d['regex'] = (string) ($d['regex'] ?? '');
    $d['format'] = (string) ($d['format'] ?? '');
    $d['required'] = (bool) ($d['required'] ?? false);
    $d['info'] = (string) ($d['info'] ?? '');

    if (!in_array($d['type'], ['string', 'numeric', 'integer'], true)
        || ($campo['tipo'] === 'C' && $d['type'] !== 'string')
    ) {
        $d['type'] = $campo['tipo'] === 'N' ? 'numeric' : 'string';
        $d['regex'] = regexDoCampo($campo);
        $d['format'] = formatoDoCampo($campo);
        $mudou[] = 'tipo';
    }

    $decimaisAtuais = preg_match('/v(\d+)/', $d['format'], $f) ? (int) $f[1] : null;
    if ($campo['tipo'] === 'N' && $campo['dec'] !== null && $decimaisAtuais !== $campo['dec']) {
        $d['type'] = 'numeric';
        $d['format'] = formatoDoCampo($campo, $d['format']);
        $d['regex'] = regexDoCampo($campo);
        $mudou[] = 'decimais';
    } elseif ($campo['dec'] === null && $decimaisAtuais !== null) {
        $d['format'] = '';
        $d['regex'] = regexDoCampo($campo);
        $mudou[] = 'decimais';
    }

    $maximo = tamanhoMaximo($d['regex']);
    if (str_contains($d['regex'], '{0}') || ($campo['tam'] !== null && $maximo !== null && $maximo !== $campo['tam'])) {
        $d['regex'] = regexDoCampo($campo);
        $mudou[] = 'tamanho';
    }

    if (!empty($campo['valores']) && $d['regex'] !== '') {
        foreach ($campo['valores'] as $valor) {
            if (@preg_match('/' . $d['regex'] . '/', $valor) !== 1) {
                $d['regex'] = regexDoCampo($campo);
                $mudou[] = 'valores válidos';
                break;
            }
        }
    }

    if (!empty($campo['regex']) && $d['regex'] !== $campo['regex']) {
        $d['regex'] = (string) $campo['regex'];
        $mudou[] = 'regex do leiaute';
    }
    return [$d, $mudou];
}

if (!is_dir($pastaDestino) && !mkdir($pastaDestino, 0777, true)) {
    fwrite(STDERR, "não consegui criar $pastaDestino\n");
    exit(1);
}

$registros = registrosComAjustes($referencia, $ajustes);
$semAuditoria = $ajustes['sem_auditoria'] ?? [];
$log = [];
$erros = [];
$codigos = array_unique(array_merge(array_keys($registros), array_keys($referencia['sem_extracao'] ?? []), array_keys($semAuditoria)));
sort($codigos, SORT_STRING);

foreach ($codigos as $reg) {
    $reg = (string) $reg;
    if (preg_match(TOTALIZADORES_EFD, $reg)) {
        continue;
    }
    $arquivoBase = "$pastaBase/$reg.json";
    $base = is_file($arquivoBase) ? json_decode((string) file_get_contents($arquivoBase), true) : null;

    if (isset($semAuditoria[$reg]) || !isset($registros[$reg])) {
        if ($base === null) {
            $erros[] = "$reg: sem referência e sem JSON-base; informe em ajustes.referencia";
            continue;
        }
        copy($arquivoBase, "$pastaDestino/$reg.json");
        $log[$reg][] = 'copiado do leiaute-base SEM AUDITORIA: ' . ($semAuditoria[$reg] ?? 'extrator não leu a tabela');
        continue;
    }

    $campos = array_values(array_filter($registros[$reg]['campos'], static fn(array $c): bool => $c['n'] > 1));
    $nomesRef = array_map(static fn(array $c): string => $c['nome'], $campos);
    $nomesBase = $base === null ? [] : array_keys($base);
    $saida = [];
    foreach (alinharCampos($nomesRef, $nomesBase) as [$i, $j]) {
        if ($i === null) {
            $log[$reg][] = "campo {$nomesBase[$j]} removido (não existe no leiaute)";
            continue;
        }
        $c = $campos[$i];
        $definicao = null;
        $nome = $c['nome'];
        if ($j !== null && nomesEquivalentesCampo($c['nome'], $nomesBase[$j])) {
            $definicao = $base[$nomesBase[$j]];
            if (empty($c['nome_ajustado']) && strlen(nomeNormal((string) $nomesBase[$j])) >= strlen(nomeNormal($c['nome']))) {
                $nome = (string) $nomesBase[$j];
            }
        } elseif ($j !== null) {
            $log[$reg][] = sprintf('campo %02d: %s no lugar de %s', $c['n'], $c['nome'], $nomesBase[$j]);
        }
        if (str_ends_with($nome, '_')) {
            $erros[] = sprintf('%s campo %02d: nome cortado no PDF (%s) sem correspondência; informe em ajustes.campos', $reg, $c['n'], $nome);
        }
        [$gerada, $mudou] = gerarDefinicao($c, $definicao);
        $ajusteJson = $ajustes['campos'][$reg][sprintf('%02d', $c['n'])]['json'] ?? null;
        if ($ajusteJson !== null) {
            $gerada = $ajusteJson + $gerada;
            $mudou[] = 'ajuste manual';
        }
        if ($mudou !== []) {
            $log[$reg][] = sprintf('campo %02d %s: %s', $c['n'], $nome, implode(', ', $mudou));
        }
        if (isset($saida[$nome])) {
            $erros[] = sprintf('%s campo %02d: nome %s repetido; informe em ajustes.campos', $reg, $c['n'], $nome);
        }
        $saida[$nome] = $gerada;
    }
    if ($base === null) {
        $log[$reg][] = 'registro novo na biblioteca';
    }
    file_put_contents("$pastaDestino/$reg.json", json_encode($saida, JSON_PRETTY_PRINT));
}

ksort($log, SORT_STRING);
$md = '# Geração de ' . basename($pastaDestino) . ' a partir de ' . basename($pastaBase) . "\n\n";
if ($erros !== []) {
    $md .= "## Pendências (a geração não está completa)\n\n- " . implode("\n- ", $erros) . "\n\n";
}
foreach ($log as $reg => $itens) {
    $md .= "## $reg\n\n- " . implode("\n- ", $itens) . "\n\n";
}
if ($relatorio !== null) {
    file_put_contents($relatorio, $md);
}
printf(
    "%s: %d arquivos gravados, %d registros com mudança, %d pendências\n",
    basename($pastaDestino),
    count(glob("$pastaDestino/*.json") ?: []),
    count($log),
    count($erros)
);
foreach ($erros as $erro) {
    echo "  PENDENTE $erro\n";
}
exit($erros === [] ? 0 : 2);
