<?php

/**
 * Funções comuns às ferramentas de leiaute (comparar.php, gerar_leiaute.php) e
 * ao teste que confere os JSONs de cada leiaute auditado contra a referência.
 */

declare(strict_types=1);

const TOTALIZADORES_EFD = '/^(.990|9001|9900|9990|9999)$/';

/**
 * Nome de campo para comparação: maiúsculo, hífen vira sublinhado, sem sublinhado repetido.
 */
function nomeNormal(string $nome): string
{
    return (string) preg_replace('/_+/', '_', str_replace('-', '_', strtoupper($nome)));
}

/**
 * Mesmo campo apesar de nome cortado na coluna do PDF (um é prefixo do outro).
 */
function nomesEquivalentesCampo(string $a, string $b): bool
{
    $a = nomeNormal($a);
    $b = nomeNormal($b);
    if ($a === $b) {
        return true;
    }
    $curto = strlen($a) < strlen($b) ? $a : $b;
    return strlen($curto) >= 3 && (str_starts_with($a, $b) || str_starts_with($b, $a));
}

/**
 * Alinha a lista de campos do leiaute com a do JSON: pares [índice ref, índice lib],
 * com null do lado que não tem o campo.
 *
 * @param list<string> $ref
 * @param list<string> $lib
 * @return list<array{0: int|null, 1: int|null}>
 */
function alinharCampos(array $ref, array $lib): array
{
    $pares = [];
    $i = 0;
    $j = 0;
    while ($i < count($ref) || $j < count($lib)) {
        if ($i >= count($ref)) {
            $pares[] = [null, $j++];
        } elseif ($j >= count($lib)) {
            $pares[] = [$i++, null];
        } elseif (nomesEquivalentesCampo($ref[$i], $lib[$j])) {
            $pares[] = [$i++, $j++];
        } elseif (isset($lib[$j + 1]) && nomesEquivalentesCampo($ref[$i], $lib[$j + 1])) {
            $pares[] = [null, $j++];
        } elseif (isset($ref[$i + 1]) && nomesEquivalentesCampo($ref[$i + 1], $lib[$j])) {
            $pares[] = [$i++, null];
        } else {
            $pares[] = [$i++, $j++];
        }
    }
    return $pares;
}

/**
 * Tamanho máximo aceito por regex simples (^.{n}$, ^.{m,n}$, ^\d{n}$, ^[0-9]{m,n}$); null se não der para dizer.
 */
function tamanhoMaximo(string $regex): ?int
{
    if (preg_match('/^\^(?:\.|\\\\d|\[0-9\])\{(\d+)(?:,(\d+))?\}\$$/', $regex, $m)) {
        return (int) ($m[2] ?? $m[1]);
    }
    return null;
}

/**
 * Regex da biblioteca para o campo, a partir do leiaute.
 *
 * @param array<string, mixed> $campo
 */
function regexDoCampo(array $campo): string
{
    if (!empty($campo['valores'])) {
        return '^(' . implode('|', array_map(static fn(string $v): string => preg_quote($v, '/'), $campo['valores'])) . ')$';
    }
    if ($campo['tipo'] === 'N' && $campo['dec'] !== null) {
        return '^\d+(\.\d*)?|\.\d+$';
    }
    $classe = $campo['tipo'] === 'N' ? '\d' : '.';
    if ($campo['tam'] === null) {
        return $campo['tipo'] === 'N' ? '^\d+$' : '^.*$';
    }
    return $campo['fixo']
        ? '^' . $classe . '{' . $campo['tam'] . '}$'
        : '^' . $classe . '{1,' . $campo['tam'] . '}$';
}

/**
 * "format" da biblioteca: "{inteiros}v{decimais}" para N com decimais; vazio no resto.
 *
 * @param array<string, mixed> $campo
 */
function formatoDoCampo(array $campo, string $formatoAtual = ''): string
{
    if ($campo['tipo'] !== 'N' || $campo['dec'] === null) {
        return '';
    }
    $inteiros = preg_match('/^(\d+)v/', $formatoAtual, $m) ? (int) $m[1] : 15;
    return $inteiros . 'v' . $campo['dec'];
}

/**
 * Registros da referência com as correções manuais aplicadas: as listas de
 * "referencia" (registro que o extrator não leu) e os "ref" de "campos".
 *
 * @param array<string, mixed> $referencia
 * @param array<string, mixed> $ajustes
 * @return array<string, array<string, mixed>>
 */
function registrosComAjustes(array $referencia, array $ajustes): array
{
    $registros = $referencia['registros'];
    foreach ($ajustes['referencia'] ?? [] as $reg => $lista) {
        $campos = [['n' => 1, 'nome' => 'REG', 'tipo' => 'C', 'tam' => 4, 'fixo' => true, 'dec' => null, 'valores' => null]];
        foreach ($lista as $k => $campo) {
            $campos[] = $campo + ['n' => $k + 2, 'valores' => null, 'fixo' => false, 'dec' => null, 'tam' => null];
        }
        $registros[(string) $reg] = [
            'titulo' => $registros[$reg]['titulo'] ?? '',
            'nivel' => $registros[$reg]['nivel'] ?? null,
            'ocorrencia' => $registros[$reg]['ocorrencia'] ?? null,
            'fonte' => 'ajustes',
            'divergencias' => [],
            'campos' => $campos,
        ];
    }
    foreach ($ajustes['campos'] ?? [] as $reg => $porCampo) {
        if (!isset($registros[$reg])) {
            continue;
        }
        foreach ($registros[$reg]['campos'] as $k => $campo) {
            $ajuste = $porCampo[sprintf('%02d', $campo['n'])] ?? null;
            if (isset($ajuste['ref'])) {
                $registros[$reg]['campos'][$k] = ['nome_ajustado' => isset($ajuste['ref']['nome'])] + $ajuste['ref'] + $campo;
            }
        }
    }
    return $registros;
}

/**
 * Divergências entre os registros de um leiaute e os JSONs de uma pasta.
 *
 * Confere o que muda o arquivo gerado ou a validação: registro sem JSON e JSON
 * sem registro; campos faltando, sobrando ou com outro nome; campo C gravado
 * como número; casas decimais do "format"; tamanho máximo (quando a regex é
 * simples); e se a regex aceita cada valor válido. Campo N gravado como string
 * não é divergência: a saída é a mesma.
 *
 * @param array<string, array<string, mixed>> $registros
 * @param array<string, string> $semAuditoria registros fora da conferência
 * @return array{achados: array<string, list<string>>, contagem: array<string, int>}
 */
function compararLeiaute(array $registros, string $pasta, array $semAuditoria = []): array
{
    $achados = [];
    $contagem = [];
    $anota = static function (string $reg, string $tipo, string $texto) use (&$achados, &$contagem): void {
        $achados[$reg][] = $texto;
        $contagem[$tipo] = ($contagem[$tipo] ?? 0) + 1;
    };

    foreach (glob("$pasta/*.json") ?: [] as $arquivo) {
        $reg = basename($arquivo, '.json');
        if (!isset($registros[$reg]) && !isset($semAuditoria[$reg])) {
            $anota($reg, 'JSON sem registro no leiaute', 'JSON na pasta, mas o registro não existe no leiaute');
        }
    }

    foreach ($registros as $reg => $r) {
        $reg = (string) $reg;
        if (preg_match(TOTALIZADORES_EFD, $reg) || isset($semAuditoria[$reg])) {
            continue;
        }
        $arquivo = "$pasta/$reg.json";
        if (!is_file($arquivo)) {
            $anota($reg, 'registro sem JSON', 'registro sem JSON na pasta');
            continue;
        }
        $lib = json_decode((string) file_get_contents($arquivo), true);
        $nomesLib = array_keys($lib);
        $campos = array_values(array_filter($r['campos'], static fn(array $c): bool => $c['n'] > 1));
        $nomesRef = array_map(static fn(array $c): string => $c['nome'], $campos);

        foreach (alinharCampos($nomesRef, $nomesLib) as [$i, $j]) {
            if ($j === null) {
                $anota($reg, 'campo faltando', sprintf('%02d %s: falta no JSON', $campos[$i]['n'], $campos[$i]['nome']));
                continue;
            }
            if ($i === null) {
                $anota($reg, 'campo sobrando', "{$nomesLib[$j]}: sobra no JSON");
                continue;
            }
            $c = $campos[$i];
            $nomeLib = (string) $nomesLib[$j];
            $d = $lib[$nomeLib];
            $rotulo = sprintf('%02d %s', $c['n'], $nomeLib);
            $mesmoNome = !empty($c['nome_ajustado'])
                ? nomeNormal($c['nome']) === nomeNormal($nomeLib)
                : nomesEquivalentesCampo($c['nome'], $nomeLib);
            if (!$mesmoNome) {
                $anota($reg, 'nome diferente', "$rotulo: no leiaute o campo {$c['n']} é {$c['nome']}");
            }
            $tipo = (string) ($d['type'] ?? '');
            $regex = (string) ($d['regex'] ?? '');
            $format = (string) ($d['format'] ?? '');
            if (!in_array($tipo, ['string', 'numeric', 'integer'], true)) {
                $anota($reg, 'tipo inválido', "$rotulo: type '$tipo' não existe");
            } elseif ($c['tipo'] === 'C' && $tipo !== 'string') {
                $anota($reg, 'campo C como número', "$rotulo: tipo C, JSON '$tipo'");
            }
            $decimaisLib = preg_match('/v(\d+)/', $format, $f) ? (int) $f[1] : null;
            if ($c['tipo'] === 'N' && $c['dec'] !== null && $decimaisLib !== $c['dec']) {
                $anota($reg, 'decimais', "$rotulo: {$c['dec']} decimais, format '$format'");
            }
            if ($c['dec'] === null && $decimaisLib !== null) {
                $anota($reg, 'decimais', "$rotulo: sem decimais, format '$format'");
            }
            $maximo = tamanhoMaximo($regex);
            if ($c['tam'] !== null && $maximo !== null && $maximo !== $c['tam']) {
                $anota($reg, 'tamanho', "$rotulo: tamanho {$c['tam']}, regex '$regex' aceita até $maximo");
            }
            if (!empty($c['valores']) && $regex !== '') {
                $recusados = array_values(array_filter(
                    $c['valores'],
                    static fn(string $v): bool => @preg_match("/$regex/", $v) !== 1
                ));
                if ($recusados !== []) {
                    $anota($reg, 'valores válidos', "$rotulo: regex '$regex' recusa " . implode(', ', $recusados));
                }
            }
        }
    }
    ksort($achados, SORT_STRING);
    ksort($contagem);
    return ['achados' => $achados, 'contagem' => $contagem];
}
