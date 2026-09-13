<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1210 extends Element
{
    const REG = '1210';
    const LEVEL = 3;
    const PARENT = '1200';

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
         * Campo 04 (VL_CRED_UTIL) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->values->vl_cred_util <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo "
            . "VL_CRED_UTIL deve ser maior que “0” (zero).";
        }
    }
}
