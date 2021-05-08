<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Curso_model extends CI_Model {

    public function retornaCursos(){

        return $this->db->get('curso');

    }

    public function cadastraCurso(Array $dados_curso){

        return $this->db->insert('curso', $dados_curso);
    }

    public function atualizaCurso(Array $dados_curso){

        $id_curso            = $this->db->escape($dados_curso['id']);
        $novo_nome_curso     = $this->db->escape($dados_curso['nome']);

        $encontra_curso = $this->db->query("    SELECT  1
                                                FROM    CURSO 
                                                WHERE   id = $id_curso"
                                            );

        if (!$encontra_curso->result()){
            return;
        }                                            
        
        $this->db->query("  UPDATE  CURSO SET NOME = $novo_nome_curso
                            WHERE   ID = $id_curso"
                        );

        return true;
    }

    public function deletaCurso(Array $dados_curso){

        $id_curso = $this->db->escape($dados_curso['id'][0]);

        $encontra_curso = $this->db->query("    SELECT  1
                                                FROM    CURSO 
                                                WHERE   ID = $id_curso
                                            ");

        if (!$encontra_curso->result()){
            return;
        }

        $this->db->query("  DELETE FROM CURSO
                            WHERE   ID = $id_curso"
                        );

        $this->db->query("  DELETE FROM ALUNO
                            WHERE ID_CURSO = $id_curso"
                        );

        return true;

    }

}


