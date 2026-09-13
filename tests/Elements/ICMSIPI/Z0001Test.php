<?php

namespace NFePHP\EFD\Tests\Elements\ICMSIPI;

use NFePHP\EFD\Common\Vigencia;
use NFePHP\EFD\Elements\ICMSIPI\Z0001;
use PHPUnit\Framework\TestCase;
use stdClass;

class Z0001Test extends TestCase
{
    private function vigencia(): stdClass
    {
        return Vigencia::carregar(Vigencia::ICMSIPI, '017');
    }

    public function testZ0001(): void
    {
        $std = new stdClass();
        $std->ind_mov = 1;
        $b1 = new Z0001($std, $this->vigencia());
        $resp = "{$b1}";
        $expected = '|0001|1|';
        $this->assertEquals($expected, $resp);
    }

    public function testZ0001FailWithString(): void
    {
        $std = new stdClass();
        $std->ind_mov = 'A';
        $z1 = new Z0001($std, $this->vigencia());
        $this->assertEquals('[0001] campo: IND_MOV deve ser um numero.', $z1->errors[0]);
    }

    public function testZ0001FailWithNotValidNumber(): void
    {
        $std = new stdClass();
        $std->ind_mov = 2;
        $z1 = new Z0001($std, $this->vigencia());
        $this->assertEquals('[0001] campo: IND_MOV valor incorreto [2]. (validação: ^[0-1]{1}$)', $z1->errors[0]);
    }
}
