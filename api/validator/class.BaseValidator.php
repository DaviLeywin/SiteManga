<?php

class BaseValidator {
    static function CampoSobrando(array $dados = [],array $descricao = []):array {
        if(empty($dados)) throw new InvalidArgumentException("Dados vazios para validacao!");
        if(empty($descricao)) throw new InvalidArgumentException("Descricao vazia para validacao!");
        $erro = [];
        $ArrayField = array_column($descricao['resposta'],'Field');
        $ArrayAssocField = array_flip($ArrayField);
        $erro = array_diff_key($dados, $ArrayAssocField);
        return $erro;
    }

    static function ValidarNotNull(array $dados = [],array $descricao = []){
        if(empty($dados)) throw new InvalidArgumentException("Dados vazios para validacao!");
        if(empty($descricao)) throw new InvalidArgumentException("Descricao vazia para validacao!");
        $erros = [];
        if(isset($dados['ID'])) $erros['ID'] = ["Campo ID e auto_increment!"];
        foreach($descricao['resposta'] as $DescCampo){
            if($DescCampo['Field'] == 'ID')continue;
            if($DescCampo['Default'] !== null)continue;
            if($DescCampo['Null'] == 'YES')continue;
            if(!isset($dados[$DescCampo['Field']])) $erros['Campos NOT NULL faltando'][] = $DescCampo['Field'];
            else if(empty(trim($dados[$DescCampo['Field']]))) $erros['Campos NOT NULL Vazio'][] = $DescCampo['Field'];
        }
        return $erros;
    }

    static function ValidarTamanhoArray(array $dados = [], array $descricao = []): array{
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

    static function ValidarTipoArray(array $dados = [], array $descricao = [],string $tabela = "",array $url = []) :array{
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
                $r = DAO::Get()->Table($tabela)->Where([$campo =>  $valor])->Execute();
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

    static function ValidarTamanho(string $Tamanho, int $TamanhoMaximo){
        if($TamanhoMaximo < strlen($Tamanho)){
            return false;
        }
        return true;
    }

    static function ValidarData(string $Data){
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$Data)){
            return false;
        }
        return true;
    }

    static function ValidarFkId($ForeignKeyid){
        if($ForeignKeyid <= 0){
            return false;
        }
        return true;
    }
     
}

?>