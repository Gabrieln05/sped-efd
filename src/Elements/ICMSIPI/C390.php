<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO C390: REGISTRO ANALÍTICO DAS NOTAS FISCAIS DE VENDA A
 * CONSUMIDOR (CÓDIGO 02)
 * Este registro tem por objetivo informar as notas fiscais de venda ao consumidor, não emitidas por ECF, e deve ser
 * apresentado de forma agrupada na combinação CST_ICMS, CFOP e Alíquota de ICMS.
 * @package NFePHP\EFD\Elements\ICMSIPI
 */
class C390 extends Element
{
    const REG = 'C390';
    const LEVEL = 3;
    const PARENT = '';

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
