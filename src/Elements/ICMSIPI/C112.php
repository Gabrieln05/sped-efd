<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C112: DOCUMENTO DE ARRECADAÇÃO REFERENCIADO
 * Este registro deve ser apresentado, obrigatoriamente,
 * quando no campo – “Informações Complementares” da nota
 * fiscal - constar a identificação de um documento de arrecadação.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C112 extends Element
{
    const REG = 'C112';
    const LEVEL = 4;
    const PARENT = 'C110';

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

    public function postValidation()
    {
        if (!$this->std->num_da xor $this->std->cod_aut) {
            $this->errors[] = "[" . self::REG . "] " .
                "Preencha o número da arrecadação (NUM_DA) ou o Código completo da autenticação bancária (COD_AUT";
        }
        if ($this->std->vl_da <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                "O valor total da arrecadação (VAL_DA) deve ser maior do que zero '0'";
        }
    }
}
