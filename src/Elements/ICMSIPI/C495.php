<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C495 extends Element
{
    const REG = 'C495';
    const LEVEL = 2;
    const PARENT = 'C400';

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
        if ($this->values->qtd <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O do campo QTD deve ser maior do que 0";
        }
        if ($this->values->vl_item <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O do campo VL_ITEM deve ser maior do que 0";
        }
    }
}
