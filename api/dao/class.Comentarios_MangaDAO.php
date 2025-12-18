<?php
class Comentarios_MangaDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('comentarios_manga')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('comentarios_manga')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('comentarios_manga')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('comentarios_manga')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('comentarios_manga')->Dados($dados)->Where($where)->Execute();   
    }
}