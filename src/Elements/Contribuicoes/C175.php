<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class C175 extends Element
{
    const REG = 'C175';
    const LEVEL = 4;
    const PARENT = 'C170';

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
        if (empty($this->values->vl_cofins)) {
            return;
        }
        $multiplicacao = null;
        if (!empty($this->values->vl_bc_cofins) && !empty($this->values->aliq_cofins)) {
            $multiplicacao = $this->values->vl_bc_cofins * $this->values->aliq_cofins/100;
        }
        if (!empty($this->values->quant_bc_cofins) && !empty($this->values->aliq_cofins_quant)) {
            $multiplicacao = $this->values->quant_bc_cofins * $this->values->aliq_cofins_quant;
        }
        if ($multiplicacao == null) {
            return;
        }
        if (number_format((float) $this->values->vl_cofins, 2) != number_format($multiplicacao, 2)) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_COFINS deve de ser o calculo da multiplicacao " .
                "da base de calculo do cofins com a aliquota do cofins";
        }
    }
}
