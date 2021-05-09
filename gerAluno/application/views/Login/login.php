<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-8 col-sm-8">
            <div class="card shadow">
                <div class="card-title text-center border-bottom">
                    <h2 class="p-3">Login</h2>
                </div>
                <div class="card-body">
                    <form id="login_form">
                        <div class="mb-4">
                            <label for="inputUsuario" class="form-label">Usuário</label>
                            <input type="text" name="usuario" class="form-control" id="inputUsuario">
                        </div>
                        <div class="mb-4">
                            <label for="inputPwd" class="form-label">Senha</label>
                            <input type="password" name="pwd" class="form-control" id="inputPwd">
                        </div>
                        <button type="submit" id="submit_btn" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_carregamento" tabindex="-1" role="dialog" aria-labelledby="modal_carregamento" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-header">
                    <span id="feedback_msg">Validando suas credenciais...</span>
                </div>
                <div class="modal-footer">
                    <i id="check_status"></i>
                    <div class="spinner-border" id="spinner_modal" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>


  
