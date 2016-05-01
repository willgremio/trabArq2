<?php

/**
 * Description of conversao
 *
 * @author willian
 */
class Conversao {
    
    public static function getDecimalToHexadecimal($numeroDecimal) {
        return dechex($numeroDecimal); // funcao do php que retorna decimal para hexadecimal
    }
    
    public static function getDecimalToBinario($numeroDecimal) {
        $numeroBinario = decbin($numeroDecimal); // funcao do php que retorna decimal para binario
        return str_pad($numeroBinario, 4, 0, STR_PAD_LEFT); // preenche com zeros na frente         
    }
    
    public static function getHexadecimalToBinario($numeroHexadecimal) {
        $numeroBinario = base_convert($numeroHexadecimal, 16, 2);
        return str_pad($numeroBinario, 4, 0, STR_PAD_LEFT);
    }
}
