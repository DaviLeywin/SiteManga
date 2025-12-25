<?php
require_once __DIR__ . '\..\validator\class.AutoresValidator.php';
require_once __DIR__ . '\..\validator\class.BaseValidator.php';


class Autores {
    public string $NOME;
    public string $BIOGRAFIA;
    public string $DATA_NASCIMENTO;
    public string $NACIONALIDADE;
    
    function AlterarNome(string $Nome){
        if(!BaseValidator::ValidarTamanho($Nome, 200)){
            throw new InvalidArgumentException("Nome excedeu o maximo de 200 caracteres!");
        }
        $this->NOME = $Nome;
    }

    function AlterarBiografia(string $Biografia){
        $this->BIOGRAFIA = $Biografia; 
    }

    function AlterarDataNascimento(string $DataNascimento){
        if(!BaseValidator::ValidarData($DataNascimento)){
            throw new InvalidArgumentException("Data de nscimento invalida!");
        }
        $this->DATA_NASCIMENTO = $DataNascimento; 
    }

    function AlterarNacionalidade(string $Nacionalidade){
        if(!BaseValidator::ValidarTamanho($Nacionalidade,100)){
            throw new InvalidArgumentException("Nacionalidade excedeu o limite de 100 caracteres!");
        }
        $this->NACIONALIDADE = $Nacionalidade;
    }

    function GetNome(){
        return $this->CAMPO;
    }

    function GetBiografia(){
        return $this->BIOGRAFIA;
    }

    function GetDataNascimento(){
        return $this->DATA_NASCIMENTO;
    }

    function GetNacionalidade(){
        return $this->NACIONALIDADE;
    }
}
?>