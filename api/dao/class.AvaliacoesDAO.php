<?php
class AvaliacoesDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('avaliacoes')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('avaliacoes')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('avaliacoes')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('avaliacoes')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('avaliacoes')->Dados($dados)->Where($where)->Execute();   
    }
}