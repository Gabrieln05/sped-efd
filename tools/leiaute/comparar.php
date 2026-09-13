<?php

/**
 * Compara a referência extraída de um leiaute com os JSONs de uma pasta de
 * leiaute da biblioteca e lista as divergências, campo a campo.
 *
 * uso: php tools/leiaute/comparar.php <referencia.json> <storage/layouts/ICMSIPI/vNNN> [relatorio.md] [--ajustes=ajustes.json]
 *
 * Com --ajustes, a referência recebe antes as correções manuais do leiaute e os
 * registros "sem_auditoria" ficam de fora: é a conferência de "diff zerado".
 * O que é conferido está em compararLeiaute() (funcoes.php).
 */

declare(strict_types=1);

require __DIR__ . '/funcoes.php';

$ajustesArquivo = null;
$posicionais = [];
foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--ajustes=')) {
        $ajustesArquivo = substr($arg, strlen('--ajustes='));
    } else {
        $posicionais[] = $arg;
    }
}
if (count($posicionais) < 2) {
    fwrite(STDERR, "uso: php comparar.php <referencia.json> <pasta-do-leiaute> [relatorio.md] [--ajustes=ajustes.json]\n");
    exit(1);
}
$referencia = json_decode((string) file_get_contents($posicionais[0]), true);
$pasta = rtrim($posicionais[1], '/\\');
$relatorio = $posicionais[2] ?? null;
$ajustes = $ajustesArquivo === null ? [] : json_decode((string) file_get_contents($ajustesArquivo), true);

$registros = registrosComAjustes($referencia, $ajustes);
['achados' => $achados, 'contagem' => $contagem] = compararLeiaute($registros, $pasta, $ajustes['sem_auditoria'] ?? []);

$md = "# Referência {$referencia['leiaute']} × " . basename($pasta) . ($ajustesArquivo !== null ? ' (com ajustes)' : '') . "\n\n";
$md .= "| Divergência | Ocorrências |\n|---|---|\n";
foreach ($contagem as $tipo => $n) {
    $md .= "| $tipo | $n |\n";
}
$md .= "\nRegistros com divergência: " . count($achados) . ' de ' . count($registros) . "\n";
$semExtracao = array_diff_key($referencia['sem_extracao'] ?? [], $ajustes['referencia'] ?? [], $ajustes['sem_auditoria'] ?? []);
if ($semExtracao !== []) {
    $md .= "\nSem extração (conferir à mão): " . implode(', ', array_keys($semExtracao)) . "\n";
}
foreach ($achados as $reg => $lista) {
    $r = $registros[$reg] ?? ['fonte' => '-', 'divergencias' => []];
    $md .= "\n## $reg (fonte: {$r['fonte']})\n\n";
    foreach ($r['divergencias'] as $div) {
        $md .= "- NT × Guia: $div\n";
    }
    foreach ($lista as $item) {
        $md .= "- $item\n";
    }
}
if ($relatorio !== null) {
    file_put_contents($relatorio, $md);
}
echo strtok($md, "\n") . "\n";
foreach ($contagem as $tipo => $n) {
    echo "  $tipo: $n\n";
}
echo '  registros com divergência: ' . count($achados) . "\n";
exit($achados === [] ? 0 : 3);
