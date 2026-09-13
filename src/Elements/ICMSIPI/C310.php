<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C310: DOCUMENTOS CANCELADOS DE NOTAS FISCAIS DE VENDA A CONSUMIDOR (CÓDIGO 02).
 * Este registro tem por objetivo informar os números dos documentos fiscais cancelados.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C310 extends Element
{
    const REG = 'C310';
    const LEVEL = 3;
    const PARENT = 'C300';

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
