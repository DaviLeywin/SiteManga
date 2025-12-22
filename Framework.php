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
    
    static function CampoSobrando(array $dados = [],array $descricao = []):array {
        if(empty($dados)) throw new InvalidArgumentException("Dados vazios para validacao!");
        if(empty($descricao)) throw new InvalidArgumentException("Descricao vazia para validacao!");
        $erro = [];
        
        $ArrayField = array_column($descricao['resposta'],'Field');
        $ArrayAssocField = array_flip($ArrayField);

        $erro = array_diff_key($dados, $ArrayAssocField);
        return $erro;

    }

    static function PriValidarNotNull(array $dados = [],array $descricao = []){
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
    
    static function TerValidarTamanho(array $dados = [], array $descricao = []): array{
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

    static function SegValidarTipo(array $dados = [], array $descricao = [],string $tabela = "",array $url = []) :array{
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
            'ENUM'  =>fn($v, $opcoes) => in_array($v, $opcoes, true), 
        ];

        $erro = [];
        foreach($descricao['resposta'] as $DescCampo){
            $campo = $DescCampo['Field'];
            if (!array_key_exists($campo, $dados)) continue;
            $valor = $dados[$campo];
            $tipo = $DescCampo['Type'];
            preg_match('/^([a-zA-Z]+)/', $tipo, $res);
            if($DescCampo['Key'] == "UNI"){
                $r = DAO::Get()->Tabela($tabela)->Where([$campo =>  $valor])->Execute();
                if(isset($r['resultado'])){
                    if(!empty($url)){
                        if($r['resultado']['ID'] !== $url['id']){
                            $erro['atualizar'][] = [ 'valor' =>  $valor, 'mensagem' => 'valor UNIQUE ja existe em outra objeto!'];
                        }
                    }else{$erro['inserir'][] = ['valor' =>  $valor, 'mensagem' => 'valor UNIQUE ja existe em outra objeto!'];}
                }
            }
            $tipoBase = strtoupper($res[0]);
            if(strtoupper($res[0]) !== 'ENUM'){
                $valido = $relacoes[$tipoBase]($valor);
                if(!$valido) $erro['tipo errado'][] = ['valor' =>  $valor,'tipo_esperado' => $tipo,];
            }else {
                preg_match('/\((.*)\)/', $tipo, $match);
                preg_match_all("/'([^']+)'/", $match[1], $values);
                $valido = $relacoes[$tipoBase]($valor, $values[1]);
                if(!$valido) $erro['enum'][] = ['valor' =>  $valor,'valores_esperados' => $values[1],];
            }
            
        }
        return $erro;

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
        if (!$this->pdo) throw new InvalidArgumentException("me matei n deu!");
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
    
    public function Table(string $tabela){
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
        if (!preg_match('/^[a-z0-9_]+$/', $campo)) throw new InvalidArgumentException("Order by: aceita apenas um campo válido");
        $this->orderBy = " ORDER BY $campo {$tipo->value};";
        return $this;
    }


    public function GroupBy($groupBy = null){
        $groupBy = strtolower($groupBy);
        if(!preg_match('/^[a-z0-9_]+$/',$groupBy))throw new InvalidArgumentException("Group by deve conter uma string/campo!");
        $this->groupBy = " GROUP BY $groupBy;";
        return $this;
    }


    public function Dados(array $dados = []){
        if(empty($dados)) throw new InvalidArgumentException("Dados nao podem estar vazios!");
        if(array_is_list($dados)) throw new InvalidArgumentException("Array deve ser associativo!");
        foreach($dados as $nome => $value){
            $this->dados[$nome] = $value;
        }
        return $this;
    }


    public function Where(array $dados = []){
        if(empty($dados)) throw new InvalidArgumentException("Dados do where nao pode estar vazio!");
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

class Documento {
    public $documento;
    public $caminho;
    public $criar;
    public $tipo;
    
    static function Local(string $local = ""){
        $caminho = str_ireplace("/","\\",__DIR__ ."\\". $local);
        if(!file_exists($caminho)) throw new InvalidArgumentException("Caminho inexistente!");   
        $inst = new self();
        $inst->caminho = $caminho;
        return $inst;
    }
    
    function Pastas(array $pastas):self{
        if(empty($pastas)) throw new InvalidArgumentException("Campo obrigatorio faltando em Pastas!");
        $this->criar = 'PASTAS';
        $this->documento = $pastas;
        return $this;
    }

    function Arquivos(array $arquivos):self{
        if(empty($arquivos)) throw new InvalidArgumentException("Campo obrigatorio faltando em Arquivos!");
        $this->criar = 'ARQUIVOS';
        $this->documento = $arquivos;
        return $this;
    }

    function Tipo(string $tipo){
        if(empty($tipo)) throw new InvalidArgumentException("Campo tipo nao pode estar vazio!");   
        if(in_array(strtolower($tipo), ['htaccess','dao','controller','service','banco','rota','request','response','index'])){
            $this->tipo = strtolower($tipo);
            return $this;
        }
        throw new InvalidArgumentException("Tipo invalido! tipos validos: service, daoe controller.");
    }

    function CriarPastas(){
        $erro = [];
        foreach($this->documento as $pasta){
            if(file_exists($this->caminho.DIRECTORY_SEPARATOR . $pasta)){
                $erro[] = "Pasta $pasta ja existe nesse local!";
            }else if(!file_exists($this->caminho.DIRECTORY_SEPARATOR . $pasta)){
                mkdir($this->caminho.DIRECTORY_SEPARATOR . $pasta);
                $erro[] = "Pasta $pasta criada com sucesso!";
            }
        }
        return $erro;
    }

    function CriarArquivos(){
        $dados = [];
        foreach($this->documento as $arquivo1){
            if(file_exists($this->caminho.DIRECTORY_SEPARATOR . $arquivo1)){
                $erro[] = "Arquivo $arquivo1 ja existe nesse local!";
                continue;
            }
            if($this->tipo){
                if($this->tipo == 'controller'){
                    $arquivo = "class." . $arquivo1 . "Controller.php";
                    $arquivo = $this->caminho . DIRECTORY_SEPARATOR . $arquivo; 
                    $texto = $this->ArquivosDados('controller',$arquivo1);
                    $arquivo = fopen($arquivo,"w");
                    fwrite($arquivo,$texto);
                }
                else if($this->tipo == 'dao'){
                    $arquivo = "class." . $arquivo1 . "DAO.php";
                    $arquivo = $this->caminho . DIRECTORY_SEPARATOR . $arquivo; 
                    $texto = $this->ArquivosDados('dao',$arquivo1);
                    $arquivo = fopen($arquivo,"w");
                    fwrite($arquivo,$texto);
                }
                else if($this->tipo == 'service'){
                    $arquivo = "class." . $arquivo1 . "Service.php";
                    $arquivo = $this->caminho . DIRECTORY_SEPARATOR . $arquivo;
                    $texto = $this->ArquivosDados('service',$arquivo1);
                    $arquivo = fopen($arquivo,"w");
                    fwrite($arquivo,$texto);
                }else{
                    $arquivo = $this->caminho . DIRECTORY_SEPARATOR . $arquivo1;
                    $texto = $this->ArquivosDados($this->tipo,$arquivo1);
                    $arquivo = fopen($arquivo,"w");
                    fwrite($arquivo,$texto);
                }
            }else{
                $arquivo = $this->caminho . DIRECTORY_SEPARATOR . $arquivo1; 
                fopen($arquivo,"w");
            }
            $dados[] = "Arquivo $arquivo1 criado com sucesso!";

        }
        return $dados;
    }

    function Exec(){
        if(empty($this->criar)) throw new InvalidArgumentException("Nenhum documento definido para criacao!");
        if(empty($this->caminho)) throw new InvalidArgumentException("Caminho nao definido para criacao!");
        if(empty($this->documento)) throw new InvalidArgumentException("Nenhum documento definido para criacao!");
        if($this->criar == 'ARQUIVOS'){
            return $this->CriarArquivos();
        }
        else if($this->criar == 'PASTAS'){
            return $this->CriarPastas();
        }
    }

    function ArquivosDados(string $tipo, string $arquivo):string{
        $relacoes = [
            'controller' => "eJzFkstOwzAQRfeR8g+D1UUiQT6g4SFBBGIFKt0gUlkmnQpLxg5+VAKUfyep0+Ii+lhQMRvL9szcc8c+vahfaogjjW+Oa6RKVgiUFrcjSiEDUmZZaVDPeYVlJZgx2Zg9o2AP/ixrq0keR3G0uAR/eaWk1UoI1PAZR9CGsczyCmZOVpYrCTdox2qqTDLohNHYYxg4LdI+vQuN1mkJa3LD4aowzX1q45eNMlsUuv0T4VMygTNIuLRpcJTvQZIsOi5BPMbvIPfKbCPhswRfa/u+Sjk5v7wrHtMwJyAZoamVNC3ENeMiIQVrJwJz9sGVOSJpgN7scrEG1qt+T3aDGfcXU/1Hz+6n5d7FPi9ZoECLB/tVy/YhThw1X7SwDeo=",
            'dao' => "eJyVk19rwjAUxd8Fv0MIPrSg9N0NN1mH7Gl7cOw5NtdZCEnJHxyI3303bepa1hpN+xDKOfeXm3v6+FQdqumkEMwYsmU7ECxfv5PTdEJwGctsWZC9k4UtlSQbsFvFlUnSIPBLg3VaErQtlyhI0sUKCwlIaFOP4ofXHyichSR9IJ3V1DhfdgO4ZHY8gIZ7eV/e1Hq7+IAc5n0og0DOsMMxYC0ZIube1ZpvJuYgwEKkySC6q8/u/Y4069pe59fxXnhDx9eOErkDU+hyB6Oh+hPEknXh4OufmVaWmcXqG2NCM5/e2pedSn6m81DlRUmrlRCgn1FB/cT++dZCBOSwq/4retbKB4Vmb9KAtuNen6eejzfDplkz9ciBG1Ef7Dz3s+Is6sbJopX8AkL7NU8=",
            'service' => "eJztlUFrwjAYhu+F/oeseKhQ+gPqxhjrNnZxomUnocT2cwZi0iWpDIb/fWlTbZwOO+dhB3MJJG+evPnSt7m+LRYFch0B7yURkHKWAUrT+HmcpihE3jQMpznm04xiKcMEz4Di+O4l1Ku8geu4Tj2BzMQExIro9Z+ug3STCiuSoXnJMkU4Q0+gEp5z6fcbQdUEqFIwtCVHUSsbGNnadD9C/V4p6FGmUbXIw7gRl1pZVQOkspm9HGQmSIY5urHJcT08g9qtJRcgC83CWt2URUbRPV4WfMJnArOcb7cJLPjGYNXI3N9i+ptDjasBJiGKHjGhvlcjJYIPJbC88oJ2hUU65mskyCumJMdiyNWwpPQ83h6E4EgXbGXYKDNemR5iJeUn253AW2M3IcXhOgaeuSPvz6aV3gLpz7Fxf6LlBMTGMl5itjjT7e+ZNfDf+N0Py24GjiamVPZZvkWxU2wuoflnoQnsf+UlOd2Ts5+FLm9YDBQUdHjGbGELXn8B+B9fhw==",
            'banco' => 'eJztVt9v2jAQfkfif/AsHoJE6esUBlUg2YREC0vyUKmqImMOiAQ22I7aauJ/r+NEKAkprTZW7WERUsj98Hd339nnbze79a7ZELBPYgERZxRQFLljP4pQF+HrbveacraMV/mrq81xr9loNuiGSImGhFGOfjUbSD9SERVT1NoteK8skftNRRIzCUJVhGsuqyIJbE0qskQmRMRVjHkaigmtIF0mjKqYMxSzWFntPFBjAJulbRtI1EetH5Pp0JkED1jnyfDjA04V+LF34mACqvUwmjqXPN5ap1xX52YSqnUymtrg9puSg/6uM8uKX7LMREfjw1t1HImYCMN6TTWzIhfwlHgpGKVPK81BIzN4QjN3auHtiw7STqvdx90CKV3co2siJKh+opZfcadczE6JjyLmEeVqoJ0dpUQ8TxRYGs22nTD0I8/3b6eu10FGlH9F3v3Im4Xj6V27V1yqsnBWYTzyPSf0kOuEztAJPDT+ju6mIfLux0EYoGMihiidCa6PD56BWumS1fgFqEQw5IPccU2MbQcJpSClhbMNRzULC/3iWySTVMG/4OIaB0oUXVveM4Wdoa0F7QoRai34k6HBJYrMiYSjtYU9ITgiGY4wMByZXGydWwuuBitQtxqWrMBql4DfaR8XNqD+NxDCrj+dldrns1qH7MjqI71z0hW/20MG8KJNNEwkJWLEGTyTT+iixZyRLfTLzPwb3ZSTbdb/G9tfl5iqy5JnBkiw39QNY31x0JxkHxWSz7K55AKITrgwBYk0W62adhnszDY6nEXIB2gKkv39GE5u+zbUZc/t4OfkT9nygZ4b+OXj/OSiUbws1CtNIxRU751eAs6NPp2G/t0MXgH8C/1r',
            'rota' => 'eJylVe9O2zAQ/47EOxgTiUTLmiFtXyiFsZFtaCBYWpBQCJZJr220NMkch3Wq+jR7lL3Yzk5S0jaID1iVGt+/39n3u/PhcTbJtrcE/CoiAYSGMc/zjodbyGUHdbS71LI0CZVJmkiRxjEIp7Q+KeTk81JYO21vaS3xUsnz+fYWwZUVD3EUEkMombZR0lGRhDJKE8IYxs6lKEJpWnNiyEmUvz3SxqRH/KBLFhs+Y5CmUYjYJgYPeUpsI0MHGEdDjj4jHudgzatIfDhs2tqEfnUHtOlidRdr8bM0fw3A1WX/RYTiVQDXL8UfQgwSXgFx6p67A3cTZQ1nw9GYgkyHiEYacEscXVY/QPwwnWYcS07RmdpUOeNf6YwfS1/ahgozCAvJBRKm1KhliJLAGDyB36Sis2l1GyZVcj1isL7r3bieTz33x7XbH7ALd/Dt8pQGTXPM7RPPAe2p048kXPBkzB2eRXTNCi2Qwgz7JYt5WN678rQptTehrr0zGjTzchwiQBYi0cE2Y5d/KgtKjsvdARFSRNPy8lHeDDdKBfBwYq70Ev50CzavTK1oZGq5X19+QHZ69U1ZRDV+lBTQfc7rqVLBemh9gkceo1IRsEdWh8bBwY3WiUH6E5KVQjVhlgF8CkKk7TBqTaTMmIA8w3kCOFWGYL5/t98WVq36wpfhn7GDWSRbVItV0doWuTiGmeLN7j3tZLhjFTVYyOP4gYc/TTTbc+7mpn9/twjeWHcLZw+7pua4aUxxGlZJUvP46pB2jKm/H3TokX/voAPtLlSXlVVQXRRYHWrs0s1Kafwpl4oSOjGbVD2bccGx0iLNrdbiPenxLFwI/oeNoliCMBsqe4TZhhP+CBbpHZGdKGdRImuRfeJ5J7fsy9n5wPXYdd9l393btqL4xtMjY1f8U5MCZlmsakk/Yi+Vp9XTImgLsvpqdZyNd6sBUj9azzBDFYoVOQimasL06c3WJG1SFkCdvnkvtJpIpaLatKbdQrIGoxqfrRz/0AxZZV81i0LGtxXUaE1yPoapFlH1QJPk39+UQKJPxLGB7Xr4ISD+/gNl+1Wu',
            'response' => 'eJzVkb9OwzAQh/dKfYer1cGR+galrRhAYmCBsYqQ6zqJkWNHPptSAe/OOcq/AiMDnKIokX/58t3d1a6pmvlMGoEIDwobZ1HB23wGVBhE0BKKaGXQzsJjlFIhcgxe2xKWtbIoSlXDBmw0ZgXCe3F+d4dnJQMsfcIRg473edYxU+mCa3xKn/CBkU0DqULl3QmsOsGdfRFGH699GSkdbl6lapIPZ/e9wFEBxuTmwB28LkVwXosFy9Yj9GN8JDUKWJfMLv/KOgyDzRaCj2r15bz3bQOD/SSVry8aXai6CWc+DONbn73LnvURlpMXb2eZjVOcdtLex3ciRG8HUpekfn9e463Q5m/usBCmEr+5QQLi/14hXbvtJ+SFBcY=',
            'request' => 'eJxtkV9LwzAUxd8H+w53ow8NVPY+rUNRfJyoLyISYna7RbI05g8ypN/dpO3W0O2+JNx7cvLLyc1K7/R0wiWzFl7wx6N18DedQCjtv6TgANmdlFDCx+f1qH+/fni/OHh6fLvYf16/ngbdqPKKO1EroJTXyjrjuctJDxArczthr247x4zGdbWEwTvR9O4ZbTcnVaIz7DcIKiGRbtHFGx0qZ/N5CGG5WAilvZuT1PnbBrYS4kI3yOsN5tGlgECKqVJUkHfqWVmC8lISSJ6RYPapteLEoEFp8fwI7nUa5bE0MxZpyKvHaYVkJIpMs9B3h7ybkxFCbKYIZ5F2P8+MYQe6R7MNrx+yLoa/KRLjI0UTo2/+AcTPm20=',
            "htaccess" => "eJwLSi0vyixJdc1Lz8xLVfDP4+UKgog45+elKKhWB7kGhroGh8S7efq4+jn6utYqKOqmEaMoBa4oqDQnVSFOQ09LU0UhMy8ltUKvIKNAITow2FHHJxYAgOUogQ==",
            "index" => "eJx9jD0LwjAURfdC/0MoDslgwLlWlyK4di6EkF5o/UjieynVf29bcXGQO95zzv4Y+5hnHdzNEiQnGlwy6RXB1U6VeUZ4jAPBBO8gjKnPjTFCi6LVuj2RvWMKdNVzpPiFiznJrJuQLP8FwDF4xpfJsw0tjqiExyRWX6r1WAbXB3Hh4A28Cx3kh94e8IQbkyWpVPkGSyZGUw==",
        ];
        if(in_array($tipo, ['controller','dao','service'])){
            $str = $relacoes[$tipo];
            $original = gzuncompress(base64_decode($str));
            $original = str_replace("Tabela",$arquivo,$original);
            return $original;
        }else if(in_array($tipo, ['htaccess','banco','rota','request','response','index'])){
            $str = $relacoes[$tipo];
            $original = gzuncompress(base64_decode($str));
            return $original;
        }
    }

}
?>