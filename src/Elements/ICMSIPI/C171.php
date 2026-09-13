<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C171: ARMAZENAMENTO DE COMBUSTÍVEIS (código 01, 55).
 * Este registro deve ser apresentado pelas empresas do comércio varejista de combustíveis, somente nas operações de
 * entrada, para informar o volume recebido (em litros), por item do documento fiscal, conforme Livro de Movimentação de
 * Combustíveis (LMC), Ajuste SINIEF 01/92.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C171 extends Element
{
    const REG = 'C171';
    const LEVEL = 4;
    const PARENT = 'C171';

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
