<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO G001: ABERTURA DO BLOCO G
 * Este  registro  deve  ser  gerado  para  abertura  do  bloco  H,  indicando
 * se  há  registros  de  informações  no  bloco.
 * Obrigatoriamente deverá ser informado “0” no campo IND_MOV no período de
 * referência fevereiro de cada ano.
 * Contribuinte que apresente inventário com periodicidade anual ou trimestral,
 * caso apresente o inventário de 31/12 na EFD ICMS IPI de dezembro ou janeiro,
 * deve repetir a informação na escrituração de fevereiro.
 */
class G001 extends Element
{
    const REG = 'G001';
    const LEVEL = 1;
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
}
