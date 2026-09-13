<?php

namespace NFePHP\EFD\Tests\Pva;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * O arquivo de validação de cada leiaute sai sem erro da biblioteca e igual ao
 * que foi levado ao PVA (tests/fixtures/pva/efd-icms-ipi-NNN.txt). Mudança
 * intencional: regravar com SPED_EFD_ATUALIZAR_GOLDEN=1 e validar de novo no PVA.
 */
final class ArquivoPvaTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function leiautes(): iterable
    {
        foreach (ArquivoPva::leiautes() as $leiaute) {
            yield "leiaute $leiaute" => [$leiaute];
        }
    }

    #[DataProvider('leiautes')]
    public function testArquivoSaiSemErrosEIgualAoValidadoNoPva(string $leiaute): void
    {
        $arquivo = dirname(__DIR__) . "/fixtures/pva/efd-icms-ipi-$leiaute.txt";
        $efd = ArquivoPva::montar($leiaute);
        $txt = $efd->get();

        $this->assertSame([], $efd->errors);

        if (getenv('SPED_EFD_ATUALIZAR_GOLDEN')) {
            file_put_contents($arquivo, $txt);
            $this->markTestSkipped("Arquivo do PVA do leiaute $leiaute regravado.");
        }

        $this->assertSame(file_get_contents($arquivo), $txt);
    }
}
