<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO D731: INFORMAÇÕES DO FUNDO DE COMBATE À POBREZA – FCP – (CÓDIGO 62)
 */
class D731 extends Element
{
    const REG = 'D731';
    const LEVEL = 4;
    const PARENT = 'D730';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}
