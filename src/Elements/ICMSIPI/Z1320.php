<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1320 extends Element
{
    const REG = '1320';
    const LEVEL = 4;
    const PARENT = '1310';

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
         * Campo 11 (VOL_VENDAS) Preenchimento: informar o volume de vendas por bico, ligado ao tanque,
         * que corresponde ao valor fornecido no campo VAL_FECHA menos a soma do campo VAL_ABERT com
         * o campo VOL_AFERI.
         */
        $diferenca = $this->values->val_fecha - $this->values->val_abert - $this->values->vol_aferi;
        if ($this->std->vol_vendas != number_format($diferenca, 3, ',', '')) {
            $this->errors[] = "[" . self::REG . "] Informar o volume de vendas por bico, "
            ."ligado ao tanque, que corresponde ao valor fornecido no campo VAL_FECHA menos a soma do campo "
            ."VAL_ABERT com o campo VOL_AFERI.";
        }
    }
}
