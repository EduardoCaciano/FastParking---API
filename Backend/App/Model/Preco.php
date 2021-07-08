<?php

use App\Core\Model;

class Preco{

    public $id;
    public $primeiraHora;
    public $demaisHoras;

    public function listarTodas(){

        $sql = " SELECT * FROM tbl_preco ";
        
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
            return $resultado;
        } else {
            return [];
        }
    }

    public function salvar(){

        $sql = " INSERT INTO tbl_preco (primeiraHora, demaisHoras) 
        VALUES (?, ?) ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->primeiraHora);
        $stmt->bindValue(2, $this->demaisHoras);

        if($stmt->execute()){
            // se der certo, atribuir o id inserido a instância desta classe
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        }else{
            return false;
        }
    }
}
