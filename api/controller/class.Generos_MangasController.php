<?php 
require_once __DIR__ . "\..\service\class.Generos_MangasService.php";

class Generos_MangasController {
    static function GetTodos($request, $url){
        return Generos_MangasService::GetTodos();
    }
    
    static function Get($request, $url){
        $url["id"] = (int) $url["id"];
        return Generos_MangasService::Get($url);
    }    

    static function Post($request, $url){
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return Generos_MangasService::Post($request->BODY);
    }

    static function Put($request, $url){
        $url["id"] = (int) $url["id"];
        if(empty($request->BODY)){
            return Response::Fail("Dados vazios!");
        }
        return Generos_MangasService::Put($request->BODY, $url);
    }    

    static function Delete($request, $url){
        $url["id"] = (int) $url["id"];
        return Generos_MangasService::Delete($url);
    }
}