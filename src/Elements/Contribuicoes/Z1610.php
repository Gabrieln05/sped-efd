<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1610 extends Element
{
    const REG = '1610';
    const LEVEL = 3;
    const PARENT = '1600';

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
        $multiplicacao = $this->values->vl_bc_cofins * $this->values->aliq_cofins;
        if (number_format((float) $this->values->vl_cofins, 2) != number_format($multiplicacao/100, 2)) {
            $this->errors[] = "[" . self::REG . "] " .
            "O campo VL_COFINS deve de ser o calculo da multiplicacao " .
            "da base de calculo do cofins com a aliquota do cofins, o resultado dividido por 100";
        }
    }
}
