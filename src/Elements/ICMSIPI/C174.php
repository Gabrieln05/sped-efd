<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C174: OPERAÇÕES COM ARMAS DE FOGO (CÓDIGO 01)
 * Este registro deve ser apresentado pelas empresas que realizam operações com armas de fogo (indústria, comércio e
 * demais) e deve ser fornecido apenas para operações de saída.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C174 extends Element
{
    const REG = 'C174';
    const LEVEL = 4;
    const PARENT = '';

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
