<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class C830 extends Element
{
    const REG = 'C830';
    const LEVEL = 4;
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
