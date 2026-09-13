<?php

namespace NFePHP\EFD\Tests\Pva;

use InvalidArgumentException;
use NFePHP\EFD\Blocks\ICMSIPI\Block0;
use NFePHP\EFD\Blocks\ICMSIPI\Block1;
use NFePHP\EFD\Blocks\ICMSIPI\BlockB;
use NFePHP\EFD\Blocks\ICMSIPI\BlockC;
use NFePHP\EFD\Blocks\ICMSIPI\BlockD;
use NFePHP\EFD\Blocks\ICMSIPI\BlockE;
use NFePHP\EFD\Blocks\ICMSIPI\BlockG;
use NFePHP\EFD\Blocks\ICMSIPI\BlockH;
use NFePHP\EFD\Blocks\ICMSIPI\BlockK;
use NFePHP\EFD\EFDICMS;
use stdClass;

/**
 * Arquivo completo de um leiaute (janeiro do ano de vigência) para validar no
 * PVA da EFD ICMS/IPI: empresa comercial fictícia de SP, perfil A, com
 * - entrada de terceiros (C100 + C170 + C190);
 * - importação própria (C100 + C120 + C190): DUIMP (COD_DOC_IMP 2) no 020, DI antes;
 * - saída própria (C100 + C190);
 * - aquisição de serviço de comunicação por NFCom (D700 + D730; DED a partir do 019);
 * - apuração do ICMS (E100 + E110) batendo com os documentos:
 *   débitos 540,00; créditos 180,00 + 360,00 + 25,00 = 565,00; saldo credor 25,00;
 * - blocos B, G, H e K sem movimento e bloco 1 com o 1010 todo "N".
 *
 * Campo de tipo C recebe string (a validação da biblioteca recusa inteiro).
 * Campo que não existe no leiaute (DED no 018) é ignorado pela biblioteca.
 */
final class ArquivoPva
{
    private const ANO_DO_LEIAUTE = ['018' => 2024, '019' => 2025, '020' => 2026, '021' => 2027];

    /**
     * @return list<string>
     */
    public static function leiautes(): array
    {
        return array_keys(self::ANO_DO_LEIAUTE);
    }

    public static function montar(string $leiaute): EFDICMS
    {
        if (!isset(self::ANO_DO_LEIAUTE[$leiaute])) {
            throw new InvalidArgumentException("Sem arquivo de validação para o leiaute $leiaute.");
        }
        $ano = (string) self::ANO_DO_LEIAUTE[$leiaute];
        $aa = (int) substr($ano, 2);
        $data = static fn(string $diaMes): string => $diaMes . $ano;

        $empresa = Documentos::cnpj('112223330001');
        $fornecedor = Documentos::cnpj('334445550001');
        $cliente = Documentos::cnpj('556667770001');
        $operadora = Documentos::cnpj('778889990001');

        $efd = new EFDICMS();

        $b0 = new Block0($leiaute);
        $b0->z0000(self::std([
            'cod_ver' => $leiaute,
            'cod_fin' => 0,
            'dt_ini' => $data('0101'),
            'dt_fin' => $data('3101'),
            'nome' => 'EMPRESA DE TESTE DO SPED EFD LTDA',
            'cnpj' => $empresa,
            'uf' => 'SP',
            'ie' => Documentos::ieSp('11004249', '11'),
            'cod_mun' => '3550308',
            'ind_perfil' => 'A',
            'ind_ativ' => 1,
        ]));
        $b0->z0001(self::std(['ind_mov' => 0]));
        $b0->z0005(self::std([
            'FANTASIA' => 'TESTE SPED',
            'CEP' => '01001000',
            'END' => 'PRACA DA SE',
            'NUM' => '100',
            'BAIRRO' => 'CENTRO',
            'FONE' => '1133334444',
            'EMAIL' => 'fiscal@empresa-teste.com.br',
        ]));
        $b0->z0100(self::std([
            'NOME' => 'CONTADOR DE TESTE',
            'CPF' => Documentos::cpf('123456789'),
            'CRC' => '1SP000001O0',
            'CEP' => '01001000',
            'END' => 'PRACA DA SE',
            'NUM' => '200',
            'BAIRRO' => 'CENTRO',
            'FONE' => '1133335555',
            'EMAIL' => 'contador@empresa-teste.com.br',
            'COD_MUN' => '3550308',
        ]));
        $participantes = [
            ['F001', 'FORNECEDOR DE TESTE LTDA', '01058', $fornecedor, '3550308', 'RUA DO FORNECEDOR'],
            ['C001', 'CLIENTE DE TESTE LTDA', '01058', $cliente, '3550308', 'RUA DO CLIENTE'],
            ['T001', 'OPERADORA DE TELECOMUNICACAO DE TESTE SA', '01058', $operadora, '3550308', 'AVENIDA DA OPERADORA'],
            ['E001', 'FOREIGN SUPPLIER INC', '02496', null, null, 'MAIN STREET'],
        ];
        foreach ($participantes as [$codigo, $nome, $pais, $cnpj, $municipio, $endereco]) {
            $b0->z0150(self::std([
                'COD_PART' => $codigo,
                'NOME' => $nome,
                'COD_PAIS' => $pais,
                'CNPJ' => $cnpj,
                'COD_MUN' => $municipio,
                'END' => $endereco,
                'NUM' => '1',
            ]));
        }
        $b0->z0190(self::std(['UNID' => 'UN', 'DESCR' => 'UNIDADE']));
        $b0->z0200(self::std([
            'COD_ITEM' => 'PROD1',
            'DESCR_ITEM' => 'MERCADORIA PARA REVENDA',
            'UNID_INV' => 'UN',
            'TIPO_ITEM' => '00',
            'COD_NCM' => '84713012',
            'ALIQ_ICMS' => 18,
        ]));
        $b0->z0200(self::std([
            'COD_ITEM' => 'PROD2',
            'DESCR_ITEM' => 'MERCADORIA IMPORTADA PARA REVENDA',
            'UNID_INV' => 'UN',
            'TIPO_ITEM' => '00',
            'COD_NCM' => '85176231',
            'ALIQ_ICMS' => 18,
        ]));
        $efd->add($b0);

        $bB = new BlockB($leiaute);
        $bB->b001(self::std(['IND_DAD' => '1']));
        $efd->add($bB);

        $bC = new BlockC($leiaute);
        $bC->c001(self::std(['IND_MOV' => '0']));
        // entrada de terceiros
        $bC->c100(self::nota(
            '0',
            '1',
            'F001',
            1001,
            Documentos::chave($fornecedor, 55, 1, 1001, 10010001, $aa),
            $data('0501'),
            $data('0601'),
            1000,
            180
        ));
        $bC->c170(self::std([
            'NUM_ITEM' => 1,
            'COD_ITEM' => 'PROD1',
            'QTD' => 10,
            'UNID' => 'UN',
            'VL_ITEM' => 1000,
            'VL_DESC' => 0,
            'IND_MOV' => '0',
            'CST_ICMS' => '000',
            'CFOP' => '1102',
            'VL_BC_ICMS' => 1000,
            'ALIQ_ICMS' => 18,
            'VL_ICMS' => 180,
            'VL_BC_ICMS_ST' => 0,
            'ALIQ_ST' => 0,
            'VL_ICMS_ST' => 0,
        ]));
        $bC->c190(self::analitico('000', '1102', 1000, 180));
        // importação própria: DUIMP a partir do 020, DI antes
        $bC->c100(self::nota(
            '0',
            '0',
            'E001',
            501,
            Documentos::chave($empresa, 55, 1, 501, 50150001, $aa),
            $data('1001'),
            $data('1001'),
            2000,
            360
        ));
        $bC->c120(self::std([
            'COD_DOC_IMP' => $leiaute === '020' ? '2' : '0',
            'NUM_DOC_IMP' => $leiaute === '020' ? $aa . 'BR00000123456' : $aa . '00123456',
            'PIS_IMP' => 42,
            'COFINS_IMP' => 193,
        ]));
        $bC->c190(self::analitico('100', '3102', 2000, 360));
        // saída própria
        $bC->c100(self::nota(
            '1',
            '0',
            'C001',
            777,
            Documentos::chave($empresa, 55, 1, 777, 77770001, $aa),
            $data('2001'),
            $data('2001'),
            3000,
            540
        ));
        $bC->c190(self::analitico('000', '5102', 3000, 540));
        $efd->add($bC);

        $bD = new BlockD($leiaute);
        $bD->d001(self::std(['IND_MOV' => '0']));
        $bD->d700(self::std([
            'IND_OPER' => '0',
            'IND_EMIT' => '1',
            'COD_PART' => 'T001',
            'COD_MOD' => '62',
            'COD_SIT' => '00',
            'SER' => '1',
            'NUM_DOC' => 12345,
            'DT_DOC' => $data('1501'),
            'DT_E_S' => $data('1501'),
            'VL_DOC' => 100,
            'VL_DESC' => 0,
            'VL_SERV' => 100,
            'VL_SERV_NT' => 0,
            'VL_TERC' => 0,
            'VL_DA' => 0,
            'VL_BC_ICMS' => 100,
            'VL_ICMS' => 25,
            'CHV_DOCE' => Documentos::chave($operadora, 62, 1, 12345, 12345001, $aa),
            'FIN_DOCe' => '0',
            'TIP_FAT' => '0',
            'DED' => 0,
        ]));
        $bD->d730(self::std([
            'CST_ICMS' => '000',
            'CFOP' => '1303',
            'ALIQ_ICMS' => 25,
            'VL_OPR' => 100,
            'VL_BC_ICMS' => 100,
            'VL_ICMS' => 25,
            'VL_RED_BC' => 0,
        ]));
        $efd->add($bD);

        $bE = new BlockE($leiaute);
        $bE->e001(self::std(['IND_MOV' => '0']));
        $bE->e100(self::std(['DT_INI' => $data('0101'), 'DT_FIN' => $data('3101')]));
        $bE->e110(self::std([
            'VL_TOT_DEBITOS' => 540,
            'VL_AJ_DEBITOS' => 0,
            'VL_TOT_AJ_DEBITOS' => 0,
            'VL_ESTORNOS_CRED' => 0,
            'VL_TOT_CREDITOS' => 565,
            'VL_AJ_CREDITOS' => 0,
            'VL_TOT_AJ_CREDITOS' => 0,
            'VL_ESTORNOS_DEB' => 0,
            'VL_SLD_CREDOR_ANT' => 0,
            'VL_SLD_APURADO' => 0,
            'VL_TOT_DED' => 0,
            'VL_ICMS_RECOLHER' => 0,
            'VL_SLD_CREDOR_TRANSPORTAR' => 25,
            'DEB_ESP' => 0,
        ]));
        $efd->add($bE);

        $bG = new BlockG($leiaute);
        $bG->g001(self::std(['IND_MOV' => '1']));
        $efd->add($bG);

        $bH = new BlockH($leiaute);
        $bH->h001(self::std(['IND_MOV' => '1']));
        $efd->add($bH);

        $bK = new BlockK($leiaute);
        $bK->k001(self::std(['IND_MOV' => '1']));
        $efd->add($bK);

        $b1 = new Block1($leiaute);
        $b1->z1001(self::std(['IND_MOV' => 0]));
        $b1->z1010(self::std([
            'IND_EXP' => 'N',
            'IND_CCRF' => 'N',
            'IND_COMB' => 'N',
            'IND_USINA' => 'N',
            'IND_VA' => 'N',
            'IND_EE' => 'N',
            'IND_CART' => 'N',
            'IND_FORM' => 'N',
            'IND_AER' => 'N',
            'IND_GIAF1' => 'N',
            'IND_GIAF3' => 'N',
            'IND_GIAF4' => 'N',
            'IND_REST_RESSARC_COMPL_ICMS' => 'N',
        ]));
        $efd->add($b1);

        return $efd;
    }

    /**
     * @param array<string, mixed> $campos
     */
    private static function std(array $campos): stdClass
    {
        return (object) $campos;
    }

    private static function nota(
        string $operacao,
        string $emitente,
        string $participante,
        int $numero,
        string $chave,
        string $emissao,
        string $entradaSaida,
        float $valor,
        float $icms
    ): stdClass {
        return self::std([
            'IND_OPER' => $operacao,
            'IND_EMIT' => $emitente,
            'COD_PART' => $participante,
            'COD_MOD' => '55',
            'COD_SIT' => '00',
            'SER' => '1',
            'NUM_DOC' => $numero,
            'CHV_NFE' => $chave,
            'DT_DOC' => $emissao,
            'DT_E_S' => $entradaSaida,
            'VL_DOC' => $valor,
            'IND_PGTO' => '0',
            'VL_DESC' => 0,
            'VL_ABAT_NT' => 0,
            'VL_MERC' => $valor,
            'IND_FRT' => '9',
            'VL_FRT' => 0,
            'VL_SEG' => 0,
            'VL_OUT_DA' => 0,
            'VL_BC_ICMS' => $valor,
            'VL_ICMS' => $icms,
            'VL_BC_ICMS_ST' => 0,
            'VL_ICMS_ST' => 0,
            'VL_IPI' => 0,
            'VL_PIS' => 0,
            'VL_COFINS' => 0,
            'VL_PIS_ST' => 0,
            'VL_COFINS_ST' => 0,
        ]);
    }

    private static function analitico(string $cst, string $cfop, float $valor, float $icms): stdClass
    {
        return self::std([
            'CST_ICMS' => $cst,
            'CFOP' => $cfop,
            'ALIQ_ICMS' => 18,
            'VL_OPR' => $valor,
            'VL_BC_ICMS' => $valor,
            'VL_ICMS' => $icms,
            'VL_BC_ICMS_ST' => 0,
            'VL_ICMS_ST' => 0,
            'VL_RED_BC' => 0,
            'VL_IPI' => 0,
        ]);
    }
}
