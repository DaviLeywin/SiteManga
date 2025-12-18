<?php
declare(strict_types=1);
require_once __DIR__ . "\..\Framework.php";
require_once "class.Rotas.php";

$rotas = new Rotas();
//autores
$rotas->get('/BuscarAutor/{id}','AutoresController@Get');
$rotas->get('/BuscarAutores','AutoresController@GetTodos');
$rotas->post('/InserirAutor','AutoresController@Post');
$rotas->delete('/DeletarAutor/{id}','AutoresController@Delete');
$rotas->put('/AtualizarAutor/{id}','AutoresController@Put'); 
//mangas
$rotas->get("/BuscarManga/{id}","MangasController@Get");
$rotas->get("/BuscarMangas","MangasController@GetTodos");
$rotas->post("/InserirManga","MangasController@Post");
$rotas->delete("/DeletarManga/{id}","MangasController@Delete");
$rotas->put("/AtualizarManga/{id}","MangasController@Put");

echo json_encode($rotas->executar());