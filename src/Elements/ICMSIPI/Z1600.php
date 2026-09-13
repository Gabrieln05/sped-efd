<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

// REGISTRO 1600: TOTAL DAS OPERAÇÕES COM CARTÃO DE CRÉDITO E/OU DÉBITO,
// LOJA (PRIVATE LABEL) E DEMAIS INSTRUMENTOS DE PAGAMENTOS ELETRÔNICOS
// (VÁLIDO ATÉ 31/12/2021)
class Z1600 extends Element
{
    const REG = '1600';
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
