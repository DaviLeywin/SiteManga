<?php
require_once __DIR__ . '\..\dao\class.Generos_MangasDAO.php';

class Generos_MangasController {
    static function GetTodos($request, $url){
        return Generos_MangasDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return Generos_MangasDAO::Get($url);
    }
    
    static function Post($request, $url){
        return Generos_MangasDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return Generos_MangasDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return Generos_MangasDAO::Delete($url);
    }
}
  
