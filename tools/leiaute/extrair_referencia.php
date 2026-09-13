<?php

/**
 * Extrai a referência de um leiaute da EFD ICMS/IPI do texto dos PDFs oficiais
 * (pdftotext -enc UTF-8 -layout) e grava um JSON com os campos de cada registro.
 *
 * uso: php tools/leiaute/extrair_referencia.php <leiaute> <nt.txt|-> <guia.txt> <saida.json>
 *      php tools/leiaute/extrair_referencia.php <leiaute> <nt.txt|-> <guia.txt> --depurar=C100
 *
 * A NT traz a tabela de campos mais limpa; o Guia Prático cobre registros que a
 * NT não traz e as linhas "Valores válidos". Em parte dos registros a tabela sai
 * embaralhada (descrição fora do lugar), mas nomes de campo e trincas
 * Tipo/Tam/Dec saem na ordem. Por isso o pareamento é por posição e só vale
 * quando a numeração é contínua e a quantidade de trincas bate.
 */

declare(strict_types=1);

$depurar = null;
$posicionais = [];
foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--depurar=')) {
        $depurar = substr($arg, strlen('--depurar='));
    } else {
        $posicionais[] = $arg;
    }
}
if (count($posicionais) < ($depurar === null ? 4 : 3)) {
    fwrite(STDERR, "uso: php extrair_referencia.php <leiaute> <nt.txt|-> <guia.txt> <saida.json> [--depurar=REG]\n");
    exit(1);
}
[$leiaute, $ntArquivo, $guiaArquivo] = $posicionais;
$saida = $posicionais[3] ?? null;

const RE_CABECALHO = '/^\s*REGISTRO\s+([0-9A-Z]{4})\s*[:\-–]\s*(.*)$/u';
const RE_CABECALHO_TABELA = '/N[º°o]\.?\s+Campo/u';
// o nome pode vir com asterisco de nota de rodapé (MES_REF*)
const RE_LINHA_CAMPO = '/^\s*(\d{1,3})\s+(\p{Lu}[\p{Lu}0-9_]*(?:-?e)?(?:_[\p{Lu}0-9_]+)?)\*?(?=\s|$)/u';
// trinca Tipo/Tam/Dec no fim da linha. O tamanho é possessivo ("N 009" não vira
// tamanho 00 com 9 decimais) e a coluna Dec às vezes vem vazia ("C 021");
// o Guia ainda põe colunas de obrigatoriedade depois.
// Dec vazio só com tamanho numérico: "N - Não" (valores válidos S/N) não é trinca.
const RE_TRINCA = '/(?<![\p{L}\p{N}])([CN])\s*(?:(\d{1,3}+\*?)(?:\s*(-|\d{1,3}))?|(-)\s*(-|\d{1,3}))(?:\s+(?:OC|O|N|-|Não|Apresentar|apresentar))*\s*$/u';
const RE_CONTINUACAO_NOME = '/^(\s*)([\p{Lu}_][\p{Lu}0-9_]*)(?=\s|$)/u';
const RE_NIVEL = '/N[íi]vel\s+hier[áa]rquico\s*[-–:]\s*(\d)/u';
const RE_OCORRENCIA = '/Ocorr[êe]ncia\s*[-–:]?\s*(.+?)(?:\s{2,}|\s*$)/u';
const RE_ENUMERACAO = '/^\s{6,}([0-9A-Z]{1,3})\s*[-–:]\s+\S/u';
const RE_VALIDOS = '/Campo\s+(\d{1,3})\s*\(([A-Za-z_0-9]+)\)\s*[-–]\s*Valor(?:es)?\s+[Vv][áa]lidos?\s*:\s*\[([^\]]*)\]/u';
const RE_VALIDOS_A_PARTIR = '/A\s+partir\s+de\s+[\d\/]+\s*:\s*Valor(?:es)?\s+[Vv][áa]lidos?\s*:\s*\[([^\]]*)\]/u';

/**
 * @return array<string, list<list<string>>> linhas de cada ocorrência de cabeçalho, por registro
 */
function secoes(string $arquivo): array
{
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
    if ($linhas === false) {
        throw new RuntimeException("não consegui ler $arquivo");
    }
    $linhas = array_map(static fn(string $l): string => str_replace("\f", '', $l), $linhas);
    $inicios = [];
    foreach ($linhas as $i => $linha) {
        if (preg_match(RE_CABECALHO, $linha, $m)) {
            $inicios[] = [$i, $m[1]];
        }
    }
    $secoes = [];
    foreach ($inicios as $k => [$inicio, $reg]) {
        $fim = $inicios[$k + 1][0] ?? count($linhas);
        $secoes[$reg][] = array_slice($linhas, $inicio, $fim - $inicio);
    }
    return $secoes;
}

/**
 * O PDF traz alguns nomes de campo com acento (VL_RETENÇAO_ST); o nome oficial é ASCII.
 */
function nomeAscii(string $nome): string
{
    return strtr($nome, [
        'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I',
        'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ç' => 'C',
    ]);
}

/**
 * Nome comprido quebra na linha de baixo, na coluna do nome, antes da coluna de
 * descrição (VL_UNIT_ICMS_OP_ESTOQUE_CO / NV_SAIDA).
 *
 * @param array<int, array{0: string, 1: int}> $captura PREG_OFFSET_CAPTURE da linha do campo
 */
function continuacaoDoNome(string $linha, array $captura, ?string $proxima): string
{
    if ($proxima === null || preg_match(RE_LINHA_CAMPO, $proxima)
        || !preg_match(RE_CONTINUACAO_NOME, $proxima, $cont)
        || preg_match('/^\s*[\p{Lu}0-9_]+\s*[-–:]\s/u', $proxima)
    ) {
        return '';
    }
    $inicioNome = $captura[2][1];
    $fimNome = $inicioNome + strlen($captura[2][0]);
    $inicioDescricao = preg_match('/[^\s*]/', $linha, $d, PREG_OFFSET_CAPTURE, $fimNome) ? $d[0][1] : strlen($linha);
    $coluna = strlen($cont[1]);
    return $coluna < $inicioDescricao && $coluna >= $inicioNome - 4 ? $cont[2] : '';
}

/**
 * @param list<string> $linhas
 * @return array<string, mixed>
 */
function analisar(array $linhas, bool $depurar = false): array
{
    $titulo = preg_match(RE_CABECALHO, $linhas[0], $m) ? trim($m[2]) : '';
    $inicio = 0;
    foreach ($linhas as $i => $linha) {
        if (preg_match(RE_CABECALHO_TABELA, $linha)) {
            $inicio = $i + 1;
            break;
        }
    }
    $campos = [];
    $trincas = [];
    $enumeracoes = [];
    $trincaNaLinhaDoCampo = 0;
    $nivel = null;
    $ocorrencia = null;
    $atual = null;
    $encerrada = false;
    $total = count($linhas);
    for ($i = $inicio; $i < $total; $i++) {
        $linha = $linhas[$i];
        $marca = '';
        if (!$encerrada && preg_match(RE_NIVEL, $linha, $m)) {
            // em página embaralhada o "Nível hierárquico" sai antes das últimas trincas
            $nivel = (int) $m[1];
            $encerrada = true;
            $marca .= 'F';
        }
        if ($encerrada && $ocorrencia === null && preg_match(RE_OCORRENCIA, $linha, $o)) {
            $ocorrencia = trim($o[1]);
        }
        $ehCampo = !$encerrada
            && preg_match(RE_LINHA_CAMPO, $linha, $c, PREG_OFFSET_CAPTURE)
            && (int) $c[1][0] === count($campos) + 1;
        $temTrinca = preg_match(RE_TRINCA, $linha, $t) === 1;
        if ($ehCampo) {
            $nome = $c[2][0] . continuacaoDoNome($linha, $c, $linhas[$i + 1] ?? null);
            $campos[] = nomeAscii($nome);
            $atual = count($campos) - 1;
            $marca .= 'C';
            if ($temTrinca) {
                $trincaNaLinhaDoCampo++;
            }
        }
        if ($temTrinca && (!$encerrada || count($trincas) < count($campos))) {
            // grupos 2/3: tamanho numérico e Dec opcional; grupos 4/5: tamanho "-" e Dec
            $numerico = ($t[2] ?? '') !== '';
            $tam = $numerico ? $t[2] : $t[4];
            $dec = $numerico ? ($t[3] ?? '') : ($t[5] ?? '');
            $trincas[] = [$t[1], $tam, $dec === '' ? '-' : $dec];
            $marca .= 'T';
        } elseif (!$encerrada && !$ehCampo && $atual !== null && preg_match(RE_ENUMERACAO, $linha, $e)) {
            $enumeracoes[$atual][] = $e[1];
            $marca .= 'V';
        }
        if ($depurar) {
            printf("%-3s| %s\n", $marca, $linha);
        }
        if ($encerrada && $ocorrencia !== null && count($trincas) >= count($campos)) {
            break;
        }
    }
    $limpa = $campos !== [] && $trincaNaLinhaDoCampo === count($campos);
    return [
        'titulo' => $titulo,
        'nomes' => $campos,
        'trincas' => $trincas,
        'enumeracoes' => $limpa ? $enumeracoes : [],
        'limpa' => $limpa,
        'nivel' => $nivel,
        'ocorrencia' => $ocorrencia,
        'ok' => $campos !== [] && count($campos) === count($trincas),
    ];
}

/**
 * Valores válidos declarados nas regras de validação do Guia: [nome do campo => valores].
 * Uma linha "A partir de dd/mm/aaaa: Valores válidos" substitui a do campo anterior.
 *
 * @param list<string> $linhas
 * @return array<string, list<string>>
 */
function valoresValidos(array $linhas): array
{
    $valores = [];
    $ultimo = null;
    foreach ($linhas as $linha) {
        if (preg_match(RE_VALIDOS, $linha, $m)) {
            $ultimo = strtoupper($m[2]);
            $valores[$ultimo] = listaDeValores($m[3]);
        } elseif ($ultimo !== null && preg_match(RE_VALIDOS_A_PARTIR, $linha, $m)) {
            $valores[$ultimo] = listaDeValores($m[1]);
        }
    }
    return $valores;
}

/**
 * "“01”, “02” e 03" -> [01, 02, 03]
 *
 * @return list<string>
 */
function listaDeValores(string $lista): array
{
    $partes = preg_split('/\s*(?:[,;]|\se\s)\s*/u', $lista) ?: [];
    $partes = array_map(static fn(string $v): string => trim($v, " \t“”‘’\"'"), $partes);
    return array_values(array_filter($partes, 'strlen'));
}

/**
 * Melhor ocorrência do registro no documento: a que fecha a tabela ou, não
 * fechando, a que tem mais campos (o sumário do Guia também tem "REGISTRO XXXX:").
 *
 * @param list<list<string>> $ocorrencias
 * @return array{0: array<string, mixed>, 1: list<string>}|null
 */
function melhor(array $ocorrencias): ?array
{
    $escolhida = null;
    foreach ($ocorrencias as $linhas) {
        $a = analisar($linhas);
        if ($escolhida === null || [$a['ok'], count($a['nomes'])] > [$escolhida[0]['ok'], count($escolhida[0]['nomes'])]) {
            $escolhida = [$a, $linhas];
        }
    }
    return $escolhida;
}

/**
 * @param array<string, mixed> $a
 * @return list<array<string, mixed>>
 */
function campos(array $a): array
{
    $saida = [];
    foreach ($a['nomes'] as $i => $nome) {
        [$tipo, $tam, $dec] = $a['trincas'][$i];
        $saida[] = [
            'n' => $i + 1,
            'nome' => $nome,
            'tipo' => $tipo,
            'tam' => $tam === '-' ? null : (int) rtrim($tam, '*'),
            'fixo' => str_ends_with($tam, '*'),
            'dec' => $dec === '-' ? null : (int) $dec,
            'valores' => null,
        ];
    }
    return $saida;
}

/**
 * Mesma lista de campos, admitindo nome cortado (um é prefixo do outro).
 *
 * @param list<string> $a
 * @param list<string> $b
 */
function nomesEquivalentes(array $a, array $b): bool
{
    if (count($a) !== count($b)) {
        return false;
    }
    foreach ($a as $i => $nome) {
        if (!str_starts_with($nome, $b[$i]) && !str_starts_with($b[$i], $nome)) {
            return false;
        }
    }
    return true;
}

/**
 * @param list<string> $trinca
 */
function trincaNormal(array $trinca): string
{
    [$tipo, $tam, $dec] = $trinca;
    $fixo = str_ends_with($tam, '*') ? '*' : '';
    $tam = $tam === '-' ? '-' : ((string) (int) rtrim($tam, '*')) . $fixo;
    $dec = $dec === '-' ? '-' : (string) (int) $dec;
    return "$tipo $tam $dec";
}

$nt = $ntArquivo === '-' ? [] : secoes($ntArquivo);
$guia = secoes($guiaArquivo);

if ($depurar !== null) {
    foreach (['NT' => $nt, 'Guia' => $guia] as $rotulo => $doc) {
        foreach ($doc[$depurar] ?? [] as $k => $linhas) {
            echo "===== $rotulo $depurar, ocorrência " . ($k + 1) . "\n";
            $a = analisar($linhas, true);
            printf("----- %d campos, %d trincas, nível %s, ocorrência %s\n", count($a['nomes']), count($a['trincas']), var_export($a['nivel'], true), var_export($a['ocorrencia'], true));
            echo '----- campos: ' . implode(', ', $a['nomes']) . "\n";
        }
    }
    exit(0);
}

$codigos = array_unique(array_merge(array_keys($nt), array_keys($guia)));
sort($codigos, SORT_STRING);

$registros = [];
$falhas = [];
$fontes = ['nt+guia' => 0, 'nt' => 0, 'guia' => 0];
$totalDivergencias = 0;
foreach ($codigos as $reg) {
    $n = isset($nt[$reg]) ? melhor($nt[$reg]) : null;
    $g = isset($guia[$reg]) ? melhor($guia[$reg]) : null;
    $nOk = $n !== null && $n[0]['ok'];
    $gOk = $g !== null && $g[0]['ok'];
    if (!$nOk && !$gOk) {
        $falhas[$reg] = sprintf(
            'NT %s | Guia %s',
            $n === null ? 'ausente' : count($n[0]['nomes']) . ' campos/' . count($n[0]['trincas']) . ' trincas',
            $g === null ? 'ausente' : count($g[0]['nomes']) . ' campos/' . count($g[0]['trincas']) . ' trincas'
        );
        continue;
    }
    $base = $nOk ? $n[0] : $g[0];
    $divergencias = [];
    if ($nOk && $gOk) {
        $nomesNt = array_map('strtoupper', $n[0]['nomes']);
        $nomesGuia = array_map('strtoupper', $g[0]['nomes']);
        if (!nomesEquivalentes($nomesNt, $nomesGuia)) {
            $divergencias[] = 'nomes: NT [' . implode(',', $nomesNt) . '] x Guia [' . implode(',', $nomesGuia) . ']';
        } else {
            // os dois PDFs cortam nome comprido na coluna: vale o mais longo
            foreach ($nomesNt as $i => $nome) {
                if (strlen($nomesGuia[$i]) > strlen($nome)) {
                    $base['nomes'][$i] = $g[0]['nomes'][$i];
                }
            }
            foreach ($n[0]['trincas'] as $i => $trinca) {
                $outra = $g[0]['trincas'][$i];
                if (trincaNormal($trinca) !== trincaNormal($outra)) {
                    $divergencias[] = sprintf('campo %02d %s: NT %s x Guia %s', $i + 1, $nomesNt[$i], implode(' ', $trinca), implode(' ', $outra));
                }
            }
        }
    }
    $lista = campos($base);
    $validos = $g !== null ? valoresValidos($g[1]) : [];
    foreach ($lista as $i => $campo) {
        $nome = strtoupper($campo['nome']);
        if (isset($validos[$nome])) {
            $lista[$i]['valores'] = $validos[$nome];
            $lista[$i]['origem_valores'] = 'guia';
        } elseif (!empty($base['enumeracoes'][$i]) && $campo['tam'] !== null && $campo['tam'] <= 3 && $campo['dec'] === null) {
            // enumeração solta na descrição só vale para campo de código curto
            $lista[$i]['valores'] = $base['enumeracoes'][$i];
            $lista[$i]['origem_valores'] = $nOk ? 'nt' : 'guia';
        }
    }
    $fonte = $nOk && $gOk ? 'nt+guia' : ($nOk ? 'nt' : 'guia');
    $fontes[$fonte]++;
    $totalDivergencias += count($divergencias);
    $registros[$reg] = [
        'titulo' => $base['titulo'],
        'nivel' => $base['nivel'] ?? ($g[0]['nivel'] ?? null),
        'ocorrencia' => $base['ocorrencia'] ?? ($g[0]['ocorrencia'] ?? null),
        'fonte' => $fonte,
        'divergencias' => $divergencias,
        'campos' => $lista,
    ];
}

$documento = [
    'leiaute' => $leiaute,
    'gerado_em' => date('Y-m-d'),
    'fontes' => [
        'nt' => $ntArquivo === '-' ? null : basename($ntArquivo),
        'guia' => basename($guiaArquivo),
    ],
    'registros' => $registros,
    'sem_extracao' => $falhas,
];
file_put_contents((string) $saida, json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");

printf(
    "leiaute %s: %d registros extraídos (nt+guia %d, só nt %d, só guia %d), %d divergências NT x Guia, %d sem extração\n",
    $leiaute,
    count($registros),
    $fontes['nt+guia'],
    $fontes['nt'],
    $fontes['guia'],
    $totalDivergencias,
    count($falhas)
);
foreach ($falhas as $reg => $motivo) {
    echo "  sem extração $reg: $motivo\n";
}
