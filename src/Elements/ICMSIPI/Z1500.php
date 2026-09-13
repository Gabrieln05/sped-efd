<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1500 extends Element
{
    const REG = '1500';
    const LEVEL = 2;
    const PARENT = '1001';

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
         * Campo 10 (NUM_DOC) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->std->num_doc <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo "
            ."NUM_DOC deve ser maior que “0” (zero).";
        }

        /*
         * Campo 13 (VL_DOC) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->std->vl_doc <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo "
            ."VL_DOC deve ser maior que “0” (zero).";
        }

        /*
         * Campo 15 (VL_FORN) Validação: o valor informado no campo deve ser maior que “0” (zero).
         */
        if ($this->std->vl_forn <= 0) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo "
            ."VL_FORN deve ser maior que “0” (zero).";
        }
    }
}
