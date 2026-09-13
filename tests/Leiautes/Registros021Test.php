<?php

namespace NFePHP\EFD\Tests\Leiautes;

use NFePHP\EFD\Blocks\ICMSIPI\Block0;
use NFePHP\EFD\Blocks\ICMSIPI\BlockC;
use NFePHP\EFD\Blocks\ICMSIPI\BlockE;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * O que o leiaute 021 (2027) mudou no arquivo: IND_BENEFICIO nos ajustes,
 * COD_PROD no C180, CNPJ alfanumérico e chave com letras nas posições do CNPJ.
 * A expectativa vem do Guia Prático 3.2.3, não da saída da biblioteca.
 */
final class Registros021Test extends TestCase
{
    private const CNPJ_ALFANUMERICO = '12ABC34501DE35';
    private const CHAVE_ALFANUMERICA = '352701' . '12ABC34501DE' . '35' . '55' . '001' . '000001001' . '1' . '10010001' . '0';

    /**
     * @return iterable<string, array{string, string, array<string, mixed>, string}>
     */
    public static function registros(): iterable
    {
        yield 'C197: IND_BENEFICIO no campo 09' => [
            BlockC::class,
            'c197',
            [
                'COD_AJ' => 'SP10090001', 'DESCR_COMPL_AJ' => null, 'COD_ITEM' => 'PROD1', 'VL_BC_ICMS' => 100,
                'ALIQ_ICMS' => 18, 'VL_ICMS' => 18, 'VL_OUTROS' => 0, 'IND_BENEFICIO' => 1,
            ],
            '|C197|SP10090001||PROD1|100,00|18,00|18,00|0,00|1|',
        ];

        yield 'E111: IND_BENEFICIO no campo 05' => [
            BlockE::class,
            'e111',
            ['COD_AJ_APUR' => 'SP020001', 'DESCR_COMPL_AJ' => 'AJUSTE', 'VL_AJ_APUR' => 10, 'IND_BENEFICIO' => 0],
            '|E111|SP020001|AJUSTE|10,00|0|',
        ];

        yield 'C180: COD_PROD no campo 12' => [
            BlockC::class,
            'c180',
            [
                'COD_RESP_RET' => 1, 'QUANT_CONV' => 10, 'UNID' => 'UN', 'VL_UNIT_CONV' => 5,
                'VL_UNIT_ICMS_OP_CONV' => 0.9, 'VL_UNIT_BC_ICMS_ST_CONV' => 6, 'VL_UNIT_ICMS_ST_CONV' => 1.08,
                'VL_UNIT_FCP_ST_CONV' => 0, 'COD_DA' => '0', 'NUM_DA' => '123', 'COD_PROD' => 'ABC-123',
            ],
            '|C180|1|10,000000|UN|5,000000|0,900000|6,000000|1,080000|0,000000|0|123|ABC-123|',
        ];

        yield '0150: CNPJ alfanumérico' => [
            Block0::class,
            'z0150',
            [
                'COD_PART' => 'F001', 'NOME' => 'FORNECEDOR', 'COD_PAIS' => '01058', 'CNPJ' => self::CNPJ_ALFANUMERICO,
                'COD_MUN' => '3550308', 'END' => 'RUA UM', 'NUM' => '1',
            ],
            '|0150|F001|FORNECEDOR|01058|' . self::CNPJ_ALFANUMERICO . '|||3550308||RUA UM|1|||',
        ];
    }

    /**
     * @param array<string, mixed> $dados
     */
    #[DataProvider('registros')]
    public function testRegistroSaiNoFormatoDoLeiaute021(string $bloco, string $metodo, array $dados, string $esperado): void
    {
        $b = new $bloco('021');
        $b->$metodo((object) $dados);

        $this->assertSame([], $b->errors);
        $this->assertSame($esperado, explode("\n", $b->get())[0]);
    }

    public function testC100AceitaChaveComCnpjAlfanumerico(): void
    {
        $b = new BlockC('021');
        $b->c100((object) [
            'IND_OPER' => '0', 'IND_EMIT' => '1', 'COD_PART' => 'F001', 'COD_MOD' => '55', 'COD_SIT' => '09',
            'SER' => '1', 'NUM_DOC' => 1001, 'CHV_NFE' => self::CHAVE_ALFANUMERICA, 'DT_DOC' => '05012027',
            'DT_E_S' => '06012027', 'VL_DOC' => 100,
        ]);

        $this->assertSame([], $b->errors);
        $this->assertStringContainsString('|09|1|1001|' . self::CHAVE_ALFANUMERICA . '|', $b->get());
    }

    public function testNo020CnpjAlfanumericoAindaERecusado(): void
    {
        $b = new Block0('020');
        $b->z0150((object) [
            'COD_PART' => 'F001', 'NOME' => 'FORNECEDOR', 'COD_PAIS' => '01058', 'CNPJ' => self::CNPJ_ALFANUMERICO,
            'COD_MUN' => '3550308', 'END' => 'RUA UM', 'NUM' => '1',
        ]);

        $this->assertNotSame([], $b->errors);
    }
}
