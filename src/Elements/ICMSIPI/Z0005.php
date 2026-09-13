<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0005 do Bloco 0
 * REGISTRO 0005: DADOS COMPLEMENTARES DA ENTIDADE
 * Registro obrigatório utilizado para complementar as informações de
 * identificação do informante do arquivo.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0005 extends Element
{
    const REG = '0005';
    const LEVEL = 2;
    const PARENT = '0001';

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
