<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/mask.js"></script>
<script>
// Captura a data e hora local no formato compatível com MySQL
const now = new Date();
const dataHoraMysql = now.toISOString().slice(0, 19).replace('T', ' ');

// Exemplo de preenchimento em um campo hidden para envio
document.getElementById('dataHora').value = dataHoraMysql;
</script>
<style>
/* Destaca a opção selecionada */
select.form-select option:checked {
  background-color: #0d6efd;
  /* Cor de fundo personalizada (azul Bootstrap) */
  color: white;
  /* Texto branco para contraste */
}
</style>
<script>
// Atualiza o valor do código do país no campo oculto
function updateCountryCode() {
  const countrySelect = document.getElementById("countryCodeSelect");
  const hiddenInput = document.getElementById("countryCode");
  hiddenInput.value = countrySelect.value;
}
</script>
<script>
$('#txtCpf').mask('000.000.000-00'); //CPF
$('#telefone').mask('9.0000-0000'); //telefone
$('#ddd').mask('(00)'); //ddd
$('#cep').mask('00000-000'); //CEP

$("#txtCpf").focusout(function() {
  {
    if (ValidaCPF($("#txtCpf").val())) {
      //ValidaCPFBanco();
    } else {
      $("#spValidaCpf").css("color", "red");
      $("#spValidaCpf").text("CPF inválido");
    }
  }
});
$("#txtCpf").keyup(function() {
  if (ValidaCPF($("#txtCpf").val())) {
    $("#spValidaCpf").css("color", "green");
    $("#spValidaCpf").text("CPF válido");
    $("#txtCpfValido").val("1");
  } else {
    $("#spValidaCpf").css("color", "red");
    $("#spValidaCpf").text("CPF inválido");
    $("#txtCpfValido").val("2");
  }
});

function ValidaCPF(strCPF) {
  var arrayNumerosInvalidos = ["11111111111", "22222222222", "33333333333", "44444444444", "55555555555", "66666666666",
    "77777777777", "88888888888", "99999999999"
  ];
  var CPFDigitosValid = true;
  strCPF = strCPF.replaceAll(".", "");
  strCPF = strCPF.replaceAll("-", "");
  strCPF.trim(); //remover espaços

  for (var i = 0; i < arrayNumerosInvalidos.length; i++) {
    if (strCPF == arrayNumerosInvalidos[i]) {
      CPFDigitosValid = false;
    }
  }

  if (CPFDigitosValid) {

    //https://www.w3schools.com/jsref/jsref_trim_string.asp

    var Soma;
    var Resto;
    Soma = 0;
    if (strCPF == "00000000000")
      return false;
    for (i = 1; i <= 9; i++)
      Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
      Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)))
      return false;
    Soma = 0;
    for (i = 1; i <= 10; i++)
      Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
      Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11)))
      return false;
    return true;
  } else {
    return false;
  }
}

function ValidaCPFBanco() {

  var cpf = $("#txtCpf").val();

  if (cpf.length == 14) {
    $.ajax({
      url: "Action/UsuarioAction.php?req=3",
      data: {
        txtCPF: $("#txtCpf").val()
      },
      type: "POST",
      dataType: "text",
      success: function(retorno) {
        if (retorno == -1) {
          $("#spValidaCpf").text("CPF não está em uso");
          $("#spValidaCpf").css("color", "#39C462");
        } else if (retorno == 1) {
          $("#spValidaCpf").text("CPF já cadastrado ");
          $("#spValidaCpf").css("color", "#FF4500");
        } else {
          $("#spValidaCpf").text("");
          $("#spValidaCpf").css("color", "#FF3730");
        }

        console.log(retorno);
      },
      error: function(erro) {
        console.log(erro);
      }
    });
  } else {
    return -10;
  }
}

// Validação de e-mail
$('#email').on('input', function() {
  const email = $(this).val();
  const feedback = $('#emailFeedback');
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    feedback.text('E-mail inválido').show();
  } else {
    feedback.text('').hide();
  }
});
</script>

<script>
async function buscarEndereco(cep) {
  // Remove caracteres não numéricos do CEP
  cep = cep.replace(/\D/g, '');

  // Verifica se o CEP tem 8 dígitos
  if (cep.length === 8) {
    try {
      const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
      const data = await response.json();
      //console.log(data)
      // Verifica se a consulta retornou erro
      if (data.erro) {
        alert("CEP não encontrado.");
        limparCampos();
        return;
      }

      // Preenche os campos com os dados do endereço
      document.getElementById('endereco').value = data.logradouro || '';
      document.getElementById('bairro').value = data.bairro || '';
      document.getElementById('cidade').value = data.localidade || '';
      setSelectValue(data.uf);
    } catch (error) {
      alert("Erro ao buscar o endereço. Verifique o CEP e tente novamente.");
      limparCampos();
    }
  } else {
    limparCampos(); // Limpa os campos caso o CEP seja apagado ou incompleto
  }
}
function setSelectValue(a) {
  const select = document.getElementById('ufSelect'); // Obtém o select pelo ID
  select.value = a; // Define a opção selecionada pelo valor
}
</script>

<script>
// Seleciona os elementos de rádio e os inputs
const sl_conta = document.getElementById("slConta");
const obsRadioSim = document.getElementById("radio5");
const obsRadioNao = document.getElementById("radio6");
const obs = document.getElementById("obs");
const contaInputs = document.getElementById("contaInputs");
const casoNao = document.getElementById("casoNao");
const conveniadaText = document.getElementById('conveniadaText');
const particularText = document.getElementById('particularText');
const sl_afastado = document.getElementById('afastado');
const label_opt = document.getElementById('label_opt');
const examOption = document.getElementById('examOption');
const uploadExame = document.getElementById('uploadExame');
const comprovantePgto = document.getElementById('comprovantePgto');
const dt_retorno = document.getElementById('dt_retorno');

obsRadioSim.addEventListener("click", () => {
  obs.style.display = "none"; // oculta o pagrágrafo
});
obsRadioNao.addEventListener("click", () => {
  obs.style.display = "block"; // exibe o pagrágrafo
});

// evento para exibir a obrigatoriedade
sl_afastado.addEventListener("change", () => {
  if (sl_afastado.value === 'N') {
    label_opt.style.display = "block";
    dt_retorno.style.display = "none";
  } else {
    label_opt.style.display = "none";
    dt_retorno.style.display = "block";
  }
});

// Use o evento "change" para capturar mudanças no select da conta bancaria
sl_conta.addEventListener("change", () => {
  if (sl_conta.value === '1' || sl_conta.value === '3') {
    contaInputs.style.display = "block"; // Mostra os campos           
  } else if (sl_conta.value === 'nao') {
    contaInputs.style.display = "none"; // oculta os campos
    casoNao.style.display = "block"; // Exibe o radio
  }
});
// Use o evento "change" para capturar mudanças no select
examOption.addEventListener("change", () => {
  if (examOption.value === 'credenciada') {
    conveniadaText.style.display = "block";
    uploadExame.style.display = "block";
    particularText.style.display = "none";
    comprovantePgto.style.display = "none";
  } else if (examOption.value === 'particular') {
    particularText.style.display = "block";
    comprovantePgto.style.display = "block";
    conveniadaText.style.display = "none";
    uploadExame.style.display = "block";
  } else {
    // Reseta as exibições quando nenhuma opção está selecionada
    conveniadaText.style.display = "none";
    particularText.style.display = "none";
    uploadExame.style.display = "none";
    comprovantePgto.style.display = "none";
  }
});
</script>
<script>
$(document).ready(function(){
    $('#baster').html('<b class="text-danger" >*** </b> Faltou selecionar o DSEI no primeiro campo: Lista dos DSEIs elegíveis');
});

document.getElementById('dseis').addEventListener('change', function() {
  const dseiId = this.value;
  const organizacaoSelect = document.getElementById('organizacao');
  // Limpa as opções anteriores
  organizacaoSelect.innerHTML = '<option value="" disabled selected>Carregando...</option>';
  // retira o asterisco da obrigatoriedade do preenchimento do campo exame admissional qdo for dsei yanomami.
  if(dseiId !== '10'){
      $('#baster').html('<b class="text-danger" >*</b> Seu exame admissional foi realizado pela Clínica credenciada ou particular?');
  }else{
      $('#baster').html('<b class="text-danger" >&nbsp;</b> Seu exame admissional foi realizado pela Clínica credenciada ou particular?');
  } 
  // Faz a requisição AJAX para buscar as opções
  fetch(`get_organizacoes.php?dsei_id=${dseiId}`)
    .then(response => response.json())
    .then(data => {
      // Limpa o `select` e adiciona as novas opções
      organizacaoSelect.innerHTML = '<option value="" disabled selected>Escolha uma conveniada</option>';
      data.forEach(organizacao => {
        const option = document.createElement('option');
        option.value = organizacao.id;
        option.textContent = organizacao.nome_orgao;
        organizacaoSelect.appendChild(option);
      });
    })
    .catch(error => {
      console.error('Erro ao buscar as organizações:', error);
      organizacaoSelect.innerHTML = '<option value="" disabled>Erro ao carregar opções</option>';
    });
});

function validaCampos(){
    var ok = true;
    //valida o preenchimento dos campos obrigatórios
    $('#msgsuccess').html('');
    $('#divValida').html('');
    let txt = '';
    let dseis = $('#dseis').val();
    if(dseis === null || dseis === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Lista dos DSEIs elegíveis\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let organizacao = $('#organizacao').val();
    if(organizacao === null || organizacao === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Conveniada pela qual está contratado(a)\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let profissao = $('#profissao').val();
    if(profissao === null || profissao === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Cargo ocupado atualmente\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let afastado = $('#afastado').val();
    if(afastado === null || afastado === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Está afastado no momento? ...\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    if(afastado === 'N'){
        let dseis = $('#dseis').val();
        if(dseis !== '10'){
            let examOption = $('#examOption').val();
            if(examOption === null || examOption === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Seu exame admissional foi realizado pela Clínica credenciada ou particular?\"</div>';
                $('#divValida').html(txt);
                return false;
            }
            if(examOption === 'credenciada'){
                let exameAdmissional = $('#exameAdmissional').val();
                if(exameAdmissional === null || exameAdmissional === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Upload do Exame Admissional\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
            }
            if(examOption === 'particular'){
                let exameAdmissional = $('#exameAdmissional').val();
                if(exameAdmissional === null || exameAdmissional === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Upload do Exame Admissional\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let reciboPagamento = $('#reciboPagamento').val();
                if(reciboPagamento === null || reciboPagamento === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Upload do Recibo de Pagamento\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
            }
        }
    }
    if(afastado === 'AS'){
        let date_return = $('#date_return').val();
        if(date_return === null || date_return === ''){
            ok = false;
            txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Informe a data de retorno\"</div>';
            $('#divValida').html(txt);
            return false;
        }
    }
    if(afastado === 'LM'){
        let date_return = $('#date_return').val();
        if(date_return === null || date_return === ''){
            ok = false;
            txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Informe a data de retorno\"</div>';
            $('#divValida').html(txt);
            return false;
        }
    }
    let txtCpf = $('#txtCpf').val();
    if(txtCpf === null || txtCpf === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"CPF\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    if(ValidaCPF(txtCpf) === false){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; CPF Inválido - preencha o campo \"CPF\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let nome = $('#nome').val();
    if(nome === null || nome === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Nome completo\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let sexo = $('#sexo').val();
    if(sexo === null || sexo === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Sexo\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let etnia = $('#etnia').val();
    if(etnia === null || etnia === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Etnia/Raça/Cor\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let estado_civil = $('#estado_civil').val();
    if(estado_civil === null || estado_civil === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Estado civil\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let grau_instrucao = $('#grau_instrucao').val();
    if(grau_instrucao === null || grau_instrucao === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Grau de Instrução\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    
    let dt_nasc = $('#dt_nasc').val();
    if(dt_nasc === null || dt_nasc === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Data de nascimento\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let nacionalidade = $('#nacionalidade').val();
    if(nacionalidade === null || nacionalidade === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Nacionalidade\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let deficiente = $('#deficiente').val();
    if(deficiente === null || deficiente === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Deficiente Habilitado ou Reabilitado\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let email = $('#email').val();
    if(email === null || email === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"E-mail\"</div>';
        $('#divValida').html(txt);
        return false;
    }else{
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            ok = false;
            txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; E-mail inválido - Preencha o campo \"E-mail\"</div>';
            $('#divValida').html(txt);
            return false;
        }
    }
    let ddd = $('#ddd').val();
    if(ddd === null || ddd === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Discagem Direta à Distância - DDD\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let telefone = $('#telefone').val();
    if(telefone === null || telefone === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Telefone\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let endereco = $('#endereco').val();
    if(endereco === null || endereco === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Endereço\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let numero = $('#numero').val();
    if(numero === null || numero === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Número\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let bairro = $('#bairro').val();
    if(bairro === null || bairro === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Bairro\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let cidade = $('#cidade').val();
    if(cidade === null || cidade === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Cidade/Aldeia\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let ufSelect = $('#ufSelect').val();
    if(ufSelect === null || ufSelect === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"UF\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    let paises = $('#paises').val();
    if(paises === null || paises === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"País\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    
    let slConta = $('#slConta').val();
    if(slConta === null || slConta === ''){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Marque o campo \"Possui conta-corrente ou salário no Banco do Brasil?\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    if(slConta !== null && slConta !== ''){
        if(slConta === '1' || slConta === '3'){
            let agencia = $('#agencia').val();
            if(agencia === null || agencia === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Agência\"</div>';
                $('#divValida').html(txt);
                return false;
            }
            let contaCorrente = $('#contaCorrente').val();
            if(contaCorrente === null || contaCorrente === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Conta\"</div>';
                $('#divValida').html(txt);
                return false;
            }
        }
        if(slConta === 'nao'){
            $('#agencia').val(null);
            $('#contaCorrente').val(null);
        }
    }
    if(!$('#radio5').is(':checked') && !$('#radio6').is(':checked')){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Marque o campo \"Possui chave PIX vinculada ao CPF?\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    if(ok === true){
        enviarDados();
        submitForm();
    }
}
</script>
<script>
 async function submitForm() {
    $('#msgsuccess').html('');
    $('#divValida').html('');
    var txt = '';
    //envio do formulário preenchido
    var formul = document.getElementById('formCadastro');
    //console.log(form);
    var dados = new FormData(formul);
    //console.log(dados);
    try {
        var response = await fetch('https://agsusbrasil.org/precadastro/views/controller/processo_cadastro.php', {
            method: 'POST',
            body: dados
        });

        var result = await response.json();
        console.log(await result);
        if (result.success) {
            txt = `<div class="alert alert-success"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; ${result.message}</div>`;
            $('#divValida').html(txt);
            $('#msgsuccess').html(txt);
            setTimeout(() => {
                limpaCampos();
            }, 1500);
        } else {
            txt = `<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; ${result.message}</div>`;
            $('#divValida').html(txt);
        }
    } catch (error) {
        txt = `<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Erro: ${error}</div>`;
        $('#divValida').html(txt);
        console.error('Erro:', error);
    }
}
    function enviarDados() {
        // Mostra a barra de progresso
        $("#progress-container").show();
        
        let progressBar = $("#progress-bar");
        let progress = 0;

        // Simulação do progresso
        let interval = setInterval(() => {
            if (progress < 100) {
                progress += 10; // Incrementa 10%
                progressBar.css("width", progress + "%");
                progressBar.attr("aria-valuenow", progress);
                progressBar.text(progress + "%");
            } else {
                clearInterval(interval);
                progressBar.removeClass("progress-bar-animated progress-bar-striped");
                progressBar.addClass("bg-success");
                progressBar.text("Concluído!");
                
                // Oculta o #progress-container após 1 segundo
                setTimeout(() => {
                    $("#progress-container").css("display", "none") // ou use .css("display", "none")
                }, 500); // Espera 1 segundo antes de ocultar
            }
        }, 500); // Simula a cada 500ms
    }
    
    function limpaCampos(){
        document.getElementById("dseis").selectedIndex = 0;
        document.getElementById("organizacao").selectedIndex = 0;
        document.getElementById("profissao").selectedIndex = 0;
        document.getElementById("afastado").selectedIndex = 0;
        document.getElementById("examOption").selectedIndex = 0;
        document.getElementById("sexo").selectedIndex = 0;
        document.getElementById("etnia").selectedIndex = 0;
        document.getElementById("estado_civil").selectedIndex = 0;
        document.getElementById("grau_instrucao").selectedIndex = 0;
        document.getElementById("nacionalidade").selectedIndex = 0;
        document.getElementById("deficiente").selectedIndex = 0;
        document.getElementById("uf_rg").selectedIndex = 0;
        document.getElementById("countryCodeSelect").selectedIndex = 0;
        document.getElementById("ufSelect").selectedIndex = 0;
        document.getElementById("paises").selectedIndex = 0;
        document.getElementById("slConta").selectedIndex = 0;
        $('#radio3').prop('checked', false);
        $('#radio4').prop('checked', false);
        $('#radio5').prop('checked', false);
        $('#radio6').prop('checked', false);
        $('#date_return').val('');
        $('#exameAdmissional').val('');
        $('#reciboPagamento').val('');
        $('#txtCpf').val('');
        $('#nome').val('');
        $('#dt_nasc').val('');
        $('#email').val('');
        $('#nr_rg').val('');
        $('#emissor_rg').val('');
        $('#date_emissao_rg').val('');
        $('#ddd').val('');
        $('#telefone').val('');
        $('#cep').val('');
        $('#endereco').val('');
        $('#numero').val('');
        $('#bairro').val('');
        $('#cidade').val('');
        $('#agencia').val('');
        $('#digitoAg').val('');
        $('#contaCorrente').val('');
        $('#digitoConta').val('');
        $('#dseis').focus();
    }
</script>
</body>

</html>