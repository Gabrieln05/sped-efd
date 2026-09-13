<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0600 do Bloco 0
 * REGISTRO 0200: TABELA DE IDENTIFICAÇÃO DO ITEM (PRODUTO E SERVIÇOS)
 * Este registro tem por objetivo informar mercadorias, serviços, produtos ou
 * quaisquer outros itens concernentes às transações fiscais e aos movimentos de
 * estoques em processos produtivos, bem como os insumos. Quando ocorrer alteração
 * somente na descrição do item, sem que haja descaracterização deste, ou seja,
 * criação de um novo item, a alteração deve constar no registro 0205.
 * Somente devem ser apresentados itens referenciados nos demais blocos, exceto
 * se for apresentado o fator de conversão no registro 0220 (a partir de julho de 2012).
 * A identificação do item (produto ou serviço) deverá receber o código próprio
 * do informante do arquivo em qualquer documento, lançamento efetuado ou arquivo
 * informado (significa que o código de produto deve ser o mesmo na emissão dos
 * documentos fiscais, na entrada das mercadorias ou em qualquer outra informação
 * prestada ao fisco), observando-se ainda que:
 * a) O código utilizado não pode ser duplicado ou atribuído a itens (produto
 * ou serviço) diferentes. Os produtos e serviços que sofrerem alterações em
 * suas características básicas deverão ser identificados com códigos diferentes.
 * Em caso de alteração de codificação, deverão ser informados o código e a
 * descrição anteriores e as datas de validade inicial e final no registro 0205;
 * b) Não é permitida a reutilização de código que tenha sido atribuído para
 * qualquer produto anteriormente.
 * c) O código de item/produto a ser informado no Inventário deverá ser aquele
 * utilizado no mês inventariado.
 * d) A discriminação do item deve indicar precisamente o mesmo, sendo vedadas
 * discriminações diferentes para o mesmo item ou discriminações genéricas
 * (a exemplo de "diversas entradas", "diversas saídas", "mercadorias para revenda", etc),
 * ressalvadas as operações abaixo, desde que não destinada à posterior circulação
 * ou apropriação na produção:
 * 1- de aquisição de "materiais para uso/consumo" que não gerem direitos a créditos;
 * 2- que discriminem por gênero a aquisição de bens para o "ativo fixo" (e sua baixa);
 * 3- que contenham os registros consolidados relativos aos contribuintes com
 * atividades econômicas de fornecimento de energia elétrica, de fornecimento de
 * água canalizada, de fornecimento de gás canalizado, e de prestação de serviço de
 * comunicação e telecomunicação que poderão, a critério do Fisco,
 * utilizar registros consolidados por classe de consumo para representar suas
 * saídas ou prestações.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0200 extends Element
{
    const REG = '0200';
    const LEVEL = 2;
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
