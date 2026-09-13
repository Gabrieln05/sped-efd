<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\ChaveAcesso;
use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C113: DOCUMENTO FISCAL REFERENCIADO
 * Este registro tem por objetivo informar, detalhadamente, outros documentos fiscais que tenham sido mencionados
 * nas informações complementares do documento que está sendo escriturado no registro C100, exceto cupons fiscais, que
 * devem ser informados no registro C114. Exemplos: nota fiscal de remessa
 * de mercadoria originária de venda para entrega futura e nota fiscal de devolução de compras.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C113 extends Element
{
    const REG = 'C113';
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
        if (in_array($this->std->cod_mod, ['2D', '02', '2E'])) {
            $this->errors[] = "[" . self::REG . "] " .
                "O código do documento fiscal (COD_MOD) deve ser diferente de 2D, 02 ou 2E";
        }
        if ($this->std->cod_mod == 57) {
            if (!ChaveAcesso::valida((string) $this->std->chv_doce)) {
                $this->errors[] = "[" . self::REG . "] " .
                    "Chave do cocumento (CHV_DOCe) inválida";
            }
        }
    }
}
