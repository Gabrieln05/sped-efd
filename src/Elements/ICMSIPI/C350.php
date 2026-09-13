<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C350: NOTA FISCAL DE VENDA A CONSUMIDOR (CÓDIGO 02)
 * Este registro deve ser apresentado pelos contribuintes que utilizam notas fiscais de venda ao consumidor, não
 * emitidas por ECF. As notas fiscais canceladas não devem ser informadas.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */

class C350 extends Element
{
    const REG = 'C350';
    const LEVEL = 2;
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
