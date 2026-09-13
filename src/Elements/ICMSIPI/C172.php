<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C172: OPERAÇÕES COM ISSQN (CÓDIGO 01)
 * Este registro tem por objetivo informar dados da prestação de serviços
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C172 extends Element
{
    const REG = 'C172';
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
