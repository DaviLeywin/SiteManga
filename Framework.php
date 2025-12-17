<?php
require_once "api\banco\class.Banco.php";
// require_once "config\config.php";

enum Ordem: string {
    case ASC = 'ASC';
    case DESC = 'DESC';
}

set_exception_handler(function (Throwable $e) {

    $resposta = [
        'erro' => true,
        'tipo' => 'ERRO_INTERNO',
        'mensagem' => 'Erro inesperado',
    ];

    if ($e instanceof TypeError) {
        $resposta['tipo'] = 'ERRO_DE_TIPO';
        $resposta['mensagem'] = 'Tipo de dado inválido';
    } elseif ($e instanceof InvalidArgumentException) {
        $resposta['tipo'] = 'ERRO_DE_REGRA';
        $resposta['mensagem'] = $e->getMessage();
    }

    $resposta['detalhes'] = [
        'mensagem' => $e->getMessage(),
        'arquivo' => $e->getFile(),
        'linha' => $e->getLine(),
    ];
    echo json_encode($resposta, JSON_PRETTY_PRINT);
    exit;
});

class DAO {
    public $tipo;
    public $pdo;
    public $tabela;
    public $groupBy;
    public $orderBy;
    public $wheres = [];
    public $dados = [];
    public $dadosGet = [];


    public function init(){
        $this->pdo = Banco::Conexao();
        if (!$this->pdo) {
            throw new InvalidArgumentException("me matei n deu!");
        }
        return $this->pdo;
    }


    public static function __callStatic($metodo,$params){
        $valido = ['get','put','post','delete'];//true
        $metodo = strtolower($metodo);
        $inst = new self();
        if(in_array($metodo,$valido)){
            $inst->tipo = $metodo;    
            foreach($params as $param){
                if($param)$inst->dadosGet = $params;    
            }
            return $inst;
        }else {  
            throw new InvalidArgumentException("Nenhum metodo $metodo encontrado!");
        } 
    }
    
    public function Tabela(string $tabela){
        $pdo = $this->init();
        global $ConnB;
        $banco = $GLOBALS['conn']["banco"];
        $tabela = strtolower($tabela);
        $SqlValTabela = 'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :banco AND table_name = :tabela';
        $stmt = $this->pdo->prepare($SqlValTabela);
        $stmt->execute([':banco' =>$banco,  ':tabela' =>$tabela, ]);
        if(!(bool) $stmt->fetchColumn()){
            throw new InvalidArgumentException("Tabela $tabela nao existe no banco $banco");
        }
        $this->tabela = $tabela;
        return $this;
    }

    public function OrderBy(string $campo = null, Ordem $tipo = Ordem::ASC){
        $campo = strtolower($campo);
        if (!preg_match('/^[a-z0-9_]+$/', $campo)) {
            throw new InvalidArgumentException("Order by: aceita apenas um campo válido");
        }
        $this->orderBy = " ORDER BY $campo {$tipo->value};";
        return $this;
    }


    public function GroupBy($groupBy = null){
        $groupBy = strtolower($groupBy);
        if(!preg_match('/^[a-z0-9_]+$/',$groupBy)){ 
            throw new InvalidArgumentException("Group by deve conter uma string/campo!");
        }
        $this->groupBy = " GROUP BY $groupBy;";
        return $this;
    }


    public function Dados(array $dados = []){
        if(empty($dados)){
            throw new InvalidArgumentException("Dados nao podem estar vazios!");
        } 
        if(array_is_list($dados)){
            throw new InvalidArgumentException("Array deve ser associativo!");
        }
        foreach($dados as $nome => $value){
            $this->dados[$nome] = $value;
        }
        return $this->dados;
    }


    public function Where(array $dados = []){
        if(empty($dados)){
            throw new InvalidArgumentException("Dados do where nao pode estar vazio!");
        } 
        foreach($dados as $nome => $value){
            $this->wheres[$nome] = $value;
        }
        return $this;
    }


    public function Execute(){
        $pdo = $this->init();
        $tipo = $this->tipo;
        $dados = $this->dados;
        $tabela = $this->tabela;
        $wheres = $this->wheres;
        $groupBy = $this->groupBy ? $this->groupBy : null;
        $orderBy = $this->orderBy ? $this->orderBy : null;
        $dadosGet = $this->dadosGet ? implode(",",$this->dadosGet): "*";
        $sql = "";

        
        if(in_array($tipo, ['put','delete']) && empty($wheres)){
            return ["erro" => true, "mensagem" => "Operação bloqueada: WHERE obrigatório"];
        }

        if($tipo == "get"){
            $sql = "SELECT $dadosGet FROM $tabela";
        }

        else if($tipo == "delete"){
            $sql = "DELETE FROM $tabela";
        }

        else if($tipo === "post"){
            $campos = implode(",", array_keys($dados));
            $valores = implode(",", array_map(function($v){
                if (is_string($v)) return "'" . addslashes($v) . "'";
                if ($v === null) return "NULL";
                return $v;
            }, array_values($dados)));
            $sql = "INSERT INTO $tabela ($campos) VALUES ($valores)";
        }

        else if($tipo === "put"){
            $set = [];
            foreach($dados as $campo => $valor){
                if (is_string($valor)) $valor = "'" . addslashes($valor) . "'";
                elseif ($valor === null) $valor = "NULL";
                $set[] = "$campo = $valor";
            }
            $sql = "UPDATE $tabela SET " . implode(", ", $set);
        }
        $conds = [];
        if(!empty($wheres)){
            $conds = [];
            foreach($wheres as $campo => $valor){
                if (is_string($valor)) $valor = "'" . addslashes($valor) . "'";
                elseif ($valor === null) $valor = "NULL";
                $conds[] = "$campo = $valor";
            }
            $sql .= " WHERE " . implode(" AND ", $conds);
        }
        if($groupBy)$sql .= $groupBy;
        if($orderBy)$sql .= $orderBy;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $this->dados = [];
        $this->wheres = [];
        $this->dadosGet = [];
        $this->groupBy = null;
        $this->orderBy = null;

        if($tipo === "get"){
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($res) === 1){
                return ["erro" => false, "mensagem" => "Dados encontrados com sucesso" ,"resultado" => $res[0]];
            }else if(count($res) > 1){
                return ["erro" => false, "mensagem" => "Dados encontrados com sucesso" ,"resultado" => $res];
            }else if(count($res) === 0){
                return ["erro" => true, "mensagem" => "Dados nao encontrados!"];
            }
            return count($res) === 1 ? $res[0] : $res;
        }

        if($tipo === "post"){
            $id = $pdo->lastInsertId();
            $sql = "SELECT * FROM $tabela WHERE ID = :ID;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":ID",$id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["erro" => false, "Resultado" => $resultado];
        }

        if($tipo === "put"){
            $linhas = $stmt->rowCount();
            $sql = "SELECT * FROM $tabela" . " WHERE " . implode(" AND ", $conds);
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if($linhas > 0) return ["erro" => false, "mensagem" => "Dados atualizados com sucesso" ,"resultado" => $resultado];
            return ["erro" => true, "mensagem" => "Nenhum dado foi alterado"];
        }

        if($tipo === "delete"){
            $linhas = $stmt->rowCount();
            if($linhas > 0){
                return ["erro" => false, "mensagem" => "Registro excluído com sucesso"];
            }
            return ["erro" => true, "mensagem" => "Nenhum registro foi excluído"];
        }

    }
}

class Documentos {
    
    static function C_arquivos($completar = true, $caminho = null, $arquivos = [], ...$datas){
        $dados = $arquivos;
        if($arquivos == [])$dados = $datas;
        foreach($dados as $nome){
            if($caminho and basename(__DIR__) == $caminho){
                echo "Essa pasta local é a pasta base, nao e necessarios colocal pasta base como local, e nao e permitido criar pasta com o mesmo nome da pasta base</br>";
            }else if ($caminho){
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho;
                if(!file_exists($pastaLocal)){
                    echo "Não foi encontrada a pasta de insercao -'$caminho'-</br>" ;
                } 
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho . DIRECTORY_SEPARATOR . $nome;
                if(file_exists($pastaLocal)){
                    echo "Arquivo -$nome- já existe!</br>" ;
                }else{
                    if(str_ends_with($caminho,"controller") && $completar == true){
                        $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho . DIRECTORY_SEPARATOR ."class.". $nome."Controller.php";
                        $arquivo = fopen($pastaLocal,"w");
                    }
                    else if(str_ends_with($caminho,"dao") && $completar == true){
                        $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho . DIRECTORY_SEPARATOR ."class.". $nome."DAO.php";
                        $arquivo = fopen($pastaLocal,"w");
                    }
                    else{ $arquivo = fopen($pastaLocal,"w");}
                    if(str_ends_with(basename($pastaLocal),".php")){
                        fwrite($arquivo,"<?php");
                    }
                    if(str_ends_with(basename($pastaLocal),"htaccess")){
                        fwrite($arquivo,"RewriteEngine On RewriteCond %{REQUEST_FILENAME} !-f RewriteCond %{REQUEST_FILENAME} !-d RewriteRule ^(.*)$ index.php [QSA,L]");
                    }
                    echo "Arquivo -$nome- criada com sucesso! </br>" ;
                }
            }else {
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $nome;
                if(file_exists($pastaLocal)){
                    echo "Arquivo -$nome- já existe! </br>" ;
                }else{
                    if(str_ends_with($caminho,"controller")){
                        $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR ."class.". $nome."Controller.php";
                        $arquivo = fopen($pastaLocal,"w");
                    }
                    else if(str_ends_with($caminho,"dao")){
                        $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR ."class.". $nome."DAO.php";
                        $arquivo = fopen($pastaLocal,"w");
                    }
                    else{ $arquivo = fopen($pastaLocal,"w");}
                    if(str_ends_with(basename($pastaLocal),".php")){
                        fwrite($arquivo,"<?php");
                    }
                    if(str_ends_with(basename($pastaLocal),"htaccess")){
                        fwrite($arquivo,"RewriteEngine On RewriteCond %{REQUEST_FILENAME} !-f RewriteCond %{REQUEST_FILENAME} !-d RewriteRule ^(.*)$ index.php [QSA,L]");

                    }
                    echo "Arquivo -$nome- criada com sucesso! </br>" ;
                }
            }
        }
    }
    static function C_pastas($caminho = null, $pastas = [], ...$datas){
        $dados = $pastas;
        if($pastas == [])$dados = $datas;
        foreach($dados as $nome){
            if($caminho and basename(__DIR__) == $caminho){
                echo "Essa pasta local é a pasta base, nao e necessarios colocal pasta base como local, e nao e permitido criar pasta com o mesmo nome da pasta base</br>";
            }else if ($caminho){
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho;
                if(!file_exists($pastaLocal)){
                    echo "Não foi encontrada a pasta de insercao -'$caminho'-</br>" ;
                } 
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho . DIRECTORY_SEPARATOR . $nome;
                if(file_exists($pastaLocal)){
                    echo "Pasta -$nome- já existe!</br>" ;
                }else{
                    mkdir($pastaLocal);
                    echo "Pasta -$nome- criada com sucesso! </br>" ;
                }
            }else {
                $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $nome;
                if(file_exists($pastaLocal)){
                    echo "Pasta -$nome- já existe! </br>" ;
                }else{
                    mkdir($pastaLocal);
                    echo "Pasta -$nome- criada com sucesso! </br>" ;
                }
            }
        }
    }
}

class OC{
    static function EstaVazio($valor, &$dadosDeErro){
        if(is_null($valor) || trim($valor) === ""){
            $dadosDeErro = ["erro" => true, "mensagem" => "dados faltando!"];
            return true;
        }
        return false;
    }

    static function TamanhoErrado($max, $min, $valor, &$dadosDeErro){
         if(strlen($valor) > $max || strlen($valor) < $min){
            $dadosDeErro = ["erro" => true, "mensagem" => "O valor deve ter entre {$min} e {$max} caracteres!"];
            return true;
        }
        return false;
    }

    static function EmailInvalido($email, &$dadosDeErro){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $dadosDeErro = ["erro" => true, "mensagem" => "Este email é invalido!"]; 
            return true;
        }
        return false;
    }

    static function senhaInvalida($validos, $senha, &$dadosDeErro){
        $parametroRegex = '';

        if(is_array($validos)){
            foreach($validos as $valido){
            if($valido == "numeros")$parametroRegex .= "0-9";
            else if($valido == "letras")$parametroRegex .= "a-zA-Z";
            else $parametroRegex .= preg_quote($valido, "/");
        }
        }
        if($parametroRegex === '')$parametroRegex = 'a-zA-Z0-9';
        
        $regex = '#^[' . $parametroRegex . ']+$#';

        if(!preg_match($regex,$senha)){
            $dadosDeErro = ["erro" => true, "mensagem" => "Senha invalida!"];
            return true;
        }
        return false;
    }
}

?>