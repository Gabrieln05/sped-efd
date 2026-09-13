<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1310 extends Element
{
    const REG = '1310';
    const LEVEL = 3;
    const PARENT = '1300';

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
         * Campo 05 (VOL_DISP) Preenchimento: informar o volume disponível, que corresponde
         * à soma dos campos ESTQ_ABERT e VOL_ENTR, para o tanque especificado no campo NUM_TANQUE.
         */
        $somatorio = $this->values->estq_abert + $this->values->vol_entr;
        if ($this->values->vol_disp != $somatorio) {
            $this->errors[] = "[" . self::REG . "] Informar o volume disponível, "
            . "que corresponde à soma dos campos ESTQ_ABERT e VOL_ENTR, para o tanque especificado "
            . "no campo NUM_TANQUE";
        }

        /*
         * Campo 07 (ESTQ_ESCR) Preenchimento: informar o estoque escritural, que corresponde ao valor
         * constante no campo VOL_DISP menos o valor constante no campo VOL_SAIDAS, para o tanque
         * especificado no campo NUM_TANQUE.
         */
        $diferenca = $this->values->vol_disp - $this->values->vol_saidas;
        if ($this->values->estq_escr != $diferenca) {
            $this->errors[] = "[" . self::REG . "] Informar o estoque escritural, "
            . "que corresponde ao valor constante no campo VOL_DISP menos o valor constante no campo "
            . "VOL_SAIDAS, para o tanque especificado no campo NUM_TANQUE.";
        }
    }
}
