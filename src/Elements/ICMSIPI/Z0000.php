<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Elemento 0000 do Bloco 0 OBRIGATÓRIO [1:1]
 * REGISTRO 0000: ABERTURA DO ARQUIVO DIGITAL E IDENTIFICAÇÃO DA ENTIDADE
 * Este Registro é obrigatório e corresponde ao primeiro registro do arquivo.
 * Obs.:  Nos  casos  de  EFD-ICMS/IPI  apresentadas  por  estabelecimentos
 * situados  em  outra  UF  e  que  possuam  Inscrição Estadual  nos  termos
 * do  Convênio  ICMS  nº  113/04  (serviços  de  comunicação  definidos
 * pela  Anatel),  deve-se  observar  o seguinte procedimento para preenchimento
 * do registro 0000:
 *   1) Informar o campo UF da unidade federada do tomador de serviços;
 *   2) Informar no campo IE a inscrição estadual na unidade federada do tomador
 *      de serviços;
 *   3) Informar no campo COD_MUN o código de município correspondente à capital
 *      do estado do tomador de serviços.
 *
 * https://www.confaz.fazenda.gov.br/legislacao/atos/2008/AC009_08
 *
 * NOTA: usada a letra Z no nome da Classe pois os nomes não podem ser exclusivamente
 * numeréricos e também para não confundir os com elementos do bloco B
 */
class Z0000 extends Element
{
    const REG = '0000';
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

    /**
     * Aqui são colocadas validações adicionais que requerem mais logica
     * e processamento
     * Deve ser usado apenas quando necessário
     * @throws \InvalidArgumentException
     */
    public function postValidation()
    {
        if (!$this->std->cnpj xor $this->std->cpf) {
            $this->errors[] = "[" . self::REG . "] Deve ser "
                . "informado apenas o CNPJ ou o CPF nunca os dois.";
        }
        if (!empty($this->std->cpf) && $this->std->ind_ativ == 0) {
            $this->errors[] = "[" . self::REG . "] Como foi "
                . "informado o CPF então IND_ATIV deve ser igual a 1.";
        }
    }
}
