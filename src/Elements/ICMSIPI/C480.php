<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C480:INFORMAÇÕES  COMPLEMENTARES  DAS  OPERAÇÕES  DE  SAÍDA  DE  MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 02, 2D e 60)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C480 extends Element
{
    const REG = 'C480';
    const LEVEL = 6;
    const PARENT = 'C470';

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
