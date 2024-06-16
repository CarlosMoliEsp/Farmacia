<?php 
namespace Lib;
class Validador
{
    static public function es_obligatorio($texto,$campo){
        if(trim($texto) == ''){
            return "El campo $campo es obligatorio";
        }
            
    }
    static public function es_float($texto){
        if(filter_var($texto,FILTER_VALIDATE_FLOAT)){
            return true;
        }else{
            return false;
        }
    }
    static public function texto_error_float($texto,$campo){
        if(!filter_var($texto,FILTER_VALIDATE_FLOAT)){
            return "El formato del campo $campo es incorrecto.";
        }
    }

    static public function error_mensaje($texto){
        if(trim($texto) == ''){
            return $texto;
        }
            
    }
    
}


