<?php
declare(strict_types=1);
require_once __DIR__ . "\..\..\Framework.php";

class MangaDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela("mangas")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('mangas')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela("generos")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela("mangas")->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela("mangas")->Dados($dados)->Where($where)->Execute();   
    }
}
