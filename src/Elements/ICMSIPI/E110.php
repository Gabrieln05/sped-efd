<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class E110 extends Element
{
    const REG = 'E110';
    const LEVEL = 3;
    const PARENT = 'E100';

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
        /*
         * Campo 13 (VL_ICMS_RECOLHER) Validação: o valor informado deve corresponder à diferença entre o
         * campo VL_SLD_APURADO e o campo VL_TOT_DED. Se o resultado dessa operação for negativo, informe
         * o valor zero neste campo, e o valor absoluto correspondente no campo VL_SLD_CREDOR_TRANSPORTAR.
         */
        $diferenca = $this->values->vl_sld_apurado - $this->values->vl_tot_ded;
        if ($diferenca < 0 && ($this->values->vl_icms_recolher != 0 || $this->values->vl_sld_credor_transportar == 0)) {
            $this->errors[] = "[" . self::REG . "] O valor informado deve corresponder à diferença "
            . "entre o campo VL_SLD_APURADO e o campo VL_TOT_DED. Se o resultado dessa operação for negativo, "
            . "informe o valor zero neste campo, e o valor absoluto correspondente no "
            . "campo VL_SLD_CREDOR_TRANSPORTAR.";
        }

        /*
         * Campo 14 (VL_SLD_CREDOR_TRANSPORTAR) Validação: o valor informado deve ser preenchido com base
         * na expressão: soma do total de débitos (VL_TOT_DEBITOS) com total de ajustes
         * (VL_AJ_DEBITOS +VL_TOT_AJ_DEBITOS) com total de estorno de crédito (VL_ESTORNOS_CRED) menos a
         * soma do total de créditos (VL_TOT_CREDITOS) com total de ajuste de créditos
         * (VL_,AJ_CREDITOS + VL_TOT_AJ_CREDITOS) com total de estorno de débito (VL_ESTORNOS_DEB)
         * com saldo credor do período anterior (VL_SLD_CREDOR_ANT). Se o valor da expressão for maior
         * ou igual a “0” (zero), então este valor deve ser informado neste campo e o campo 14
         * (VL_SLD_CREDOR_TRANSPORTAR) deve ser igual a “0” (zero). Se o valor da expressão for menor que
         * “0” (zero), então este campo deve ser preenchido com “0” (zero) e o valor absoluto da expressão deve
         * ser informado no campo VL_SLD_CREDOR_TRANSPORTAR, adicionado ao valor total das deduções (VL_TOT_DED)
         */
        $somatorio = $this->values->vl_tot_debitos
                    + $this->values->vl_aj_debitos
                    + $this->values->vl_tot_aj_debitos
                    + $this->values->vl_estornos_cred
                    - $this->values->vl_tot_creditos
                    - $this->values->vl_aj_creditos
                    - $this->values->vl_tot_aj_creditos
                    - $this->values->vl_estornos_deb
                    - $this->values->vl_sld_credor_ant
                    - $this->values->vl_tot_ded;

        if (($somatorio >= 0 && $this->values->vl_sld_credor_transportar != 0)
        || ($somatorio < 0 && $this->values->vl_sld_credor_transportar == 0)) {
            $this->errors[] = "[" . self::REG . "] O valor informado deve ser preenchido com base "
            . "na expressão: soma do total de débitos (VL_TOT_DEBITOS) com total de ajustes "
            . "(VL_AJ_DEBITOS +VL_TOT_AJ_DEBITOS) com total de estorno de crédito (VL_ESTORNOS_CRED) menos a "
            . "soma do total de créditos (VL_TOT_CREDITOS) com total de ajuste de créditos "
            . "(VL_,AJ_CREDITOS + VL_TOT_AJ_CREDITOS) com total de estorno de débito (VL_ESTORNOS_DEB) "
            . "com saldo credor do período anterior (VL_SLD_CREDOR_ANT). Se o valor da expressão for maior "
            . "ou igual a “0” (zero), então este valor deve ser informado neste campo e o campo 14 "
            . "(VL_SLD_CREDOR_TRANSPORTAR) deve ser igual a “0” (zero). Se o valor da expressão for menor que "
            . "“0” (zero), então este campo deve ser preenchido com “0” (zero) e o valor absoluto da expressão deve "
            . "ser informado no campo VL_SLD_CREDOR_TRANSPORTAR, adicionado ao valor total das deduções (VL_TOT_DED)";
        }
    }
}
