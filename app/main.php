<?php

require_once('app/Controladores/Gerar.php');

$objGerar = new Gerar();
$objGerar->gerarTabelas($argv); // $argv é o array dos parametros passados na linha de comando