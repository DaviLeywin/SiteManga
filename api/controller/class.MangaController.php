<?php 
// require_once __DIR__ . "\..\..\Framework.php";
require_once __DIR__ . "\..\dao\class.MangaDAO.php";

class MangaController {
    static function GetTodos($request, $url){
        return MangaDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return MangaDAO::Get($url);
    }
    
    static function Post($request, $url){
        return MangaDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return MangaDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return MangaDAO::Delete($url);
    }
}