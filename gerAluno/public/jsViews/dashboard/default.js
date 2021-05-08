// Quando o usuário clicar em deslogar, chamar método para remover sessão autenticada
$("#deslogar").on("click", () => {
  $.ajax({
    type: "GET",
    url: "logoutUser",
    success: (response) => {
      window.location.reload();
    },
    error: (err) => {
      alert(
        "Alguma coisa deu errada no processo de autenticação do seu usuário, por favor entre em contato com o administrador!"
      );
    },
  });
});

// Limpa valores do formulário de cep.
function limpa_formulário_cep() {
  $("#rua").val("");
  $("#bairro").val("");
  $("#cidade").val("");
  $("#uf").val("");
  $("#ibge").val("");
}

// Ao clicar nas tabs para consulta de alunos ou cursos, atualizar novamente as datatables

$("#pills-curso-tab").on("click", () => {
  if ($.fn.dataTable.isDataTable("#cursosTable")) {
    $("#cursosTable").DataTable().ajax.reload();
  }
});

$("#pills-alunos-tab").on("click", () => {
  if ($.fn.dataTable.isDataTable("#alunosTable")) {
    $("#alunosTable").DataTable().ajax.reload();
  }
});
