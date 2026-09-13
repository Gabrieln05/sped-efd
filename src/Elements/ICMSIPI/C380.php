<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C380: INFORMAÇÕES  COMPLEMENTARES  DAS  OPERAÇÕES  DE  SAÍDA  DE  MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 02)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C380 extends Element
{
    const REG = 'C380';
    const LEVEL = 4;
    const PARENT = 'C370';

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
