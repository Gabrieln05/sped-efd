<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0001 do Bloco 0
 * REGISTRO 0001: ABERTURA DO BLOCO 0
 * Este registro deve ser gerado para abertura do bloco 0 e indica as
 * informações previstas para este bloco.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0001 extends Element
{
    const REG = '0001';
    const LEVEL = 1;
    const PARENT = '0000';

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
