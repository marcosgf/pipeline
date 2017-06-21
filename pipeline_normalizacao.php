<?php
include("Normalize.php");
error_reporting(E_ERROR | E_PARSE);
$eg = "Fournes-en-Weppes R$1.654,85
    Somme (onde foi ferido), Século XVIII de Arras e de Passchendaele
    Ele a, 20º RECEBENDO A CRUZ a Cruz de Ferro, de segunda classe, ao fim de 1914
    Sob , em 4 de agosto de 1918, uma condecoração raramente dada a um soldado de sua patente (\"Gefreiter\")
    Ele também século XX recebeu o Distintivo de Ferido em 18 de maio de 1918 R$ 125,06
    nº 4.000 a.C. e pode ser também 5.544 d.C. ou podemos ter algo do tipo 23°32'19\" ou também 23°54' ou simplesmente 78° existe também a opção de número romanos século XIII";
//abreviações a.C. e d.C.
// graus minutos e segundos
$t = new Normalize($eg);
//$t->romanNumeralSec();
$t->numberOrdinal();
$t->temperature();
$tt = $t->getText();
echo $tt;
//$t->numberOrdinal();
//$t->temperature();
//$tt = $t->getText();
//echo $tt;
//$t->numberOrdinal();
//$tt = $t->getText();
//echo $tt;
//$t->numberCardinal();
//$t->lowerCaracteres();
//$tt = $t->getText();
//echo $tt;

/*
$result = numberOrdinal($eg);
echo $result;
$result1 = numberCardinal($result);
echo $result1;
*/

?>