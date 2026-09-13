<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C165: OPERAÇÕES COM COMBUSTÍVEIS (CÓDIGO 01).
 * Este registro deve ser apresentado pelas empresas do segmento de combustíveis (distribuidoras, refinarias,
 * revendedoras) em operações de saída. Postos de combustíveis não devem apresentar este registro.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C165 extends Element
{
    const REG = 'C165';
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
