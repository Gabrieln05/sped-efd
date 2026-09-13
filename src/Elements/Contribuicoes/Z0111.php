<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z0111 extends Element
{
    const REG = '0111';
    const LEVEL = 3;
    const PARENT = '0110';

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
        $somatorio = $this->values->rec_bru_ncum_trib_mi;
        $somatorio += $this->values->rec_bru_ncum_nt_mi;
        $somatorio += $this->values->rec_bru_ncum_exp;
        $somatorio += $this->values->rec_bru_cum;

        if ($this->values->rec_bru_total != $somatorio) {
            $this->errors[] = "[" . self::REG . "] " .
                " A soma dos valores dos campos 02, 03, 04 e " .
                "05 deve ser igual ao valor informado no campo REC_BRU_TOTAL.";
        }
    }
}
