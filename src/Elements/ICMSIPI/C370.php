<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C370: ITENS DO DOCUMENTO (CÓDIGO 02)
 * Este registro é o detalhamento por itens das notas fiscais de venda ao consumidor, modelo 2.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C370 extends Element
{
    const REG = 'C370';
    const LEVEL = 3;
    const PARENT = '';

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
