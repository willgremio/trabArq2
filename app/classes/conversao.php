<?php

/**
 * Description of conversao
 *
 * @author willian
 */
class Conversao {
    
    public static function getDecimalToBinario($numeroDecimal) {
        return decbin($numeroDecimal); // funcao do php que retorna decimal para binario      
    }
    
    public static function getHexadecimalToBinario($numeroHexadecimal) {
        $numeroBinario =  base_convert($numeroHexadecimal, 16, 2); //hexa para binario
        return str_pad($numeroBinario, 32, 0, STR_PAD_LEFT); // preenche com zeros na frente
    }
}
