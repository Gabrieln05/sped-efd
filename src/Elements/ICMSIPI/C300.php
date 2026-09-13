<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C300: RESUMO DIÁRIO DAS NOTAS FISCAIS DE VENDA A CONSUMIDOR (CÓDIGO 02)
 * Este registro deve ser apresentado pelos contribuintes que utilizam notas fiscais de venda ao consumidor, não
 * emitidas por ECF. Trata-se de um resumo diário, por série e subsérie do
 * documento fiscal, de todas as operações praticadas.
 * Existirão tantos registros C300 quantos forem os agrupamentos de séries
 * e subséries dos documentos fiscais emitidos no dia.
 * Os valores de documentos fiscais cancelados não devem ser computados no valor total dos documentos (campo VL_DOC).
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C300 extends Element
{
    const REG = 'C300';
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
}
