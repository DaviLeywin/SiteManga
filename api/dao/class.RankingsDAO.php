<?php
class RankingsDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('rankings')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('rankings')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('rankings')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('rankings')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('rankings')->Dados($dados)->Where($where)->Execute();   
    }
}