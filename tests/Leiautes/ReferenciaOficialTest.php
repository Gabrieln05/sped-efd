<?php

namespace NFePHP\EFD\Tests\Leiautes;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/tools/leiaute/funcoes.php';

/**
 * Os JSONs de cada leiaute auditado batem com a referência extraída dos PDFs
 * oficiais (docs/leiautes/referencia/NNN.json) mais as decisões manuais
 * (tools/leiaute/ajustes/NNN.json). Editar um JSON contra o leiaute derruba este teste.
 */
final class ReferenciaOficialTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function leiautesAuditados(): iterable
    {
        foreach (glob(dirname(__DIR__, 2) . '/docs/leiautes/referencia/*.json') ?: [] as $arquivo) {
            $leiaute = basename($arquivo, '.json');
            yield "leiaute $leiaute" => [$leiaute];
        }
    }

    #[DataProvider('leiautesAuditados')]
    public function testJsonsDoLeiauteBatemComAReferenciaOficial(string $leiaute): void
    {
        $raiz = dirname(__DIR__, 2);
        $referencia = json_decode((string) file_get_contents("$raiz/docs/leiautes/referencia/$leiaute.json"), true);
        $ajustes = json_decode((string) file_get_contents("$raiz/tools/leiaute/ajustes/$leiaute.json"), true);

        $resultado = \compararLeiaute(
            \registrosComAjustes($referencia, $ajustes),
            "$raiz/storage/layouts/ICMSIPI/v$leiaute",
            $ajustes['sem_auditoria'] ?? []
        );

        $this->assertSame([], $resultado['achados']);
    }
}
