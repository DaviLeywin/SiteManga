<?php
class MangasDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela("mangas")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('mangas')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela("mangas")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela("mangas")->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela("mangas")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe();   
    }
}
