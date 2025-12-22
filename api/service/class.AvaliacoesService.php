<?php 
require_once __DIR__ . "\..\dao\class.AvaliacoesDAO.php";

class AvaliacoesService {
    static function GetTodos(){
        return AvaliacoesDAO::GetTodos();
    }
    
    static function Get($url){
        return AvaliacoesDAO::Get($url);
    }

    static function Post($request){
        $descricao = AvaliacoesDAO::Describe();

        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Avaliacoes");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return AvaliacoesDAO::Post($request);
    }

    static function Put($request, $url){
        $descricao = AvaliacoesDAO::Describe();
        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Avaliacoes",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return AvaliacoesDAO::Put($request, $url);
    }
    
    static function Delete($url){
        return AvaliacoesDAO::Delete($url);
    }
}