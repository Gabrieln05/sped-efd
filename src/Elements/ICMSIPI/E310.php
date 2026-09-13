<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class E310 extends Element
{
    const REG = 'E310';
    const LEVEL = 3;
    const PARENT = 'E300';

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
         * Campo 08 (VL_SLD_DEV_ANT_DIFAL) Validação: Se (VL_TOT_DEBITOS_DIFAL + VL_OUT_DEB_DIFAL) menos
         * (VL_SLD_CRED_ANT_DIFAL + VL_TOT_CREDITOS_DIFAL + VL_OUT_CRED_DIFAL) for maior ou igual a ZERO,
         * então o resultado deverá ser igual ao VL_SLD_DEV_ANT_DIFAL; senão VL_SLD_DEV_ANT_DIFAL deve
         * ser igual a ZERO.
         */
        $somatorio = $this->values->vl_tot_debitos_difal
                    + $this->values->vl_out_deb_difal
                    - $this->values->vl_sld_cred_ant_difal
                    - $this->values->vl_tot_creditos_difal
                    - $this->values->vl_out_cred_difal;

        if (($somatorio > 0 && $this->values->vl_sld_dev_ant_difal == 0)
        || ($somatorio < 0 && $this->values->vl_sld_dev_ant_difal != 0)) {
            $this->errors[] = "[" . self::REG . "] Se (VL_TOT_DEBITOS_DIFAL + VL_OUT_DEB_DIFAL) "
            . "menos (VL_SLD_CRED_ANT_DIFAL + VL_TOT_CREDITOS_DIFAL + VL_OUT_CRED_DIFAL) for maior ou igual a "
            . "ZERO, então o resultado deverá ser igual ao VL_SLD_DEV_ANT_DIFAL; senão VL_SLD_DEV_ANT_DIFAL deve "
            . "ser igual a ZERO.";
        }

        /*
         * Campo 10 (VL_RECOL_DIFAL) Validação: Se (VL_SLD_DEV_ANT_DIFAL menos VL_DEDUCOES_DIFAL) for maior
         * ou igual a ZERO, então VL_RECOL_DIFAL é igual ao resultado da equação; senão o VL_RECOL_DIFAL
         * deverá ser igual a ZERO.
         */
        $diferenca = $this->values->vl_sld_dev_ant_difal - $this->values->vl_deducoes_difal;

        if (($diferenca > 0 && $this->values->vl_recol_difal == 0)
        || ($diferenca < 0 && $this->values->vl_recol_difal != 0)) {
            $this->errors[] = "[" . self::REG . "] Se (VL_SLD_DEV_ANT_DIFAL menos VL_DEDUCOES_DIFAL) "
            . "for maior ou igual a ZERO, então VL_RECOL é igual ao resultado da equação; "
            . "senão o VL_RECOL deverá ser igual a ZERO.";
        }

         /*
         * Campo 18 (VL_SLD_DEV_ANT_FCP) Validação: Se (VL_TOT_DEB_FCP + VL_OUT_DEB_FCP) menos (VL_SLD_CRED_ANT_FCP
         * + VL_TOT_CRED_FCP + VL_OUT_CRED_FCP) for maior ou igual a ZERO, então o resultado deverá ser igual ao
         * VL_SLD_DEV_ANT_FCP; senão VL_SLD_DEV_ANT_FCP deve ser igual a ZERO.
         */
        $somatorio = $this->values->vl_tot_deb_fcp
                    + $this->values->vl_out_deb_fcp
                    - $this->values->vl_sld_cred_ant_fcp
                    - $this->values->vl_tot_cred_fcp
                    - $this->values->vl_out_cred_fcp;

        if (($somatorio > 0 && $this->values->vl_sld_dev_ant_fcp == 0)
        || ($somatorio < 0 && $this->values->vl_sld_dev_ant_fcp != 0)) {
            $this->errors[] = "[" . self::REG . "] Se (VL_TOT_DEB_FCP + VL_OUT_DEB_FCP) menos "
            . "(VL_SLD_CRED_ANT_FCP + VL_TOT_CRED_FCP + VL_OUT_CRED_FCP) for maior ou igual a ZERO, então o "
            . "resultado deverá ser igual ao VL_SLD_DEV_ANT_FCP; senão VL_SLD_DEV_ANT_FCP deve ser igual a ZERO.";
        }

        /*
        * Campo 20 (VL_RECOL_FCP) Validação: Se (VL_SLD_DEV_ANT_FCP menos VL_DEDUCOES_FCP) for maior
        * ou igual a ZERO, então VL_RECOL_FCP é igual ao resultado da equação; senão o VL_RECOL_FCP
        * deverá ser igual a ZERO.
        */
        $diferenca = $this->values->vl_sld_dev_ant_fcp - $this->values->vl_deducoes_fcp;

        if (($diferenca > 0 && $this->values->vl_recol_fcp == 0)
        || ($diferenca < 0 && $this->values->vl_recol_fcp != 0)) {
            $this->errors[] = "[" . self::REG . "] Se (VL_SLD_DEV_ANT_FCP menos VL_DEDUCOES_FCP) "
            . "for maior ou igual a ZERO, então VL_RECOL_FCP é igual ao resultado da equação; "
            . "senão o VL_RECOL_FCP deverá ser igual a ZERO.";
        }

        /*
         * Campo 21 (VL_SLD_CRED_TRANSPORTAR_FCP) Validação: Se (VL_SLD_CRED_ANT_FCP + VL_TOT_CRED_FCP
         * + VL_OUT_CRED_FCP + VL_DEDUÇÕES_FCP) menos (VL_TOT_DEB_FCP + VL_OUT_DEB_FCP) for maior que ZERO,
         * então VL_SLD_CRED_TRANSPORTAR_FCP deve ser igual ao resultado da equação; senão
         * VL_SLD_CRED_TRANSPORTAR_FCP será ZERO.
         */
        $somatorio = $this->values->vl_sld_cred_ant_fcp
                    + $this->values->vl_tot_cred_fcp
                    + $this->values->vl_out_cred_fcp
                    + $this->values->vl_deducoes_fcp
                    - $this->values->vl_tot_deb_fcp
                    - $this->values->vl_out_deb_fcp;

        if (($somatorio > 0 && $this->values->vl_sld_cred_transportar_fcp == 0)
        || ($somatorio < 0 && $this->values->vl_sld_cred_transportar_fcp != 0)) {
            $this->errors[] = "[" . self::REG . "] Se (VL_SLD_CRED_ANT_FCP + VL_TOT_CRED_FCP "
            . "+ VL_OUT_CRED_FCP + VL_DEDUÇÕES_FCP) menos (VL_TOT_DEB_FCP + VL_OUT_DEB_FCP) for maior que ZERO, "
            . "então VL_SLD_CRED_TRANSPORTAR_FCP deve ser igual ao resultado da equação; senão "
            . "VL_SLD_CRED_TRANSPORTAR_FCP será ZERO.";
        }
    }
}
