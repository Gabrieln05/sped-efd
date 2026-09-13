<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0100 do Bloco 0
 * REGISTRO 0100: DADOS DO CONTABILISTA
 * Registro utilizado para identificação do contabilista responsável pela
 * escrituração fiscal do estabelecimento, mesmo que o contabilista seja
 * funcionário da empresa ou prestador de serviço.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0100 extends Element
{
    const REG = '0100';
    const LEVEL = 2;
    const PARENT = '0015|0005';

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
