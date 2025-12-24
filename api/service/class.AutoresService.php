<?php 
require_once __DIR__ . "\..\model\class.AutoresModel.php";
require_once __DIR__ . "\..\dao\class.AutoresDAO.php";
require_once __DIR__ . '\..\validator\class.BaseValidator.php';

class AutoresService {
    static function GetTodos(){
        return AutoresDAO::GetTodos();
    }
    
    static function Get($url){
        return AutoresDAO::Get($url);
    }

    static function Post($request){
        $descricao = AutoresDAO::Describe();

        $resposta = BaseValidator::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = BaseValidator::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = BaseValidator::ValidarTipoArray($request, $descricao,"Autores");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = BaseValidator::ValidarTamanhoArray($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        $Autores = new Autores();

        return AutoresDAO::Post($Autores);
    }

    static function Put($request, $url){
        $descricao = AutoresDAO::Describe();
        $resposta = BaseValidator::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = BaseValidator::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = BaseValidator::ValidarTipoArray($request, $descricao,"Autores",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = BaseValidator::ValidarTamanhoArray($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return AutoresDAO::Put($request, $url);
    }
    
    static function Delete($url){
        return AutoresDAO::Delete($url);
    }
}