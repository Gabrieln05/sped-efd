<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C175: OPERAÇÕES COM VEÍCULOS NOVOS (CÓDIGO 01 e 55)
 * Este registro deve ser apresentado pelas empresas do segmento automotivo (montadoras-capítulo 87 da NCM,
 * concessionárias e importadoras) para informar os itens relativos
 * aos veículos novos. Deve ser informado nas operações de
 * entrada e saída (exceto pelos contribuintes emissores de NF-e), exceto quando se tratar de operações de exportação.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C175 extends Element
{
    const REG = 'C175';
    const LEVEL = 4;
    const PARENT = 'C170';

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
