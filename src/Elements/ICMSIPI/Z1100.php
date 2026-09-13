<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1100 extends Element
{
    const REG = '1100';
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
         * Campo 06 (NRO_RE) Preenchimento: este campo deve ser preenchido se o campo IND_DOC for “0” (zero).
         */
        if ($this->std->ind_doc == 0 && empty($this->std->nro_re)) {
            $this->errors[] = "[" . self::REG . "] Este campo deve ser preenchido se o "
            . "campo IND_DOC for “0” (zero).";
        }
    }
}
