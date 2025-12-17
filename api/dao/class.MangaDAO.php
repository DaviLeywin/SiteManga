<?php
declare(strict_types=1);
require_once __DIR__ . "\..\..\Framework.php";

class MangaDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela("mangas")->Execute();            
    }   
    static function Get($campos = null, $wheres = null){
        return DAO::Get()->Tabela('capitulos')->Execute();
    }
    static function Post($dados){
        print_r(DAO::Get()->Tabela("mangas")->Where(id:1)->Execute());
    }
    static function Delete($where){
        print_r(DAO::Delete()->Tabela("mangas")->Where(["id" => 1])->Execute());     
    }
    static function Put($campos,$where){
        print_r(DAO::Put()->Tabela("mangas")->Dados(titulo:'Naruta')->Where(id:2)->Execute());   
    }
}