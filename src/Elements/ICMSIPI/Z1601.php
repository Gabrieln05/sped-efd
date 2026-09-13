<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

// REGISTRO 1601: OPERAÇÕES COM INSTRUMENTOS DE PAGAMENTOS ELETRÔNICOS
// (VÁLIDO A PARTIR DE 01/01/2022)
class Z1601 extends Element
{
    const REG = '1601';
    const LEVEL = 2;
    const PARENT = '1001';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
        $this->postValidation();
    }
}
