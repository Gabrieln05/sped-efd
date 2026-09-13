<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z0110 extends Element
{
    const REG = '0110';
    const LEVEL = 2;
    const PARENT = '000';

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
        if (!$this->std->ind_apro_cred and ($this->std->cod_inc_trib != 2)) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo IND_APRO_CRED deve ser informado quando COD_INC_TRIB = 1 ou 3 ";
        }

        if ($this->std->ind_apro_cred and ($this->std->cod_inc_trib == 2)) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo IND_APRO_CRED não deve ser informado quando COD_INC_TRIB = 2 ";
        }

        if ((!$this->std->ind_reg_cum and ($this->std->cod_inc_trib == 2))) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo IND_REG_CUM deve ser informado quando COD_INC_TRIB = 2 ";
        }

        if (($this->std->ind_reg_cum and ($this->std->cod_inc_trib != 2))) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo IND_REG_CUM não deve ser informado quando COD_INC_TRIB != 2 ";
        }
    }
}
