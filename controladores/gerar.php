<?php

require_once('classes/regra.php');
/**
 * Description of Gerar
 *
 * @author Willian
 */
class Gerar {

    public function gerarTabelas($arrayParametros) {
        try {
            $this->_validaParametros($arrayParametros);
            $objRegra = new Regra($arrayParametros);
            $objRegra->gerar();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function _validaParametros($arrayParametros) {
        $numeroParametros = count($arrayParametros);
        if ($numeroParametros == 0) {  //tem q ter pelo menos o parametro do .txt
            throw new Exception('É necessário o arquivo .txt!');
        }
        
        if ($numeroParametros > 2) {
            throw new Exception('Só é valido passar 2 parametros apenas!');
        }
    }

}
