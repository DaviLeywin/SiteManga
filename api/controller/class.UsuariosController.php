<?php
require_once __DIR__ . '\..\dao\class.UsuariosDAO.php';

class UsuariosController {
    static function GetTodos($request, $url){
        return UsuariosDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return UsuariosDAO::Get($url);
    }
    
    static function Post($request, $url){
        return UsuariosDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return UsuariosDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return UsuariosDAO::Delete($url);
    }
}

