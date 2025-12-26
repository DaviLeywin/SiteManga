<?php 
require_once __DIR__ . "\..\model\class.MangasModel.php";
require_once __DIR__ . "\..\dao\class.MangasDAO.php";
require_once __DIR__ . '\..\validator\class.BaseValidator.php';

class MangasService {
    static function GetTodos(){
        return MangasDAO::GetTodos();
    }

    static function GetMangasGeneros(){
        return MangasDAO::GetMangasGeneros();
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
        
        $Mangas = new Mangas();

        $Mangas->AlterarTitulo($request['TITULO']);
        $Mangas->AlterarAutoresId($request['AUTORES_ID']);
        $Mangas->AlterarDataPublicacao($request['DATA_PUBLICACAO']);
        $Mangas->AlterarSinopse($request['SINOPSE']);
        $Mangas->AlterarTipo($request['TIPO']);
        $Mangas->AlterarStatus($request['STATUS']);

        return MangasDAO::Post($Mangas);
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
        
        $Mangas = new Mangas();

        $Mangas->AlterarTitulo($request['TITULO']);
        $Mangas->AlterarAutoresId($request['AUTORES_ID']);
        $Mangas->AlterarDataPublicacao($request['DATA_PUBLICACAO']);
        $Mangas->AlterarSinopse($request['SINOPSE']);
        $Mangas->AlterarTipo($request['TIPO']);
        $Mangas->AlterarStatus($request['STATUS']);

        return MangasDAO::Put($Mangas, $url);
    }
    
    static function Delete($url){
        return MangasDAO::Delete($url);
    }
}