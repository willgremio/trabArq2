<?php

require_once('app/config/parametros.php');
require_once('app/classes/conversao.php');

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
        $this->setIsPassoAPasso($this->_isPassoAPasso($arrayParametros));
        $this->setArquivo($this->_getArquivoEntrada($arrayParametros));
        $this->setTabelaCache($this->_getTabelaCacheInicial());
    }

    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    public function getArquivo() {
        return $this->arquivo;
    }

    public function setIsPassoAPasso($isPassoAPasso) {
        $this->isPassoAPasso = $isPassoAPasso;
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

    public function getNumeroMisses() {
        return $this->numeroMiss;
    }

    public function setTabelaCache($tabelaCache) {
        $this->tabelaCache = $tabelaCache;
    }

    public function getTabelaCache() {
        return $this->tabelaCache;
    }

    public function gerar() {
        if ($this->isPassoAPasso) {
            $this->mostrarTabela();
        }

        $linhasArquivo = file($this->getArquivo());
        $ultimoEnderecoArquivo = end($linhasArquivo);

        foreach ($linhasArquivo as $endereco) {
            $endereco = trim($endereco); //remove espaçamentos
            if ($this->_existeEnderecoNaMemoria($endereco)) {
                $this->setNumeroHits();
            } else {
                $this->setNumeroMiss();
            }

            $this->setEnderecoNaMemoria($endereco);

            $isUltimoEndereco = $ultimoEnderecoArquivo == $endereco; //se não é passo a passo, mostro a tela somente no ultimo endereco do arquivo
            if ($this->isPassoAPasso || $isUltimoEndereco) {
                $this->mostrarTabela($endereco);
            }
        }
    }

    private function _existeEnderecoNaMemoria($endereco) {
        $memoriaCache = $this->getTabelaCache();
        $idx = self::getIdxEndereco($endereco);

        if (!isset($memoriaCache[$idx])) {
            return false;
        }

        $tag = self::getTagEndereco($endereco);
        if ($memoriaCache[$idx]['tag'] != $tag) {
            return false;
        }

        return true;
    }

    public function setEnderecoNaMemoria($endereco) {
        $memoriaCache = $this->getTabelaCache();
        $idx = self::getIdxEndereco($endereco);
        $tag = self::getTagEndereco($endereco);
        $this->removeUltimoHexadecimalEndereco($endereco);
        $memoriaCache[$idx] = [
            'v' => 1,
            'tag' => $tag
        ];

        for ($i = 1; $i <= self::getQuantidadeBlocosPalavra(); $i ++) {
            $memoriaCache[$idx]['data' . $i] = 'mem(' . $endereco . ')';
        }

        $this->setTabelaCache($memoriaCache);
    }

    public static function getIdxEndereco($endereco) {
        $tamanhoIndex = self::getTamanhoIndex();
        $tamanhoTag = self::getTamanhoTag();
        return substr($endereco, $tamanhoTag, $tamanhoIndex);
    }

    public static function getTagEndereco($endereco) {
        $tamanhoTag = self::getTamanhoTag();
        return substr($endereco, 0, $tamanhoTag);
    }

    public static function getTamanhoTag() {
        return NUMERO_BITS_TAG / 4; //tem que ve se isso ta certo
    }

    public static function getTamanhoIndex() {
        return NUMERO_BITS_INDEX / 4; //tem que ve se isso ta certo
    }

    public static function getQuantidadeLinhas() {
        return pow(2, NUMERO_BITS_INDEX); //Potência. base 2 e expoente NUMERO_BITS_INDEX
    }

    public static function getQuantidadeBlocosPalavra() {
        return pow(2, NUMERO_BITS_OFFSET); //Potência. base 2 e expoente NUMERO_BITS_OFFSET
    }

    private function removeUltimoHexadecimalEndereco(&$endereco) {
        $endereco = substr_replace($endereco, "X", -1);
    }

    private function _isPassoAPasso($arrayParametros) {
        if (count($arrayParametros) > 1) {
            return true;
        }

        return false;
    }

    private function _getArquivoEntrada($arrayParametros) {
        if ($this->isPassoAPasso) {
            return $arrayParametros[1];
        }

        return $arrayParametros[0];
    }

    private function _getTabelaCacheInicial() {
        $arrayTabelaCache = array();
        for ($i = 0; $i < self::getQuantidadeLinhas(); $i++) {
            $idx = Conversao::getDecimalToHexadecimal($i);
            $arrayTabelaCache[$idx] = [
                'v' => 0,
                'tag' => ''
            ];
            for ($i2 = 1; $i2 <= self::getQuantidadeBlocosPalavra(); $i2 ++) {
                $arrayTabelaCache[$idx]['data' . $i2] = '';
            }
        }

        return $arrayTabelaCache;
    }

    public function mostrarTabela($enderecoQueEEstaSendoLendo = '') {
        if ($this->isPassoAPasso && !empty($enderecoQueEEstaSendoLendo)) {
            echo "Leitura do endereço 0x" . $enderecoQueEEstaSendoLendo . "\n\n";
        }

        $mask = "|%3s|%1s|%7s|";
        printf($mask, 'Idx', 'V', 'Tag');
        $maskBlocos = "%14s|";
        for ($i = 1; $i <= self::getQuantidadeBlocosPalavra(); $i ++) {
            printf($maskBlocos, 'data');
        }

        echo "\n";
        foreach ($this->getTabelaCache() as $linha => $valores) {
            printf($mask, $linha, $valores['v'], $valores['tag']);
            for ($i = 1; $i <= self::getQuantidadeBlocosPalavra(); $i ++) {
                printf($maskBlocos, $valores['data' . $i]);
            }
            
            echo "\n";
        }

        echo "\n HITS: " . $this->getNumeroHits() . "     MISSES: " . $this->getNumeroMisses() . "\n";
        if ($this->isPassoAPasso) {
            echo "\n Pressione enter para continuar... \n";
            fgetc(STDIN);
        }
    }

}
