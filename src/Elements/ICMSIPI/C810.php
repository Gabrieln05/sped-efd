<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C810: ITENS  DO  DOCUMENTO  DO  CUPOM  FISCAL ELETRÔNICO  –  SAT (CF-E-SAT)  (CÓDIGO 59):
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C810 extends Element
{
    const REG = 'C810';
    const LEVEL = 3;
    const PARENT = 'C800';

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
