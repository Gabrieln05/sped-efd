<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO H010: INVENTÁRIO.
 * Este registro deve ser informado para discriminar os itens existentes no estoque.
 * Este registro não pode ser fornecido se o campo 03 (VL_INV) do registro H005
 * for igual a “0” (zero).
 * A partir de janeiro de 2015, caso o contribuinte utilize o bloco H para
 * atender à legislação do Imposto de Renda, especificamente o artigo 261 do
 * Regulamento do Imposto de Renda – RIR/99 – Decreto nº 3.000/1999, deverá
 * informar neste registro, além dos itens exigidos pelas legislações do ICMS e
 * do IPI, aqueles bens exigidos pela legislação do Imposto de Renda.
 */
class H010 extends Element
{
    const REG = 'H010';
    const LEVEL = 3;
    const PARENT = 'H005';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}
