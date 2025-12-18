<?php
class GenerosDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('generos')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('generos')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('generos')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('generos')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('generos')->Dados($dados)->Where($where)->Execute();   
    }
}