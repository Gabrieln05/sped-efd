<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C870: ITENS DO RESUMO DIÁRIO DOS DOCUMENTOS (CF-E-SAT) (CÓDIGO 59)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C870 extends Element
{
    const REG = 'C870';
    const LEVEL = 3;
    const PARENT = 'C800';

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
