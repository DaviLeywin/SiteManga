<?php 
require_once __DIR__ . "\..\dao\class.Generos_MangasDAO.php";

class Generos_MangasService {
    static function GetTodos(){
        return Generos_MangasDAO::GetTodos();
    }
    
    static function Get($url){
        return Generos_MangasDAO::Get($url);
    }

    static function Post($request){
        $descricao = Generos_MangasDAO::Describe();

        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Generos_Mangas");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return Generos_MangasDAO::Post($request);
    }

    static function Put($request, $url){
        $descricao = Generos_MangasDAO::Describe();
        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Generos_Mangas",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return Generos_MangasDAO::Put($request, $url);
    }
    
    static function Delete($request, $url){
        return Generos_MangasDAO::Delete($url);
    }
}