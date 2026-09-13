<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO K220: OUTRAS MOVIMENTAÇÕES INTERNAS ENTRE MERCADORIAS
 * Este registro tem o objetivo de informar a movimentação interna entre
 * mercadorias de tipos:
 * 00 – Mercadoria para revenda;
 * 01 – Matéria-Prima;
 * 02 – Embalagem;
 * 03 – Produtos em Processo;
 * 04 – Produto Acabado;
 * 05 – Subproduto
 * e 10 – Outros Insumos – campo TIPO_ITEM do Registro 0200; que não se
 * enquadre nas movimentações internas já informadas nos demais tipos de
 * registros.
 * Exemplos:
 * 1) Reclassificação de um produto em outro código em função do cliente
 * a que se destina: o contribuinte aponta a quantidade produzida de
 * determinado produto, por exemplo, código 1.
 * Este produto, quando destinado a determinado cliente recebe uma outra
 * codificação, código 2. Neste caso há a necessidade de controle do
 * estoque por cliente.
 * Assim  o  contribuinte deverá fazer um registro K220 dando saída no estoque
 * do  produto 1 e entrada no estoque do produto 2.
 * 2) Reclassificação de um produto em função do controle de qualidade:
 * quando o produto não conforme não permanecerá com o mesmo código,por exemplo:
 * venda como produto com defeito ou subproduto;
 * consumo em outra fase de produção.
 * Caso o produto não conforme tiver como destino o reprocessamento, onde o
 * produto reprocessado permanecerá com o mesmo código do produto a ser
 * reprocessado, deverá ser escriturado no Registro K260.
 * A quantidade movimentada deve ser expressa, obrigatoriamente, na unidade
 * de medida do item de origem e do item de destino constante no campo 06 do
 * registro 0200(UNID_INV).
 * Validação do Registro: A chave deste registro são os campos DT_MOV,
 * COD_ITEM_ORI e COD_ITEM_DEST
 */
class K220 extends Element
{
    const REG = 'K220';
    const LEVEL = 3;
    const PARENT = 'K215|K210|K200|K100';

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
