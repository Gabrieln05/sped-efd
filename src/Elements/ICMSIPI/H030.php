<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO H030: Informações complementares do inventário das mercadorias sujeitas ao
 *0 regime de substituição tributária
 *
 */
class H030 extends Element
{
    const REG = 'H030';
    const LEVEL = 4;
    const PARENT = 'H010';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}
