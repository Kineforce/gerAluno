// Token a ser utilizado para consumir a API
let token = $(".span-token").text();

// Clonando estado inicial da Modal
let modal_init = $("#modal_crud_aluno").clone();

// Quando o usuário clicar para buscar alunos, carregar dinamicamente na página a view
$("#pills-alunos-tab").on("click", () => {
  if ($.fn.dataTable.isDataTable("#alunosTable")) {
    table = $("#alunosTable").DataTable();
  } else {
    $(document).ready(() => {
      // Adiciona um input de texto em cada coluna
      $("#alunosTable thead tr").clone(true).appendTo("#alunosTable thead");
      $("#alunosTable   thead tr:eq(1) th").each(function (i) {
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

      let table = $("#alunosTable").DataTable({
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
        ajax: `api/getAlunos/${token}`,
        columns: [
          { data: "matricula" },
          { data: "nome" },
          { data: "curso" },
          { data: "id_curso", visible: false },
          { data: "cep" },
          { data: "rua" },
          { data: "numero" },
          { data: "bairro" },
          { data: "cidade" },
          { data: "estado" },
          { data: "ibge" },
        ],
      });
      $(".custom-select").removeClass("form-control");
      $(".custom-select").removeClass("form-control-sm");

      table.on("select", (e, dt, type, indexes) => {
        let resultado_linha_selecionada = dt.row({ selected: true }).data();

        //$("#modal_crud_aluno").replaceWith(modal_init);

        // Modifica o estado da modal para fazer update
        $(".titleModal").text("Atualizar dados do aluno");
        $(".titleModal").val("update");

        // Habilita exclusão de aluno
        $("#deleta-aluno").show();

        // Define na sessão o ID do curso do aluno para ser usado posteriormente
        sessionStorage.setItem(
          "curso_id",
          resultado_linha_selecionada.id_curso
        );

        crudModal_aluno(resultado_linha_selecionada);
      });
    });
  }
});

// Função que carrega os dados na modal para edição
const crudModal_aluno = (data) => {
  // Abrindo modal para input
  //$("#modal_crud_aluno").modal("toggle");
  //$(".modal-backdrop").show();
  $("#modal_crud_aluno").modal("show");

  // Verifica se a modal está presente para cadastrar ou fazer update
  let option_up_or_set = $(".titleModal").val();

  // Select contendo os cursos
  let combo_box_cursos = $("#curso-atual");

  if (option_up_or_set === "update") {
    // Caso esteja vazio, popular

    // Limpa select
    $("#curso-atual option").each(function () {
      if ($(this).val() != "") {
        $(this).remove();
      }
    });

    populaSelectCurso();

    setTimeout(() => {
      let id_curso_select = sessionStorage.getItem("curso_id");

      $(`#curso-atual option[value=${id_curso_select}]`).attr(
        "selected",
        "selected"
      );
    }, 200);

    // Carregar dados do registro atual nos inputs da modal
    let modal_input = $("#input-atualiza-aluno :input");
    let data_keys = Object.keys(data);
    let data_values = Object.values(data);

    // Carrega o id_curso do usuário no select box
    //$("#default-curso").text(data_values[2]);
    //$("#default-curso").val(data_values[3]);

    for (let i = 0; i < modal_input.length; i++) {
      for (let j = 0; j < modal_input.length; j++) {
        if (modal_input[i].name !== "") {
          if (modal_input[i].name === data_keys[j]) {
            modal_input[i].value = data_values[j];
          }
        }
      }
    }
  }
};

//Quando o botão para retornar o CEP é clicado
const recuperaCEP = (e) => {
  e.preventDefault();
  //Nova variável "cep" somente com dígitos.
  var cep = $("#cep").val().replace(/\D/g, "");
  //Verifica se campo cep possui valor informado.
  if (cep != "") {
    //Expressão regular para validar o CEP.
    var validacep = /^[0-9]{8}$/;
    //Valida o formato do CEP.
    if (validacep.test(cep)) {
      //Preenche os campos com "..." enquanto consulta webservice.
      $("#rua").val("...");
      $("#bairro").val("...");
      $("#cidade").val("...");
      $("#uf").val("...");
      $("#ibge").val("...");
      //Consulta o webservice viacep.com.br/
      $.getJSON(
        "https://viacep.com.br/ws/" + cep + "/json/?callback=?",
        function (dados) {
          if (!("erro" in dados)) {
            //Atualiza os campos com os valores da consulta.
            $("#rua").val(dados.logradouro);
            $("#bairro").val(dados.bairro);
            $("#cidade").val(dados.localidade);
            $("#uf").val(dados.uf);
            $("#ibge").val(dados.ibge);
          } //end if.
          else {
            //CEP pesquisado não foi encontrado.
            limpa_formulário_cep();
            Swal.fire({
              title: "CEP não encontado!",
              icon: "error",
            });
          }
        }
      ).fail((err) => {
        // Caso a API esteja fora do ar, retornar mensagem de erro para o usuário
        if (err.status === 404) {
          alert("Desculpe! API para consulta de CEP indiponível no momento");
        }
      });
    } //end if.
    else {
      //cep é inválido.
      limpa_formulário_cep();
      alert("Formato de CEP inválido.");
    }
  } //end if.
  else {
    //cep sem valor, limpa formulário.
    limpa_formulário_cep();
  }
};

// Quando a modal fechar, chamar a função para resetar o formulário
$("#modal_crud_aluno").on("hidden.bs.modal", (e) => {
  limpa_formulário_cep();
});

// Carregar os cursos quando o usuário abrir a tela de edição
// $("#modal_crud_aluno").on("shown.bs.modal", () => {});

// Função para deletar o aluno
$("#deleta-aluno").on("click", (e) => {
  e.preventDefault();

  let matricula_aluno = $("#matricula").val();

  let array_aluno = { id: matricula_aluno };

  Swal.fire({
    title: "Você tem certeza?",
    text: "Esta ação irá excluir o aluno permanentemente!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sim, excluir!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "DELETE",
        url: `api/removeAluno/${token}`,
        data: JSON.stringify(array_aluno),
        success: (response) => {
          // Fechando modal, pois o aluno não existe mais
          // $("#modal_crud_aluno").hide();
          // $("body").removeClass("modal-open");
          // $(".modal-backdrop").remove();
          $("#modal_crud_aluno").modal("hide");

          // Recarregando a datatable
          $("#alunosTable").DataTable().ajax.reload();

          // Resetando dados do input da modal
          $("#input-atualiza-aluno").trigger("reset");
          $("#matricula").val(null);

          Swal.fire("Excluido!", "Aluno excluido com sucesso", "success");
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

const populaSelectCurso = (control) => {
  // Popular select dos cursos
  $.ajax({
    type: "GET",
    url: `api/getCursos/${token}`,
    success: (response) => {
      let input = $("#curso-atual")[0];

      // Limpa select
      $("#curso-atual option").each(function () {
        if ($(this).val() != "") {
          $(this).remove();
        }
      });

      response.data.forEach((item) => {
        let option_el = document.createElement("option");
        option_el.value = item.id;
        option_el.text = item.nome;

        input.append(option_el);
      });
    },
    error: (error) => {
      console.log(error);
    },
  });
};

// Abre modal para cadastrar o aluno

$(".cadastra-aluno-btn").on("click", (e) => {
  e.preventDefault();

  //$("#modal_crud_aluno").replaceWith(modal_init);
  $("#deleta-aluno").hide();

  // Resetando form ao abrir cadastro de aluno
  let form = $("#input-atualiza-aluno");
  form[0].reset();

  // Abre modal para cadastro do aluno
  $("#modal_crud_aluno").modal("show");

  populaSelectCurso("update");

  // Modificar modal para efetuar o cadastro, e não update
  $(".titleModal").text("Cadastrar aluno");
  $(".titleModal").val("cadastro");
});

// Botão para cadastrar ou atualizar o aluno
$("#envia-dados-atualizados").on("click", (e) => {
  e.preventDefault();
  let estado_modal = $(".titleModal").val();

  if (estado_modal === "cadastro") {
    let modal_input_cadastra = $("#input-atualiza-aluno :input");

    let array_data = {
      nome: modal_input_cadastra[0].value,
      id_curso: modal_input_cadastra[1].value,
      cep: modal_input_cadastra[2].value,
      rua: modal_input_cadastra[4].value,
      numero: modal_input_cadastra[5].value,
      bairro: modal_input_cadastra[6].value,
      cidade: modal_input_cadastra[7].value,
      estado: modal_input_cadastra[8].value,
      ibge: modal_input_cadastra[9].value,
    };

    if (array_data.nome == "") {
      Swal.fire("Por favor, preencha um nome para o aluno!", "", "error");
      return;
    }
    if (array_data.id_curso == "") {
      Swal.fire("Por favor, selecione um curso para o aluno!", "", "error");
      return;
    }

    $.ajax({
      type: "POST",
      url: `api/setAluno/${token}`,
      data: JSON.stringify(array_data),
      success: (resp) => {
        // Recarregando a datatable
        $("#alunosTable").DataTable().ajax.reload();
        Swal.fire("Cadastrado!", "Aluno cadastrado com sucesso", "success");
      },
      error: (error) => {
        Swal.fire("Erro!", "Curso não cadastrado!", "error");
      },
    });
  } else {
    let modal_input_atualiza = $("#input-atualiza-aluno :input");

    let array_data = {
      nome: modal_input_atualiza[0].value,
      id_curso: modal_input_atualiza[1].value,
      cep: modal_input_atualiza[2].value,
      rua: modal_input_atualiza[4].value,
      numero: modal_input_atualiza[5].value,
      bairro: modal_input_atualiza[6].value,
      cidade: modal_input_atualiza[7].value,
      estado: modal_input_atualiza[8].value,
      ibge: modal_input_atualiza[9].value,
      matricula: modal_input_atualiza[10].value,
    };

    if (array_data.nome == "") {
      Swal.fire("Por favor, preencha um nome para o aluno!", "", "error");
      return;
    }

    if (array_data.id_curso == "") {
      Swal.fire("Erro!", "Por favor, selecionar um curso!", "error");
      return;
    }

    $.ajax({
      url: `api/updateAluno/${token}`,
      type: "PUT",
      data: JSON.stringify(array_data),
      success: (response) => {
        // Atualiza datatable
        $("#alunosTable").DataTable().ajax.reload();

        // Fechando modal
        $("#modal_crud_aluno").modal("hide");

        // Enviar mensagem de sucesso pro usuário
        Swal.fire({
          title: "Dados atualizados!",
          text: "",
          icon: "success",
        });
      },
      error: (response) => {
        Swal.fire({
          title: "Erro!",
          text: "Curso não cadastrado!",
          icon: "error",
        });
      },
    });
  }
});
