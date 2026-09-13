<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C177: COMPLEMENTO DE ITEM - OUTRAS INFORMAÇÕES (código 01, 55) -
 * (VÁLIDO A PARTIR DE 01/01/2019)
 * Este registro deverá ser apresentado somente pelos contribuintes
 * obrigados por legislação específica de cada UF, com o
 * objetivo de agregar informações adicionais ao item, de acordo com tabela a ser publicada pela UF.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C177 extends Element
{
    const REG = 'C177';
    const LEVEL = 4;
    const PARENT = 'C170';

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
