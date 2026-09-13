<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\Common\Keys;
use NFePHP\EFD\Common\Element;
use stdClass;

class A100 extends Element
{
    const REG = 'A100';
    const LEVEL = 2;
    const PARENT = 'A000';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
        //$this->postValidation();
    }

    public function postValidation()
    {
        if ($this->std->chv_nfse and !Keys::isValid($this->std->chv_nfse)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo campo chave da " .
                "nota fiscal de serviço eletronica (CHV_NFSE)";
        }
    }
}
