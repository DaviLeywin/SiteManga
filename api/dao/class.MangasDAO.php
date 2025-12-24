<?php

class MangasDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Mangas")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Mangas")->Where($where)->Execute();
    }
    static function Post(Mangas $dados){
        return DAO::Post()->Table("Mangas")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Mangas")->Where($where)->Execute();     
    }
    static function Put(Mangas $dados,$where){
        return DAO::Put()->Table("Mangas")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Mangas")->Execute();   
    }
}


