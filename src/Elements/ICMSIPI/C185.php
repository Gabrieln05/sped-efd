<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C185: INFORMAÇÕES  COMPLEMENTARES  DAS  OPERAÇÕES  DE  SAÍDA  DE  MERCADORIAS SUJEITAS À SUBSTITUIÇÃO
 * TRIBUTÁRIA (CÓDIGO 01, 1B, 04, 55 e 65).
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C185 extends Element
{
    const REG = 'C185';
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
