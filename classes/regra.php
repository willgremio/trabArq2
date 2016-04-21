<?php

/**
 * Description of regras
 *
 * @author Willian
 */
class Regra {

    private $arquivo;
    private $isPassoAPasso;
    private $tabelaCache = array();
    private $numeroHits = 0;
    private $numeroMiss = 0;

    public function __construct($arrayParametros) {
        $this->isPassoAPasso = $this->_isPassoAPasso($arrayParametros);
        $this->arquivo = $this->_getArquivoEntrada($arrayParametros);
        $this->tabelaCache = $this->_getTabelaCacheInicial();
    }

    public function gerar() {
        $this->mostrarTabela();
        $linhasArquivo = file($this->arquivo);

        foreach ($linhasArquivo as $linha) {
            
        }
    }

    private function _isPassoAPasso($arrayParametros) {
        if (count($arrayParametros) > 1) {
            return true;
        }

        return false;
    }

    private function _getArquivoEntrada($arrayParametros) {
        if ($this->isPassoAPasso) {
            return $arrayParametros[2];
        }

        return $arrayParametros[1];
    }

    private function _getTabelaCacheInicial() {
        $arrayTabelaCache = array();
        for ($i = 0; $i < 16; $i++) {
            $arrayTabelaCache[$i] = [
                'v' => 0,
                'tag' => '',
                'data1' => '',
                'data2' => '',
                'data3' => '',
                'data4' => '',
            ];
        }

        return $arrayTabelaCache;
    }

    public function mostrarTabela() {
        $mask = "|%3s|%1s|%6s|%13s|%13s|%13s|%13s|\n";
        printf($mask, 'Idx', 'V', 'Tag', 'data', 'data', 'data', 'data');
        foreach ($this->tabelaCache as $linha => $valores) {
            printf($mask, $linha, $valores['v'], $valores['tag'], $valores['data1'], $valores['data2'], $valores['data3'], $valores['data4']);
        }

        echo "\n HITS: " . $this->numeroHits . "     MISSES: " . $this->numeroMiss . "\n";
        echo "\n Pressione enter para continuar... \n";
        fgetc(STDIN);
    }

}
