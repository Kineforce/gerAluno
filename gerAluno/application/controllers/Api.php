<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use yidas\rest\Controller as REST_Controller;

class Api extends REST_Controller {

  function __construct(){

    // Carregando models e helpers necessários para o funcionamento da API
    parent::__construct();
    $this->load->model('Auth_model');
    $this->load->model('Aluno_model');
    $this->load->model('Curso_model');
    $this->load->helper('valida_parametros_helper');
    $this->usuario = $this->session->userdata('usuario_autenticado');
  }

  /**
   * Recebe como parâmetro um token vindo do request e valida se o mesmo existe no banco de dados
   * Todos os outros métodos da API implementam este método, ele está agindo como se fosse um middleware
   */
  public function validaToken($token){

    $result_auth = $this->Auth_model->validaToken($token);

    if ($result_auth){
      return true;
    }

    return false;

  }

  /**
   * Retorna todos os cursos presentes no banco de dados
   */
  public function getCursos($token = null){

    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "GET"){
      return $this->response->json('nao_autorizado', 401);
    }
    

    $cursos = $this->Curso_model->retornaCursos()->result();
    // Convertendo formato para o plugin DataTable receber corretamente os dados
    $cursos['data'] = $cursos;

    return $this->response->json($cursos, 200);
  }

  /**
   * Retorna todos os alunos presentes no banco de dados
   */
  public function getAlunos($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "GET"){
      return $this->response->json('nao_autorizado', 401);
    }

    $alunos = $this->Aluno_model->retornaAlunos()->result();

    // Convertendo formato para o plugin DataTable receber corretamente os dados
    $alunos['data'] = $alunos;

    return $this->response->json($alunos, 200);

  }

  /**
   * Registra um novo aluno com base nos parâmetros do corpo do request
   */
  public function setAluno($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "POST"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('nome', 'id_curso', 'cep', 'rua', 'numero','bairro', 'cidade', 'estado', 'ibge');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);

    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $cadastra_aluno = $this->Aluno_model->cadastraAluno($dados_aluno);

    if(!$cadastra_aluno){
      return $this->response->json('erro_curso', 422);
    };  

    return $this->response->json('aluno_cadastrado', 201);

  }

   /**
   * Registra um novo curso com base nos parâmetros do corpo do request
   */
  public function setCurso($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "POST"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('nome');
    $validacao = parameterValidation($param_requeridos, $dados_curso);


    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $cadastra_curso = $this->Curso_model->cadastraCurso($dados_curso);

    if(!$cadastra_curso){
      return $this->response->json('erro_curso', 422);
    };  

    return $this->response->json('curso_cadastrado', 201);

  }

   /**
   * Atualiza um curso existente com base ID do curso presente no corpo do request
   */
  public function updateCurso($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "PUT"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id', 'nome');
    $validacao = parameterValidation($param_requeridos, $dados_curso);

    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $atualiza_curso = $this->Curso_model->atualizaCurso($dados_curso);

    if(!$atualiza_curso){
      return $this->response->json('curso_nao_existe', 422);
    };  

    return $this->response->json('curso_atualizado', 200);

  }

   /**
   * Atualiza um aluno existente com base na matŕicula presente no corpo do request
   */
  public function updateAluno($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "PUT"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);

    $param_requeridos = array('matricula', 'nome', 'id_curso', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'estado', 'ibge');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);


    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $atualiza_aluno = $this->Aluno_model->atualizaAluno($dados_aluno);

    if($atualiza_aluno !== true){
      return $this->response->json($atualiza_aluno, 422);
    };  

    return $this->response->json('aluno_atualizado', 200);

  }

   /**
   * Remove um curso existente com base no ID do curso presente do corpo do request
   */
  public function removeCurso($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "DELETE"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_curso = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id');
    $validacao = parameterValidation($param_requeridos, $dados_curso);

    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $deleta_curso = $this->Curso_model->deletaCurso($dados_curso);

    if(!$deleta_curso){
      return $this->response->json('curso_nao_existe', 422);
    };  

    return $this->response->json('curso_deletado', 200);

  }

  /**
   * Remove um aluno com base na matrícula presente no corpo do request
   */
  public function removeAluno($token = null){
    if ($this->validaToken($token) === false OR $this->request->getMethod() !== "DELETE"){
      return $this->response->json('nao_autorizado', 401);
    }

    $dados_aluno = json_decode($this->request->getRawBody(), true);
    $param_requeridos = array('id');
    $validacao = parameterValidation($param_requeridos, $dados_aluno);

    if($validacao !== 'validado')
    {
      return $this->response->json($validacao, 422);
    }

    $deleta_aluno = $this->Aluno_model->deletaAluno($dados_aluno);

    if(!$deleta_aluno){
      return $this->response->json('aluno_nao_existe', 422);
    };  

    return $this->response->json('aluno_deletado', 200);

  }

}
