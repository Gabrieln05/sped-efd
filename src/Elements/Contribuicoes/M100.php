<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class M100 extends Element
{
    const REG = 'M100';
    const LEVEL = 3;
    const PARENT = 'M001';

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
        $calculo = $this->values->vl_cred+$this->values->vl_ajus_acres;
        $calculo = $calculo-$this->values->vl_ajus_reduc;
        if ($this->values->vl_cred_dif>$calculo) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_CRED_DIF não deve de ser maior do que  " .
                "não pode ser maior que VL_CRED + VL_AJUS_ACRES - VL_AJUS_REDUC";
        }
    }
}
