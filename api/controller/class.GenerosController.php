<?php
require_once __DIR__ . '\..\dao\class.GenerosDAO.php';

class GenerosController {
    static function GetTodos($request, $url){
        return GenerosDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return GenerosDAO::Get($url);
    }
    
    static function Post($request, $url){
        return GenerosDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return GenerosDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return GenerosDAO::Delete($url);
    }
}
   
