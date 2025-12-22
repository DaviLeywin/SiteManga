<?php
class Comentarios_MangaDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Comentarios_Manga")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Comentarios_Manga")->Where($where)->Execute();
    }
    static function Post($dados){
        return DAO::Post()->Table("Comentarios_Manga")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Comentarios_Manga")->Where($where)->Execute();     
    }
    static function Put($dados,$where){
        return DAO::Put()->Table("Comentarios_Manga")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Comentarios_Manga")->Execute();   
    }
}


$rotas->get("/GetComentarios_Manga/{id}","Comentarios_MangaController@Get");
$rotas->get("/GetAllComentarios_Manga","Comentarios_MangaController@GetTodos");
$rotas->post("/InsertComentarios_Manga","Comentarios_MangaController@Post");
$rotas->delete("/DeleteComentarios_Manga/{id}","Comentarios_MangaController@Delete");
$rotas->put("/UpdateComentarios_Manga/{id}","Comentarios_MangaController@Put"); 
