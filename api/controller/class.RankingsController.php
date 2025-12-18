<?php
require_once __DIR__ . '\..\dao\class.RankingsDAO.php';

class RankingsController {
    static function GetTodos($request, $url){
        return RankingsDAO::GetTodos();
    }
    
    static function Get($request, $url){
        return RankingsDAO::Get($url);
    }
    
    static function Post($request, $url){
        return RankingsDAO::Post($request->BODY);
    }
    
    static function Put($request, $url){
        return RankingsDAO::Put($request->BODY, $url);
    }
    
    static function Delete($request, $url){
        return RankingsDAO::Delete($url);
    }
}
