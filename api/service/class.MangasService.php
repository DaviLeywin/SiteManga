<?php 
require_once __DIR__ . "\..\model\class.MangasModel.php";
require_once __DIR__ . "\..\dao\class.MangasDAO.php";
require_once __DIR__ . '\..\validator\class.BaseValidator.php';

class MangasService {
    static function GetTodos(){
        return MangasDAO::GetTodos();
    }
    
    static function Get($url){
        return MangasDAO::Get($url);
    }

    static function Post($request){
        $descricao = MangasDAO::Describe();

        $resposta = BaseValidator::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = BaseValidator::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = BaseValidator::ValidarTipoArray($request, $descricao,"Mangas");
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = BaseValidator::ValidarTamanhoArray($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        $manga = new Mangas();
        
        $manga->AlterarTitulo($request['TITULO']);
        $manga->AlterarAutoresId($request['AUTORES_ID']);
        $manga->AlterarDataPublicacao($request['DATA_PUBLICACAO']);
        $manga->AlterarSinopse($request['SINOPSE']);
        $manga->AlterarTipo($request['TIPO']);
        $manga->AlterarStatus($request['STATUS']);

        return MangasDAO::Post($manga);
    }

    static function Put($request, $url){
        $descricao = MangasDAO::Describe();
        $resposta = BaseValidator::CampoSobrando($request, $descricao);
        if($resposta) return Response::Fail("Campos extras!",$resposta);
        
        $resposta = BaseValidator::ValidarNotNull($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar campos nao nulos!",$resposta);
        
        $resposta = BaseValidator::ValidarTipoArray($request, $descricao,"Mangas",$url);
        if($resposta) return Response::Fail("Erro ao validar tipo dos campos",$resposta);
        
        $resposta = BaseValidator::ValidarTamanhoArray($request, $descricao);
        if($resposta) return Response::Fail("Erro ao validar tamanho dos campos",$resposta);
        
        return MangasDAO::Put($request, $url);
    }
    
    static function Delete($url){
        return MangasDAO::Delete($url);
    }
}