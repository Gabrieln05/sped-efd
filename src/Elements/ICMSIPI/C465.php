<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\Common\Keys;
use NFePHP\EFD\Common\Element;
use stdClass;

class C465 extends Element
{
    const REG = 'C465';
    const LEVEL = 5;
    const PARENT = 'C460';

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
        /**
         * Verifica a chave cfe
         */
        if ($this->std->chv_cfe and !Keys::isValid($this->std->chv_cfe)) {
            $this->errors[] = "[" . self::REG . "] "
                . " Dígito verificador incorreto no da Chave "
                . "do Cupom Fiscal Eletrônico (CHV_CFE)";
        }
    }
}
