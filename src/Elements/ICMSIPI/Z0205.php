<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0205 do Bloco 0
 * REGISTRO 0205: ALTERAÇÃO DO ITEM
 * Este registro tem por objetivo informar alterações ocorridas na descrição do
 * produto ou quando ocorrer alteração na codificação  do produto, desde que não
 * o descaracterize ou haja modificação  que o identifique como sendo  novo produto.
 * Caso não tenha ocorrido movimentação no período da alteração do item, deverá
 * ser informada no primeiro período em que houver movimentação do item ou no inventário.
 * Validação do Registro:  Não  podem ser informados dois ou mais registros
 * com sobreposição  de períodos para  o mesmo campo alterado (02 ou 05).
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0205 extends Element
{
    const REG = '0205';
    const LEVEL = 3;
    const PARENT = '0200';

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
