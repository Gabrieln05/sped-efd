<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1500 extends Element
{
    const REG = '1500';
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
        if ($this->std->vl_cred_per_efd and
            !in_array($this->std->cod_cred, [201, 202, 203, 204, 208, 301, 302, 303, 304, 307,308])) {
            $this->errors[] = "[" . self::REG . "] " .
                "O valor do campo VL_CRED_PER_EFD deverá ser informado apenas se o campo
                COD_CRED for igual a 201, 202, 203, 204, 208, 301, 302, 303, 304, 307 ou 308";
        }
    }
}
