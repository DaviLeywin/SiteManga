<?php
require_once __DIR__ . '\..\dao\class.AvaliacoesDAO.php';

class AvaliacoesController {
    static function GetTodos($request, $url){
        return AvaliacoesDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return AvaliacoesDAO::Get($url);
    }
    
    static function Post($request, $url){
        return AvaliacoesDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return AvaliacoesDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return AvaliacoesDAO::Delete($url);
    }
}
  
