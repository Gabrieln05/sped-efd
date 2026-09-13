<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class Z1920 extends Element
{
    const REG = '1920';
    const LEVEL = 4;
    const PARENT = '1910';

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
         * Campo 09 (VL_SLD_APURADO_OA) Validação: o valor informado deve ser preenchido com base na expressão:
         * soma do total de débitos transferidos (VL_TOT_TRANSF_DEBITOS_OA) com total de ajustes a débito
         * (VL_TOT_AJ_DEBITOS_OA) com total de estorno de crédito (VL_ESTORNOS_CRED_OA) menos a soma do total
         * de créditos transferidos (VL_TOT_TRANSF_CREDITOS_OA) com total de ajustes a crédito
         * (VL_AJ_CREDITOS_OA) com total de estorno de débito (VL_ESTORNOS_DEB_OA) com saldo credor do período
         * anterior (VL_SLD_CREDOR_ANT_OA). Se o valor da expressão for maior ou igual a “0” (zero), então este
         * valor deve ser informado neste campo e o campo 12 (VL_SLD_CREDOR_TRANSP_OA) deve ser igual a “0”
         * (zero). Se o valor da expressão for menor que “0” (zero), então este campo deve ser preenchido com
         * “0” (zero) e o valor absoluto da expressão deve ser informado no campo VL_SLD_CREDOR_TRANSP_OA.
         */
        $somatorio = $this->values->vl_tot_transf_debitos_oa;
        $somatorio += $this->values->vl_tot_aj_debitos_oa;
        $somatorio += $this->values->vl_estornos_cred_oa;
        $somatorio -= $this->values->vl_tot_transf_creditos_oa;
        $somatorio -= $this->values->vl_tot_aj_creditos_oa;
        $somatorio -= $this->values->vl_estornos_deb_oa;
        $somatorio -= $this->values->vl_sld_credor_ant_oa;

        if (($somatorio >= 0 && $this->values->vl_sld_credor_transp_oa != 0)
        || ($somatorio < 0 && $this->values->vl_sld_apurado_oa != 0)) {
            $this->errors[] = "[" . self::REG . "] " .
                " O valor informado deve ser preenchido com base na expressão: "
                ."soma do total de débitos transferidos (VL_TOT_TRANSF_DEBITOS_OA) "
                ."com total de ajustes a débito (VL_TOT_AJ_DEBITOS_OA) com total de "
                ."estorno de crédito (VL_ESTORNOS_CRED_OA) menos a soma do total de "
                ."créditos transferidos (VL_TOT_TRANSF_CREDITOS_OA) com total de "
                ."ajustes a crédito (VL_AJ_CREDITOS_OA) com total de estorno de "
                ."débito (VL_ESTORNOS_DEB_OA) com saldo credor do período anterior "
                ."(VL_SLD_CREDOR_ANT_OA). Se o valor da expressão for maior ou igual "
                ."a “0” (zero), então este valor deve ser informado neste campo e o "
                ."campo 12 (VL_SLD_CREDOR_TRANSP_OA) deve ser igual a “0” (zero). Se "
                ."o valor da expressão for menor que “0” (zero), então este campo "
                ."deve ser preenchido com “0” (zero) e o valor absoluto da expressão "
                ."deve ser informado no campo VL_SLD_CREDOR_TRANSP_OA.";
        }
    }
}
