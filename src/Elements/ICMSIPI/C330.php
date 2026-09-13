<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C330: INFORMAÇÕES COMPLEMENTARES DAS OPERAÇÕES DE SAÍDA DE MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 02)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C330 extends Element
{
    const REG = 'C330';
    const LEVEL = 5;
    const PARENT = 'C321';

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
