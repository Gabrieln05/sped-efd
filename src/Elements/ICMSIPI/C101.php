<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C101: INFORMAÇÃO COMPLEMENTAR DOS DOCUMENTOS FISCAIS
 * QUANDO DAS OPERAÇÕES INTERESTADUAIS DESTINADAS A CONSUMIDOR FINAL
 * NÃO CONTRIBUINTE EC 87/15 (CÓDIGO 55)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C101 extends Element
{
    const REG = 'C101';
    const LEVEL = 3;
    const PARENT = 'C100';

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
