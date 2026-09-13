<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z0190 extends Element
{
    const REG = '0190';
    const LEVEL = 3;
    const PARENT = '1000';

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
        if ($this->std->unid == $this->std->descr) {
            $this->errors[] = "[" . self::REG . "] " .
                " Os campos UNID e DESCR ";
        }
    }
}
