<!-- <?php
require_once "Framework.php";

$erro = [];

$erro[] = Documento::Local("api/dao")->Tipo('dao')->Arquivos(["Mangas"])->Exec();
$erro[] = Documento::Local("api/model")->Tipo('model')->Arquivos(["Mangas"])->Exec();
$erro[] = Documento::Local("api/service")->Tipo('service')->Arquivos(["Mangas"])->Exec();
$erro[] = Documento::Local("api/controller")->Tipo('controller')->Arquivos(["Mangas"])->Exec();
$erro[] = Documento::Local("api/validator")->Arquivos(["class.MangasValidator.php"])->Exec();

echo json_encode($erro); -->
