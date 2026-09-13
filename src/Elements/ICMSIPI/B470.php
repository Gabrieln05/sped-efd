<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B470 extends Element
{
    const REG = 'B470';
    const LEVEL = 2;
    const PARENT = 'B001';

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
         * Campo 07 (VL_DED_BC) Validação: o valor informado deve ser igual ao somatório dos valores
         * dos campos VL_MAT_TERC, VL_MAT_PROP, VL_SUB e VL_ISNT.
         */
        $somatorio = $this->values->vl_mat_terc
                    + $this->values->vl_mat_prop
                    + $this->values->vl_sub
                    + $this->values->vl_isnt;
        if ($this->values->vl_ded_bc != $somatorio) {
            $this->errors[] = "[" . self::REG . "] O valor informado deve ser igual "
            ."ao somatório dos valores dos campos VL_MAT_TERC, VL_MAT_PROP, VL_SUB e VL_ISNT.";
        }
    }
}
