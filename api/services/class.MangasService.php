<?php 
require_once __DIR__ . "\..\dao\class.MangasDAO.php";

class MangasService {
    static function GetTodos(){
        return MangasDAO::GetTodos();
    }
    
    static function Get($url){
        return MangasDAO::Get($url);
    }

    static function Post($request){
        $descricao = MangasDAO::Describe();
        $resposta = Services::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        $resposta = Services::ValidarTipo($request, $descricao,"mangas");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        $resposta = Services::ValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        return MangasDAO::Post($dados);
    }
    
    static function Put($request, $url){
        $descricao = MangasDAO::Describe();
        $resposta = Services::ValidarTipo($request, $descricao,"mangas",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        $resposta = Services::ValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        return MangasDAO::Put($request, $url);
    }
    
    static function Delete($request, $url){
        return MangasDAO::Delete($url);
    }
}