<?php

namespace NFePHP\EFD\Tests\Pva;

use PHPUnit\Framework\TestCase;

/**
 * O arquivo de validação do leiaute 020 sai sem erro da biblioteca e igual ao
 * que foi levado ao PVA (tests/fixtures/pva/efd-icms-ipi-020.txt). Mudança
 * intencional: regravar com SPED_EFD_ATUALIZAR_GOLDEN=1 e validar de novo no PVA.
 */
final class ArquivoPva020Test extends TestCase
{
    private const ARQUIVO = __DIR__ . '/../fixtures/pva/efd-icms-ipi-020.txt';

    public function testArquivoDoLeiaute020SaiSemErrosEIgualAoValidadoNoPva(): void
    {
        $efd = ArquivoPva020::montar();
        $txt = $efd->get();

        $this->assertSame([], $efd->errors);

        if (getenv('SPED_EFD_ATUALIZAR_GOLDEN')) {
            if (!is_dir(dirname(self::ARQUIVO))) {
                mkdir(dirname(self::ARQUIVO), 0777, true);
            }
            file_put_contents(self::ARQUIVO, $txt);
            $this->markTestSkipped('Arquivo do PVA regravado.');
        }

        $this->assertSame(file_get_contents(self::ARQUIVO), $txt);
    }
}
