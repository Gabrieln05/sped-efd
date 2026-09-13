<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C321: ITENS DO RESUMO DIÁRIO DOS DOCUMENTOS (CÓDIGO 02).
 * Este registro é o detalhamento, por itens de mercadoria,
 * da consolidação diária dos valores das notas fiscais de venda
 * ao consumidor, não emitidas por ECF.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C321 extends Element
{
    const REG = 'C321';
    const LEVEL = 4;
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
