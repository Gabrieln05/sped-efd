<?php

/**
 * Deriva a referência de um leiaute a partir da de outro já auditado, aplicando
 * as mudanças documentadas entre os dois (histórico de alterações do Guia
 * Prático e Notas Técnicas).
 *
 * uso: php tools/leiaute/derivar_referencia.php <referencia-base.json> <ajustes-base.json> <delta.json> <saida.json>
 *
 * A base entra já com as correções manuais dela (ajustes-base.json), de modo que
 * a referência derivada sai limpa. delta.json:
 * {
 *   "leiaute": "019",
 *   "registros": {
 *     "1310": { "remover_campos": ["CAP_TANQUE"], "_fonte": "..." },
 *     "C120": { "campos": { "COD_DOC_IMP": { "valores": ["0", "1"] } }, "_fonte": "..." }
 *   }
 * }
 * "campos" é por nome do campo e aceita os atributos da referência (tipo, tam,
 * fixo, dec, valores). Chaves que começam com "_" são comentário.
 */

declare(strict_types=1);

require __DIR__ . '/funcoes.php';

if ($argc < 5) {
    fwrite(STDERR, "uso: php derivar_referencia.php <referencia-base.json> <ajustes-base.json> <delta.json> <saida.json>\n");
    exit(1);
}
[, $baseArquivo, $ajustesBaseArquivo, $deltaArquivo, $saida] = $argv;
$base = json_decode((string) file_get_contents($baseArquivo), true);
$ajustesBase = json_decode((string) file_get_contents($ajustesBaseArquivo), true);
$delta = json_decode((string) file_get_contents($deltaArquivo), true);
if (!is_array($base) || !is_array($ajustesBase) || !is_array($delta) || empty($delta['leiaute'])) {
    fwrite(STDERR, "base, ajustes ou delta com JSON inválido (o delta precisa de \"leiaute\")\n");
    exit(1);
}

$registros = registrosComAjustes($base, $ajustesBase);
$erros = [];
$aplicados = 0;
foreach ($delta['registros'] ?? [] as $reg => $mudanca) {
    if (str_starts_with((string) $reg, '_')) {
        continue;
    }
    if (!isset($registros[$reg])) {
        $erros[] = "$reg: não existe na referência-base";
        continue;
    }
    $campos = $registros[$reg]['campos'];
    foreach ($mudanca['remover_campos'] ?? [] as $nome) {
        $antes = count($campos);
        $campos = array_values(array_filter($campos, static fn(array $c): bool => nomeNormal($c['nome']) !== nomeNormal($nome)));
        if (count($campos) === $antes) {
            $erros[] = "$reg: campo $nome não existe para remover";
        }
        $aplicados++;
    }
    foreach ($mudanca['campos'] ?? [] as $nome => $atributos) {
        $achou = false;
        foreach ($campos as $k => $c) {
            if (nomeNormal($c['nome']) === nomeNormal($nome)) {
                $campos[$k] = array_filter($atributos, static fn($chave): bool => !str_starts_with((string) $chave, '_'), ARRAY_FILTER_USE_KEY) + $c;
                $achou = true;
            }
        }
        if (!$achou) {
            $erros[] = "$reg: campo $nome não existe para alterar";
        }
        $aplicados++;
    }
    foreach ($campos as $k => $c) {
        $campos[$k]['n'] = $k + 1;
    }
    $registros[$reg]['campos'] = $campos;
    $registros[$reg]['fonte'] = $registros[$reg]['fonte'] . ' + delta ' . $delta['leiaute'];
}

if ($erros !== []) {
    fwrite(STDERR, "delta não aplicado:\n  " . implode("\n  ", $erros) . "\n");
    exit(2);
}

// as correções da base já estão aplicadas: a referência derivada não carrega
// "sem_extracao" dos registros que os ajustes resolveram
$semExtracao = array_diff_key($base['sem_extracao'] ?? [], $ajustesBase['referencia'] ?? []);
$documento = [
    'leiaute' => $delta['leiaute'],
    'gerado_em' => date('Y-m-d'),
    'fontes' => [
        'derivado_de' => $base['leiaute'],
        'ajustes_da_base' => basename($ajustesBaseArquivo),
        'delta' => basename($deltaArquivo),
    ],
    'registros' => $registros,
    'sem_extracao' => $semExtracao,
];
file_put_contents($saida, json_encode($documento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
printf("leiaute %s derivado do %s: %d mudanças aplicadas\n", $delta['leiaute'], $base['leiaute'], $aplicados);
