<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0400 do Bloco 0
 *
 * Este registro tem por objetivo codificar os textos das diferentes
 * naturezas  da operação/prestações discriminadas nos documentos fiscais.
 * Esta codificação  e suas descrições são livremente criadas e mantidas
 * pelo contribuinte.
 *
 * Este registro não se refere a CFOP. Algumas empresas utilizam outra
 * classificação  além das apresentadas nos CFOP. Esta codificação permite
 * informar estes agrupamentos próprios.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0400 extends Element
{
    const REG = '0400';
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
