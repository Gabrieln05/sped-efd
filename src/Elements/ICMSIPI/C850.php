<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C850: REGISTRO ANALÍTICO DO CF-e-SAT (CODIGO 59)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C850 extends Element
{
    const REG = 'C850';
    const LEVEL = 3;
    const PARENT = 'C800';

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
        //pega o fim da string do CST_ICMS e faz a verificacao
        $cstIcmsLast = (int) substr($this->std->cst_icms, -2);
        if (in_array($cstIcmsLast, [40, 41, 50, 60])) {
            if ($this->values->vl_bc_icms != 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo VL_BC_ICMS deve ser Igual 0";
            }
            if ($this->values->aliq_icms != 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo VL_ICMS deve ser Igual 0";
            }
            if ($this->values->vl_icms != 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo ALIQ_ICMS deve ser Igual 0";
            }
        } elseif (!in_array($cstIcmsLast, [51, 90])) {
            if ($this->values->vl_bc_icms <= 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo VL_BC_ICMS deve ser maior do que 0";
            }
            if ($this->values->aliq_icms <= 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo ALIQ_ICMS deve ser maior do que 0";
            }
            if ($this->values->vl_icms <= 0) {
                $this->errors[] = "[" . self::REG . "] "
                    . " O do campo VL_ICMS deve ser maior do que 0";
            }
        }
    }
}
