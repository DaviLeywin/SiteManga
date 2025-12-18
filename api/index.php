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
//usuarios
$rotas->get('/BuscarUsuario/{id}','UsuariosController@Get');
$rotas->get('/BuscarUsuarios','UsuariosController@GetTodos');
$rotas->post('/InserirUsuario','UsuariosController@Post');
$rotas->delete('/DeletarUsuario/{id}','UsuariosController@Delete');
$rotas->put('/AtualizarUsuario/{id}','UsuariosController@Put');    
//mangas
$rotas->get("/BuscarManga/{id}","MangasController@Get");
$rotas->get("/BuscarMangas","MangasController@GetTodos");
$rotas->post("/InserirManga","MangasController@Post");
$rotas->delete("/DeletarManga/{id}","MangasController@Delete");
$rotas->put("/AtualizarManga/{id}","MangasController@Put");
//generos
$rotas->get('/BuscarGenero/{id}','GenerosController@Get');
$rotas->get('/BuscarGeneros','GenerosController@GetTodos');
$rotas->post('/InserirGenero','GenerosController@Post');
$rotas->delete('/DeletarGenero/{id}','GenerosController@Delete');
$rotas->put('/AtualizarGenero/{id}','GenerosController@Put'); 
//generos_mangas
$rotas->get('/BuscarGenero_Manga/{id}','Generos_MangasController@Get');
$rotas->get('/BuscarGeneros_Mangas','Generos_MangasController@GetTodos');
$rotas->post('/InserirGenero_Manga','Generos_MangasController@Post');
$rotas->delete('/DeletarGenero_Manga/{id}','Generos_MangasController@Delete');
$rotas->put('/AtualizarGenero_Manga/{id}','Generos_MangasController@Put');  
//rankings
$rotas->get('/BuscarRanking/{id}','RankingsController@Get');
$rotas->get('/BuscarRankings','RankingsController@GetTodos');
$rotas->post('/InserirRanking','RankingsController@Post');
$rotas->delete('/DeletarRanking/{id}','RankingsController@Delete');
$rotas->put('/AtualizarRanking/{id}','RankingsController@Put');    
//avaliacoes
$rotas->get('/BuscarAvaliacao/{id}','AvaliacoesController@Get');
$rotas->get('/BuscarAvaliacoes','AvaliacoesController@GetTodos');
$rotas->post('/InserirAvaliacao','AvaliacoesController@Post');
$rotas->delete('/DeletarAvaliacao/{id}','AvaliacoesController@Delete');
$rotas->put('/AtualizarAvaliacao/{id}','AvaliacoesController@Put');    
//favoritos
$rotas->get('/BuscarFavorito/{id}','FavoritosController@Get');
$rotas->get('/BuscarFavoritos','FavoritosController@GetTodos');
$rotas->post('/InserirFavorito','FavoritosController@Post');
$rotas->delete('/DeletarFavorito/{id}','FavoritosController@Delete');
$rotas->put('/AtualizarFavorito/{id}','FavoritosController@Put');    
//comentarios_manga
$rotas->get('/BuscarComentario_Manga/{id}','Comentarios_MangaController@Get');
$rotas->get('/BuscarComentarios_Manga','Comentarios_MangaController@GetTodos');
$rotas->post('/InserirComentario_Manga','Comentarios_MangaController@Post');
$rotas->delete('/DeletarComentario_Manga/{id}','Comentarios_MangaController@Delete');
$rotas->put('/AtualizarComentario_Manga/{id}','Comentarios_MangaController@Put');    
//capitulos
$rotas->get('/BuscarCapitulo/{id}','CapitulosController@Get');
$rotas->get('/BuscarCapitulos','CapitulosController@GetTodos');
$rotas->post('/InserirCapitulo','CapitulosController@Post');
$rotas->delete('/DeletarCapitulo/{id}','CapitulosController@Delete');
$rotas->put('/AtualizarCapitulo/{id}','CapitulosController@Put');    

echo json_encode($rotas->executar());