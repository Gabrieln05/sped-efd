<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C430: INFORMAÇÕES  COMPLEMENTARES  DAS  OPERAÇÕES  DE  SAÍDA  DE  MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 02, 2D e 60)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C430 extends Element
{
    const REG = 'C430';
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
