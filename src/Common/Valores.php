<?php

namespace NFePHP\EFD\Common;

use stdClass;

/**
 * Valores numéricos do registro guardados para a validação posterior
 * (postValidation). Campo não informado devolve null, sem aviso de
 * propriedade indefinida.
 */
#[\AllowDynamicProperties]
final class Valores extends stdClass
{
    public function __get(string $nome): mixed
    {
        return null;
    }
}
