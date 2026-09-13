<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C176: RESSARCIMENTO DE ICMS E FUNDO DE COMBATE À POBREZA
 * (FCP) EM OPERAÇÕES COM SUBSTITUIÇÃO TRIBUTÁRIA (CÓDIGO 01, 55)
 * Este registro deve ser informado quando da escrituração de documento fiscal, que acoberte operação que represente
 * desfazimento de substituição tributária realizada em operações anteriores.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C176 extends Element
{
    const REG = 'C176';
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

    public function postValidation()
    {
        //Valida se QUANT_ULT_E é maior que zero
        if ($this->values->quant_ult_e <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " Quantidade do item relativa a última entrada (QUANT_ULT_E) deve ser maior do que zero ";
        }
        //Valida se VL_UNIT_ULT_E é maior que zero
        if ($this->values->vl_unit_ult_e <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " Valor unitário da mercadoria constante na NF (VL_UNIT_ULT_E) deve ser maior do que zero ";
        }
        //Valida se VL_UNIT_BC_ST é maior que zero
        if ($this->values->vl_unit_bc_st <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " Valor unitário da base de cálculo do imposto " .
                "pago por substituição (VL_UNIT_BC_ST) deve ser maior do que zero ";
        }
        //Valida se VL_UNIT_BC_ICMS_ULT_E é maior que zero
        if ($this->values->vl_unit_bc_icms_ult_e <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                " Valor unitário da base de cálculo do ICMS " .
                "relativo à última entrada da mercadoria, limitado " .
                "ao valor da BC da retenção (VL_UNIT_BC_ICMS_ULT_E) deve ser maior do que zero ";
        }
        //Valida se ALIQ_ICMS_ULT_E é maior que zero
        if ($this->values->aliq_icms_ult_e <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                "Alíquota do ICMS aplicável à última entrada da mercadoria  " .
                "(ALIQ_ICMS_ULT_E) deve ser maior do que zero ";
        }
        if ($this->values->vl_unit_limite_bc_icms_ult_e <= 0 ||
            $this->values->vl_unit_limite_bc_icms_ult_e >= $this->values->vl_unit_bc_st ||
            $this->values->vl_unit_limite_bc_icms_ult_e >= $this->values->vl_unit_bc_icms_ult_e
        ) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo  VL_UNIT_LIMITE_BC_ICMS_ULT_E deve ser maior que zero e menor que os " .
                "campos VL_UNIT_BC_ST e VL_UNIT_BC_ICMS_ULT_E";
        }
        $multplicacao = round(
            $this->values->aliq_icms_ult_e
            * $this->values->vl_unit_limite_bc_icms_ult_e,
            2
        );
        if (round($this->values->vl_unit_icms_ult_e, 2) != $multplicacao) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo  VL_UNIT_ICMS_ULT_E deve ser maior que zero e " .
                "deve corresponder a multiplicação entre os campos " .
                "campos ALIQ_ICMS_ULT_E e VL_UNIT_LIMITE_BC_ICMS_ULT_E";
        }
        if ($this->values->aliq_st_ult_e <= 0) {
            $this->errors[] = "[" . self::REG . "] " .
                "O campo  ALIQ_ST_ULT_E deve ser maior que zero";
        }
        $calc = round(
            $this->values->vl_unit_bc_st
            * $this->values->aliq_st_ult_e
            - $this->values->vl_unit_icms_ult_e,
            2
        );
        if (round($this->values->vl_unit_res, 2) !== $calc) {
            $this->errors[] = "[" . self::REG . "] " .
                "o valor informado no campo VL_UNIT_RES deve ser maior ou igual que “0” (zero), e deve corresponder " .
                "a multiplicação entre os campos VL_UNIT_BC_ST e ALIQ_ST_ULT_E, subtraindo, deste resultado," .
                " o campo VL_UNIT_ICMS_ULT_E.";
        }
        if (!$this->std->cod_da and $this->std->cod_resp_ret == 3) {
            $this->errors[] = "[" . self::REG . "] " .
                "Quando o campo COD_RESP_RET é igual a '3', o campo COD_DA é obrigatório";
        }
        if ($this->std->chave_nfe_ret and !ChaveAcesso::valida((string) $this->std->chave_nfe_ret)) {
            $this->errors[] = "[" . self::REG . "] " .
                " Dígito verificador incorreto no campo campo chave da " .
                "nota fiscal eletronica (CHAVE_NFE_RET)";
        }
    }
}
