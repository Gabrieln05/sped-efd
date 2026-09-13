<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO G110: ICMS – ATIVO PERMANENTE – CIAP
 *
 * Este registro tem o objetivo de prestar informações sobre o CIAP:
 *
 * a) saldo de ICMS do CIAP,  composto pelo valor do ICMS de bens
 *    ou componentes (somente componentes cujo crédito de ICMS já foi
 *    apropriado) que entraram anteriormente ao período de apuração. (campo 4);
 * b) o somatório das parcelas de ICMS passíveis de apropriação de cada bem ou
 *    componente, inclusive aqueles que foram escriturados no CIAP em período
 *    anterior (campo 5);
 * c) o valor do índice de participação do somatório do valor das saídas
 *    tributadas e saídas para exportação no valor total das saídas (campo 8) -
 *    (o valor é sempre igual ou menor que 1 (um);
 * d) o valor de ICMS a ser apropriado como crédito. Esse valor (campo 9) será
 *    apropriado diretamente no Registro de Apuração do ICMS, como ajuste de
 *    apuração, salvo se a legislação obrigar à emissão de documento fiscal;
 * e) o valor de outras parcelas de ICMS a ser apropriado. Esse valor
 *    (campo 10) será apropriado diretamente no Registro de Apuração do ICMS,
 *    como ajuste de apuração, salvo se a legislação obrigar à emissão
 *    de documento fiscal.
 *
 * Não podem ser informados dois ou mais registros com a mesma combinação de
 * conteúdo nos campos DT_INI e DT_FIN e esta combinação deve ser igual à
 * informada em um registro E100.
 */
class G110 extends Element
{
    const REG = 'G110';
    const LEVEL = 2;
    const PARENT = 'G001';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
        $this->postValidation();
    }

    /**
     * Aqui são colocadas validações adicionais que requerem mais logica
     * e processamento
     * Deve ser usado apenas quando necessário
     * @throws \InvalidArgumentException
     */
    public function postValidation()
    {
        if ($this->values->vl_trib_exp > $this->values->vl_total) {
            $this->errors[] = "[" . self::REG . "] O valor "
                . "informado no campo VL_TRIB_EXP deve ser menor ou igual ao informado "
                . "no campo VL_TOTAL.";
        }
    }
}
