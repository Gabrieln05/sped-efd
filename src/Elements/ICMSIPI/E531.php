<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class E531 extends Element
{
    const REG = 'E531';
    const LEVEL = 5;
    const PARENT = 'E530';

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
         * Campo 10 (CHV_NFE) Validação: A informação da chave é obrigatória quando o COD_MOD = “55”.
         */
        if ($this->std->cod_mod == '55' && empty($this->std->chv_nfe)) {
            $this->errors[] = "[" . self::REG . "] A informação da chave é obrigatória "
            . "quando o COD_MOD = “55”.";
        }
    }
}
