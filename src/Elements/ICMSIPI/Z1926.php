<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1926 extends Element
{
    const REG = '1926';
    const LEVEL = 5;
    const PARENT = '1920';

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
         * Campo 06 (NUM_PROC) Validação: se este campo estiver preenchido, os campos
         * IND_PROC e PROC também devem estar preenchidos.
         */
        if (!empty($this->std->num_proc) && (empty($this->std->ind_proc) || empty($this->std->proc))) {
            $this->errors[] = "[" . self::REG . "] Se o campo NUM_PROC estiver preenchido, "
            ."os campos IND_PROC e PROC também devem estar preenchidos.";
        }
    }
}
