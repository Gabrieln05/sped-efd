<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C700 extends Element
{
    const REG = 'C700';
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
        if ($this->std->nro_ord_ini > $this->std->nro_ord_fin) {
            $this->errors[] = "[" . self::REG . "] "
                . " O do campo NRO_ORD_INI deve ser menor ou igual ao campo "
                . "NRO_ORD_FIN";
        }
    }
}
