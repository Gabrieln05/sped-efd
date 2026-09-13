<?php

namespace NFePHP\EFD\Blocks\Contribuicoes;

use NFePHP\EFD\Elements\Contribuicoes as Elements;
use NFePHP\EFD\Common\Block;

/**
 * Classe constutora do bloco F EFD Contribuições
 *
 */
final class BlockF extends Block
{
    const TOTAL = 'F990';

    public $elements = [
        'f001' => ['class' => Elements\F001::class, 'level' => 1, 'type' => 'single'],
        'f010' => ['class' => Elements\F010::class, 'level' => 2, 'type' => 'single'],
        'f100' => ['class' => Elements\F100::class, 'level' => 3, 'type' => 'multiple'],
        'f200' => ['class' => Elements\F200::class, 'level' => 3, 'type' => 'multiple'],
        'f550' => ['class' => Elements\F550::class, 'level' => 3, 'type' => 'single'],
    ];

    public function __construct(string $layout)
    {
        $this->grupo = 'Contribuicoes';
        parent::__construct($layout);
        $this->elementTotal = 'F990';
    }
}
