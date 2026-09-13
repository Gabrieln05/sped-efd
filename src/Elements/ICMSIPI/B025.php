<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B025 extends Element
{
    const REG = 'B025';
    const LEVEL = 3;
    const PARENT = 'B020';

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
         * Campo 05 (VL_ISS_P) Validação: O valor deve ser igual ao produto
         * da base de cálculo “VL_BC_ISS_P” pela alíquota “ALIQ_ISS”.
         */
        $vl_iss_p = ($this->values->vl_bc_iss_p/100) * $this->std->aliq_iss;
        $vl_iss_p = (float) number_format((float) $vl_iss_p, 2, '.', '');

        if ($this->values->vl_iss_p != $vl_iss_p) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo “VL_ISS_P” "
            ."deve ser igual ao produto da base de cálculo “VL_BC_ISS_P” pela alíquota “ALIQ_ISS”.";
        }
    }
}
