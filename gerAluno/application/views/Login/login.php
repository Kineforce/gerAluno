<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container justify-content-center login-container p-5">
    <h2>Login - gerAluno</h2>
    <div class="p-3">
        <form id="login_form">
        <div class="mw-50 mb-3">
            <label for="inputUsuario" class="form-label">Usuário</label>
            <input type="text" name="usuario" class="form-control" id="inputUsuario">
        </div>
        <div class="mw-50 mb-3">
            <label for="inputPwd" class="form-label">Senha</label>
            <input type="password" name="pwd" class="form-control" id="inputPwd">
        </div>
        <button type="submit" id="submit_btn" class="btn btn-primary">Enviar</button>
        </form>
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


  
