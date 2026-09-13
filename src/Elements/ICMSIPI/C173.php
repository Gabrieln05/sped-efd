<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C173: OPERAÇÕES COM MEDICAMENTOS (CÓDIGO 01 e 55).
 * Este registro deve ser apresentado pelas empresas do segmento farmacêutico (distribuidoras, indústrias,
 * revendedoras e importadoras), exceto comércio varejista.
 * A obrigatoriedade deriva do §26 do art. 19 do Convênio S/N de 1970
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C173 extends Element
{
    const REG = 'C173';
    const LEVEL = 4;
    const PARENT = 'C';

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
        if ((float)$this->std->qtd_item <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " O valor do preco tabelado (VL_TAB_MAX) deve ser maior do que zero ";
        }
    }
}
