<?php 
require_once __DIR__ . "\..\dao\class.CapitulosDAO.php";

class CapitulosService {
    static function GetTodos(){
        return CapitulosDAO::GetTodos();
    }
    
    static function Get($url){
        return CapitulosDAO::Get($url);
    }

    static function Post($request){
        $descricao = CapitulosDAO::Describe();

        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Capitulos");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return CapitulosDAO::Post($request);
    }

    static function Put($request, $url){
        $descricao = CapitulosDAO::Describe();
        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Capitulos",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return CapitulosDAO::Put($request, $url);
    }
    
    static function Delete($url){
        return CapitulosDAO::Delete($url);
    }
}