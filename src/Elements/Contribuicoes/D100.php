<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Common\Element;
use stdClass;

class D100 extends Element
{
    const REG = 'D100';
    const LEVEL = 3;
    const PARENT = 'D001';

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
        if (!empty($this->std->chv_cte) and !ChaveAcesso::valida((string) $this->std->chv_cte)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo chave do " .
                " campo CHV_CTE";
        }

        if (!empty($this->std->chv_cte_ref) and !ChaveAcesso::valida((string) $this->std->chv_cte_ref)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo chave do " .
                " campo CHV_CTE_REF";
        }
    }
}
