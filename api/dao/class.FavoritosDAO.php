<?php
class FavoritosDAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('favoritos')->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Tabela('favoritos')->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Tabela('favoritos')->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Tabela('favoritos')->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Tabela('favoritos')->Dados($dados)->Where($where)->Execute();   
    }
}