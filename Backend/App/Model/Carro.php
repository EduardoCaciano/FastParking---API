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
        $stmt->bindValue(2, $this->placa);
        $stmt->bindValue(3, $this->idPreco);


        if($stmt->execute()){
            // se der certo, atribuir o id inserido a instância desta classe
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        }else{
            return false;
        }
    }

    public function findById($id)
    {
        $sql = " SELECT idCarro, 
        nome, 
        placa, 
        time_format(horaEntrada, '%H:%i') as horaEntrada,
        time_format(horaSaida, '%H:%i') as horaSaida,
        valor,
        idPreco
        FROM tblCarros WHERE idCarro = ? ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $carro = $stmt->fetch(PDO::FETCH_OBJ);

            $this->idCarro = $carro->idCarro;
            $this->nome = $carro->nome;
            $this->placa = $carro->placa;
            $this->horaEntrada = $carro->horaEntrada;
            $this->horaSaida = $carro->horaSaida;
            $this->valor = $carro->valor;
            $this->idPreco = $carro->idPreco;

            return $this;
        } else {
            return false;
        }
    }

    public function getpreco()
    {
        $sql = " SELECT MAX(idPreco) as idPreco, primeiraHora, demaisHoras  FROM tbl_precos ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $preco = $stmt->fetch(PDO::FETCH_OBJ);

            return $preco;
        } else {
            return [];
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

    public function getHourIn($hour)
    {
        $sql = " SELECT time_format( '$hour', '%H') as hora";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $hour = $stmt->fetch(PDO::FETCH_OBJ);

            return $hour;
        } else {
            return [];
        }
    }

    public function getDiference(){
        $sql = " SELECT timediff( ?, ? ) AS diferenca ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->horaSaida);
        $stmt->bindValue(2, $this->horaEntrada);
        $stmt->execute();
  
        if ($stmt->rowCount() > 0) {
            $valor = $stmt->fetch(PDO::FETCH_OBJ);

            return $valor;
        } else {
            return [];
        }
    }

    public function getNowHour()
    {
        $sql = " SELECT curtime() as hora";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $hour = $stmt->fetch(PDO::FETCH_OBJ);

            return $hour;
        } else {
            return [];
        }
    }

}