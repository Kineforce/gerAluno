<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth_model extends CI_Model {


    /**
     * Método recebe um array como parâmetro contendo o usuário e senha vindo do input 
     */
    public function autenticarUsuario(Array $dados_usuario){


        // O método escape realiza uma verificação nas variáveis do input, evitando possíveis injeções SQL
        $usuario = $this->db->escape($dados_usuario['usuario']); 
        $pwd     = $this->db->escape($dados_usuario['pwd']);

        $query_autenticacao = $this->db->query("    SELECT  *
                                                    FROM    USUARIOS
                                                    WHERE   USUARIO =  $usuario
                                                    AND     PWD     =  $pwd");

        $resultado = $query_autenticacao->result();

        return $resultado;
    }

    /**
     * Método que recebe um token e usuário e verifica se eles batem com os que existem no banco
     */
    public function validaToken($received_token){

        $received_token   = $this->db->escape($received_token);

        $query_valida_token = $this->db->query("    SELECT  1
                                                    FROM    USUARIOS 
                                                    WHERE   api_key = $received_token");

        $resultado = $query_valida_token->result();
        
        return $resultado;

    }

}


