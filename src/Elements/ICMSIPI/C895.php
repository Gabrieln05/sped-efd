<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C895: OBSERVAÇÕES DO LANÇAMENTO FISCAL (CÓDIGO 59)
 */
class C895 extends Element
{
    const REG = 'C895';
    const LEVEL = 3;
    const PARENT = 'C860';

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
