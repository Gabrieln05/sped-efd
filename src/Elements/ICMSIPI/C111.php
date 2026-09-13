<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C111: PROCESSO REFERENCIADO
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C111 extends Element
{
    const REG = 'C111';
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
