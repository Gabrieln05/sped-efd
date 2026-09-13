<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class C170 extends Element
{
    const REG = 'C170';
    const LEVEL = 3;
    const PARENT = 'C100';

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
        $multiplicacao = $this->values->vl_bc_cofins * $this->values->aliq_cofins / 100;
        if (isset($this->values->quant_bc_cofins) && $this->values->quant_bc_cofins > 0) {
            $multiplicacao = $this->values->quant_bc_cofins * $this->values->aliq_cofins_quant;
        }

        if (number_format((float) $this->values->vl_cofins, 2) != number_format($multiplicacao, 2)) {
            $message = 'vl_cofins: '.$this->values->vl_cofins.'<br>';
            $message .= 'multiplicacao: '.$multiplicacao.'<br>';
            echo $message;
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_COFINS deve de ser o calculo da multiplicacao " .
                "da base de calculo do cofins com a aliquota do cofins";
        }
    }
}
