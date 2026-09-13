<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class D600 extends Element
{
    const REG = 'D600';
    const LEVEL = 3;
    const PARENT = 'D001';

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
        if ($this->values->vl_doc <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo VL_DOC deve ser maior do que 0";
        }
    }
}
