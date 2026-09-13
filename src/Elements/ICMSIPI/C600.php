<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C600 extends Element
{
    const REG = 'C600';
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
        if ($this->std->cod_mod == '06' or $this->std->cod_mod == '28') {
            if (!in_array($this->std->cod_cons, ['01', '02', '03', '04', '05', '06', '07', '08'])) {
                $this->errors[] = "[" . self::REG . "] "
                    . " Se o campo COD_MOD for igual a 06 ou 28, então o campo "
                    . "o campo COD_CONS deve ser igual a "
                    . "'01', '02', '03', '04', '05', '06', '07' ou '08'";
            }
        }
    }
}
