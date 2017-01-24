<?php
/**
 * Created by PhpStorm.
 * User: marcos
 * Date: 24/01/17
 * Time: 13:41
 */
error_reporting(E_ERROR | E_PARSE);
$file = "A032.wav"; //arquivo de áudio com canais do tipo MONO e amostragem de 16000Hz
exec("python time-example.py 1 $file > segmentos");
$json = "";
$arquivo = file('segmentos');
$flag = 0;
$inicio = 0;
for($i = 0 ; $i < sizeof($arquivo); $i++){
    preg_match_all('/[0-9.]+/',$arquivo[$i],$out,PREG_PATTERN_ORDER);
    if($flag!=1){
        $inicio = $out[0][0];
        $fim = ((float)$out[0][1] - (float)$out[0][0]);
    }
    else {
        $fim = ((float)$out[0][1] - (float)$inicio);
    }
    if($fim >= 10.0){
        exec("sudo ffmpeg -i $file -ss $inicio -t $fim seg.wav");
        $json = $json . shell_exec('curl -T seg.wav "http://138.121.71.38:8080/client/dynamic/recognize" ');
        unlink('seg.wav');
        $flag = 0;
    }
    else{
        $flag = 1;
    }
}

echo $json;
unlink('segmentos');