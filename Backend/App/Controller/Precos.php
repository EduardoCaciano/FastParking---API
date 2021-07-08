<?php

use App\Core\Controller;

class Precos extends Controller
{

    public function index()
    {

        $precoModel = $this->model("Preco");

        $precos = $precoModel->listarTodas();

        echo json_encode($precos, JSON_UNESCAPED_UNICODE);
    }

    public function store()
    {

        //pegando o corpo da requisição, retona uma string
        $novoPreco = $this->getRequestBody();

        //instanciando o model
        $precoModel = $this->model("Preco");

        //atribuindo a descricao ao model
        $precoModel->primeiraHora = $novoPreco->primeiraHora;
        $precoModel->demaisHoras = $novoPreco->demaisHoras;

        //chamando o método inserir do model
        $precoModel = $precoModel->salvar();

        //verificando se deu certo
        if ($precoModel) {
            //se deu certo, retornar a preco inserida
            http_response_code(201);
            echo json_encode($precoModel, JSON_UNESCAPED_UNICODE);
        } else {
            //se deu errado, mudar status code para 500 e retornar mensagem de erro
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir preco"]);
        }
    }

}
