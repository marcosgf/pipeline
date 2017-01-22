<?php
    error_reporting(E_ERROR | E_PARSE);
    $eg = " 30 Quando eclodiu a Primeira Guerra Mundial, Hitler vivia em Munique e, embora fosse um cidadão austríaco, ele se voluntariou no Exército da Baviera
    Um relatório feito pelas autoridades bávaras em 1924 diz que Hitler serviu no exército local por erro
    Ele se juntou ao 16º Regimento Reserva de Infantaria Bávara (1ª Companhia do Regimento), servindo como mensageiro na Frente Ocidental na França e na Bélgica, uma função perigosa, que envolvia exposição a fogo inimigo, em vez da proteção proporcionada por uma trincheira
    Serviu também parte do tempo no quartel-general do regimento em Fournes-en-Weppes
    Ele esteve presente nas batalhas de Ypres, do Somme (onde foi ferido), de Arras e de Passchendaele
    Ele foi condecorado por bravura, 20º recebendo a Cruz de Ferro, de segunda classe, ao fim de 1914
    Sob recomendação do oficial judeu Hugo Gutmann, Hitler recebeu outra medalha, a Cruz de Ferro de primeira classe, em 4 de agosto de 1918, uma condecoração raramente dada a um soldado de sua patente (\"Gefreiter\")
    Ele também recebeu o Distintivo de Ferido em 18 de maio de 1918
    A folha de serviço de Hitler, no geral, foi exemplar, mas nunca foi promovido além de Cabo, que era a patente mais alta oferecida a um estrangeiro no exército alemão à época.
    nº ";


    function UpperCaracteres($input) {
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

        return $input=(strtoupper(str_replace($trocaMIN,$trocaMAI,$input)));
    }

    function Encoding ($input){
        return utf8_encode($input);
    }

    function extensoOrdinalO($valor, $maiusculas) {
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

    function extensoOrdinalA($valor, $maiusculas) {
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

    function extensoCardinal ($valor, $maiusculas ){
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

    function numberOrdinal ($input){
        preg_match_all('/[0-9]+º/',$input,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = extensoOrdinalO(explode('º',$out[$i][$j])[0]);
                $result = str_replace($out[$i][$j],$full,$input);
                $input = $result;
            }
        }
        preg_match_all('/[0-9]+ª/',$input,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = extensoOrdinalA(explode('ª',$out[$i][$j])[0]);
                $result = str_replace($out[$i][$j],$full,$input);
                $input = $result;
            }
        }
        return $result;
    }

    function numberCardinal ($input){
        preg_match_all('/[0-9]+/',$input,$out,PREG_PATTERN_ORDER);
        for($i=0;$i<sizeof($out);$i++){
            for($j=0;$j<sizeof($out[$i]);$j++){
                $full = extensoCardinal($out[$i][$j]);
                $result = str_replace($out[$i][$j],$full,$input);
                $input = $result;
            }
        }
        return $result;
    }

    function extensoMonetario($input){
        //Converte para o formato float
        if(strpos($input, ',') !== FALSE){
            $input = str_replace('.','',$input);
            $input = str_replace(',','.',$input);
        }
        //Separa o valor "reais" dos "centavos";
        $input = explode('.',$input);
        return ucfirst(extensoCardinal($input[0])). ' reais' . ((isset($input[1]) && $input[1] > 0)?' e '.extensoCardinal($input[1]).' centavos.':'');
    }

    echo extensoMonetario('0,67');
    /*
    $result = numberOrdinal($eg);
    echo $result;
    $result1 = numberCardinal($result);
    echo $result1;
    */
?>