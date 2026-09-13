<?php

namespace NFePHP\EFD\Elements\ICMSIPI;

use NFePHP\EFD\Common\Element;
use stdClass;

class B510 extends Element
{
    const REG = 'B510';
    const LEVEL = 3;
    const PARENT = 'B500';

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

    public function postValidation()
    {
        /*
         * Campo 04 (IND_SOC) Validação: O profissional sócio necessariamente tem de ser
         * habilitado (campo IND_PROF preenchido com “0”)
         */
        if ($this->std->ind_soc == '1' && $this->std->ind_prof != '0') {
            $this->errors[] = "[" . self::REG . "] O profissional sócio necessariamente tem de "
            ."ser habilitado (campo IND_PROF preenchido com “0”)";
        }

        /*
         * Campo 05 (CPF) Validação: será conferido o dígito verificador (DV) do
         * CPF informado.
         */
        if (!$this->validaCPF($this->std->cpf)) {
            $this->errors[] = "[" . self::REG . "] O CPF informado não é válido.";
        }
    }

    private function validaCPF($cpf)
    {
        //Cria um array com apenas os digitos numéricos
        $j=0;
        $num=[];
        for ($i=0; $i<(strlen($cpf)); $i++) {
            if (is_numeric($cpf[$i])) {
                $num[$j]=(int)$cpf[$i];
                $j++;
            }
        }

        //Verifica a quantidade de digitos
        if (count($num)!=11) {
            $validaCPF=false;
        } else { //Filtra combinações como 00000000000 e 22222222222
            for ($i=0; $i<10; $i++) {
                if ($num[0]==$i && $num[1]==$i && $num[2]==$i && $num[3]==$i
                && $num[4]==$i && $num[5]==$i && $num[6]==$i && $num[7]==$i
                && $num[8]==$i) {
                    $validaCPF=false;
                    break;
                }
            }
        }

        //Calcula e compara o primeiro dígito verificador
        if (!isset($validaCPF)) {
            $j=10;
            $multiplica=[];
            for ($i=0; $i<9; $i++) {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma%11;
            if ($resto<2) {
                $dg=0;
            } else {
                $dg=11-$resto;
            }
            if ($dg!=$num[9]) {
                $validaCPF=false;
            }
        }

        //Calcula e compara o segundo dígito verificador.
        if (!isset($validaCPF)) {
            $j=11;
            $multiplica=[];
            for ($i=0; $i<10; $i++) {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma%11;
            if ($resto<2) {
                $dg=0;
            } else {
                $dg=11-$resto;
            }
            if ($dg!=$num[10]) {
                $validaCPF=false;
            } else {
                $validaCPF=true;
            }
        }
        //Retorna o resutado (booleano)
        return $validaCPF;
    }
}
