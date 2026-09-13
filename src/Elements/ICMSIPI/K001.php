<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO K001: ABERTURA DO BLOCO K
 * Este registro deve ser gerado para abertura do bloco K, indicando se há
 * registros de informações no bloco.
 */
class K001 extends Element
{
    const REG = 'K001';
    const LEVEL = 1;
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
