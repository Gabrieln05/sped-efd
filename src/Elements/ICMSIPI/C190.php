<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C190: REGISTRO ANALÍTICO DO DOCUMENTO (CÓDIGO 01, 1B, 04, 55 e 65)
 * Este registro tem por objetivo representar a escrituração dos documentos fiscais totalizados por CST, CFOP e
 * Alíquota de ICMS.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C190 extends Element
{
    const REG = 'C190';
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
