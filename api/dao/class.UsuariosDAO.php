<?php
class UsuariosDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('usuarios')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('usuarios')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('usuarios')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('usuarios')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('usuarios')->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Tabela("usuarios")->Execute();   
    }
}