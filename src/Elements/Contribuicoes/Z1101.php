<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;
use NFePHP\EFD\Common\ChaveAcesso;

class Z1101 extends Element
{
    const REG = '1101';
    const LEVEL = 3;
    const PARENT = '1100';

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
        if (!empty($this->std->chv_nfe) and !ChaveAcesso::valida((string) $this->std->chv_nfe)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo chave do " .
                " campo CHV_NFE";
        }

        $multiplicacao = $this->values->vl_bc_pis * $this->values->aliq_pis;
        if (number_format((float) $this->values->vl_pis, 2) != number_format($multiplicacao/100, 2)) {
            $this->errors[] = "[" . self::REG . "] " .
            "O campo VL_PIS deve de ser o calculo da multiplicacao " .
            "da base de calculo do PIS com a aliquota do PIS, o resultado dividido por 100";
        }
    }
}
