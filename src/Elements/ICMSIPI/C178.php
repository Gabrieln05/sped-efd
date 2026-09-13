<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C178: OPERAÇÕES COM PRODUTOS SUJEITOS À TRIBUTAÇÃO DE IPI POR
 * UNIDADE OU QUANTIDADE DE PRODUTO
 * O registro tem por objetivo fornecer informações adicionais sobre os produtos cuja forma de tributação do IPI,
 * fixada em reais, seja calculada por unidade ou por determinada quantidade de produto, conforme tabelas de classes de
 * valores.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C178 extends Element
{
    const REG = 'C178';
    const LEVEL = 4;
    const PARENT = 'C170';

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
