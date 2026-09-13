<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class A170 extends Element
{
    const REG = 'A170';
    const LEVEL = 4;
    const PARENT = 'A100';

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

    /**
     * Arre
     * @param float $valor
     * @return float|int
     */
    private function roundFloat($valor)
    {
        return (float)number_format($valor, 2);
    }

    public function postValidation()
    {
        if ($this->roundFloat($this->values->vl_pis) !=
            $this->roundFloat(($this->values->vl_bc_pis * $this->values->aliq_pis) / 100)) {
            $this->errors[] = "[" . self::REG . "] " .
                "valor do campo “VL_PIS” deve corresponder ao valor da base de cálculo (VL_BC_PIS) multiplicado " .
                "pela alíquota aplicável ao item (ALIQ_PIS). No caso de aplicação da alíquota " .
                "do campo 07, o resultado deverá ser dividido pelo valor “100”.";
        }

        if ($this->roundFloat($this->values->vl_cofins) !=
            $this->roundFloat(($this->values->vl_bc_cofins * $this->values->aliq_cofins) / 100)) {
            $this->errors[] = "[" . self::REG . "] " .
                "o valor do campo “VL_COFINS” deve corresponder ao valor da base de cálculo (VL_BC_COFINS) " .
                "multiplicado pela alíquota aplicável ao item (ALIQ_COFINS). No caso de aplicação da alíquota " .
                "do campo 07, o resultado deverá ser dividido pelo valor “100”.";
        }
    }
}
