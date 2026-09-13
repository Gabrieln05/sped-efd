<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C500 extends Element
{
    const REG = 'C500';
    const LEVEL = 2;
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
        if ($this->values->vl_doc <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O do campo VL_DOC deve ser maior do que 0";
        }
        if ($this->values->vl_forn <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O do campo VL_FORN deve ser maior do que 0";
        }
    }
}
