<?php
class MangasDAO {
    static function GetTodos(){
        return DAO::Get()->Table("Mangas")->Execute();            
    }   
    static function Get($where){
        return DAO::Get()->Table("Mangas")->Where($where)->Execute();
    }
    static function Post(Mangas $dados){
        return DAO::Post()->Table("Mangas")->Dados($dados)->Execute();
    }
    static function Delete($where){
        return DAO::Delete()->Table("Mangas")->Where($where)->Execute();     
    }
    static function Put(Mangas $dados,$where){
        return DAO::Put()->Table("Mangas")->Dados($dados)->Where($where)->Execute();   
    }
    static function Describe(){
        return DAO::Describe()->Table("Mangas")->Execute();   
    }
    static function GetMangasGeneros(){
        $pdo = Banco::BuscarConexao();

        $sql = "SELECT M.*,G.NOME as G_NOME,G.ID as G_ID FROM MANGAS M
        INNER JOIN GENEROSMANGAS GM ON M.ID = GM.MANGAS_ID
        INNER JOIN GENEROS G ON G.ID = GM.GENEROS_ID;";

        $stmt = $pdo->query($sql);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $mangas = [];

        foreach($resultado as $linha){
            $id = $linha['ID'];
            if(!isset($mangas[$id])){
                $mangas[$id] = [
                    'TITULO' => $linha['TITULO'],
                    'ID' => $linha['ID'],
                    'GENEROS' => [],
                ]; 
            }
            $mangas[$id]['GENEROS'][] = [
                'NOME' => $linha['G_NOME'],
                'ID' => $linha['G_ID'],
            ];
        }
        $resultadoFinal = array_values($mangas);
        return Response::Success("Dados de Generos e mangas encontrados com sucesso",$resultadoFinal);       
    } 
}

