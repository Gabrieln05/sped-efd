<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C880: INFORMAÇÕES COMPLEMENTARES DAS OPERAÇÕES DE SAÍDA DE MERCADORIAS SUJEITAS À
 * SUBSTITUIÇÃO TRIBUTÁRIA (CF-E-SAT) (CÓDIGO 59)
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C880 extends Element
{
    const REG = 'C880';
    const LEVEL = 4;
    const PARENT = 'C870';

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
