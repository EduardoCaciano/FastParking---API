<?php

namespace App\Core;

class Router{

    private $controller;

    private $httpMethod = "GET";

    private $controllerMethod;

    private $params = [];

    function __construct(){
        
        //setando no header do responde o content-hype
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type");
        header("content-type: application/json");

        //recuperar a url que está sendo acessada
        $url = $this->parseURL();

        //se o controller existir dentro da pasta de controllers
        if(isset($url[1]) && file_exists("../App/Controller/" . $url[1] . ".php")){

            $this->controller = $url[1];
            unset($url[1]);
        }else {
            // print_r($url);
            echo "Algo deu errado";
            exit;
        }

        //importamos o controller
        require_once "../App/Controller/" . $this->controller . ".php";

        //instancia o controller
        $this->controller = new $this->controller;

        //pegando o HTTP Method
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];

        //pegando o método do controller baseando-se no http method
        switch($this->httpMethod){

            case "GET":
                if(!isset($url[2])){
                        $this->controllerMethod = "index";
                    }else{
                        http_response_code(400);
                        echo json_encode(["erro" => "Parâmetro inválido"], 
                        JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                
                break;
           
            case "POST":
           $this->controllerMethod = "store";
           break;
           
            case "PUT":
                $this->controllerMethod = "update";
                $this->getParams($url);
           break;
           
            case "DELETE":
           $this->controllerMethod = "delete";
           $this->getParams($url);
            break;
           
        }

        //executamos o método dentro do controller, passando os parametros
        call_user_func_array([$this->controller, $this->controllerMethod], $this->params);

    }

    //recuperar a URL e retornar os parametros
    private function parseURL(){
        return explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
    }

    private function getParams($url){
        if(isset($url[2]) && is_numeric($url[2])){
            $this->params = [$url[2]];
        }else{
            http_response_code(400); //400 bad request
            echo json_encode(["erro" => "Parâmetro inválido"], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}