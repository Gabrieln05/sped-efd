<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\Common\Keys;
use NFePHP\EFD\Common\Element;
use stdClass;

class C116 extends Element
{
    const REG = 'C116';
    const LEVEL = 4;
    const PARENT = 'C110';

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
         * Verifica a chave do cupom fiscal eletronico
         */
        if (!empty($this->std->chv_cfe) and !Keys::isValid($this->std->chv_cfe)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo campo chave do " .
                "cupom fiscal eletronico (CHV_CFE)";
        }

        return true;
    }
}
