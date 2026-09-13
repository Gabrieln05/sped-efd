<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO K010: INFORMAÇÃO SOBRE O TIPO DE LEIAUTE (SIMPLIFICADO/COMPLETO)
 * Este registro deve ser gerado, indicando o tipo de layout informado
 */
class K010 extends Element
{
    const REG = 'K010';
    const LEVEL = 1;
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
