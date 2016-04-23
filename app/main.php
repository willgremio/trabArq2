<?php

require_once('app/controladores/gerar.php');

// $argv é o array dos parametros passados na linha de comando
unset($argv[0]); // o 1 parametro é o main.php, entao removo do array
unset($argv[1]); // o 2 parametro é o ./chachesim, entao removo tbm

$arrayParametros = array_values($argv); // indexa o array na ordem correta

$objGerar = new Gerar();
$objGerar->gerarTabelas($arrayParametros);