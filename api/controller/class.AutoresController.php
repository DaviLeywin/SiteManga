<?php
require_once __DIR__ . '\..\dao\class.AutoresDAO.php';

class AutoresController {
    static function GetTodos($request, $url){
        return AutoresDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return AutoresDAO::Get($url);
    }
    
    static function Post($request, $url){
        return AutoresDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return AutoresDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return AutoresDAO::Delete($url);
    }
} 
