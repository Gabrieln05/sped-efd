<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1700 extends Element
{
    const REG = '1700';
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
        $calculo = $this->values->vl_ret_apu-
            $this->values->vl_ret_ded-
            $this->values->vl_ret_per-
            $this->values->vl_ret_dcomp;
        if (number_format((float) $this->values->sld_ret, 2)!==number_format($calculo, 2)) {
            $this->errors[] = "[" . self::REG . "] " .
                "O valor do campo SLD_RET deverá ser igual a
                VL_RET_APU - VL_RET_DED - VL_RET_PER - VL_RET_DCOMP.";
        }
    }
}
