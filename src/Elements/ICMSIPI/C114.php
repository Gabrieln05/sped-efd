<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C114: CUPOM FISCAL REFERENCIADO
 * Este registro será utilizado para informar, detalhadamente, nas operações de saídas, cupons fiscais que tenham sido
 * mencionados nas informações complementares do documento que está sendo escriturado no registro C100. Nas operações de
 * entradas, somente informar quando o emitente do cupom fiscal for o próprio informante do arquivo.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C114 extends Element
{
    const REG = 'C114';
    const LEVEL = 4;
    const PARENT = 'C110';

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
