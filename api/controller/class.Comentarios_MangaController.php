<?php 
require_once __DIR__ . "\..\service\class.Comentarios_MangaService.php";

class Comentarios_MangaController {
    static function GetTodos($request, $url){
        return Comentarios_MangaService::GetTodos();
    }
    
    static function Get($request, $url){
        $url["id"] = (int) $url["id"];
        return Comentarios_MangaService::Get($url);
    }    

    static function Post($request, $url){
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return Comentarios_MangaService::Post($request->BODY);
    }

    static function Put($request, $url){
        $url["id"] = (int) $url["id"];
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return Comentarios_MangaService::Put($request->BODY, $url);
    }    

    static function Delete($request, $url){
        $url["id"] = (int) $url["id"];
        return Comentarios_MangaService::Delete($url);
    }
}