<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C100: NOTA FISCAL (CÓDIGO 01), NOTA FISCAL AVULSA (CÓDIGO 1B),
 * NOTA FISCAL DE PRODUTOR (CÓDIGO 04), NF-e (CÓDIGO 55) e NFC-e (CÓDIGO 65).
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C100 extends Element
{
    const REG = 'C100';
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
        if ($this->std->cod_mod == 65 and $this->std->cod_mod == 55) {
            if (empty($this->std->chv_nfe)) {
                $this->errors[] = "[" . self::REG . "] " .
                    " Dígito verificador incorreto no campo campo chave do " .
                    "conhecimento de transporte eletrônico (CHV_CTE)";
            }
        }
        if (!empty($this->std->chv_nfe) and !ChaveAcesso::valida((string) $this->std->chv_nfe)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo campo chave da " .
                "nota fiscal eletronica (CHV_NFE)";
        }
        return true;
    }
}
