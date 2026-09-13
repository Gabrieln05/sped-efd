<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C460 extends Element
{
    const REG = 'C460';
    const LEVEL = 4;
    const PARENT = 'C400';

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
            $this->errors[] = "[" . self::REG . "] "
                . " O Valor totao do documento fiscal "
                . "(VL_DOC) deve ser maior que 0";
        }
    }
}
