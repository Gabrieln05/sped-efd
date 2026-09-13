<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C425 extends Element
{
    const REG = 'C425';
    const LEVEL = 5;
    const PARENT = 'C420';

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
        if ($this->values->vl_item <= 0) {
            $this->errors[] = "[" . self::REG . "] "
                . " O Valor acumulado do item (VL_ITEM) deve ser maior que 0";
        }
    }
}
