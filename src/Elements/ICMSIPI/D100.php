<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * Este registro deve ser apresentado por todos os contribuintes adquirentes ou prestadores dos serviços que utilizem os
 * documentos especificados.
 * O campo CHV_CTE passa a ser de preenchimento obrigatório a partir de abril de 2012 em todas as situações, exceto
 * para COD_SIT = 5 (numeração inutilizada).
 * IMPORTANTE: para documentos de entrada, os campos de valor de imposto/contribuição, base de cálculo e
 * alíquota só devem ser informados se o adquirente tiver direito à apropriação do crédito (enfoque do declarante).
 * Validação do Registro: não podem ser informados dois ou mais registros com a combinação de mesmos valores
 * dos campos:
 * 1. emissão de terceiros : IND_EMIT+NUM_DOC+COD_MOD+SER+SUB+COD_PART;
 * 2. emissão própria: IND_EMIT+NUM_DOC+COD_MOD+SER+SUB.
 * 3. A partir de 01/01/2014, foi incluído o campo CHV_CTE para compor a chave do registro
 */
class D100 extends Element
{
    const REG = 'D100';
    const LEVEL = 2;
    const PARENT = 'D001';

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
     * @return void
     */
    public function postValidation()
    {
        /*
         * Campo 07 (SER) – Validação: campo de preenchimento obrigatório com três posições para CT-e e CT-e OS, COD_MOD
         * iguais a “57” e “67”, respectivamente, de emissão própria ou de terceiros.
         * Se não existir Série para CT-e e CT-e OS, informar 000.
         */
        if ($this->std->cod_mod == '57' || $this->std->cod_mod == '67') {
            if (strlen($this->std->ser) < 1) {
                $this->errors[] = "[" . self::REG . "] Deve ser "
                    . "informado 3 numeros para a Série do documento fiscal(SER) "
                    . "quanto o Código do modelo do documento fiscal(COD_MOD) for CT-e e CT-e OS (57 e 67) "
                    . "Se não existir Série para CT-e e CT-e OS, informar 000";
            }
        }
        if ($this->std->cod_mod == '57' || $this->std->cod_mod == '63' || $this->std->cod_mod == '67') {
            /*
             * Campo 13 (TP_CT-e) Preenchimento: informar o tipo de CT-e, BP-e ou CT-e OS,
             * quando o modelo do documento for “57”, “63” ou “67”, respectivamente.
             */
            if (empty($this->std->tp_ct_e) && (int)$this->std->tp_ct_e!==0) {
                $this->errors[] = "[" . self::REG . "] Deve ser "
                    . "informado o tipo de CT-e, BP-e ou CT-e OS, quando o modelo do documento for "
                    . "“57”, “63” ou “67”, respectivamente.";
            }

            /**
             * Campo 24 (COD_MUN_ORIG): Campo obrigatório se “COD_MOD” do registro D100 for “57”, “63” ou “67”.
             */
            if (empty($this->std->cod_mun_orig)) {
                $this->errors[] = "[" . self::REG . "] "
                    . "Deve ser informado o código do município de origem (COD_MUN_ORIG) quando"
                    . " código do modelo for igual a: 57, 63 ou 67";
            }
            /**
             * Campo 25 (COD_MUN_DEST): Campo obrigatório se “COD_MOD” do registro D100 for “57”, “63” ou “67”.
             */
            if (empty($this->std->cod_mun_dest)) {
                $this->errors[] = "[" . self::REG . "] "
                    . "Deve ser informado o código do municiopio de destino (COD_MUN_DEST) quando"
                    . " código do modelo for igual a 57, 63 ou 67";
            }
        }

        /*
         * Faz a verificação do digito verificador do campo
         */
        if (!ChaveAcesso::valida((string) $this->std->chv_cte)) {
            $this->errors[] = "[" . self::REG . "] "
                . " Dígito verificador incorreto no campo campo chave do "
                . "conhecimento de transporte eletrônico (CHV_CTE)";
        }

        /**
         * Se o Campo COD_MOD for igual a 07, 08, 08B, 09, 10, 11, 26 ou 27
         * a DT_DOC informada deverá ser menor que 01/01/2018.
         */
        if (in_array($this->std->cod_mod, ['07', '08', '08B', '09', '10', '11', '26', '27'])) {
            $year = (int) substr($this->std->dt_doc, -4);
            if ($year >= 2018) {
                $this->errors[] = "[" . self::REG . "] "
                    . " Se o Campo Código do modelo do documento fiscal (COD_MOD) "
                    . "for igual a 07, 08, 08B, 09, 10, 11, 26 ou 27,"
                    . " a Data da emissão do documento fiscal (DT_DOC) informada deverá ser menor que 01/01/2018.";
            }
        }
    }
}
