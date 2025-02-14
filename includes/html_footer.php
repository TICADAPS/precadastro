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

    if (ValidaCPF($("#txtCpf").val())) {
      //ValidaCPFBanco();
    } else {
      $("#spValidaCpf").css("color", "red");
      $("#spValidaCpf").text("CPF inválido");
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
function keyupcpf(nr){
  $(`#cpfdep${nr}`).mask('000.000.000-00');
  let cpfdep = $(`#cpfdep${nr}`).val();
  if (ValidaCPF(cpfdep)) {
    $(`#spValidaCpfdep${nr}`).css("color", "green");
    $(`#spValidaCpfdep${nr}`).text("CPF válido");
  } else {
    $(`#spValidaCpfdep${nr}`).css("color", "red");
    $(`#spValidaCpfdep${nr}`).text("CPF inválido");
  }
}

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
    $("#blestrangeiro").hide();
    $("#divqtddep").hide();
    // Verifica se a variável de sessão já existe, caso contrário, cria e inicializa com 0
    if (sessionStorage.getItem('contadep') === null) {
        sessionStorage.setItem('contadep', 0);
        $("#contadep").val(0);
    }
});
//condição para preencher os campos relativos à estrangeiro
$("#nacionalidade").change(function(){
   let nacionalidade =  $("#nacionalidade").val();
   if(nacionalidade != null && nacionalidade != ''){
       if(nacionalidade != '10'){
           $("#blestrangeiro").show(400);
       }else{
           $("#blestrangeiro").hide(300);
       }
   }
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
//apresenta o campo de nr de dependente
function clickqtddep(){
    if($("#rddep1").is(':checked')){
        sessionStorage.setItem('contadep', 0);
        $("#divqtddep").show(400);
        $("#divdependente").html('');
        addBlocoDependente();
    }
    if($("#rddep2").is(':checked')){
       $("#divdependente").html('');
       $("#divqtddep").hide();
       sessionStorage.setItem('contadep', 0);
       $("#divdependente").html('');
       //console.log(document.querySelector("#divdependente"));
    }
}
//bloco de dependente 
function addBlocoDependente(){ //add campos em Qualificação Clínica
    let valorAtual = parseInt(sessionStorage.getItem('contadep'));  // Obtém o valor atual da variável
    let tipodep = [];
    let nomedep = [];
    let sexodep = [];
    let dtnascdep = [];
    let cpfdep = [];
    let idadedep = [];
    let pjdep = [];
    let declarairpfdep = [];
    // Obtém a data atual no formato YYYY-MM-DD
    let hoje = new Date().toISOString().split("T")[0];

    // preenchendo o laço pela primeira vez.
    if(valorAtual === 0){
        let i = 0;
        let html = '';
        // tipo de dependente
        html += `<fieldset id="fild${i}" class="mb-3 px-2"><legend><h5 class="font-weight-bold text-primary">${(i+1)}º de Dependente</h5></legend>`;
        html += '<div class="mb-3 divdep">';
        html += `<label for="tipodep${i}" class="form-label"><b class="text-danger">*</b> Tipo de dependente</label>`;
        html += `<select class="form-select" id="tipodep${i}" name="tipodep${i}">`;
        html += `<option value="" disabled selected>Escolha uma opção...</option>`;
        html += `<option value="1">Filho(a)</option>`;
        html += `<option value="2">Cônjuge</option>`;
        html += `<option value="3">Pai/Mãe</option>`;
//        html += `<option value="4">Avô/Avó</option>`;
//        html += `<option value="5">Bisavô(a)`;
//        html += `<option value="6">Sobrinho(a)`;
//        html += `<option value="7">Tio(a)`;
//        html += `<option value="8">Neto(a)`;
//        html += `<option value="9">Sogro(a)`;
//        html += `<option value="10">Genro/Nora`;
        html += `<option value="11">Enteado(a)`;
//        html += `<option value="12">Irmão(a)`;
        html += `<option value="13">Filho(a) Adotivo(a)`;
//        html += `<option value="14">Pensionistas`;
        html += `<option value="15">Companheiro(a)`;
        html += `<option value="16">Tutelado`;
        html += `<option value="17">Menor sob Guarda`;
        html += `<option value="18">Madrasta`;
        html += `<option value="19">Padrasto`;
        html += `<option value="20">Tutor`;
        html += `<option value="21">Ex-Esposo(a)`;
//        html += `<option value="22">Bisneto(a)`;
        html += `<option value="23">Ex-Companheiro(a)`;
//        html += `<option value="24">Concubino(a)`;
        html += `<option value="25">Curatelado`;
        html += `<option value="26">Pai/Mãe Socioafetivos`;
//        html += `<option value="99">Outros`;
        html += `</select>`;
        html += `</div>`;
        // Nome completo do dependente
        html += `<div class="mb-3">`;
        html += `<label for="nomedep${i}" class="form-label"><b class="text-danger">*</b> Nome completo do dependente</label>`;
        html += `<input type="text" class="form-control" id="nomedep${i}" name="nomedep${i}">`;
        html += `</div>`;
         // sexo do dependente
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Sexo</label>`;
        html += `<select class="form-select" id="sexodep${i}" name="sexodep${i}">`;
        html += `<option value="" disabled selected>Escolha uma opção...</option>`;
        html += `<option value="M">Masculino</option>`;
        html += `<option value="F">Feminino</option>`;
        html += `</select>`;
        html += `</div>`;
        // Data de nascimento do dependente
        html += `<div class="mb-3">`;
        html += `<label for="dtnascdep${i}" class="form-label"><b class="text-danger">*</b> Data de nascimento do dependente</label>`;
        html += `<input type="date" class="form-control" id="dtnascdep${i}" name="dtnascdep${i}" max="${hoje}" onchange="validaDataHoje(${i})" oninput="document.getElementById('idadedep${i}').value = calcularIdade(this.value)">`;
        html += `<span id="spdtnascdep${i}"></span>`;
        html += `</div>`;
        // CPF do dependente
        html += `<div class="mb-3">`;
        html += `<label for="cpfdep${i}" class="form-label"><b class="text-danger">*</b> CPF do dependente</label>`;
        html += `<input type="text" class="form-control" id="cpfdep${i}" name="cpfdep${i}" maxlength="14" placeholder="000.000.000-00" onkeyup="keyupcpf(${i})">`;
        html += `<b><span id="spValidaCpfdep${i}"></span></b>`;
        html += `</div>`;
        // Idade do dependente
        html += `<div class="mb-3">`;
        html += `<label for="idadedep${i}" class="form-label"><b class="text-danger">*</b> Idade do dependente</label>`;
        html += `<input type="text" class="form-control" id="idadedep${i}" name="idadedep${i}" maxlength="3" readonly>`;
        html += `</div>`;
        // Pensão Judicial
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Pensão Judicial?</label>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="pjdep1${i}" name="pjdep${i}" value="S">Sim`;
        html += `<label class="form-check-label" for="pjdep3${i}"></label>`;
        html += `</div>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="pjdep2${i}" name="pjdep${i}" value="N">Não`;
        html += `<label class="form-check-label" for="pjdep4${i}"></label>`;
        html += `</div>`;
        html += `</div>`;
        // Declara o dependente no IRPF
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Declara o dependente no IRPF?</label>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="declarairpfdep1${i}" name="declarairpfdep${i}" value="S">Sim`;
        html += `<label class="form-check-label" for="declarairpfdep3${i}"></label>`;
        html += `</div>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="declarairpfdep2${i}" name="declarairpfdep${i}" value="N">Não`;
        html += `<label class="form-check-label" for="declarairpfdep4${i}"></label>`;
        html += `</div>`;
        html += `</div>`;
        html += `</fieldset>`;
        $("#divdependente").html(html);
        valorAtual += 1;  // Incrementa +1
        sessionStorage.setItem('contadep', valorAtual);  // Atualiza a variável de sessão
    }else{
        for (let a = 0; a < valorAtual; a++) {
            tipodep.push($(`#tipodep${a}`).val());
            nomedep.push($(`#nomedep${a}`).val());
            sexodep.push($(`#sexodep${a}`).val());
            dtnascdep.push($(`#dtnascdep${a}`).val());
            cpfdep.push($(`#cpfdep${a}`).val());
            idadedep.push($(`#idadedep${a}`).val());
            if($(`#pjdep1${a}`).is(':checked')){
                pjdep.push("S");
            }
            if($(`#pjdep2${a}`).is(':checked')){
                pjdep.push("N");
            }
            if($(`#declarairpfdep1${a}`).is(':checked')){
                declarairpfdep.push("S");
            }
            if($(`#declarairpfdep2${a}`).is(':checked')){
                declarairpfdep.push("N");
            }
        }
        $("#divdependente").html('');
        let html = '';
        var i = null;
        for(i=0; i < valorAtual; i++){
            // tipo de dependente
            html += `<fieldset id="fild${i}" class="mb-3 px-2"><legend><h5 class="font-weight-bold text-primary">${(i+1)}º de Dependente</h5></legend>`;
            html += '<div class="mb-3 divdep">';
            html += `<label for="tipodep${i}" class="form-label"><b class="text-danger">*</b> Tipo de dependente</label>`;
            html += `<select class="form-select" id="tipodep${i}" name="tipodep${i}">`;
            html += `<option value="" disabled selected>Escolha uma opção...</option>`;
            html += `<option value="1" ${tipodep[i] === "1" ? "selected" : ""}>Filho(a)</option>`;
            html += `<option value="2" ${tipodep[i] === "2" ? "selected" : ""}>Cônjuge</option>`;
            html += `<option value="3" ${tipodep[i] === "3" ? "selected" : ""}>Pai/Mãe</option>`;
//            html += `<option value="4" ${tipodep[i] === "4" ? "selected" : ""}>Avô/Avó</option>`;
//            html += `<option value="5" ${tipodep[i] === "5" ? "selected" : ""}>Bisavô(a)`;
//            html += `<option value="6" ${tipodep[i] === "6" ? "selected" : ""}>Sobrinho(a)`;
//            html += `<option value="7" ${tipodep[i] === "7" ? "selected" : ""}>Tio(a)`;
//            html += `<option value="8" ${tipodep[i] === "8" ? "selected" : ""}>Neto(a)`;
//            html += `<option value="9" ${tipodep[i] === "9" ? "selected" : ""}>Sogro(a)`;
//            html += `<option value="10" ${tipodep[i] === "10" ? "selected" : ""}>Genro/Nora`;
            html += `<option value="11" ${tipodep[i] === "11" ? "selected" : ""}>Enteado(a)`;
//            html += `<option value="12" ${tipodep[i] === "12" ? "selected" : ""}>Irmão(a)`;
            html += `<option value="13" ${tipodep[i] === "13" ? "selected" : ""}>Filho(a) Adotivo(a)`;
//            html += `<option value="14" ${tipodep[i] === "14" ? "selected" : ""}>Pensionistas`;
            html += `<option value="15" ${tipodep[i] === "15" ? "selected" : ""}>Companheiro(a)`;
            html += `<option value="16" ${tipodep[i] === "16" ? "selected" : ""}>Tutelado`;
            html += `<option value="17" ${tipodep[i] === "17" ? "selected" : ""}>Menor sob Guarda`;
            html += `<option value="18" ${tipodep[i] === "18" ? "selected" : ""}>Madrasta`;
            html += `<option value="19" ${tipodep[i] === "19" ? "selected" : ""}>Padrasto`;
            html += `<option value="20" ${tipodep[i] === "20" ? "selected" : ""}>Tutor`;
            html += `<option value="21" ${tipodep[i] === "21" ? "selected" : ""}>Ex-Esposo(a)`;
//            html += `<option value="22" ${tipodep[i] === "22" ? "selected" : ""}>Bisneto(a)`;
            html += `<option value="23" ${tipodep[i] === "23" ? "selected" : ""}>Ex-Companheiro(a)`;
//            html += `<option value="24" ${tipodep[i] === "24" ? "selected" : ""}>Concubino(a)`;
            html += `<option value="25" ${tipodep[i] === "25" ? "selected" : ""}>Curatelado`;
            html += `<option value="26" ${tipodep[i] === "26" ? "selected" : ""}>Pai/Mãe Socioafetivos`;
//            html += `<option value="99" ${tipodep[i] === "99" ? "selected" : ""}>Outros`;
            html += `</select>`;
            html += `</div>`;
            // Nome completo do dependente
            html += `<div class="mb-3">`;
            html += `<label for="nomedep${i}" class="form-label"><b class="text-danger">*</b> Nome completo do dependente</label>`;
            html += `<input type="text" class="form-control" id="nomedep${i}" name="nomedep${i}" value="${nomedep[i]}">`;
            html += `</div>`;
            // sexo do dependente
            html += `<div class="mb-3">`;
            html += `<label for=""><b class="text-danger">*</b> Sexo</label>`;
            html += `<select class="form-select" id="sexodep${i}" name="sexodep${i}">`;
            html += `<option value="" disabled selected>Escolha uma opção...</option>`;
            html += `<option value="M" ${sexodep[i] === "M" ? "selected" : ""}>Masculino</option>`;
            html += `<option value="F" ${sexodep[i] === "F" ? "selected" : ""}>Feminino</option>`;
            html += `</select>`;
            html += `</div>`;
            // Data de nascimento do dependente
            html += `<div class="mb-3">`;
            html += `<label for="dtnascdep${i}" class="form-label"><b class="text-danger">*</b> Data de nascimento do dependente</label>`;
            html += `<input type="date" class="form-control" id="dtnascdep${i}" name="dtnascdep${i}" max="${hoje}" onchange="validaDataHoje(${i})" value="${dtnascdep[i]}" oninput="document.getElementById('idadedep${dtnascdep[i]}').value = calcularIdade(this.value)">`;
            html += `<span id="spdtnascdep${i}"></span>`;
            html += `</div>`;
            // CPF do dependente
            html += `<div class="mb-3">`;
            html += `<label for="cpfdep${i}" class="form-label"><b class="text-danger">*</b> CPF do dependente</label>`;
            html += `<input type="text" class="form-control" id="cpfdep${i}" name="cpfdep${i}" value="${cpfdep[i]}" maxlength="14" placeholder="000.000.000-00" onkeyup="keyupcpf(${cpfdep[i]})">`;
            html += `<b><span id="spValidaCpfdep${cpfdep[i]}"></span></b>`;
            html += `</div>`;
            // Idade do dependente
            html += `<div class="mb-3">`;
            html += `<label for="idadedep${i}" class="form-label"><b class="text-danger">*</b> Idade do dependente</label>`;
            html += `<input type="number" class="form-control" id="idadedep${i}" name="idadedep${i}" value="${idadedep[i]}" maxlength="3" readonly>`;
            html += `</div>`;
            // Pensão Judicial
            html += `<div class="mb-3">`;
            html += `<label for=""><b class="text-danger">*</b> Pensão Judicial?</label>`;
            html += `<div class="form-check">`;
            html += `<input type="radio" class="form-check-input" id="pjdep1${i}" name="pjdep${i}" value="S" ${pjdep[i] === "S" ? "checked" : ''}>Sim`;
            html += `<label class="form-check-label" for="pjdep3${i}"></label>`;
            html += `</div>`;
            html += `<div class="form-check">`;
            html += `<input type="radio" class="form-check-input" id="pjdep2${i}" name="pjdep${i}" value="N" ${pjdep[i] === "N" ? "checked" : ''}>Não`;
            html += `<label class="form-check-label" for="pjdep4${i}"></label>`;
            html += `</div>`;
            html += `</div>`;
            // Declara o dependente no IRPF
            html += `<div class="mb-3">`;
            html += `<label for=""><b class="text-danger">*</b> Declara o dependente no IRPF?</label>`;
            html += `<div class="form-check">`;
            html += `<input type="radio" class="form-check-input" id="declarairpfdep1${i}" name="declarairpfdep${i}" value="S" ${declarairpfdep[i] === "S" ? "checked" : ''}>Sim`;
            html += `<label class="form-check-label" for="declarairpfdep3${i}"></label>`;
            html += `</div>`;
            html += `<div class="form-check">`;
            html += `<input type="radio" class="form-check-input" id="declarairpfdep2${i}" name="declarairpfdep${i}" value="N" ${declarairpfdep[i] === "N" ? "checked" : ''}>Não`;
            html += `<label class="form-check-label" for="declarairpfdep4${i}"></label>`;
            html += `</div>`;
            html += `</div>`;
            html += `</fieldset>`;
        }
        html += `<fieldset id="fild${i}" class="mb-3 px-2"><legend><h5 class="font-weight-bold text-primary">${(i + 1)}º de Dependente</h5></legend>`;
        html += '<div class="mb-3 divdep">';
        html += `<label for="tipodep${i}" class="form-label"><b class="text-danger">*</b> Tipo de dependente</label>`;
        html += `<select class="form-select" id="tipodep${i}" name="tipodep${i}">`;
        html += `<option value="" disabled selected>Escolha uma opção...</option>`;
        html += `<option value="1">Filho(a)</option>`;
        html += `<option value="2">Cônjuge</option>`;
        html += `<option value="3">Pai/Mãe</option>`;
//        html += `<option value="4">Avô/Avó</option>`;
//        html += `<option value="5">Bisavô(a)`;
//        html += `<option value="6">Sobrinho(a)`;
//        html += `<option value="7">Tio(a)`;
//        html += `<option value="8">Neto(a)`;
//        html += `<option value="9">Sogro(a)`;
//        html += `<option value="10">Genro/Nora`;
        html += `<option value="11">Enteado(a)`;
//        html += `<option value="12">Irmão(a)`;
        html += `<option value="13">Filho(a) Adotivo(a)`;
//        html += `<option value="14">Pensionistas`;
        html += `<option value="15">Companheiro(a)`;
        html += `<option value="16">Tutelado`;
        html += `<option value="17">Menor sob Guarda`;
        html += `<option value="18">Madrasta`;
        html += `<option value="19">Padrasto`;
        html += `<option value="20">Tutor`;
        html += `<option value="21">Ex-Esposo(a)`;
//        html += `<option value="22">Bisneto(a)`;
        html += `<option value="23">Ex-Companheiro(a)`;
//        html += `<option value="24">Concubino(a)`;
        html += `<option value="25">Curatelado`;
        html += `<option value="26">Pai/Mãe Socioafetivos`;
//        html += `<option value="99">Outros`;
        html += `</select>`;
        html += `</div>`;
        // Nome completo do dependente
        html += `<div class="mb-3">`;
        html += `<label for="nomedep${i}" class="form-label"><b class="text-danger">*</b> Nome completo do dependente</label>`;
        html += `<input type="text" class="form-control" id="nomedep${i}" name="nomedep${i}">`;
        html += `</div>`;
         // sexo do dependente
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Sexo</label>`;
        html += `<select class="form-select" id="sexodep${i}" name="sexodep${i}">`;
        html += `<option value="" disabled selected>Escolha uma opção...</option>`;
        html += `<option value="M">Masculino</option>`;
        html += `<option value="F">Feminino</option>`;
        html += `</select>`;
        html += `</div>`;
        // Data de nascimento do dependente
        html += `<div class="mb-3">`;
        html += `<label for="dtnascdep${i}" class="form-label"><b class="text-danger">*</b> Data de nascimento do dependente</label>`;
        html += `<input type="date" class="form-control" id="dtnascdep${i}" name="dtnascdep${i}" max="${hoje}" onchange="validaDataHoje(${i})" oninput="document.getElementById('idadedep${i}').value = calcularIdade(this.value)">`;
        html += `<span id="spdtnascdep${i}"></span>`;
        html += `</div>`;
        // CPF do dependente
        html += `<div class="mb-3">`;
        html += `<label for="cpfdep${i}" class="form-label"><b class="text-danger">*</b> CPF do dependente</label>`;
        html += `<input type="text" class="form-control" id="cpfdep${i}" name="cpfdep${i}" maxlength="14" placeholder="000.000.000-00" onkeyup="keyupcpf(${i})">`;
        html += `<b><span id="spValidaCpfdep${i}"></span></b>`;
        html += `</div>`;
        // Idade do dependente
        html += `<div class="mb-3">`;
        html += `<label for="idadedep${i}" class="form-label"><b class="text-danger">*</b> Idade do dependente</label>`;
        html += `<input type="number" class="form-control" id="idadedep${i}" name="idadedep${i}" maxlength="3" readonly>`;
        html += `</div>`;
        // Pensão Judicial
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Pensão Judicial?</label>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="pjdep1${i}" name="pjdep${i}" value="S">Sim`;
        html += `<label class="form-check-label" for="pjdep3${i}"></label>`;
        html += `</div>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="pjdep2${i}" name="pjdep${i}" value="N">Não`;
        html += `<label class="form-check-label" for="pjdep4${i}"></label>`;
        html += `</div>`;
        html += `</div>`;
        // Declara o dependente no IRPF
        html += `<div class="mb-3">`;
        html += `<label for=""><b class="text-danger">*</b> Declara o dependente no IRPF?</label>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="declarairpfdep1${i}" name="declarairpfdep${i}" value="S">Sim`;
        html += `<label class="form-check-label" for="declarairpfdep3${i}"></label>`;
        html += `</div>`;
        html += `<div class="form-check">`;
        html += `<input type="radio" class="form-check-input" id="declarairpfdep2${i}" name="declarairpfdep${i}" value="N">Não`;
        html += `<label class="form-check-label" for="declarairpfdep4${i}"></label>`;
        html += `</div>`;
        html += `</div>`;
        html += `</fieldset>`;
        $("#divdependente").html(html);
        valorAtual += 1;  // Incrementa +1
        sessionStorage.setItem('contadep', valorAtual);  // Atualiza a variável de sessão
    }
}    
function validaDataHoje(x){
     // Obtém a data atual no formato YYYY-MM-DD
    let hoje = new Date().toISOString().split("T")[0];
    let inputData = document.getElementById("dtnascdep"+x);
    
    if (inputData.value > hoje) {
        inputData.value = hoje; // Reseta para a data máxima permitida
        document.getElementById('idadedep'+x).value = calcularIdade(hoje);
        $("#spdtnascdep"+x).html("Data corrigida! A data não pode exceder a de hoje.");
        $("#spdtnascdep"+x).css({
            "color": "orange",            // Cor da letra
            "-webkit-text-stroke": "0.2px black" // Contorno preto sem sombra
        });
    }else{
        $("#spdtnascdep"+x).html("");
    }
}
function validaDtHoje(inputId){
     // Obtém a data atual no formato YYYY-MM-DD
    let hoje = new Date().toISOString().split("T")[0];
    let inputData = document.getElementById(inputId);
    
    if (inputData.value > hoje) {
        inputData.value = hoje; // Reseta para a data máxima permitida
        $("#sp"+inputId).html("Data corrigida! A data não pode exceder a de hoje.");
        $("#sp"+inputId).css({
            "color": "orange",            // Cor da letra
            "-webkit-text-stroke": "0.2px black" // Contorno preto sem sombra
        });
    }else{
        $("#sp"+inputId).html("");
    }
}
function validaDataNasc(inputId) {
    let dataNascimento = document.getElementById(inputId).value;
    
    if (!dataNascimento) return; // Evita erro caso o campo esteja vazio

    let dataNasc = new Date(dataNascimento);
    let hoje = new Date();

    let idade = hoje.getFullYear() - dataNasc.getFullYear();
    let mes = hoje.getMonth() - dataNasc.getMonth();
    let dia = hoje.getDate() - dataNasc.getDate();

    // Ajusta a idade caso o aniversário ainda não tenha ocorrido este ano
    if (mes < 0 || (mes === 0 && dia < 0)) {
        idade--;
    }

    if (idade < 14) {
        $("#sp"+inputId).html("A idade mínima permitida é 14 anos!");
        $("#sp"+inputId).css({
            "color": "red",            // Cor da letra
            "-webkit-text-stroke": "0.2px black" // Contorno preto sem sombra
        });
        document.getElementById(inputId).value = ""; // Apaga a data inválida
    }else{
        $("#sp"+inputId).html("");
    }
}

        
//para saber a idade do dependente
function calcularIdade(dataNascimento) {
    if (!dataNascimento) return; // Evita erro caso o campo esteja vazio
    let dataNasc = new Date(dataNascimento);
    let hoje = new Date();
           
    let idade = hoje.getFullYear() - dataNasc.getFullYear();
    let mes = hoje.getMonth() - dataNasc.getMonth();
    let dia = hoje.getDate() - dataNasc.getDate();

    // Ajusta a idade se ainda não fez aniversário no ano atual
    if (mes < 0 || (mes === 0 && dia < 0)) {
        idade--;
    }
    return idade;
}
function subBlocoDependente(){ //add campos em Qualificação Clínica
    let valorAtual = parseInt(sessionStorage.getItem('contadep'));  // Obtém o valor atual da variável
    if(valorAtual > 0){
        $(`#fild${(valorAtual - 1)}`).html('');
        if(valorAtual === 1){
            valorAtual -= 1;  // Incrementa +1
            sessionStorage.setItem('contadep', valorAtual);  // Atualiza a variável de sessão
            $("#rddep2").prop('checked',true);
            $("#divdependente").html('');
            $("#divqtddep").hide();
        }else{
            valorAtual -= 1;  // Incrementa +1
            sessionStorage.setItem('contadep', valorAtual);  // Atualiza a variável de sessão
            $("#divdependente").show();
        }
        
    }
}
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
    }else{
        if(nacionalidade != '10'){
            let dtchegada = $("#dtchegada").val();
            if(dtchegada === null || dtchegada === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Data de Chegada ao Brasil\"</div>';
                $('#divValida').html(txt);
                return false;
            }
            let rne = $("#rne").val();
            if(rne === null || rne === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Registro Nacional de Estrangeiro - RNE\"</div>';
                $('#divValida').html(txt);
                return false;
            }
            let orgemissorrne = $("#orgemissorrne").val();
            if(orgemissorrne === null || orgemissorrne === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Órgão Emissor (RNE)\"</div>';
                $('#divValida').html(txt);
                return false;
            }
            let tipopermanencia = $("#tipopermanencia").val();
            if(tipopermanencia === null || tipopermanencia === ''){
                ok = false;
                txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Tipo de permanência\"</div>';
                $('#divValida').html(txt);
                return false;
            }
        }
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
    if(!$('#rddep1').is(':checked') && !$('#rddep2').is(':checked')){
        ok = false;
        txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Marque o campo \"Possui dependente?\"</div>';
        $('#divValida').html(txt);
        return false;
    }
    if($('#rddep1').is(':checked')){
        let valorAtual = parseInt(sessionStorage.getItem('contadep'));  // Obtém o valor atual da variável
        console.log(valorAtual);
        if(valorAtual > 0){
            var ok = true;
            //valida o preenchimento dos campos obrigatórios
            $('#msgsuccess').html('');
            $('#divValida').html('');
            let txt = '';
            for (let x = 0; x < valorAtual; x++){
                let tipodep = $(`#tipodep${x}`).val();
                if(tipodep === null || tipodep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Selecione o campo \"Tipo de dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let nomedep = $(`#nomedep${x}`).val();
                if(nomedep === null || nomedep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Nome completo do dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let sexodep = $(`#sexodep${x}`).val();
                if(sexodep === null || sexodep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Sexo do dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let dtnascdep = $(`#dtnascdep${x}`).val();
                if(dtnascdep === null || dtnascdep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Data de nascimento do dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let cpfdep = $(`#cpfdep${x}`).val();
                if(cpfdep === null || cpfdep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"CPF do dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                let idadedep = $(`#idadedep${x}`).val();
                if(idadedep === null || idadedep === ''){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Preencha o campo \"Data de nascimento do dependente\" para preenchimento automático do campo \"Idade do dependente\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                if(!$(`#pjdep1${x}`).is(':checked') && !$(`#pjdep2${x}`).is(':checked')){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Marque o campo \"Pensão Judicial?\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
                if(!$(`#declarairpfdep1${x}`).is(':checked') && !$(`#declarairpfdep2${x}`).is(':checked')){
                    ok = false;
                    txt = '<div class="alert alert-danger"><strong><i class="fas fa-chevron-circle-right"></i> </strong>&nbsp; Marque o campo \"Declara o dependente no IRPF\"</div>';
                    $('#divValida').html(txt);
                    return false;
                }
            }
            //atribuindo a qtd de dependente inseridos
            $("#contadep").val(valorAtual);
        }
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
    var dados = new FormData(formul);
    try {
        var response = await fetch('http://localhost:83/precadastro/views/controller/processo_cadastro.php', {
            method: 'POST',
            body: dados
        });

        var result = await response.json();
        //console.log(await result);
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
        document.getElementById("tipopermanencia").selectedIndex = 0;
        $('#radio3').prop('checked', false);
        $('#radio4').prop('checked', false);
        $('#radio5').prop('checked', false);
        $('#radio6').prop('checked', false);
        $('#rddep1').prop('checked', false);
        $('#rddep2').prop('checked', false);
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
        $('#dtchegada').val('');
        $('#orgemissorrne').val('');
        $("#divdependente").html("");
        $('#dseis').focus();
    }
</script>
</body>

</html>