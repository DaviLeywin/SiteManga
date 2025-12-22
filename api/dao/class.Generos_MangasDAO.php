<?php
class Generos_MangasDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Generos_Mangas")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Generos_Mangas")->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Table("Generos_Mangas")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Generos_Mangas")->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Table("Generos_Mangas")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Generos_Mangas")->Execute();   
    }
}

