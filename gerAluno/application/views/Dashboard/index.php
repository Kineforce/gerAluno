<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<header class="container-fluid">
    <h1>gerAluno - Dashboard</h1>
   
</header>
<main>
    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-alunos-tab" data-bs-toggle="pill" data-bs-target="#pills-aluno" type="button" role="tab" aria-controls="pills-aluno" aria-selected="true">Buscar alunos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-curso-tab" data-bs-toggle="pill" data-bs-target="#pills-curso" type="button" role="tab" aria-controls="pills-curso" aria-selected="false">Buscar cursos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="deslogar" type="button" role="tab" aria-selected="false">Logout</button>
    </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="container d-flex flex-column justify-content-center" id="container-home">
          <div class="row mx-auto">
            <h1 class="display-1">Bem vindo, <?=$usuario?>.</h1>
          </div>
          <div class="row justify-content-center">
            <button class="btn btn-primary w-25" type="button" data-toggle="collapse" data-target="#collapseToken" aria-expanded="false" aria-controls="collapseToken">
                Mostrar chave da API
            </button>
            <div class="collapse" id="collapseToken">
              <div class="card card-body w-50 mx-auto">
                 <span>Não compartilhe esta chave: <i class="span-token"><?=$token?></i></span>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-aluno" role="tabpanel" aria-labelledby="pills-aluno-tab">
        <div class="container" id="container-aluno">  
            <button class="btn btn-primary cadastra-aluno-btn">Cadastrar aluno</button> 
            <div class="table-responsive" style="width: 100%">            
                <table id="alunosTable" class="table table-striped table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr role="row">
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Matrícula</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Nome</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Curso</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1" hidden>Id_curso</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">CEP</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Rua</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Numero</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Bairro</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Cidade</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">Estado</th>
                            <th class="sorting" title="Ordenar" tabindex="0" aria-controls="alunosTable" rowspan="1" colspan="1">IBGE</th>                     
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot style="width: 100%">
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Curso</th>
                            <th hidden>Id_curso</ht>
                            <th>CEP</th>
                            <th>Rua</th>
                            <th>Numero</th>
                            <th>Bairro</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>IBGE</th>                      
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Modal - CRUD -->
            <div class="modal fade" id="modal_crud_aluno" tabindex="-1" role="dialog" aria-labelledby="modal_crud_aluno" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container">
                                <form id="input-atualiza-aluno">           
                                    <p class="text-center fs-3 titleModal" value="update">Atualizar dados do aluno</p>          
                                    <div class="form-group">
                                        <label for="nome">NOME</label>
                                        <input type="text" name="nome" class="form-control" value="" id="nome">
                                    </div>
                                    <div class="form-group">
                                        <label for="id_curso">CURSO</label>
                                        <select class="form-select" id="curso-atual">
                                            <option name="id_curso" id="default-curso" value="" selected></option>                                           
                                        </select>
                                        <!-- <input class="form-control" type="text" id="curso" placeholder="Id do curso"> -->
                                    </div>    
                                    <div class="form-group">
                                        <label>CEP
                                        <input name="cep" class="form-control" value="" type="text" id="cep" placeholder="ex: 72510-418" value="" size="10" maxlength="9" /></label>
                                        <button class="btn btn-secondary" id="btn_cep" onclick="recuperaCEP(event)">CEP</button><br />
                                        <label>RUA
                                        <input name="rua" class="form-control" value="" type="text" id="rua" size="60" /></label><br />
                                        <label>NUMERO
                                        <input name="numero" class="form-control" value="" type="text" id="numero" size="4" /></label><br />
                                        <label>BAIRRO
                                        <input name="bairro" class="form-control" value="" type="text" id="bairro" size="40" /></label><br />
                                        <label>CIDADE
                                        <input name="cidade" class="form-control" value="" type="text" id="cidade" size="40" /></label><br />
                                        <label>ESTADO
                                        <input name="estado" class="form-control" value="" type="text" id="uf" size="2" /></label><br />
                                        <label>IBGE
                                        <input name="ibge" class="form-control" value="" type="text" id="ibge" size="8" /></label><br />
                                    </div><br />
                                    <input type="hidden" name="matricula" id="matricula" value="">
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary" id="envia-dados-atualizados">Enviar</button>
                                        <button type="submit" class="btn btn-danger" id="deleta-aluno">Deletar Aluno</button>
                                    </div>
                                </form>  
                            </div>                                                                  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-curso" role="tabpanel" aria-labelledby="pills-curso-tab">
        <div class="container d-flex justify-content-center" id="container-aluno">
            <div class="table-responsive tbCurso" style="width: 100%"> 
            <button button class="btn btn-primary cadastra-curso-btn" onclick="cadastraCurso(event)">Cadastrar curso</button>            
                    <table id="cursosTable" class="table table-striped table-bordered table-hover" style="width: 100%">
                        <thead>
                            <tr role="row">
                                <th class="sorting" title="Ordenar" tabindex="0" aria-controls="cursosTable" rowspan="1" colspan="1">ID</th>
                                <th class="sorting" title="Ordenar" tabindex="0" aria-controls="cursosTable" rowspan="1" colspan="1">Nome</th>                                            
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot style="width: 100%">
                            <tr>
                                <th>Matrícula</th>
                                <th>Nome</th>                                           
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Modal - CRUD -->
                <div class="modal fade" id="modal_crud_curso" tabindex="-1" role="dialog" aria-labelledby="modal_crud_curso" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="container">
                                    <form id="input-atualiza-curso">           
                                        <p class="text-center fs-3">Atualizar dados do curso</p>          
                                        <div class="form-group">
                                            <label for="id_curso">NOME</label>
                                            <input type="text" name="nome_curso" class="form-control" value="" id="nome_curso">
                                        </div><br />                                   
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary" id="envia-dados-atualizados-curso">Enviar</button>
                                            <button type="submit" class="btn btn-danger" id="deleta-curso">Deletar Curso</button>
                                        </div>
                                    </form>  
                                </div>                                                                  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>




