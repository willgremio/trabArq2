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

    public function setNumeroHits() {
        $this->numeroHits ++;
    }

    public function getNumeroHits() {
        return $this->numeroHits;
    }

    public function setNumeroMiss() {
        $this->numeroMiss ++;
    }

    public function getNumeroMiss() {
        return $this->numeroMiss;
    }

    public function setTabelaCache($tabelaCache) {
        $this->tabelaCache = $tabelaCache;
    }

    public function getTabelaCache() {
        return $this->tabelaCache;
    }

    public function gerar() {
        $this->mostrarTabela();

        $linhasArquivo = file($this->arquivo);

        foreach ($linhasArquivo as $linha) {
            if ($this->_existeValorNaMemoria($linha)) {
                $this->setNumeroHits();
            } else {
                $this->setNumeroMiss();
            }

            $this->setEnderecoNaMemoria(trim($linha));
            $this->mostrarTabela();
        }
    }

    private function _existeValorNaMemoria($linha) {
        $memoriaCache = $this->getTabelaCache();
        $idx = self::getIdxEndereco($linha);

        if (!isset($memoriaCache[$idx])) {
            return false;
        }

        $tag = self::getTagEndereco($linha);
        if ($memoriaCache[$idx]['tag'] != $tag) {
            return false;
        }

        return true;
    }

    public function setEnderecoNaMemoria($endereco) {
        $memoriaCache = $this->getTabelaCache();
        $idx = self::getIdxEndereco($endereco);
        $tag = self::getTagEndereco($endereco);
        $memoriaCache[$idx] = [
            'v' => 1,
            'tag' => $tag,
            'data1' => 'mem(' . $endereco . ')',
            'data2' => 'mem(' . $endereco . ')',
            'data3' => 'mem(' . $endereco . ')',
            'data4' => 'mem(' . $endereco . ')'
        ];

        $this->setTabelaCache($memoriaCache);
    }

    public static function getIdxEndereco($endereco) {
        return substr(trim($endereco), -2, 1);
    }

    public static function getTagEndereco($endereco) {
        return substr(trim($endereco), 0, 6);
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
        $mask = "|%3s|%1s|%7s|%17s|%17s|%17s|%17s|\n";
        printf($mask, 'Idx', 'V', 'Tag', 'data', 'data', 'data', 'data');
        foreach ($this->getTabelaCache() as $linha => $valores) {
            printf($mask, $linha, $valores['v'], $valores['tag'], $valores['data1'], $valores['data2'], $valores['data3'], $valores['data4']);
        }

        echo "\n HITS: " . $this->getNumeroHits() . "     MISSES: " . $this->getNumeroMiss() . "\n";
        echo "\n Pressione enter para continuar... \n";
        fgetc(STDIN);
    }

}
