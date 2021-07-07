<?php

use App\Core\Controller;

class Carros extends Controller
{

    public function index(){
        $carroModel = $this->Model("Carro");

        $carros = $carroModel->listAll();


        echo json_encode($carros, JSON_UNESCAPED_UNICODE);
    }

    public function inserir(){

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
        $carroModel = $carroModel->inserir();

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

    public function editar($id){

        $carroEditar = $this->getRequestBody();

        $carroModel = $this->model("Carro");

        $carroModel = $carroModel->buscarPorId($id);

        if(!$carroModel){
            http_response_code(404);
            echo json_encode(["erro" => "Carro não encontrada"]);
            exit;
        }

        $carroModel->descricao = $carroEditar->descricao;

        if($carroModel->atualizar()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao editar carro"]);
        }
    }

    public function deletar($id){

        $carroModel = $this->model("Carro");

        $carroModel = $carroModel->buscarPorId($id);

        if(!$carroModel){
            http_response_code(404);
            echo json_encode(["erro" => "carro não encontrada"]);
            exit;
        }

        $produtos = $carroModel->getProdutos();

        if($carroModel->deletar()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao excluir carro"]);
        }
    }
}