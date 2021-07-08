<?php

use App\Core\Controller;

class Carros extends Controller
{

    public function index(){
        $carroModel = $this->Model("Carro");

        $carros = $carroModel->listarTodos();


        echo json_encode($carros, JSON_UNESCAPED_UNICODE);
    }

    public function store(){

        //pegando o corpo da requisição, retona uma string
        $json = file_get_contents("php://input");

        //convertendo a string em objeto
        $novaCarro = json_decode($json);

        //instanciando o model
        $carroModel = $this->model("Carro");

        $carroModel->nome = $novaCarro->nome;
        $carroModel->placa = $novaCarro->descricao;
        $carroModel->idPreco = $carroModel->getPreco()->idPreco;

        //chamando o método inserir do model
        $carroModel = $carroModel->store();

        //verificando se deu certo
        if($carroModel){
            //se deu certo, retornar a carro inserida
            http_response_code(201);
            echo json_encode($carroModel, JSON_UNESCAPED_UNICODE);

        }else{
            //se deu errado, mudar status code para 500 e retornar mensagem de erro
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir carro"]);
        }
    }    

    public function update($id){

        $carroEditar = $this->getRequestBody();

        $carroModel = $this->model("Carro");

        $carroModel = $carroModel->buscarPorId($id);

        if(!$carroModel){
            http_response_code(404);
            echo json_encode(["erro" => "Carro não encontrada"]);
            exit;
        }

        $carroModel->descricao = $carroEditar->nome;
        $carroModel->placa = $carroEditar->placa;

        if($carroModel->update()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao editar carro"]);
        }
    }


    public function delete($id){

        $carroModel = $this->model("Carro");

        $carroModel = $carroModel->buscarPorId($id);

        if(!$carroModel){
            http_response_code(404);
            echo json_encode(["erro" => "Carro não encontrada"]);
            exit;
        }

        $valorPrimeiraHora = $carroModel->getPreco()->primeiraHora;
        $valorDemaisHoras = $carroModel->getPreco()->demaisHoras;

        $horaEntrada = floatval($carroModel->getHourIn($carroModel->horaEntrada)->hora);
        $carroModel->horaSaida = $carroModel->getNowHour()->hora;
        $horaSaida = floatval($carroModel->getHourIn($carroModel->horaSaida)->hora);

        $horasEstacionado = $horaEntrada - $horaSaida;
        if ($horasEstacionado < 0) {
            $horasEstacionado *= -1;
        }
        if ($horasEstacionado > 1) {
            $demaisHorasEstacionado = $horasEstacionado - 1;
            $carroModel->valor = $demaisHorasEstacionado * floatval($valorDemaisHoras);
            $carroModel->valor += floatval($valorPrimeiraHora);
        } else {
            $carroModel->valor = floatval($valorPrimeiraHora);
        }


        if($carroModel->delete()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao excluir carro"]);
        }
    }

    private function validarCampos($nome, $placa)
    {
        $erros = [];

        if (!isset($nome) || $nome == "") {
            $erros[] = "O campo nome é obrigatório";
        }

        if (!isset($placa) || $placa == "") {
            $erros[] = "O campo placa é obrigatório";
        }

        return $erros;
    }
}