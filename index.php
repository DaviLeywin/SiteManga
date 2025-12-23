<?php
require_once "Framework.php";
$erro = [];
$erro[] = Documento::Local("api/validator")->Arquivos(["class.baseValidator.php","class.AutoresValidator.php","class.UsuariosValidator.php","class.MangasValidator.php","class.GenerosValidator.php","class.Generos_MangasValidator.php","class.AvaliacoesValidator.php","class.FavoritosValidator.php","class.Comentarios_MangaValidator.php","class.CapitulosValidator.php"])->Exec();
// $erro[] = Documento::Local("api/config")->Arquivos(["config.php"])->Exec();
// $erro[] = Documento::Local()->Pastas(["api","web"])->Exec();
// $erro[] = Documento::Local("api")->Pastas(["banco","config","controller","dao","service"])->Tipo("service")->Exec();
// $erro[] = Documento::Local("api")->Arquivos(["class.Rotas.php"])->Tipo('rota')->Exec();
// $erro[] = Documento::Local("api")->Arquivos(["class.Request.php"])->Tipo('request')->Exec();
// $erro[] = Documento::Local("api")->Arquivos(["class.Response.php"])->Tipo('response')->Exec();
// $erro[] = Documento::Local("api")->Arquivos([".htaccess"])->Tipo('htaccess')->Exec();
// $erro[] = Documento::Local("api")->Arquivos(["index.php"])->Tipo('index')->Exec();
// $erro[] = Documento::Local("api/banco")->Arquivos(["class.Banco.php"])->Tipo('banco')->Exec();

// $erro[] = Documento::Local("api/dao")->Tipo('dao')->Arquivos(["Rankings","Autores","Usuarios","Mangas","Generos","Generos_Mangas","Avaliacoes","Favoritos","Comentarios_Manga","Capitulos"])->Exec();
// $erro[] = Documento::Local("api/controller")->Tipo('controller')->Arquivos(["Rankings","Autores","Usuarios","Mangas","Generos","Generos_Mangas","Avaliacoes","Favoritos","Comentarios_Manga","Capitulos"])->Exec();

echo json_encode($erro);
