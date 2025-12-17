<?php
require_once "class.Rotas.php";

$rotas = new Rotas();

$rotas->get("/BuscarManga/{id}","MangaController@Get");
$rotas->get("/BuscarMangas","MangaController@GetTodos");
$rotas->post("/InserirManga","MangaController@Post");
$rotas->delete("/DeletarManga/{id}","MangaController@Delete");
$rotas->put("/AtualizarManga/{id}","MangaController@Put");

$rotas->get("/BuscarRanking/{id}","RankingController@Get");
$rotas->get("/BuscarRankings","RankingController@GetTodos");
$rotas->post("/InserirRanking","RankingController@Post");
$rotas->delete("/DeletarRanking/{id}","RankingController@Delete");
$rotas->put("/AtualizarRanking/{id}","RankingController@Put");

$rotas->get("/BuscarNovel/{id}","NovelController@Get");
$rotas->get("/BuscarNovels","NovelController@GetTodos");
$rotas->post("/InserirNovel","NovelController@Post");
$rotas->delete("/DeletarNovel/{id}","NovelController@Delete");
$rotas->put("/AtualizarNovel/{id}","NovelController@Put");

$rotas->get("/BuscarUsuario/{id}","UsuarioController@Get");
$rotas->get("/BuscarUsuarios","UsuarioController@GetTodos");
$rotas->post("/InserirUsuario","UsuarioController@Post");
$rotas->post("/Login","UsuarioController@Login");
$rotas->put("/AtualizarUsuario/{id}","UsuarioController@Put");

echo json_encode($rotas->executar());