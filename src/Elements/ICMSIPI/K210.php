<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO K210: DESMONTAGEM DE MERCADORIAS – ITEM DE ORIGEM
 * Este registro tem o objetivo de escriturar a desmontagem de mercadorias
 * de tipos:
 * 00 – Mercadoria para revenda;
 * 01 –  Matéria-Prima;
 * 02  –  Embalagem;
 * 03  –  Produtos  em  Processo;
 * 04  –  Produto  Acabado;
 * 05  –  Subproduto
 * e  10  –  Outros Insumos – campo TIPO_ITEM do Registro 0200,
 * no que se refere à  saída do estoque do item de origem.
 * A quantidade deve ser expressa, obrigatoriamente, na unidade de medida de
 * controle de estoque constante no campo 06 do registro 0200, UNID_INV.
 * Validação do Registro: Quando houver identificação da ordem de serviço,
 * a chave deste registro são os campos: COD_DOC_OS e COD_ITEM_ORI.
 * Nos casos em que a ordem de serviço não for identificada, o campo chave
 * passa a ser COD_ITEM_ORI.
 */
class K210 extends Element
{
    const REG = 'K210';
    const LEVEL = 3;
    const PARENT = 'K200|K100';

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
