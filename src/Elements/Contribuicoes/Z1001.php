<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1001 extends Element
{
    const REG = '1001';
    const LEVEL = 2;
    const PARENT = '0000';

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
