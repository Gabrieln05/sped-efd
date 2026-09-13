<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C595:INFORMAÇÕES DO FUNDO DE COMBATE À POBREZA – FCP NA NF3e (CÓDIGO 66)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C595 extends Element
{
    const REG = 'C595';
    const LEVEL = 3;
    const PARENT = 'C500';

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
