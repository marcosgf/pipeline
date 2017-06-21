<?php

/**
 * Created by PhpStorm.
 * User: bavi
 * Date: 01/06/17
 * Time: 16:36
 */
class Normalize
{
    public $text;

    function __construct($text){
        $this->text = $text;
    }

    function lowerCaracteres() {
        $trocaMIN = array(
            'à', 'á', 'ã', 'â',
            'è','ê', 'é', 'ẽ',
            'ì','í', 'î', 'ĩ',
            'ò','ó', 'õ', 'ô',
            'ù','ú', 'ü',
            'ç',
            'é', 'ê');

        $trocaMAI = array(
            'À', 'Á', 'Ã', 'Â',
            'È','Ê', 'É', 'Ẽ',
            'Ì','Í', 'Î', 'Ĩ',
            'Ò','Ó', 'Õ', 'Ô',
            'Ù','Ú', 'Ü',
            'Ç',
            'É', 'Ê');

        $this->text=(strtolower(str_replace($trocaMAI,$trocaMIN,$this->text)));
    }

    function abbreviation(){ //http://www.soportugues.com.br/secoes/abrev/abrev9.php
        $abbrev = array('a.C.', 'd.C.','°C', 'Cia.' , 'Dr.', 'Dra.', 'Drs.', 'Dras.', ' kg ',
                        ' km ', ' km² ', ' km/h ', 'Lt.da', 'Ltda', 'm²', 'm³');
        $trasl = array('antes de Cristo','depois de Cristo', ' grau Celsius', ' companhia', 'doutor ', 'doutora ', 'doutores ', 'doutoras ', ' quilogramas ',
                        ' quilômetros ', ' quilômetros quadrados ', ' quilômetros por hora ',' limitada ', ' limitada ', ' metros quadrados ', ' metros cúbicos ');
        $this->text = str_replace($abbrev,$trasl,$this->text);
        }

    function temperature(){
        preg_match_all('/[0-9]+[°]+[0-9]*[\']*[0-9]*["]*/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = $this->extensoTemperature($out[$i][$j]);
                $result = str_replace($out[$i][$j],$full,$this->text);
                $this->text = $result;
            }
        }
        return $result;
    }

    private function extensoTemperature($valor){
        //echo $valor;
        $table=array("°"=>' graus ', '\''=>' minutos ' , '"'=>' segundos ');
        for($i = 0 ; $i < strlen($valor); $i++){

            if(array_key_exists($valor{$i},$table)|| ord($valor{$i}==194)){
                $valor = str_replace($valor{$i},$table[$valor{$i}],$valor);
            }
        }
        return $valor;
    }

    private function extensoRomanNumeral($number){
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
        $value = 0;
        for($i = 0 ; $i < strlen($number); $i++){
            $aux = $number{$i};
            if(array_key_exists($aux.$number{$i+1},$table)){
                $aux  = $aux.$number{$i+1};
                $i++;
            }
            $value += $table[$aux];
        }
        return $this->extensoCardinal($value);
    }

    function Encoding (){
        return utf8_encode($this->text);
    }

    private function extensoOrdinalO($valor, $maiusculas) {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
            // retira o ponto de milhar, se tiver
            $valor = str_replace(".", "", $valor);
            // troca a virgula decimal por ponto decimal
            $valor = str_replace(",", ".", $valor);
        }
        $singular = array("", "","milésimo", "milionésimo", "bilionésimo", "trilhonésimo",
            "quatrilhonésimo");
        $plural = array("", "", "milésimo", "milionésimo", "bilionésimo", "trilhonésimo",
            "quatrilhonésimo");
        $c = array("", "centésimo", "ducentésimo", "tricentésimo", "quadrigentésimo",
            "quingentésimo", "seiscentésimo", "septigentésimo", "octigentésimo", "nongentésimo");
        $d = array("", "décimo", "vigésimo", "trigésimo", "quadragésimo", "quinquagésimo",
            "sexagésimo", "septuagésimo", "octogésimo", "nonagésimo");
        $d10 = array("décimo", "décimo primeiro", "décimo segundo", "décimo terceiro", "décimo quarto", "décimo quinto",
            "décimo sexto", "décimo sétimo", "décimo oitavo", "décimo nono");
        $u = array("", "primeiro", "segundo", "terceiro", "quarto", "quinto", "sexto",
            "sétimo", "oitavo", "nono");
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "centésimo" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " " : "") . $rd . (($rd &&
                    $ru) ? " " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000"
            )$z++; elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " ") : " ") . $r;
        }
        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
            return (strtoupper($rt) ? strtoupper($rt) : "zero");
        } else {
            return (ucwords($rt) ? ucwords($rt) : "zero");
        }
    }

    private function extensoOrdinalA($valor, $maiusculas) {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
            // retira o ponto de milhar, se tiver
            $valor = str_replace(".", "", $valor);
            // troca a virgula decimal por ponto decimal
            $valor = str_replace(",", ".", $valor);
        }
        $singular = array("", "","milésima", "milionésima", "bilionésima", "trilhonésima",
            "quatrilhonésima");
        $plural = array("", "", "milésima", "milionésima", "bilionésima", "trilhonésima",
            "quatrilhonésima");
        $c = array("", "centésima", "ducentésima", "tricentésima", "quadrigentésima",
            "quingentésima", "seiscentésima", "septigentésima", "octigentésima", "nongentésima");
        $d = array("", "décima", "vigésima", "trigésima", "quadragésima", "quinquagésima",
            "sexagésima", "septuagésima", "octogésima", "nonagésima");
        $d10 = array("décima", "décima primeira", "décima segunda", "décima terceira", "décima quarta", "décima quinta",
            "décima sexta", "décima sétima", "décima oitava", "décima nona");
        $u = array("", "primeira", "segunda", "terceira", "quarta", "quinta", "sexta",
            "sétima", "oitava", "nona");
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "centésima" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " " : "") . $rd . (($rd &&
                    $ru) ? " " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000"
            )$z++; elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " ") : " ") . $r;
        }
        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
            return (strtoupper($rt) ? strtoupper($rt) : "zero");
        } else {
            return (ucwords($rt) ? ucwords($rt) : "zero");
        }
    }

    private function extensoCardinal ($valor, $maiusculas ){
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
            // retira o ponto de milhar, se tiver
            $valor = str_replace(".", "", $valor);
            // troca a virgula decimal por ponto decimal
            $valor = str_replace(",", ".", $valor);
        }
        $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões",
            "quatrilhões");
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
            "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
            "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
            "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
            "sete", "oito", "nove");
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                    $ru) ? " e " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000"
            )$z++; elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
            return (strtoupper($rt) ? strtoupper($rt) : "zero");
        } else {
            return (ucwords($rt) ? ucwords($rt) : "zero");
        }
    }

    function numberOrdinal (){
        preg_match_all('/[0-9]+º/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = $this->extensoOrdinalO(explode('º',$out[$i][$j])[0]);
                $result = str_replace($out[$i][$j],$full,$this->text);
                $this->text = $result;
            }
        }
        preg_match_all('/[0-9]+ª/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = $this->extensoOrdinalA(explode('ª',$out[$i][$j])[0]);
                $result = str_replace($out[$i][$j],$full,$this->text);
                $this->text = $result;
            }
        }
        return $result;
    }

    function numberCardinal (){
        preg_match_all('/[0-9]+/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = $this->extensoCardinal($out[$i][$j]);
                $result = str_replace($out[$i][$j],$full,$this->text);
                $this->text = $result;
            }
        }
        return $result;
    }

    function romanNumeralSec (){
        preg_match_all('/[sS]éculo [a-zA-Z]+/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = $this->extensoRomanNumeral($out[$i][$j]);
                $result = str_replace($out[$i][$j],"século".$full,$this->text);
                $this->text = $result;
            }
        }
        return $result;
    }

    private function extensoMonetario($value){
        //Converte para o formato float
        if(strpos($value, ',') !== FALSE){
            $value = str_replace('.','',$value);
            $value = str_replace(',','.',$value);
        }
        //Separa o valor "reais" dos "centavos";
        $value = explode('.',$value);
        return ucfirst($this->extensoCardinal($value[0])). ' reais' . ((isset($value[1]) && $value[1] > 0)?' e '.$this->extensoCardinal($value[1]).' centavos':'');
    }

    function numberMonetary(){
        preg_match_all('/[R$]+\s*[0-9.,]+/',$this->text,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $value = str_replace("R$","",$out[$i][$j]);
                $value = $this->extensoMonetario($value);
                $this->text = str_replace($out[$i][$j],$value,$this->text);
            }
        }
    }

    function getText(){
        return $this->text;
    }
}