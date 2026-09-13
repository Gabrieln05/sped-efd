<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Registro: M215
 * Ajustes da Base de Cálculo da Contribuição para o PIS/Pasep Apurada
 * Este registro será utilizado pela pessoa jurídica para detalhar os totais de ajustes da base de cálculo,
 * informados nos campos 05 e 06 do registro pai M210.
 * A chave do registro é formada pelos campos: IND_AJ_BC; COD_AJ_BC; NUM_DOC; COD_CTA; DT_REF; CNPJ
*  e INFO_COMPL
 */
class M215 extends Element
{
    const REG = 'M215';
    const LEVEL = 4;
    const PARENT = 'M215';

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
}
