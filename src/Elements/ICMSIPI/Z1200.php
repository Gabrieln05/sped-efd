<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1200 extends Element
{
    const REG = '1200';
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
         * Campo 07 (SLD_CRED_FIM) Validação: O valor desse campo deve ser igual à soma dos valores dos campos
         * SLD_CRED, CRED_APR e CRED_RECEB, diminuída do valor do campo CRED_UTIL.
         */
        $somatorio = $this->values->sld_cred
                    + $this->values->cred_apr
                    - $this->values->cred_receb
                    - $this->values->cred_util;

        if ($this->std->sld_cred_fim != number_format($somatorio, 2, ',', '')) {
            $this->errors[] = "[" . self::REG . "] O valor do campo SLD_CRED_FIM "
            . "deve ser igual à soma dos valores dos campos SLD_CRED, CRED_APR e CRED_RECEB, diminuída "
            . "do valor do campo CRED_UTIL.";
        }
    }
}
