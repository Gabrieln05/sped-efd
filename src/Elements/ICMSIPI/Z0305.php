<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0600 do Bloco 0
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0305 extends Element
{
    const REG = '0305';
    const LEVEL = 0;
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
