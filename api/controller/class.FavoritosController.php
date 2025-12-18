<?php
require_once __DIR__ . '\..\dao\class.FavoritosDAO.php';

class FavoritosController {
    static function GetTodos($request, $url){
        return FavoritosDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return FavoritosDAO::Get($url);
    }
    
    static function Post($request, $url){
        return FavoritosDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return FavoritosDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return FavoritosDAO::Delete($url);
    }
}
