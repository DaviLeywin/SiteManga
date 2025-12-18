<?php
class CapitulosDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('capitulos')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('capitulos')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('capitulos')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('capitulos')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('capitulos')->Dados($dados)->Where($where)->Execute();   
    }
}