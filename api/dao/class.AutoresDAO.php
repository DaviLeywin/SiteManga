<?php
class AutoresDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('autores')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('autores')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('autores')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('autores')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('autores')->Dados($dados)->Where($where)->Execute();   
    }
}