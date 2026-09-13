<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class E311 extends Element
{
    const REG = 'E311';
    const LEVEL = 4;
    const PARENT = 'E310';

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
         * Campo 04 (VL_AJ_APUR) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->values->vl_aj_apur <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo deve ser "
            . "maior que “0” (zero).";
        }
    }
}
