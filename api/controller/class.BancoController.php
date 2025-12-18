<?php
require_once __DIR__ . "\..\banco\class.Banco.php";
require_once __DIR__ . "\..\class.Response.php";

class BancoController {
    static function CriarBanco($request, $url){
        return Banco::CriarBanco();
    }

    static function DeletarBanco($request, $url){
        return Banco::DeletarBanco();
    }

    static function RecriarBanco($request, $url){
        return Banco::RecriarBanco();
    }
}