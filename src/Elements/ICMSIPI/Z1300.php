<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1300 extends Element
{
    const REG = '1300';
    const LEVEL = 2;
    const PARENT = '1001';

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
         * Campo 06 (VOL_DISP) Preenchimento: informar o volume disponível, que corresponde
         * à soma dos campos ESTQ_ABERT e VOL_ENTR.
         */
        $somatorio = $this->values->estq_abert + $this->values->vol_entr;
        if ($this->std->vol_disp != number_format($somatorio, 3, ',', '')) {
            $this->errors[] = "[" . self::REG . "] Informar o volume "
            . "disponível, que corresponde à soma dos campos ESTQ_ABERT e VOL_ENTR.";
        }

        /*
         * Campo 08 (ESTQ_ESCR) Preenchimento: informar o estoque escritural, que corresponde
         * ao valor constante no campo VOL_DISP
         * menos o valor constante no campo VOL_SAIDAS.
         */
        $diferenca = $this->values->vol_disp - $this->values->vol_saidas;
        if ($this->std->estq_escr != number_format($diferenca, 3, ',', '')) {
            $this->errors[] = "[" . self::REG . "] Informar o estoque "
            . "escritural, que corresponde ao valor constante no campo VOL_DISP menos o "
            . "valor constante no campo VOL_SAIDAS.";
        }
    }
}
