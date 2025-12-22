<?php
class GenerosDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Generos")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Generos")->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Table("Generos")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Generos")->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Table("Generos")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Generos")->Execute();   
    }
}

