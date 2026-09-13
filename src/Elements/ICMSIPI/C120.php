<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C120: COMPLEMENTO DE DOCUMENTO - OPERAÇÕES DE IMPORTAÇÃO (CÓDIGOS 01 e 55)
 * Este registro tem por objetivo informar detalhes das operações de importação, que estejam sendo documentadas pela
 * nota fiscal escriturada no registro C100, quando o campo IND_OPER for igual a “0” (zero),
 * indicando operação de entrada.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C120 extends Element
{
    const REG = 'C120';
    const LEVEL = 3;
    const PARENT = 'C100';

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
