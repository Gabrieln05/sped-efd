<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

/**
 * REGISTRO H005: TOTAIS DO INVENTÁRIO
 * Este registro deve ser apresentado para discriminar os valores totais dos
 * itens/produtos do inventário realizado em 31 de dezembro de cada exercício,
 * ou nas demais datas estabelecidas pela legislação fiscal ou comercial.
 * O inventário deverá ser apresentado no arquivo da EFD-ICMS/IPI até o
 * segundo mês subsequente ao evento. Ex. Inventário realizado em 31/12/08
 * deverá ser apresentado na EFD-ICMS/IPI de período de referência
 * fevereiro de 2009.
 * A partir de julho de 2012, as empresas que exerçam as atividades descritas
 * na Classificação Nacional de Atividades Econômicas/Fiscal  (CNAE-Fiscal)
 * sob  os  códigos  4681-8/01 e 4681-8/02 deverão  apresentar  este
 * registro, mensalmente, para discriminar os valores itens/produtos do
 * Inventário  realizado ao final do mesmo período de referência do arquivo
 * da EFD-ICMS/IPI.  Informar  como  MOT_INV  o  código  “01”.
 * Exemplo:  o  inventário  realizado  no  final  do  mês  de  janeiro,
 * deverá ser apresentado na escrituração do mês de janeiro.
 *
 * Atribuir valor Zero ao inventário significa escriturar sem estoque.
 */
class H005 extends Element
{
    const REG = 'H005';
    const LEVEL = 2;
    const PARENT = 'H001';

    /**
     * Constructor
     * @param stdClass $std
     * @param stdClass $vigencia
     */
    public function __construct(stdClass $std, stdClass $vigencia)
    {
        parent::__construct(self::REG, $vigencia);
        $this->std = $this->standarize($std);
    }
}
