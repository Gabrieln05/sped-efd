<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class M115 extends Element
{
    const REG = 'M115';
    const LEVEL = 4;
    const PARENT = 'M110';

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
