<?php
error_reporting(E_ERROR | E_PARSE);
$dir = scandir('result',1);
foreach ($dir as $diretorio) {
    $file = scandir('result/' . $diretorio . '/', 1);
    exec('mkdir artigos/' . $diretorio);
    for ($i = 0; $i < sizeof($file); $i++) {
        $line = file('result/' . $diretorio . '/' . $file[$i]);
        for ($j = 0, $l = 0; $j < sizeof($line); $j++) {
            if ($line[$j] == "</doc>\n") {
                $l = 0;
                $j++;
            }
            if ($l == 0) {
                $k = $j + 1;
                $name = explode("\n", $line[$k])[0];
                $cab = explode(" ",$line[$j]);
                file_put_contents("artigos/$name", "$cab[0]>\n<$cab[1]>", FILE_APPEND);
                $l += 2;
                $j++;
            } else {
                $phrase = explode(". ", $line[$j]);
                for ($m = 0; $m < sizeof($phrase); $m++) {
                    /*if(!ctype_upper($phrase[$m][0]) && $phrase[$m] != "\n" && $phrase[$m][0] !="'" ){
                        echo $phrase[$m]."\n\n";
                    }*/
                    if ($phrase[$m] != "\n" && $phrase[$m] != " ") {
                        file_put_contents("artigos/$diretorio/$name", $phrase[$m] . ".\n", FILE_APPEND);
                    }
                }
            }


        }
    }
}