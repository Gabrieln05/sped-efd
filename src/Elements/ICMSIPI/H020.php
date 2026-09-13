<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO H020: INFORMAÇÃO COMPLEMENTAR DO INVENTÁRIO
 * Este registro deve ser preenchido para complementar as informações do
 * inventário, quando o campo MOT_INV do registro H005 for de “02” a “05”.
 * Não informar se o campo 03 (VL_INV) do registro H005 for igual a “0” (zero).
 * No caso de mudança da forma de tributação do ICMS da mercadoria
 * (MOT_INV=2 do H005), somente deverá ser gerado esse registro para os itens
 * que sofreram alteração da tributação do ICMS.
 */
class H020 extends Element
{
    const REG = 'H020';
    const LEVEL = 4;
    const PARENT = 'H010';

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
