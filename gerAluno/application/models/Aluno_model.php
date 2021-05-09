<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Aluno_model extends CI_Model {

    public function retornaAlunos(){

        $resultado = $this->db->query(" SELECT  MATRICULA
                                                ,ALU.NOME
                                                ,CUR.NOME AS curso
                                                ,CUR.ID as id_curso
                                                ,CEP
                                                ,RUA
                                                ,NUMERO
                                                ,BAIRRO
                                                ,CIDADE
                                                ,ESTADO
                                                ,IBGE

                                         FROM   ALUNO AS ALU
                                         JOIN   CURSO AS CUR ON CUR.ID = ALU.ID_CURSO
                                                ");

        return $resultado;

    }
   
    public function cadastraAluno(Array $dados_aluno){

        $curso = $this->db->escape($dados_aluno['id_curso']);

        $valida_curso = $this->db->query("  SELECT  1
                                            FROM    CURSO
                                            WHERE   ID = $curso");

        if(!$valida_curso->result()){
            return false;
        }

        return $this->db->insert('aluno', $dados_aluno);
    }

    public function atualizaAluno(Array $dados_aluno){

        $matricula_aluno         = $this->db->escape($dados_aluno['matricula']);
        $novo_nome_aluno         = $this->db->escape($dados_aluno['nome']);
        $novo_id_curso           = $this->db->escape($dados_aluno['id_curso']);
        $novo_cep                = $this->db->escape($dados_aluno['cep']);
        $nova_rua                = $this->db->escape($dados_aluno['rua']);
        $novo_numero             = $this->db->escape($dados_aluno['numero']);
        $novo_bairro             = $this->db->escape($dados_aluno['bairro']);
        $nova_cidade             = $this->db->escape($dados_aluno['cidade']);
        $novo_estado             = $this->db->escape($dados_aluno['estado']);
        $novo_ibge               = $this->db->escape($dados_aluno['ibge']);

        $encontra_curso = $this->db->query("    SELECT  1
                                                FROM    CURSO 
                                                WHERE   id = $novo_id_curso"
                                            ); 
                                            
        $encontra_aluno = $this->db->query("    SELECT  1
                                                FROM    ALUNO 
                                                WHERE   matricula = $matricula_aluno"
                                            );                                                 

        if (!$encontra_curso->result()){
            return 'curso_nao_existe';
        }                              
        
        if (!$encontra_aluno->result()){
            return 'aluno_nao_existe';
        }
        
        $this->db->query("  UPDATE  ALUNO SET NOME      = $novo_nome_aluno
                                             ,ID_CURSO  = $novo_id_curso
                                             ,CEP       = $novo_cep
                                             ,RUA       = $nova_rua
                                             ,NUMERO    = $novo_numero
                                             ,BAIRRO    = $novo_bairro
                                             ,CIDADE    = $nova_cidade
                                             ,ESTADO    = $novo_estado
                                             ,IBGE      = $novo_ibge
                                             
                            WHERE   MATRICULA = $matricula_aluno"
                        );

        return true;
    }

    public function deletaAluno(Array $dados_curso){

        $matricula_aluno = $this->db->escape($dados_curso['id']);



        $encontra_aluno = $this->db->query("    SELECT  1
                                                FROM    ALUNO 
                                                WHERE   MATRICULA = $matricula_aluno"
                                            );

        if (!$encontra_aluno->result()){
            return;
        }

        $this->db->query("  DELETE FROM ALUNO
                            WHERE  MATRICULA = $matricula_aluno"
                        );

        return true;

    }


}


