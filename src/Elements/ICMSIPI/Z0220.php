<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0220 do Bloco 0
 * REGISTRO 0220: FATORES DE CONVERSÃO DE UNIDADES
 * Este registro tem por objetivo informar os fatores de conversão dos itens
 * discriminados na Tabela de Identificação do Item (Produtos e Serviços)
 * entre a unidade informada no registro 0200 e as unidades informadas nos
 * registros dos documentos fiscais ou nos registros do controle da produção
 * e do estoque - Bloco K.
 * Nos documentos eletrônicos de emissão própria, quando a unidade comercial for
 * diferente da unidade do inventário, este registro deverá ser informado.
 * Quando for utilizada unidade de inventário (bloco H) ou unidade de medida de
 * controle de estoque (bloco K) diferente da unidade comercial do produto é
 * necessário informar o registro 0220 para informar os fatores de conversão
 * entre as unidades.
 * Na movimentação interna entre mercadorias (Registro K220), caso a unidade de
 * medida da mercadoria de destino for diferente da unidade de medida da mercadoria
 * de origem, este registro é obrigatório para informar o fator de conversão entre
 * a unidade de medida de origem e a unidade de medida de destino.
 * Validação do Registro: Não podem ser informados dois ou mais registros com o
 * mesmo  conteúdo  no  campo UNID_CONV.
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0220 extends Element
{
    const REG = '0220';
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
