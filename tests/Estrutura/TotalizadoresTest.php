<?php

namespace NFePHP\EFD\Tests\Estrutura;

use NFePHP\EFD\Tests\Caracterizacao\ExemploEFDICMS;
use PHPUnit\Framework\TestCase;

/**
 * Os totalizadores gerados pela biblioteca (x990 e bloco 9) batem com as
 * linhas de fato presentes no arquivo.
 */
final class TotalizadoresTest extends TestCase
{
    public function testTotalizadoresBatemComAsLinhasDoArquivo(): void
    {
        $linhas = array_values(array_filter(explode("\n", ExemploEFDICMS::montar()->get())));
        $porRegistro = [];
        $porBloco = [];
        foreach ($linhas as $linha) {
            $reg = explode('|', $linha)[1];
            $porRegistro[$reg] = ($porRegistro[$reg] ?? 0) + 1;
            $porBloco[$reg[0]] = ($porBloco[$reg[0]] ?? 0) + 1;
        }

        foreach ($linhas as $linha) {
            $campos = explode('|', $linha);
            if ($campos[1] === '9900') {
                $this->assertSame($porRegistro[$campos[2]] ?? 0, (int) $campos[3], "9900 do registro {$campos[2]}");
            }
            if (substr($campos[1], 1) === '990') {
                $this->assertSame($porBloco[$campos[1][0]], (int) $campos[2], "totalizador {$campos[1]}");
            }
        }

        $ultima = explode('|', end($linhas));
        $this->assertSame('9999', $ultima[1]);
        $this->assertSame(count($linhas), (int) $ultima[2]);
    }
}
