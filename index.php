<?php
require_once "Framework.php";

// Documentos::C_pastas(pastas:["api","web"]);
// Documentos::C_pastas(caminho:"api", pastas:["controller","dao","banco","config","db"]);
// Documentos::C_pastas(caminho:"web", pastas:["paginas"]);
// Documentos::C_pastas(caminho:"web/paginas",pastas:["inicio","login","mangas"]);
// Documentos::C_arquivos(caminho:"web/paginas/inicio",arquivos:["script.js","index.html","estilo.css"]);
// Documentos::C_arquivos(caminho:"web/paginas/login",arquivos:["script.js","index.html","estilo.css"]);
// Documentos::C_arquivos(caminho:"web/paginas/mangas",arquivos:["script.js","index.html","estilo.css"]);
// Documentos::C_arquivos(caminho:"web",arquivos:["script.js","index.html","estilo.css"]);
// Documentos::C_arquivos(caminho:"api/banco",arquivos:["class.Banco.php"]);
// Documentos::C_arquivos(caminho:"api/db",arquivos:["seed.sql","db.sql"]);
// Documentos::C_arquivos(caminho:"api/controller",arquivos:["Usuario","Manga","Novel","Ranking","Origem"]);
// Documentos::C_arquivos(caminho:"api/config",arquivos:["config.php"]);
// Documentos::C_arquivos(caminho:"api/dao",arquivos:["Usuario","Manga","Novel","Ranking","Origem"]);
// Documentos::C_arquivos(caminho:"api",arquivos:["index.php","class.Rotas.php","class.Request.php",".htaccess"]);
// Documentos::C_arquivos(caminho:"web",arquivos:[".htaccess"]);
// Documentos::C_arquivos(caminho:"api/controller",arquivos:["Autor","Genero","GeneroManga","Avaliacao","Favorito","ComentarioManga","Capitulo"]);
// Documentos::C_arquivos(caminho:"api/controller",arquivos:["Autores","Usuarios","Generos","Generos_Mangas","Rankings","Avaliacoes","Favoritos","Comentarios_Manga","Capitulos"]);
// Documentos::C_arquivos(caminho:"api/dao",arquivos:["Autores","Usuarios","Generos","Generos_Mangas","Rankings","Avaliacoes","Favoritos","Comentarios_Manga","Capitulos"]);
// Documentos::C_arquivos(caminho:"api/controller",arquivos:["Autora"]);

Documentos::C_pastas(caminho:"api/services",pastas:["autores","capitulos","generos_mangas","generos","mangas","usuarios"]);
Documentos::C_arquivos(caminho:"api/services/autores",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
Documentos::C_arquivos(caminho:"api/services/capitulos",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
Documentos::C_arquivos(caminho:"api/services/generos_mangas",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
Documentos::C_arquivos(caminho:"api/services/generos",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
Documentos::C_arquivos(caminho:"api/services/mangas",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
Documentos::C_arquivos(caminho:"api/services/usuarios",arquivos:["Post.php","Delete.php","Put.php","Get.php"]);
?>