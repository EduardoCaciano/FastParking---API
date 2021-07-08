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

    public function insert(){

        $sql = " INSERT INTO tbl_carros (nome, placa, dia, horaEntrada, horaSaida, valor) values ('Rafael Leme', 'ASD-1507', now(), now(), now(), 5) ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->nome);
        $stmt->bindValue(1, $this->placa);
        $stmt->bindValue(1, $this->valor);


        if($stmt->execute()){
            // se der certo, atribuir o id inserido a instância desta classe
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        }else{
            return false;
        }
    }

    public function update()
    {
        $sql = " UPDATE tbl_carro  
                 SET nome = ?, placa = ? 
                 WHERE idCarro = ? ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->nome);
        $stmt->bindValue(2, $this->placa);
        $stmt->bindValue(3, $this->idCarro);

        return $stmt->execute();
    }


    public function delete()
    {
        $sql = " UPDATE tbl_carros 
                 SET horaSaida = curtime(), valor = ?  
                 WHERE idCarro = ? ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->valor);
        $stmt->bindValue(2, $this->idCarro);

        return $stmt->execute();
    }

}