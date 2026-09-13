<?php

namespace NFePHP\EFD\Elements\Contribuicoes;

use NFePHP\EFD\Common\Element;
use NFePHP\EFD\Common\ElementInterface;
use \stdClass;

class F200 extends Element implements ElementInterface
{
    const REG = 'F200';
    const LEVEL = 3;
    const PARENT = 'F010';

    /**
     * Constructor
     * @param \stdClass $std
     * @param \stdClass $vigencia
     */
    public function __construct(\stdClass $std, \stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}