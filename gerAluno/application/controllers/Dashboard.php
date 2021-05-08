<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Usuario_model');

    }

	/**
	 * Primeiro método a ser chamado quando o usuário entrar na página
	 * 
	 */
	public function index()
	{

        // Dashboard só estará disponível caso o usuário estiver autenticado
        if ($this->session->userdata('usuario_autenticado') !== "sim"){

            // Caso não esteja autenticado, redirecionar para a página de login
            customRedir('Login');

            // Certifica que a execução do código seja terminada
            exit();
        }

        // Carregando arquivos estáticos para a view
        $data_header = [

			'title' => 'Dashboard',
			'css_file' => 'dashboard.css',

		];

        $data_footer = [
            'js_file' => 'dashboard.js'
  
        ];

        $data = [
            'usuario' => $this->session->userdata('usuario'),
            'token'   => $this->session->userdata('token')
        ];

        // Carregando a view da página principal da aplicação
        $this->load->view('BootstrapSharing/header', $data_header);
        $this->load->view('Dashboard/index', $data);
        $this->load->view('BootstrapSharing/footer', $data_footer);

	}

    /**
     * Método invocado quando o usuário clica em deslogar
     */
    public function logout(){

        // O usuário só pode deslogar se ele estiver autenticado
        if ($this->session->userdata('usuario_autenticado') === "sim"){
            
            // Remove a variável da sessão
            $this->session->unset_userdata('usuario_autenticado');

            // Retorna um código com o status OK para que o Javascript dê um reload na página e seja realizado o redirect automático
            echo json_encode(http_response_code(200));
        
        }

    }

}
