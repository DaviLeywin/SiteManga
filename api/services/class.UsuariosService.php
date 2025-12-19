<?php
require_once __DIR__ . '\..\dao\class.UsuariosDAO.php';

class UsuariosService {
    static function GetTodos(){
        return UsuariosDAO::GetTodos();
    }
    
    static function Get($url){
        return UsuariosDAO::Get($url);
    }

    static function Post($request){
        $descricao = UsuariosDAO::Describe();
        $resposta = Services::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        $resposta = Services::ValidarTipo($request, $descricao,"usuarios");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        $resposta = Services::ValidarTamanho($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        return UsuariosDAO::Post($request);
    }
    
    static function Put($request, $url){
        $descricao = UsuariosDAO::Describe();
        $resposta = Services::ValidarTipo($request, $descricao,"usuarios",$url);

        if($resposta){
            return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        } 
        $resposta = Services::ValidarTamanho($request, $descricao);

        if($resposta){
            return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        }

        return UsuariosDAO::Put($request, $url);
    }
    
    static function Delete($url){
        return UsuariosDAO::Delete($url);
    }
}