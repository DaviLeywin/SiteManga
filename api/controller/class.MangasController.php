<?php 
require_once __DIR__ . "\..\dao\class.MangasDAO.php";

class MangasController {
    static function GetTodos($request, $url){
        return MangasDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return MangasDAO::Get($url);
    }
    
    static function Post($request, $url){
        return MangasDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return MangasDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return MangasDAO::Delete($url);
    }
}