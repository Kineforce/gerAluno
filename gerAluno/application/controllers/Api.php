<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use yidas\rest\Controller as REST_Controller;

class Api extends REST_Controller {
  
  private $method_error_response = [
    "error" => "Método não autorizado, rota destinada para "
  ];

  function __construct(){
    
    // Carregando models e helpers necessários para o funcionamento da API
    parent::__construct();

    $this->load->model('Auth_model');
    $this->load->model('Aluno_model');
    $this->load->model('Curso_model');
    $this->load->helper('valida_parametros_helper');
    $this->usuario = $this->session->userdata('usuario_autenticado');
    
    // Valida o token recebido no request
    $this->validaToken();
  }

  /**
   * Recebe como parâmetro um token vindo do request e valida se o mesmo existe no banco de dados
   * Todos os outros métodos da API implementam este método, ele está agindo como se fosse um middleware
   */
  public function validaToken(){

    $token = $this->uri->segment(3);

    $result_auth = $this->Auth_model->validaToken($token);

    if (!$result_auth){
      return $this->response->json([
        "error" => "Não autenticado!"
      ]);
    }

  }

  /**
   * Retorna todos os cursos presentes no banco de dados
   */
  public function getCursos(){
    if ($this->request->getMethod() !== "GET"){
      return $this->response->json($this->method_error_response + "GET", 401);
    }
    
    // Convertendo formato para o plugin DataTable receber corretamente os dados
    $cursos['data'] = $this->Curso_model->retornaCursos()->result();

    return $this->response->json($cursos, 200);
  }

  /**
   * Retorna todos os alunos presentes no banco de dados
   */
  public function getAlunos(){
    if ($this->request->getMethod() !== "GET"){
      return $this->response->json($this->method_error_response, 401);
    }

    // Convertendo formato para o plugin DataTable receber corretamente os dados
    $alunos['data'] = $this->Aluno_model->retornaAlunos()->result();

    return $this->response->json($alunos, 200);

  }

  /**
   * Registra um novo aluno com base nos parâmetros do corpo do request
   */
  public function setAluno(){
    if ($this->request->getMethod() !== "POST"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('nome', 'id_curso', 'cep', 'rua', 'numero','bairro', 'cidade', 'estado', 'ibge');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);

    if(!isset($validacao['success'])){
      return $this->response->json($validacao, 422);
    }

    $cadastra_aluno = $this->Aluno_model->cadastraAluno($dados_aluno);

    if(isset($cadastra_aluno['error'])){
      return $this->response->json([
        "error" => "Curso não existe!"
      ], 422);
    };  

    return $this->response->json([
      "success" => "Operação efetuada com sucesso!"
    ], 201);

  }

   /**
   * Registra um novo curso com base nos parâmetros do corpo do request
   */
  public function setCurso(){
    if ($this->request->getMethod() !== "POST"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('nome');
    $validacao = parameterValidation($param_requeridos, $dados_curso);


    if(!isset($validacao['success']))
    {
      return $this->response->json($validacao, 422);
    }

    $cadastra_curso = $this->Curso_model->cadastraCurso($dados_curso);

    if(!$cadastra_curso){
      return $this->response->json([
        "error" => "Erro desconhecido"
      ], 422);
    };  

    return $this->response->json([
      "success" => "Curso cadastrado com sucesso!"
    ], 201);

  }

   /**
   * Atualiza um curso existente com base ID do curso presente no corpo do request
   */
  public function updateCurso(){
    if ($this->request->getMethod() !== "PUT"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id', 'nome');
    $validacao = parameterValidation($param_requeridos, $dados_curso);

    if(!isset($validacao['success'])){
      return $this->response->json($validacao, 422);
    }

    $atualiza_curso = $this->Curso_model->atualizaCurso($dados_curso);

    if(!$atualiza_curso){
      return $this->response->json([
        "error" => "Curso não existe"
      ], 422);
    };  

    return $this->response->json([
      "success" => "Curso atualizado com sucesso!"
    ], 200);

  }

   /**
   * Atualiza um aluno existente com base na matŕicula presente no corpo do request
   */
  public function updateAluno(){
    if ($this->request->getMethod() !== "PUT"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);

    $param_requeridos = array('matricula', 'nome', 'id_curso', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'estado', 'ibge');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);


    if(!isset($validacao['success']))
    {
      return $this->response->json($validacao, 422);
    }

    $atualiza_aluno = $this->Aluno_model->atualizaAluno($dados_aluno);

    if(isset($atualiza_aluno['error'])){
      return $this->response->json($atualiza_aluno, 422);
    };  

    return $this->response->json([
      'sucess' => 'Operação efetuada com sucesso!'
    ], 200);

  }

   /**
   * Remove um curso existente com base no ID do curso presente do corpo do request
   */
  public function removeCurso(){
    if ($this->request->getMethod() !== "DELETE"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id');
    $validacao = parameterValidation($param_requeridos, $dados_curso);

    if(!isset($validacao['success']))
    {
      return $this->response->json($validacao, 422);
    }

    $deleta_curso = $this->Curso_model->deletaCurso($dados_curso);

    if(!$deleta_curso){
      return $this->response->json([
        "error" => "Curso não existe"
      ], 422);
    };  

    return $this->response->json([
      "success" => "Curso deletado com sucesso!"
    ], 200);

  }

  /**
   * Remove um aluno com base na matrícula presente no corpo do request
   */
  public function removeAluno(){
    if ($this->request->getMethod() !== "DELETE"){
      return $this->response->json($this->method_error_response, 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);

    if(!isset($validacao['success']))
    {
      return $this->response->json($validacao, 422);
    }

    $deleta_aluno = $this->Aluno_model->deletaAluno($dados_aluno);

    if(isset($deleta_aluno['error'])){
      return $this->response->json($deleta_aluno, 422);
    };  

    return $this->response->json([
      "success" => "Operação efetuada com sucesso!"
    ], 200);

  }

}
