<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B350 extends Element
{
    const REG = 'B350';
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
         * Campo 10 (VL_ISS) Validação: O valor deve ser igual ao produto da
         * base de cálculo “VL_BC_ISS” pela alíquota “ALIQ_ISS”
         */
        $vl_iss = ($this->values->vl_bc_iss/100) * $this->std->aliq_iss;
        $vl_iss = (float) number_format((float) $vl_iss, 2, '.', '');

        if ($this->values->vl_iss != $vl_iss) {
            $this->errors[] = "[" . self::REG . "] O valor deve ser igual ao produto "
            ."da base de cálculo “VL_BC_ISS” pela alíquota “ALIQ_ISS”";
        }
    }
}
