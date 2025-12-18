<?php
require_once __DIR__ . '\..\dao\class.Comentarios_MangaDAO.php';

class Comentarios_MangaController {
    static function GetTodos($request, $url){
        return Comentarios_MangaDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return Comentarios_MangaDAO::Get($url);
    }
    
    static function Post($request, $url){
        return Comentarios_MangaDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return Comentarios_MangaDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return Comentarios_MangaDAO::Delete($url);
    }
}

