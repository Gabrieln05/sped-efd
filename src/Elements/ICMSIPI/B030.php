<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B030 extends Element
{
    const REG = 'B030';
    const LEVEL = 2;
    const PARENT = 'B001';

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
         * Campo 05 (NUM_DOC_FIN) Validação: o valor tem de ser maior ou igual ao
         * valor informado no campo NUM_DOC_INI.
         */
        if ($this->std->num_doc_fin < $this->std->num_doc_ini) {
            $this->errors[] = "[" . self::REG . "] O valor informado no campo NUM_DOC_FIN "
            ."tem de ser maior ou igual ao valor informado no campo NUM_DOC_INI.";
        }
    }
}
