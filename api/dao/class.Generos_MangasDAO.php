<?php
class Generos_MangasDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('generos_mangas')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('generos_mangas')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('generos_mangas')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('generos_mangas')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('generos_mangas')->Dados($dados)->Where($where)->Execute();   
    }
}