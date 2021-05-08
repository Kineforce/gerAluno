// Listener que escuta o botão de enviar do formulário
$("#login_form").on("submit", (event) => {
  event.preventDefault();

  const { usuario, pwd } = event.target;

  // Validação para caso o usuário não preencha os campos
  if (usuario.value.length == 0 || pwd.value.length == 0) {
    return Swal.fire(
      "Erro!",
      "Por favor, preencha corretamente os campos!",
      "error"
    );
  }

  let data_form = { usuario: usuario.value, pwd: pwd.value };

  // Vamos abrir a modal do bootstrap
  $("#modal_carregamento").modal("toggle");

  // Botão confirmando status da autenticação
  let check_status = $("#check_status");

  // Spinner de carregamento
  let spinner_modal = $("#spinner_modal");
  spinner_modal.show();

  // Mensagem de feedback da autenticação
  let feedback_msg = $("#feedback_msg");

  $.ajax({
    type: "POST",
    url: "authUser",
    data: data_form,
    success: (response) => {
      // Após enviar o POST para o servidor com os dados do formulário, tratar a resposta e redirecionar o usuário
      let parsed_response = JSON.parse(response);
      if (parsed_response.status === "usuario_autenticado") {
        setTimeout(() => {
          // Animação para login não autenticado

          check_status.show();
          check_status.addClass("fa fa-check");
          check_status.css("color", "green");
          spinner_modal.hide();

          feedback_msg.text("Credenciais autenticadas!");

          setTimeout(() => {
            $("#modal_carregamento").modal("hide");
            window.location.replace("/Dashboard");
          }, 2000);
        }, 1000);
      }

      if (parsed_response.status === "usuario_nao_autenticado") {
        // Animação para login não autenticado

        setTimeout(() => {
          check_status.show();
          check_status.addClass("fa fa-times");
          check_status.css("color", "red");
          spinner_modal.hide();

          feedback_msg.text("Usuário ou senha incorretos!");
          setTimeout(() => {
            $("#modal_carregamento").modal("hide");
          }, 2000);
        }, 1000);
      }
    },
    error: (err) => {
      Swal.fire(
        "Erro!",
        "Por favor, entre em contato com o administrador do sistema!",
        "error"
      );
    },
  }).then(() => {
    // Resetando HTML
    check_status.hide();
    check_status.removeClass();
    feedback_msg.text("Validando suas credenciais...");
  });
});
