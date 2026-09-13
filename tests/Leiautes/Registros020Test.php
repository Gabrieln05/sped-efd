<?php

namespace NFePHP\EFD\Tests\Leiautes;

use NFePHP\EFD\Blocks\ICMSIPI\Block1;
use NFePHP\EFD\Blocks\ICMSIPI\BlockC;
use NFePHP\EFD\Blocks\ICMSIPI\BlockD;
use NFePHP\EFD\Blocks\ICMSIPI\BlockE;
use NFePHP\EFD\Blocks\ICMSIPI\BlockH;
use NFePHP\EFD\Blocks\ICMSIPI\BlockK;
use NFePHP\EFD\Tests\Pva\Documentos;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Linha gerada, campo a campo, dos registros que mudaram entre o 017 e o 020
 * ou que estavam errados na biblioteca e não cabem no arquivo do PVA (exigem
 * posto de combustível, usina, ressarcimento de ST, bloco K...). A expectativa
 * vem do leiaute (NT 2025.001), não da saída da biblioteca.
 */
final class Registros020Test extends TestCase
{
    /**
     * @return iterable<string, array{string, string, array<string, mixed>, string}>
     */
    public static function registros(): iterable
    {
        yield '1310: CAP_TANQUE novo no campo 11; volumes com 3 decimais' => [
            Block1::class,
            'z1310',
            [
                'NUM_TANQUE' => '01', 'ESTQ_ABERT' => 1000, 'VOL_ENTR' => 500, 'VOL_DISP' => 1500,
                'VOL_SAIDAS' => 300, 'ESTQ_ESCR' => 1200, 'VAL_AJ_PERDA' => 0, 'VAL_AJ_GANHO' => 0,
                'FECH_FISICO' => 1200, 'CAP_TANQUE' => 15000,
            ],
            '|1310|01|1000,000|500,000|1500,000|300,000|1200,000|0,000|0,000|1200,000|15000|',
        ];

        yield '1391: campos 18 a 23 (COD_ITEM, TP_RESIDUO e resíduos DDG/WDG/cana)' => [
            Block1::class,
            'z1391',
            [
                'DT_REGISTRO' => '15012026', 'QTD_MOID' => 0, 'ESTQ_INI' => 0, 'QTD_PRODUZ' => 0,
                'ENT_ANID_HID' => 0, 'OUTR_ENTR' => 0, 'PERDA' => 0, 'CONS' => 0, 'SAI_ANI_HID' => 0,
                'SAIDAS' => 0, 'ESTQ_FIN' => 0, 'ESTQ_INI_MEL' => 0, 'PROD_DIA_MEL' => 0, 'UTIL_MEL' => 0,
                'PROD_ALC_MEL' => 0, 'OBS' => null, 'COD_ITEM' => 'PROD1', 'TP_RESIDUO' => '01',
                'QTD_RESIDUO' => 10, 'QTD_RESIDUO_DDG' => 0, 'QTD_RESIDUO_WDG' => 0, 'QTD_RESIDUO_CANA' => 5.5,
            ],
            '|1391|15012026|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00|0,00||PROD1|01|10,00|0,00|0,00|5,50|',
        ];

        yield 'C105: OPER aceita 2 (recusa de recebimento, leiaute 018)' => [
            BlockC::class,
            'c105',
            ['OPER' => '2', 'UF' => 'SP'],
            '|C105|2|SP|',
        ];

        yield 'C120: COD_DOC_IMP aceita 2 (DUIMP, leiaute 020)' => [
            BlockC::class,
            'c120',
            ['COD_DOC_IMP' => '2', 'NUM_DOC_IMP' => '26BR00000123456', 'PIS_IMP' => 42, 'COFINS_IMP' => 193, 'NUM_ACDRAW' => null],
            '|C120|2|26BR00000123456|42,00|193,00||',
        ];

        yield 'C180: valores unitários com 6 decimais; UNID e COD_DA aceitam valor' => [
            BlockC::class,
            'c180',
            [
                'COD_RESP_RET' => 1, 'QUANT_CONV' => 10, 'UNID' => 'UN', 'VL_UNIT_CONV' => 5,
                'VL_UNIT_ICMS_OP_CONV' => 0.9, 'VL_UNIT_BC_ICMS_ST_CONV' => 6, 'VL_UNIT_ICMS_ST_CONV' => 1.08,
                'VL_UNIT_FCP_ST_CONV' => 0, 'COD_DA' => '0', 'NUM_DA' => '123',
            ],
            '|C180|1|10,000000|UN|5,000000|0,900000|6,000000|1,080000|0,000000|0|123|',
        ];

        yield 'D750: DED como valor com 2 decimais (leiaute 019)' => [
            BlockD::class,
            'd750',
            [
                'COD_MOD' => '62', 'SER' => '1', 'DT_DOC' => '31012026', 'QTD_CONS' => 10, 'IND_PREPAGO' => '0',
                'VL_DOC' => 90, 'VL_SERV' => 100, 'VL_SERV_NT' => 0, 'VL_TERC' => 0, 'VL_DESC' => 0, 'VL_DA' => 0,
                'VL_BC_ICMS' => 100, 'VL_ICMS' => 25, 'VL_PIS' => null, 'VL_COFINS' => null, 'DED' => 10,
            ],
            '|D750|62|1|31012026|10|0|90,00|100,00|0,00|0,00|0,00|0,00|100,00|25,00|||10,00|',
        ];

        yield 'H030: valores médios unitários com 6 decimais' => [
            BlockH::class,
            'h030',
            ['VL_ICMS_OP' => 1.5, 'VL_BC_ICMS_ST' => 10, 'VL_ICMS_ST' => 1.8, 'VL_FCP' => 0.2],
            '|H030|1,500000|10,000000|1,800000|0,200000|',
        ];

        yield 'K250: QTD com 6 decimais' => [
            BlockK::class,
            'k250',
            ['DT_PROD' => '31012026', 'COD_ITEM' => 'PROD1', 'QTD' => 10.5],
            '|K250|31012026|PROD1|10,500000|',
        ];

        $chave = Documentos::chave(Documentos::cnpj('334445550001'), 55, 1, 1001, 10010001);
        yield 'E313: CHV_DOCe no campo 07, entre NUM_DOC e DT_DOC' => [
            BlockE::class,
            'e313',
            [
                'COD_PART' => 'F001', 'COD_MOD' => '55', 'SER' => '1', 'SUB' => null, 'NUM_DOC' => 1001,
                'CHV_DOCe' => $chave, 'DT_DOC' => '05012026', 'COD_ITEM' => 'PROD1', 'VL_AJ_ITEM' => 10,
            ],
            "|E313|F001|55|1||1001|$chave|05012026|PROD1|10,00|",
        ];
    }

    /**
     * @param array<string, mixed> $dados
     */
    #[DataProvider('registros')]
    public function testRegistroSaiNoFormatoDoLeiaute020(string $bloco, string $metodo, array $dados, string $esperado): void
    {
        $b = new $bloco('020');
        $b->$metodo((object) $dados);

        $this->assertSame([], $b->errors);
        $this->assertSame($esperado, explode("\n", $b->get())[0]);
    }
}
