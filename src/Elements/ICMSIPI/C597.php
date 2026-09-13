<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C597:OUTRAS   OBRIGAÇÕES   TRIBUTÁRIAS,   AJUSTES   E   INFORMAÇÕES   DE   VALORES PROVENIENTES DE
 * DOCUMENTO FISCAL
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C597 extends Element
{
    const REG = 'C597';
    const LEVEL = 4;
    const PARENT = 'C595';

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
