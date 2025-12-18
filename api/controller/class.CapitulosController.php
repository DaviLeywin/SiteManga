<?php
require_once __DIR__ . '\..\dao\class.CapitulosDAO.php';

class CapitulosController {
    static function GetTodos($request, $url){
        return CapitulosDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return CapitulosDAO::Get($url);
    }
    
    static function Post($request, $url){
        return CapitulosDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return CapitulosDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return CapitulosDAO::Delete($url);
    }
}