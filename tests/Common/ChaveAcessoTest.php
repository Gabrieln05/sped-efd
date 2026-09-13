<?php

namespace NFePHP\EFD\Tests\Common;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Tests\Pva\Documentos;
use PHPUnit\Framework\TestCase;

final class ChaveAcessoTest extends TestCase
{
    public function testChaveNumericaComDigitoVerificadorCertoValida(): void
    {
        $this->assertTrue(ChaveAcesso::valida(Documentos::chave(Documentos::cnpj('112223330001'), 55, 1, 777, 77770001)));
    }

    public function testChaveNumericaComDigitoVerificadorErradoNaoValida(): void
    {
        $chave = Documentos::chave(Documentos::cnpj('112223330001'), 55, 1, 777, 77770001);
        $errada = substr($chave, 0, 43) . (((int) substr($chave, 43)) + 1) % 10;
        $this->assertFalse(ChaveAcesso::valida($errada));
    }

    public function testChaveComCnpjAlfanumericoValidaPeloFormato(): void
    {
        $this->assertTrue(ChaveAcesso::valida('352701' . '12ABC34501DE' . '35' . '55' . '001' . '000001001' . '1' . '10010001' . '0'));
    }

    public function testLetraForaDasPosicoesDoCnpjNaoValida(): void
    {
        $this->assertFalse(ChaveAcesso::valida('3527A1' . '12ABC34501DE' . '35' . '55' . '001' . '000001001' . '1' . '10010001' . '0'));
        $this->assertFalse(ChaveAcesso::valida('352701' . '12ABC34501DE' . '3X' . '55' . '001' . '000001001' . '1' . '10010001' . '0'));
    }

    public function testTamanhoDiferenteDe44NaoValida(): void
    {
        $this->assertFalse(ChaveAcesso::valida(substr(Documentos::chave(Documentos::cnpj('112223330001'), 55, 1, 777, 77770001), 0, 43)));
    }
}
