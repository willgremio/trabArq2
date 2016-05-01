<?php

require_once('app/controladores/gerar.php');

$objGerar = new Gerar();
$objGerar->gerarTabelas($argv); // $argv é o array dos parametros passados na linha de comando