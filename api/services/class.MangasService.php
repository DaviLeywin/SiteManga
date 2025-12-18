<?php 
require_once __DIR__ . "\..\dao\class.MangasDAO.php";

class MangasService {
    static function GetTodos(){
        return MangasDAO::GetTodos();
    }
    
    static function Get(){
        return MangasDAO::Get();
    }
    
    static function Post(){
        $descricao = MangasDAO::Describe();
        return $descricao;
        // return MangasDAO::Post($request->BODY);
    }
    
    static function Put(){
        // return MangasDAO::Put($request->BODY, $url);
    }
    
    static function Delete(){
        // return MangasDAO::Delete($url);
    }
}