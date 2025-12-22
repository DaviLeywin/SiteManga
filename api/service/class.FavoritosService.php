<?php 
require_once __DIR__ . "\..\dao\class.FavoritosDAO.php";

class FavoritosService {
    static function GetTodos(){
        return FavoritosDAO::GetTodos();
    }
    
    static function Get($url){
        return FavoritosDAO::Get($url);
    }

    static function Post($request){
        $descricao = FavoritosDAO::Describe();

        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Favoritos");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return FavoritosDAO::Post($request);
    }

    static function Put($request, $url){
        $descricao = FavoritosDAO::Describe();
        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Favoritos",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return FavoritosDAO::Put($request, $url);
    }
    
    static function Delete($request, $url){
        return FavoritosDAO::Delete($url);
    }
}