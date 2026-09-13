<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1400 extends Element
{
    const REG = '1400';
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
         * Campo 04 (VALOR) Validação: o valor informado no campo deve ser maior que “0” (zero).
         * Se o valor for negativo ou zero, o contribuinte não deve prestar a informação no mês.
         */
        if ($this->values->valor <= 0) {
            $this->errors[] = "[" . self::REG . "] Se o valor for negativo ou "
            ."zero, o contribuinte não deve prestar a informação no mês.";
        }
    }
}
