<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0150 do Bloco 0
 * REGISTRO 0150: TABELA DE CADASTRO DO PARTICIPANTE
 * Registro utilizado para informações cadastrais das pessoas físicas ou
 * jurídicas envolvidas nas transações comerciais com o estabelecimento,
 * no período. Participantes sem movimentação no período não devem ser
 * informados neste registro.
 * Obs.: Não  devem  ser  informados  como participantes  os CNPJ  e CPF apenas
 * citados  nos registros  C350 –  Nota Fiscal  de Venda  ao  Consumidor,
 * C460  –  Documento  Fiscal  emitido  por  ECF
 * e no  C100,  quando  se  tratar  de NFC-e -  Nota Fiscal Eletrônica ao
 * Consumidor Final - modelo 65.
 * O  código  a ser utilizado  é de livre  atribuição  pelo  contribuinte e
 * possui validade  para o  arquivo  informado.
 * Este código deve ser único para o participante, não havendo necessidade,
 * sempre que possível, de se criar um código para cada período.
 * Não podem ser informados dois ou mais registros com o mesmo Código de Participante.
 * Para o caso de participante pessoa física com mais de um endereço,
 * podem ser fornecidos mais de um registro, com o mesmo NOME e CPF.
 * Neste caso, deve ser usado um COD_PART para cada registro, alterando os demais dados.
 * As  informações  deste registro  representam  os  dados  atualizados  no  
 * último  evento  fiscal  (emissão/recebimento  de documento fiscal) da EFD-ICMS/IPI.
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0150 extends Element
{
    const REG = '0150';
    const LEVEL = 2;
    const PARENT = '0100';

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

    /**
     * Aqui são colocadas validações adicionais que requerem mais logica
     * e processamento
     * Deve ser usado apenas quando necessário
     * @throws \InvalidArgumentException
     */
    public function postValidation()
    {
        if ($this->std->cod_pais == '1058' || $this->std->cod_pais == '01058') {
            if (!$this->std->cnpj xor $this->std->cpf) {
                $this->errors[] = "[" . self::REG . "] Deve ser informado apenas o CNPJ ou o CPF";
            }
        }
    }
}
