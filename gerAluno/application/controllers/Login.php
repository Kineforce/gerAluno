<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {

        parent::__construct();
        $this->load->model('Auth_model');

    }
	
	/**
	 * Primeiro método a ser chamado quando o usuário entrar na página
	 * 
	 */
	public function index()
	{

		// Caso o usuário já esteja autenticado, não tem motivo para ele voltar a tela de login
		if ($this->session->userdata('usuario_autenticado') === "sim"){
			customRedir('Dashboard');
			exit();
		}	
		
		// Carregando arquivos estáticos para a view
		$data_header = [

			'title' => 'Login',
			'css_file' => 'login.css',
		];

		$data_footer = [
			'js_file' => 'login.js'

		];

		// Carregando a view para o usuário efetuar a autenticação
		$this->load->view('BootstrapSharing/header', $data_header);
		$this->load->view('Login/login');
		$this->load->view('BootstrapSharing/footer', $data_footer);
	}

	/**
	 * Método recebe os dados do input e realiza a autenticação dos mesmos
	 */
	public function authUser(){

		// Tratando e recebendo input do formulário
		$usuario = htmlspecialchars($this->input->post('usuario'));
		$pwd     = htmlspecialchars($this->input->post('pwd')) ;


		// Array de controle 
		$response_array['status'] = array();

		// Validando se o input não veio vazio
		if (empty($usuario) && empty($pwd)){
			$response_array['status'] = 'input_vazio';
			echo json_encode($response_array);
			return;
		} 
		
		$dados_usuario['usuario'] = $usuario;
		$dados_usuario['pwd'] 	  = $pwd;

		$autentica_usuario = $this->Auth_model->autenticarUsuario($dados_usuario);

		// Caso a consulta ao banco retorne algum resultado, significa que o usuário está autenticado
		if ($autentica_usuario){
			$response_array['status'] = 'usuario_autenticado';

			// Caso o usuário esteja autenticado, definimos uma variável na sessão indicando o estado
			$this->session->set_userdata('usuario_autenticado', 'sim');
			$this->session->set_userdata('usuario', ucwords($autentica_usuario[0]->usuario));
			$this->session->set_userdata('token', $autentica_usuario[0]->api_key);

			echo json_encode($response_array);
			return;
		}	

		// Consulta não retorna nada, usuário não autenticado
		$response_array['status'] = 'usuario_nao_autenticado';
		
		echo json_encode($response_array);
		return;
	}

}
