<?php

namespace NFePHP\EFD\Tests\Pva;

use NFePHP\Common\Keys;

/**
 * Identificadores fictícios com dígito verificador válido: o PVA confere CNPJ,
 * CPF, inscrição estadual e chave de acesso.
 */
final class Documentos
{
    public static function cnpj(string $base12): string
    {
        $digitos = $base12;
        foreach ([[5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2], [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]] as $pesos) {
            $soma = 0;
            foreach ($pesos as $i => $peso) {
                $soma += (int) $digitos[$i] * $peso;
            }
            $resto = $soma % 11;
            $digitos .= $resto < 2 ? '0' : (string) (11 - $resto);
        }
        return $digitos;
    }

    public static function cpf(string $base9): string
    {
        $digitos = $base9;
        for ($tamanho = 9; $tamanho < 11; $tamanho++) {
            $soma = 0;
            for ($i = 0; $i < $tamanho; $i++) {
                $soma += (int) $digitos[$i] * ($tamanho + 1 - $i);
            }
            $resto = ($soma * 10) % 11;
            $digitos .= $resto === 10 ? '0' : (string) $resto;
        }
        return $digitos;
    }

    /**
     * Inscrição estadual de SP com 12 dígitos: 8 dígitos, DV, 2 dígitos, DV.
     */
    public static function ieSp(string $base8, string $sufixo2): string
    {
        $soma = 0;
        foreach ([1, 3, 4, 5, 6, 7, 8, 10] as $i => $peso) {
            $soma += (int) $base8[$i] * $peso;
        }
        $parcial = $base8 . (($soma % 11) % 10) . $sufixo2;
        $soma = 0;
        foreach ([3, 2, 10, 9, 8, 7, 6, 5, 4, 3, 2] as $i => $peso) {
            $soma += (int) $parcial[$i] * $peso;
        }
        return $parcial . (($soma % 11) % 10);
    }

    /**
     * Chave de 44 dígitos de documento emitido em SP em janeiro de 2026.
     */
    public static function chave(string $cnpj, int $modelo, int $serie, int $numero, int $codigo): string
    {
        return Keys::build(35, 26, 1, $cnpj, $modelo, $serie, $numero, 1, $codigo);
    }
}
