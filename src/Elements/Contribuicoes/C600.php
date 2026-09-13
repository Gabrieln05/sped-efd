<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class C600 extends Element
{
    const REG = 'C600';
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
        if ((int)$this->std->qtd_canc > (int)$this->std->qtd_cons) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo QTD_CANC deve ser menor ou igual ao valor do campo QTD_CONS";
        }
    }
}
