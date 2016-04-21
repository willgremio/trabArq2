<?php

require_once('controladores/gerar.php');

$arrayParametros = $argv;
unset($arrayParametros[0]); // o 1 parametro é o executavel, entao removo o 1

$objGerar = new Gerar();
$objGerar->gerarTabelas($arrayParametros); // $argv é o array dos parametros passados na linha de comando
?>