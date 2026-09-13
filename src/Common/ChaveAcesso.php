<?php

namespace NFePHP\EFD\Common;

use NFePHP\Common\Keys;

/**
 * Chave de acesso de documento fiscal eletrônico (44 posições).
 *
 * Com o CNPJ alfanumérico (IN RFB nº 2.229/2024) as posições 7 a 18 da chave,
 * que trazem a raiz e a ordem do CNPJ, podem ter letras. Chave só com dígitos
 * continua conferida pelo dígito verificador da sped-common; chave com letras é
 * conferida só no formato, até a regra do dígito verificador alfanumérico da
 * chave ser incorporada e testada.
 */
final class ChaveAcesso
{
    public const REGEX = '^[0-9]{6}[0-9A-Z]{12}[0-9]{26}$';

    public static function valida(string $chave): bool
    {
        if (preg_match('/^[0-9]{44}$/', $chave) === 1) {
            return Keys::isValid($chave);
        }
        return preg_match('/' . self::REGEX . '/', $chave) === 1;
    }
}
