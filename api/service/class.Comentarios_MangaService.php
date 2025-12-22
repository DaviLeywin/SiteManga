<?php 
require_once __DIR__ . "\..\dao\class.Comentarios_MangaDAO.php";

class Comentarios_MangaService {
    static function GetTodos(){
        return Comentarios_MangaDAO::GetTodos();
    }
    
    static function Get($url){
        return Comentarios_MangaDAO::Get($url);
    }

    static function Post($request){
        $descricao = Comentarios_MangaDAO::Describe();

        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Comentarios_Manga");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return Comentarios_MangaDAO::Post($request);
    }

    static function Put($request, $url){
        $descricao = Comentarios_MangaDAO::Describe();
        $resposta = Services::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = Services::PriValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = Services::SegValidarTipo($request, $descricao,"Comentarios_Manga",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = Services::TerValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return Comentarios_MangaDAO::Put($request, $url);
    }
    
    static function Delete($request, $url){
        return Comentarios_MangaDAO::Delete($url);
    }
}