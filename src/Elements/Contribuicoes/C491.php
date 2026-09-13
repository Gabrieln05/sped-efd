<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class C491 extends Element
{
    const REG = 'C491';
    const LEVEL = 4;
    const PARENT = 'C490';

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

    public function postValidation()
    {
        if ($this->values->vl_item <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_ITEM deve ser maior que “0” (zero).";
        }
    }
}
