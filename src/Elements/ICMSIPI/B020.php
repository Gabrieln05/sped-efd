<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B020 extends Element
{
    const REG = 'B020';
    const LEVEL = 2;
    const PARENT = 'B001';

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
        /*
         * Campo 03 (IND_EMIT) Validação: se este campo tiver valor igual a “1” (um),
         * o campo IND_OPER deve ser igual a “0” (zero).
         */
        if ($this->std->ind_emit == '1' && $this->std->ind_oper != 0) {
            $this->errors[] = "[" . self::REG . "] Se o campo IND_EMIT tiver valor igual a “1” (um), "
            ."o campo IND_OPER deve ser igual a “0” (zero).";
        }

        /*
         * Campo 05 (COD_MOD) Preenchimento: O modelo “65” só pode ser informado no caso
         * de prestação de serviço, ou seja, campo “IND_OPER” preenchido com “1”.
         */
        if ($this->std->cod_mod == '65' && $this->std->ind_oper != '1') {
            $this->errors[] = "[" . self::REG . "] O modelo “65” só pode ser informado "
            ."no caso de prestação de serviço.";
        }

        /*
         * Campo 09 (CHV_NFE) Preenchimento: Este campo é de preenchimento obrigatório
         * para COD_MOD igual a “55” e “65”.
         */
        if (in_array($this->std->cod_mod, array('55', '65')) && empty($this->std->chv_nfe)) {
            $this->errors[] = "[" . self::REG . "] Este campo é de preenchimento obrigatório "
            ."para COD_MOD igual a “55” e “65”.";
        }
    }
}
