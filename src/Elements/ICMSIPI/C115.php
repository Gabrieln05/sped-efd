<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class C115 extends Element
{
    const REG = 'C115';
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


    /**
     * Aqui são colocadas validações adicionais que requerem mais logica
     * e processamento
     * Deve ser usado apenas quando necessário
     * @throws \InvalidArgumentException
     */
    public function postValidation()
    {
        if (!$this->std->cnpj_entg xor $this->std->cpf_entg) {
            $this->errors[] = "[" . self::REG . "] " .
                "Deve ser informado apenas o CNPJ (CNPJ_ENTG) ou o CPF (CPF_ENTG)";
        }
    }
}
