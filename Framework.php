<?php
require_once "api\banco\class.Banco.php";

set_exception_handler(function (Throwable $e) {

    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }

    $code = 500;
    $tipo = 'ERRO_INTERNO';
    $mensagem = 'Erro inesperado';

    if ($e instanceof TypeError || $e instanceof ValueError) {
        $code = 400;
        $tipo = 'ERRO_DE_TIPO';
        $mensagem = 'Tipo de dado inválido';
    }
    elseif ($e instanceof InvalidArgumentException || $e instanceof DomainException) {
        $code = 422;
        $tipo = 'ERRO_DE_REGRA';
        $mensagem = $e->getMessage();
    }
    elseif ($e instanceof PDOException || $e instanceof DatabaseException) {
        $code = 500;
        $tipo = 'ERRO_DE_BANCO';
        $mensagem = 'Erro ao acessar o banco de dados';
    }
    elseif ($e instanceof LogicException) {
        $code = 500;
        $tipo = 'ERRO_DE_LOGICA';
        $mensagem = 'Erro interno de lógica';
    }
    elseif ($e instanceof Error) {
        $code = 500;
        $tipo = 'ERRO_DE_EXECUCAO';
        $mensagem = 'Erro interno de execução';
    }

    http_response_code($code);

    echo json_encode([
        'erro' => true,
        'tipo' => $tipo,
        'mensagem' => $mensagem,
        'detalhes' => [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
        ]
    ], JSON_PRETTY_PRINT);

    exit;
});

enum Ordem: string {
    case ASC = 'ASC';
    case DESC = 'DESC';
}

class Services {
    static function capturaParenteses(string $tipo): ?string {
        if (preg_match('/\(([^)]+)\)/', $tipo, $matches)) return $matches[1]; 
        return null;
    }
    
    static function ValidarNotNull(array $dados = [],array $descricao = []){
        if(empty($dados)) throw new InvalidArgumentException("Dados vazios para validacao!");
        if(empty($descricao)) throw new InvalidArgumentException("Descricao vazia para validacao!");
        $erros = [];
        if(isset($dados['ID'])) $erros['ID'] = ["Campo ID e auto_increment!"];
        foreach($descricao['resposta'] as $DescCampo){
            if($DescCampo['Field'] == 'ID')continue;
            if($DescCampo['Null'] == 'YES')continue;
            if(!isset($dados[$DescCampo['Field']])) $erros['Campos NOT NULL faltando'][] = $DescCampo['Field'];
            else if(empty(trim($dados[$DescCampo['Field']]))) $erros['Campos NOT NULL Vazio'][] = $DescCampo['Field'];
        }
        return $erros;
    }
    
    static function ValidarTamanho(array $dados = [], array $descricao = []): array{
        if (empty($dados))  throw new InvalidArgumentException("Dados vazios para validacao!");
        if (empty($descricao))  throw new InvalidArgumentException("Descricao vazia para validacao!");
        $erros = [];
        foreach ($descricao['resposta'] as $coluna) {
            if ($coluna['Field'] === 'ID') continue;
            if (!isset($dados[$coluna['Field']])) continue;
            $tipo = strtoupper($coluna['Type']);
            if (!preg_match('/^([A-Z]+)\((\d+)\)/', $tipo, $match)) continue;
            $max = (int) $match[2];
            $valor = $dados[$coluna['Field']];
            if (strlen($valor) > $max)$erros[] = ['campo' => $coluna['Field'],'tamanho_recebido' => strlen($valor),'tamanho_maximo' => $max,];
        }
        return $erros;
    }

    static function ValidarTipo(array $dados = [], array $descricao = [],string $tabela = "",array $url = []) :array{
        if(empty($dados)) throw new InvalidArgumentException("Dados vazios para validacao!");
        if(empty($descricao)) throw new InvalidArgumentException("Descricao vazia para validacao!");
        if(empty($tabela)) throw new InvalidArgumentException("Tabela vazia para validacao!");

        $relacoes = [
            'VARCHAR'  =>fn($v) => is_string($v),
            'CHAR'  =>fn($v) => is_string($v),
            'TEXT'  =>fn($v) => is_string($v),
            'INT'  =>fn($v) => is_numeric($v) && (int)$v == $v,
            'TINYINT'  =>fn($v) => is_numeric($v) && (int)$v == $v,
            'SMALLINT'  =>fn($v) => is_numeric($v) && (int)$v == $v,
            'MEDIUMINT'  =>fn($v) => is_numeric($v) && (int)$v == $v,
            'BIGINT'  =>fn($v) => is_numeric($v) && (int)$v == $v,
            'DECIMAL'  =>fn($v) => is_numeric($v),
            'NUMERIC'  =>fn($v) => is_numeric($v),
            'FLOAT'  =>fn($v) => is_numeric($v),
            'DOUBLE'  =>fn($v) => is_numeric($v),
            'BOOL'  =>fn($v) => is_bool($v) || $v === 0 || $v === 1, 
            'BOOLEAN'  =>fn($v) => is_bool($v) || $v === 0 || $v === 1,
            'DATE'  =>fn($v) => strtotime($v) !== false,
            'DATETIME'  =>fn($v) => strtotime($v) !== false,
            'TIMESTAMP'  =>fn($v) => strtotime($v) !== false,
            'TIME'  =>fn($v) => preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $v),
            'YEAR'  =>fn($v) => is_numeric($v) && (int)$v > 0,
            'ENUM'  =>fn($v, $opcoes) => str_contains($v, $opcoes, true), 
        ];
        $dadosInvalidosTipo = [];
        foreach($descricao['resposta'] as $d){
            if($d['Key'] == "ID")continue;
            $tipoBanco = $d['Type'];
            preg_match('/^([a-zA-Z]+)/', $tipoBanco, $m);
            $TipoDeDadosSemP = strtoupper($m[0]);
            $ValorDoParenteses = self::capturaParenteses($tipoBanco);
            $ValorRecebido = $dados[$d['Field']] ?? null;
            if(is_null($ValorRecebido))continue;
            if($d['Key'] == "UNI"){
                $resposta = DAO::Get()->Tabela($tabela)->Where([$d['Field'] => $ValorRecebido])->Execute();
                $id = $resposta['resultado']['ID'] ?? "";
                $id2 = $url['id'] ?? "0";
                if($id == $id2)continue;
                else if($resposta['erro'] == false){
                    $dadosInvalidosTipo[] = [
                    'valor recebido' => $ValorRecebido,
                    'campo' => $d['Field'],
                    'tipo_de_campo' => 'UNIQUE',
                    'mensagem' => "Ja existe um campo com este valor!"
                    ];
                }
            }
            if($TipoDeDadosSemP !== 'ENUM'){
                $valido = $relacoes[$TipoDeDadosSemP]($ValorRecebido);
                if(!$valido)$dadosInvalidosTipo[] = [
                    'valor recebido' => $ValorRecebido,
                    'valore valido' => $TipoDeDadosSemP,
                ];
            }
            else{
                preg_match_all("/'([^']+)'/", $ValorDoParenteses, $matches);
                $arrayValido = $matches[1];
                $valido = $relacoes[$TipoDeDadosSemP]($ValorRecebido,$arrayValido);
                if(!$valido)$dadosInvalidosTipo[] = [
                    'valor recebido' => $ValorRecebido,
                    'valores validos' => $arrayValido
                ];
            }
        }
        return $dadosInvalidosTipo;
    }
}

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
        $this->pdo = Banco::BuscarConexao();
        if (!$this->pdo) {
            throw new InvalidArgumentException("me matei n deu!");
        }
        return $this->pdo;
    }


    public static function __callStatic($metodo,$params){
        $valido = ['get','put','post','delete','describe'];
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
        return $this;
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
        try{
            
            if(in_array($tipo, ['put','delete']) && empty($wheres)){
                return ["erro" => true, "mensagem" => "Operação bloqueada: WHERE obrigatório"];
            }
    
            if($tipo == "get"){
                $sql = "SELECT $dadosGet FROM $tabela";
            }
    
            else if($tipo == "delete"){
                $sql = "DELETE FROM $tabela";
            }
            else if($tipo == "describe"){
                $sql = "DESCRIBE $tabela";
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
            
            if($tipo == "describe"){
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($res){
                    return Response::Success("Descricao encontrada com sucesso!",$res);
                }
                return Response::Fail("Erro ao encontrar descricao!");
            }

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
                return ["erro" => false, "resultado" => $resultado];
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
        }catch(Exception $e){
            throw new InvalidArgumentException("Erro desconhecido no executar!");
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
                        $nomeMS = substr($nome,1);
                        fwrite($arquivo,"<?php
require_once __DIR__ . '\..\dao\class.{$nome}DAO.php';

class {$nome}Controller {
    static function GetTodos(\$request, \$url){
        return {$nome}DAO::GetTodos();
    }
    
    static function Get(\$request, \$url){
        return {$nome}DAO::Get(\$url);
    }
    
    static function Post(\$request, \$url){
        return {$nome}DAO::Post(\$request->BODY);
    }
    
    static function Put(\$request, \$url){
        return {$nome}DAO::Put(\$request->BODY, \$url);
    }
    
    static function Delete(\$request, \$url){
        return {$nome}DAO::Delete(\$url);
    }
}

\$rotas->get('/BuscarNome/{id}','{$nome}Controller@Get');
\$rotas->get('/Buscar{$nome}','{$nome}Controller@GetTodos');
\$rotas->post('/InserirNome','{$nome}Controller@Post');
\$rotas->delete('/DeletarNome/{id}','{$nome}Controller@Delete');
\$rotas->put('/AtualizarNome/{id}','{$nome}Controller@Put');    
"
                        );
                    }
                    else if(str_ends_with($caminho,"dao") && $completar == true){
                        $pastaLocal = __DIR__ . DIRECTORY_SEPARATOR . $caminho . DIRECTORY_SEPARATOR ."class.". $nome."DAO.php";
                        $arquivo = fopen($pastaLocal,"w");
                        $nomeD = strtolower($nome);
                        fwrite($arquivo,"<?php
class {$nome}DAO {
    static function GetTodos(){
        return DAO::Get()->Tabela('{$nomeD}')->Execute();            
    }   
    static function Get(\$where){
        return DAO::Get()->Tabela('{$nomeD}')->Where(\$where)->Execute();
    }
    static function Post(\$dados){
        return DAO::Post()->Tabela('{$nomeD}')->Dados(\$dados)->Execute();
    }
    static function Delete(\$where){
        return DAO::Delete()->Tabela('{$nomeD}')->Where(\$where)->Execute();     
    }
    static function Put(\$dados,\$where){
        return DAO::Put()->Tabela('{$nomeD}')->Dados(\$dados)->Where(\$where)->Execute();   
    }
}");
                    }
                    else{ $arquivo = fopen($pastaLocal,"w");}
                    if(str_ends_with(basename($pastaLocal),".php") && !str_ends_with(basename($pastaLocal),"Controller.php") && !str_ends_with(basename($pastaLocal),"DAO.php")){
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
?>