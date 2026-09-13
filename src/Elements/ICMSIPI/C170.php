<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C170: ITENS DO DOCUMENTO (CÓDIGO 01, 1B, 04 e 55).
 * Registro obrigatório para discriminar os itens da nota fiscal (mercadorias e/ou serviços constantes em notas
 * conjugadas), inclusive em operações de entrada de mercadorias acompanhadas
 * de Nota Fiscal Eletrônica (NF-e) de emissão de terceiros.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C170 extends Element
{
    const REG = 'C170';
    const LEVEL = 3;
    const PARENT = '';

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
        if (((float)  str_replace('.', '', str_replace(',', '.', $this->std->qtd))) < 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " O valor do campo  Quantidade do item (QTD) deve ser positivo ";
        }
        return false;
    }
}
