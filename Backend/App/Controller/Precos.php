<?php

use App\Core\Controller;

class Categorias extends Controller{

    public function index(){

        $categoriaModel = $this->model("Preco");

        $categorias = $categoriaModel->listarTodas();

        echo json_encode($categorias, JSON_UNESCAPED_UNICODE);
    }

    public function store(){

        //pegando o corpo da requisição, retona uma string
        $json = file_get_contents("php://input");

        //convertendo a string em objeto
        $novaPreco = json_decode($json);

        //instanciando o model
        $precoModel = $this->model("Preco");

        //atribuindo a descricao ao model
        $precoModel->descricao = $novaPreco->descricao;

        //chamando o método inserir do model
        $precoModel = $precoModel->inserir();

        //verificando se deu certo
        if($precoModel){
            //se deu certo, retornar a preco inserida
            http_response_code(201);
            echo json_encode($precoModel, JSON_UNESCAPED_UNICODE);

        }else{
            //se deu errado, mudar status code para 500 e retornar mensagem de erro
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir preco"]);
        }

    }

}