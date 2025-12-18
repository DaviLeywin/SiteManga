<?php 
declare(strict_types=1);
require_once __DIR__ . "\..\services\class.MangasService.php";

class MangasController {
    static function GetTodos($request, $url){
        return MangasDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return MangasDAO::Get($url);
    }
    
    static function Post($request, $url){
        $mundo = MangasServices::Post();
        foreach($request->BODY as $index => $value ){
            if(empty($value)) return Response::Fail("Campos obrigatorios faltando!");
        }
    }
    
    static function Put($request, $url){
        foreach($request->BODY as $index => $value ){
            if(empty($value)) return Response::Fail("Campos obrigatorios faltando!");
        }
        return MangasDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return MangasDAO::Delete($url);
    }
}