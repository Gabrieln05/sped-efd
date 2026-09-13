<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO D735: OBSERVAÇÕES DO LANÇAMENTO FISCAL (CÓDIGO 62)
 */
class D735 extends Element
{
    const REG = 'D735';
    const LEVEL = 3;
    const PARENT = 'D700';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}
