<?php
declare(strict_types=1);
require_once __DIR__ . "\..\Framework.php";
require_once "class.Rotas.php";
require_once "class.Response.php";

$rotas = new Rotas();

$rotas->get("/GetAutores/{id}","AutoresController@Get");
$rotas->get("/GetAllAutores","AutoresController@GetTodos");
$rotas->post("/InsertAutores","AutoresController@Post");
$rotas->delete("/DeleteAutores/{id}","AutoresController@Delete");
$rotas->put("/UpdateAutores/{id}","AutoresController@Put"); 

$rotas->get("/GetAvaliacoes/{id}","AvaliacoesController@Get");
$rotas->get("/GetAllAvaliacoes","AvaliacoesController@GetTodos");
$rotas->post("/InsertAvaliacoes","AvaliacoesController@Post");
$rotas->delete("/DeleteAvaliacoes/{id}","AvaliacoesController@Delete");
$rotas->put("/UpdateAvaliacoes/{id}","AvaliacoesController@Put"); 

$rotas->get("/GetCapitulos/{id}","CapitulosController@Get");
$rotas->get("/GetAllCapitulos","CapitulosController@GetTodos");
$rotas->post("/InsertCapitulos","CapitulosController@Post");
$rotas->delete("/DeleteCapitulos/{id}","CapitulosController@Delete");
$rotas->put("/UpdateCapitulos/{id}","CapitulosController@Put"); 

$rotas->get("/GetComentarios_Manga/{id}","Comentarios_MangaController@Get");
$rotas->get("/GetAllComentarios_Manga","Comentarios_MangaController@GetTodos");
$rotas->post("/InsertComentarios_Manga","Comentarios_MangaController@Post");
$rotas->delete("/DeleteComentarios_Manga/{id}","Comentarios_MangaController@Delete");
$rotas->put("/UpdateComentarios_Manga/{id}","Comentarios_MangaController@Put"); 

$rotas->get("/GetFavoritos/{id}","FavoritosController@Get");
$rotas->get("/GetAllFavoritos","FavoritosController@GetTodos");
$rotas->post("/InsertFavoritos","FavoritosController@Post");
$rotas->delete("/DeleteFavoritos/{id}","FavoritosController@Delete");
$rotas->put("/UpdateFavoritos/{id}","FavoritosController@Put"); 

$rotas->get("/GetGeneros_Mangas/{id}","Generos_MangasController@Get");
$rotas->get("/GetAllGeneros_Mangas","Generos_MangasController@GetTodos");
$rotas->post("/InsertGeneros_Mangas","Generos_MangasController@Post");
$rotas->delete("/DeleteGeneros_Mangas/{id}","Generos_MangasController@Delete");
$rotas->put("/UpdateGeneros_Mangas/{id}","Generos_MangasController@Put"); 

$rotas->get("/GetGeneros/{id}","GenerosController@Get");
$rotas->get("/GetAllGeneros","GenerosController@GetTodos");
$rotas->post("/InsertGeneros","GenerosController@Post");
$rotas->delete("/DeleteGeneros/{id}","GenerosController@Delete");
$rotas->put("/UpdateGeneros/{id}","GenerosController@Put"); 

$rotas->get("/GetMangas/{id}","MangasController@Get");
$rotas->get("/GetAllMangas","MangasController@GetTodos");
$rotas->post("/InsertMangas","MangasController@Post");
$rotas->delete("/DeleteMangas/{id}","MangasController@Delete");
$rotas->put("/UpdateMangas/{id}","MangasController@Put"); 

$rotas->get("/GetUsuarios/{id}","UsuariosController@Get");
$rotas->get("/GetAllUsuarios","UsuariosController@GetTodos");
$rotas->post("/InsertUsuarios","UsuariosController@Post");
$rotas->delete("/DeleteUsuarios/{id}","UsuariosController@Delete");
$rotas->put("/UpdateUsuarios/{id}","UsuariosController@Put"); 
echo json_encode($rotas->executar());