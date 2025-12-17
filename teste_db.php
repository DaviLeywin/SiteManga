<?php
require_once __DIR__ . '/api/banco/class.Banco.php';
$mensagem = [];
$pdo = Banco::Conexao($mensagem);
echo "PDO: ";
var_dump($pdo);
echo PHP_EOL . "Mensagens: ";
var_dump($mensagem);