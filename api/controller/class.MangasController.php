<?php 
require_once __DIR__ . "\..\service\class.MangasService.php";

class MangasController {
    static function GetTodos($request, $url){
        return MangasService::GetTodos();
    }

    static function GetMangasGeneros($request, $url){
        return MangasService::GetMangasGeneros();
    }

    static function GetMangaGeneroAutorCapitulos($request, $url){
        $url["id"] = (int) $url["id"];
        return MangasService::GetMangaGeneroAutorCapitulos($url);
    }
    
    static function Get($request, $url){
        $url["id"] = (int) $url["id"];
        return MangasService::Get($url);
    }    

    static function Post($request, $url){
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return MangasService::Post($request->BODY);
    }

    static function Put($request, $url){
        $url["id"] = (int) $url["id"];
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return MangasService::Put($request->BODY, $url);
    }    

    static function Delete($request, $url){
        $url["id"] = (int) $url["id"];
        return MangasService::Delete($url);
    }
}