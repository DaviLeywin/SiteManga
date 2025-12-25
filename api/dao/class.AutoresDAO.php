<?php
declare(strict_types=1);
require_once __DIR__ . "\..\..\Framework.php";
require_once __DIR__ . "\..\class.Response.php";

class AutoresDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Autores")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Autores")->Where($where)->Execute();
    }
    static function Post(Autores $dados){
        return DAO::Post()->Table("Autores")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Autores")->Where($where)->Execute();     
    }
    static function Put(Autores $dados,$where){
        return DAO::Put()->Table("Autores")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Autores")->Execute();   
    }
}
