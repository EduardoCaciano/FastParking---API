<?php

use App\Core\Model;

class Carro{

    public $idCarro;
    public $nome;
    public $placa;
    public $dia;
    public $horaEntrada;
    public $horaSaida;
    public $idPreco;
    public $primeiraHora;
    public $demaisHoras;

        
    public function listarTodos(){

        $sql = " SELECT * FROM tbl_carros ";
        
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

        $sql = " INSERT INTO tbl_preco (primeiraHora, demaisHoras) VALUES (now(), now()) ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->descricao);

        if($stmt->execute()){
            // se der certo, atribuir o id inserido a instância desta classe
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        }else{
            return false;
        }
    }


}