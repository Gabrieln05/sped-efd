<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\Common\Keys;
use NFePHP\EFD\Common\Element;
use stdClass;

class C100 extends Element
{
    const REG = 'C100';
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
        if (!empty($this->std->chv_nfe) and !Keys::isValid($this->std->chv_nfe)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo campo chave da " .
                "nota fiscal eletronica (CHV_NFE)";
        }
    }
}
