<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class P100 extends Element
{
    const REG = 'P100';
    const LEVEL = 3;
    const PARENT = 'P001';

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
        if ($this->values->vl_rec_ativ_estab > $this->values->vl_rec_tot_est) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_REC_ATIV_ESTAB deve ser MENOR ou " .
                "IGUAL ao valor do Campo VL_REC_TOT_EST";
        }
    }
}
