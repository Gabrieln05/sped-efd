<?php

namespace NFePHP\EFD\Tests\Caracterizacao;

use PHPUnit\Framework\TestCase;

/**
 * Trava a saída atual da biblioteca para que refatorações não mudem o arquivo
 * gerado sem querer. Mudança intencional: regravar com
 * SPED_EFD_ATUALIZAR_GOLDEN=1 vendor/bin/phpunit --filter EFDICMSExemploTest
 * e explicar a diferença no commit.
 */
final class EFDICMSExemploTest extends TestCase
{
    private const GOLDEN_TXT = __DIR__ . '/../fixtures/golden/efdicms-exemplo.txt';
    private const GOLDEN_ERROS = __DIR__ . '/../fixtures/golden/efdicms-exemplo-erros.json';

    public function testArquivoEErrosIguaisAoGolden(): void
    {
        $efd = ExemploEFDICMS::montar();
        $txt = $efd->get();

        if (getenv('SPED_EFD_ATUALIZAR_GOLDEN')) {
            file_put_contents(self::GOLDEN_TXT, $txt);
            file_put_contents(
                self::GOLDEN_ERROS,
                json_encode($efd->errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
            );
            $this->markTestSkipped('Golden regravado.');
        }

        $this->assertSame(file_get_contents(self::GOLDEN_TXT), $txt);
        $this->assertSame(json_decode(file_get_contents(self::GOLDEN_ERROS), true), $efd->errors);
    }
}
