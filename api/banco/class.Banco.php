<?php 
require_once "config\config.php";
// require_once __DIR__ . "\..\..\Framework.php";

class Banco {
    static $connDados;
    static $sql;
    static $insert;

    static function init(){
        self::$connDados = $GLOBALS['conn'];
        self::$sql = $GLOBALS['sql'];
        self::$insert = $GLOBALS['insert'];
    }
    
    static function IniciarBanco(){
        self::init();
        $usuario = self::$connDados["usuario"];
        $senha = self::$connDados["senha"];
        $banco = self::$connDados["banco"];
        $host = self::$connDados["host"];
        $sqls = self::$sql;
        $inserts = self::$insert;
        
        try{
            $conn = new PDO("mysql:host=$host;charset=utf8",$usuario,$senha);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $conn->exec("DROP DATABASE IF EXISTS $banco;");
            $conn->exec("CREATE DATABASE IF NOT EXISTS $banco;");    
            $conn->exec("USE $banco;");
            
            foreach($sqls as $sql){
                $conn->exec($sql);
            }
            foreach($inserts as $insert){
                $conn->exec($insert);
            }
            return "Banco ". $banco ." recriado com sucesso!";
        }catch(Exception $e){
            throw new InvalidArgumentException("Erro ao recriar com o banco");
        }
    }
    
    static function Conexao(){
        try{
            self::init();
            $usuario = self::$connDados["usuario"];
            $senha = self::$connDados["senha"];
            $banco = self::$connDados["banco"];
            $host = self::$connDados["host"];

            $conn = new PDO("mysql:host=".$host.";dbname=".$banco,$usuario,$senha);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }catch(Exception $e){
            throw new InvalidArgumentException("Erro ao conectar com o banco");
        }
    }
}
?>