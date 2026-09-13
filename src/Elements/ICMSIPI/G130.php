<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;
use NFePHP\EFD\Common\ChaveAcesso;

class G130 extends Element
{
    const REG = 'G130';
    const LEVEL = 4;
    const PARENT = 'G100';

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
        if (!empty($this->std->chv_nfe_cte) and !ChaveAcesso::valida((string) $this->std->chv_nfe_cte)) {
            $this->errors[] = "[" . self::REG . "] "
                . " Dígito verificador incorreto no campo chave do "
                . " campo CHV_NFE_CTE";
        }
    }
}
