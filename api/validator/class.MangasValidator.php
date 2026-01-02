<?php

class MangasValidator {
    const TIPO_MANGA = 'MANGA';
    const TIPO_NOVEL = 'NOVEL';

    static function ValidarTipo($Tipo){
        $Validos = [self::TIPO_MANGA, self::TIPO_NOVEL];
        if(!in_array($Tipo, $Validos)){
            return false;
        }
        return true;
    }
    
    
    
}

?>