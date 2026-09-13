<?php

namespace NFePHP\EFD\Tests\Common;

use DateTimeImmutable;
use InvalidArgumentException;
use NFePHP\EFD\Blocks\ICMSIPI\Block0;
use NFePHP\EFD\Blocks\ICMSIPI\BlockC;
use NFePHP\EFD\Common\Vigencia;
use NFePHP\EFD\Elements\ICMSIPI\Z0001;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

final class VigenciaTest extends TestCase
{
    public function testLeiauteForaDoVigenciasLancaExcecaoEmVezDeCairNoUltimo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new BlockC('099');
    }

    public function testLeiauteQueSaiuDoVigenciasLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new BlockC('017');
    }

    public function testLeiauteSemOsTresDigitosLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Block0('20');
    }

    public function testGrupoDesconhecidoLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Vigencia::leiautes('ECD');
    }

    public function testPeriodoDentroDaVigenciaDevolveOLeiaute(): void
    {
        $this->assertSame('018', Vigencia::paraPeriodo(Vigencia::ICMSIPI, new DateTimeImmutable('2024-12-01')));
        $this->assertSame('019', Vigencia::paraPeriodo(Vigencia::ICMSIPI, new DateTimeImmutable('2025-01-01')));
        $this->assertSame('020', Vigencia::paraPeriodo(Vigencia::ICMSIPI, new DateTimeImmutable('2026-06-01')));
    }

    public function testPeriodoSemLeiauteDisponivelLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Vigencia::paraPeriodo(Vigencia::ICMSIPI, new DateTimeImmutable('2023-06-01'));
    }

    public function testRegistroSemJsonNoLeiauteLancaExcecao(): void
    {
        $vigencia = Vigencia::carregar(Vigencia::ICMSIPI, '020');
        $vigencia->path = sys_get_temp_dir() . '/sped-efd-leiaute-inexistente';
        $this->expectException(RuntimeException::class);
        new Z0001((object) ['ind_mov' => 1], $vigencia);
    }

    public function testCodVerDiferenteDoLeiauteDosBlocosGeraErro(): void
    {
        $b0 = new Block0('020');
        $b0->z0000($this->dados0000('019'));
        $this->assertContains('[0000] campo: COD_VER [019] diferente do leiaute dos blocos [020].', $b0->errors);
    }

    public function testCodVerNaoInformadoAssumeOLeiauteDosBlocos(): void
    {
        $b0 = new Block0('020');
        $b0->z0000($this->dados0000(null));
        $this->assertStringStartsWith('|0000|020|', $b0->get());
    }

    private function dados0000(?string $codVer): stdClass
    {
        $std = new stdClass();
        if ($codVer !== null) {
            $std->cod_ver = $codVer;
        }
        $std->cod_fin = 0;
        $std->dt_ini = '01062026';
        $std->dt_fin = '30062026';
        $std->nome = 'EMPRESA DE TESTE LTDA';
        $std->cnpj = '00258807000129';
        $std->uf = 'SP';
        $std->ie = '206084839119';
        $std->cod_mun = 3550308;
        $std->ind_perfil = 'B';
        $std->ind_ativ = 0;
        return $std;
    }
}
