<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\Common\Keys;
use NFePHP\EFD\Common\Element;
use stdClass;

class C800 extends Element
{
    const REG = 'C800';
    const LEVEL = 3;
    const PARENT = 'C001';

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
        if (!empty($this->std->chv_cfe) and !Keys::isValid($this->std->chv_cfe)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo chave do " .
                "cupom fiscal eletronica (CHV_CFE)";
        }
    }
}
