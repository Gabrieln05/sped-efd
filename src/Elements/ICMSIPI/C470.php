<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C470 extends Element
{
    const REG = 'C470';
    const LEVEL = 5;
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
        if ($this->values->vl_item <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O Valor total do item"
                . "(VL_ITEM) deve ser maior que 0";
        }
        if ($this->values->qtd <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " Quantidade total do item"
                . "(QTD) deve ser maior que 0";
        }
    }
}
