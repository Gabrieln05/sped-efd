<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class E313 extends Element
{
    const REG = 'E313';
    const LEVEL = 5;
    const PARENT = 'E311';

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
        /*
         * Campo 10 (VL_AJ_ITEM) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->values->vl_aj_item <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo deve "
            . "ser maior que “0” (zero).";
        }
    }
}
