// Quando o usuário clicar para buscar cursos, carregar dinamicamente na página a view
$("#pills-curso-tab").on("click", () => {
  if ($.fn.dataTable.isDataTable("#cursosTable")) {
    table = $("#cursosTable").DataTable();
  } else {
    $(document).ready(() => {
      // Adiciona um input de texto em cada coluna
      $("#cursosTable thead tr").clone(true).appendTo("#cursosTable thead");
      $("#cursosTable thead tr:eq(1) th").each(function (i) {
        var title = $(this).text();
        $(this).html(
          '<input type="text" placeholder="Buscar ' + title + '" />'
        );

        $("input", this).on("keyup change", function () {
          if (table.column(i).search() !== this.value) {
            table.column(i).search(this.value).draw();
          }
        });
      });

      let table = $("#cursosTable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        lengthMenu: [5, 10, 15, 20, 25, 50],
        select: true,
        select: {
          style: "single",
        },
        language: {
          searchPlaceholder: "Consulta dinâmica",
          select: {
            rows: "",
          },
          search: "",
          lengthMenu: "Mostrar _MENU_ por página ",
          zeroRecords: "Nenhum resultado encontrado",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "Não há registros disponíveis!",
          infoFiltered: "(filtrados _MAX_ do total)",
          paginate: {
            first: "<",
            last: ">",
            next: ">",
            previous: "<",
          },
        },
        ajax: `api/getCursos/${token}`,
        columns: [{ data: "id" }, { data: "nome" }],
      });

      $(".custom-select").removeClass("form-control");
      $(".custom-select").removeClass("form-control-sm");

      table.on("select", (e, dt, type, indexes) => {
        let resultado_linha_selecionada = dt.row({ selected: true }).data();
        crudModal_curso(resultado_linha_selecionada);
      });
    });
  }
});

// Função que carrega os dados na modal para edição
const crudModal_curso = (data) => {
  // Abrindo modal para input
  $("#modal_crud_curso").modal("toggle");

  // Carregar dados do registro atual nos inputs da modal

  let data_values = Object.values(data);

  // Carrega o nome do curso no input text e define o id do mesmo na session storage para uso posterior
  $("#nome_curso").val(data_values[1]);

  sessionStorage.setItem("id_curso", data_values[0]);
};

// Quando o usuário enviar os dados atualizados do curso
$("#envia-dados-atualizados-curso").on("click", (e) => {
  e.preventDefault();

  let array_data = {
    id: sessionStorage.getItem("id_curso"),
    nome: $("#nome_curso").val(),
  };

  $.ajax({
    url: `api/updateCurso/${token}`,
    type: "PUT",
    data: JSON.stringify(array_data),
    success: (response) => {
      // Atualiza datatable
      $("#cursosTable").DataTable().ajax.reload();

      // Fechando modal
      // $("#modal_crud_curso").hide();
      // $("body").removeClass("modal-open");
      // $(".modal-backdrop").remove();
      $("#modal_crud_curso").modal("hide");

      // Enviar mensagem de sucesso pro usuário
      Swal.fire({
        title: "Dados atualizados!",
        text: "",
        icon: "success",
      });
    },
    error: (response) => {
      console.log(response);
    },
  });
});

// Carregar os cursos quando o usuário abrir a tela de edição
$("#modal_crud_curso").on("shown.bs.modal", () => {
  let combo_box_cursos = $("#curso-atual");

  if (combo_box_cursos[0].childElementCount > 1) {
    return;
  }

  $.ajax({
    type: "GET",
    url: `api/getCursos/${token}`,
    success: (response) => {
      for (const [key, value] of Object.entries(response)) {
        if (key !== "data" && value.id !== sessionStorage.getItem("curso_id")) {
          let nova_opcao_curso = document.createElement("option");
          nova_opcao_curso.value = value.id;
          nova_opcao_curso.text = value.nome;
          combo_box_cursos.append(nova_opcao_curso);
        }
      }
    },
    error: (response) => {
      console.log(response);
    },
  });
});

// Função para deletar o curso
$("#deleta-curso").on("click", (e) => {
  e.preventDefault();

  let id_curso = sessionStorage.getItem("id_curso");

  let array_curso = { id: id_curso };

  Swal.fire({
    title: "Você tem certeza?",
    text:
      "A ação de deletar um curso implica em deletar TODOS os ALUNOS ligados a este CURSO",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sim, excluir!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "DELETE",
        url: `api/removecurso/${token}`,
        data: JSON.stringify(array_curso),
        success: (response) => {
          // Fechando modal, pois o curso não existe mais
          // $("#modal_crud_curso").hide();
          // $("body").removeClass("modal-open");
          // $(".modal-backdrop").remove();
          $("#modal_crud_curso").modal("hide");

          // Recarregando a datatable
          $("#cursosTable").DataTable().ajax.reload();

          Swal.fire("Excluido!", "Curso excluido com sucesso", "success");

          // Resetando dados do input da modal
          $("#input-atualiza-curso").trigger("reset");
          $("#matricula").val(null);
        },
        error: (err) => {
          console.log(err);
          Swal.fire(
            "Inesperado!",
            "Algo de errado aconteceu, tente novamente",
            "error"
          );
        },
      });
    }
  });
});

// Função para cadastrar um novo curso

async function cadastraCurso(e) {
  e.preventDefault();
  const { value: nome_curso } = await Swal.fire({
    title: "Insira o nome do novo curso",
    input: "text",
    inputLabel: "Nome do curso",
    inputPlaceholder: "Nome do curso",
    inputValidator: (value) => {
      if (!value) {
        return "Por favor, preencha o nome do curso!";
      }
    },
  });

  if (nome_curso) {
    $.ajax({
      type: "POST",
      url: `api/setCurso/${token}`,
      data: JSON.stringify({ nome: nome_curso }),
      success: (response) => {
        Swal.fire("Sucesso!", "Curso cadastrado com sucesso", "success");

        // Recarregando a datatable
        $("#cursosTable").DataTable().ajax.reload();
      },
      error: (err) => {
        console.log(err);
        Swal.fire(
          "Erro!",
          "Alguma coisa deu errada... Tente novamente",
          "error"
        );
      },
    });
  }
}
