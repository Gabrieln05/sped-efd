<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C180: INFORMAÇÕES COMPLEMENTARES DAS OPERAÇÕES DE ENTRADA DE MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 01, 1B, 04 e 55).
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C180 extends Element
{
    const REG = 'C180';
    const LEVEL = 4;
    const PARENT = 'C170';

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
