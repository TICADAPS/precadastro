<?php
session_start();

// querys de consulta a base
$query_orgao = "SELECT * FROM orgaos;";
$query_profissao = "SELECT * FROM profissoes;";
$query_candidato = "SELECT * FROM candidatos";
$query_dseis = "SELECT * FROM dseis";
$query_paises = "SELECT * FROM paises";
$query_etnia = "SELECT * FROM etnia";
$query_nacionalidade = "SELECT * FROM nacionalidade";
$query_estado_civil = "SELECT * FROM estado_civil";
$query_grau_instrucao = "SELECT * FROM grau_instrucao";

// instância do banco
$db = new database();

$result_query1 = $db->EXE_QUERY($query_orgao);
$result_query2 = $db->EXE_QUERY($query_profissao);
$result_query3 = $db->EXE_QUERY($query_candidato);
$result_query4 = $db->EXE_QUERY($query_dseis);
$result_query5 = $db->EXE_QUERY($query_paises);
$result_query6 = $db->EXE_QUERY($query_etnia);
$result_query7 = $db->EXE_QUERY($query_nacionalidade);
$result_query8 = $db->EXE_QUERY($query_estado_civil);
$result_query9 = $db->EXE_QUERY($query_grau_instrucao);


// gerar um numero rendomico
define("INFERIOR", 000001);
define("SUPERIOR", 100000);
$rand = rand(INFERIOR, SUPERIOR);

// declaração de variáveis
$erro = 0;
$uploadOk = 1;
$uploadOk2 = 1;
$data = date('Y-m-d H:i:s');
?>
<div class="container-fluid my-5">
  <div class="row justify-content-center">
    <div class="d-flex flex-row flex-wrap justify-content-center">
      <div class="form-container">
        <!-- inicio do formulário -->
        <h3 class="text-center">Formulário de Pré-cadastro para Admissão na AgSUS</h3>
        <h3 class="text-center p-2 titulo">Boas Vindas</h3>
        <div class="cards p-2">
          <p>Prezados(as) Trabalhador(a),</p>
          <p><b>Seja muito bem-vindo(a) ao ambiente virtual da Agência Brasileira de Apoio à Gestão do SUS
              (AgSUS)!</b><br>É com grande satisfação que damos início, juntos, a esta nova etapa em nossa missão de
            apoio ao Sistema Único de Saúde.</p>
          <p><b>ATENÇÃO:</b></p>
          <p><b>Reforçamos que este formulário é destinado exclusivamente ao pré-cadastro de trabalhadores(as), não
              constituindo, nesta etapa, vínculo empregatício.</b></p>
          <P><b>Para realizar seu pré-cadastro, é imprescindível realizar o exame admissional em uma clínica do trabalho
              credenciada com a Agência ou na clínica mais próxima da sua localização.</b></P>
          <p>A AgSUS respeita a sua privacidade e se compromete a proteger os seus dados pessoais em conformidade com a
            LGPD. Após o cumprimento da finalidade para a qual foram coletados, todos os dados fornecidos serão
            eliminados de maneira segura, em conformidade com a legislação vigente.</p>
          <P>Acesse aqui a lista de <a href="views/conveniadas.pdf" target="__blank"> Clínicas credenciadas com a
              AgSUS:</a></P>
          <p>Caso opte por realizar o exame admissional em uma clínica de sua preferência, será necessário apresentar o
            recibo de pagamento para solicitar o reembolso, no valor máximo de R$ 60,00. </p>
          <p>VAMOS COMEÇAR?</p>
        </div>
        <!-- início do formulário -->
        <h3 class="text-center mb-4 p-2 titulo">Cadastro</h3>
        <div id="msgsuccess"></div>
        <!--<form method="post" action="<?php // echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data"-->
        <form id="formCadastro" enctype="multipart/form-data">
          <input type="hidden" id="dataHora" name="data_hora">
          <!-- Lista dos DSEIs -->
          <div class="mb-3">
            <label for="dseis" class="form-label"><b class="text-danger">*</b> Lista dos DSEIs elegíveis</label>
            <select class="form-select" id="dseis" name="dseis">
              <option value="" disabled selected>Escolha um DSEI</option>
              <?php
                            foreach ($result_query4 as $dsei) {
                                echo "<option value=" . $dsei['id'] . ">" . $dsei['nome_dsei'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- Lista das credenciada -->
          <div class="mb-3">
            <label for="sl_conveniada" class="form-label"><b class="text-danger">*</b> Conveniada pela qual está
              contratado(a)</label>
            <select class="form-select" id="organizacao" name="organizacao">
              <option value="" disabled selected>Escolha uma conveniada</option>
            </select>
          </div>
          <!-- Lista de cargos -->
          <div class="mb-3">
            <label for="sl_cargo" class="form-label"><b class="text-danger">*</b> Cargo ocupado atualmente</label>
            <select class="form-select" id="profissao" name="profissao">
              <option value="" disabled selected>Escolha uma profissão</option>
              <?php
                            foreach ($result_query2 as $profissoes) {
                                echo "<option value=" . $profissoes['id'] . ">" . $profissoes['nome_profissao'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- Lista de afastado no momento -->
          <div class="mb-3">
            <label for="afastado" class="form-label"><b class="text-danger">*</b> Está afastado no momento? Se o seu
              afastamento for por motivo de saúde, por favor, informe apenas se for superior a 15 dias e se estiver
              vinculado ao INSS.</label>
            <select class="form-select" id="afastado" name="afastado">
              <option value="" disabled selected>Escolha uma opção...</option>
              <option value="N">Não</option>
              <option value="AS">Afastamento por Motivo de Saúde superior a 15 dias (vinculado ao INSS)</option>
              <option value="LM">Licença Maternidade</option>
            </select>
          </div>
          <!-- Data de retorno do afastamento -->
          <div class="mb-3" id="dt_retorno" style="display: none;">
            <label for="date_return" class="form-label"><b class="text-danger">*</b> Informe a data de retorno</label>
            <input type="date" class="form-control" id="date_return" name="date_return">
          </div>
          <!-- lista de conveniada -->
          <div class="mb-3" id="label_opt" style="display: none;">
            <label for="examOption" class="form-label">
              <b class="text-danger">*</b> Seu exame admissional foi realizado pela Clínica credenciada ou particular?
            </label>
            <select class="form-select mb-2" id="examOption" name="clinica">
              <option value="" selected>Selecione uma opção</option>
              <option value="credenciada">Credenciada</option>
              <option value="particular">Particular</option>
            </select>
            <!-- Texto para opção Conveniada -->
            <div id="conveniadaText" class="alert alert-info" style="display: none;">
              Você deverá inserir aqui o Exame Admissional.
            </div>
            <!-- Texto para opção Particular -->
            <div id="particularText" class="alert alert-warning" style="display: none;">
              Você deverá inserir aqui o Exame Admissional e o Recibo de Pagamento para o ressarcimento.
            </div>
            <!-- Campo de upload para Exame Admissional -->
            <div class="mb-3" id="uploadExame" style="display: none;">
              <label for="exameAdmissional" class="form-label">
                <b class="text-danger">*</b> Upload do Exame Admissional
              </label>
              <input type="file" class="form-control" id="exameAdmissional" name="exameAdmissional">
            </div>
            <!-- Campo de upload para Recibo de Pagamento -->
            <div class="mb-3" id="comprovantePgto" style="display: none;">
              <label for="reciboPagamento" class="form-label">
                <b class="text-danger">*</b> Upload do Recibo de Pagamento
              </label>
              <input type="file" class="form-control" id="reciboPagamento" name="reciboPagamento">
            </div>
          </div>
          <!-- campo do cpf -->
          <div class="mb-3">
            <label for="cpf" class="form-label"><b class="text-danger">*</b> CPF </label>
            <input type="text" class="form-control" id="txtCpf" name="cpf" maxlength="14" placeholder="000.000.000-00">
            <input type="hidden" name="CpfValido" class="form-control input_user" id="txtCpfValido" value="">
            <b><span id="spValidaCpf"></span></b>
          </div>
          <!-- campo do nome completo -->
          <div class="mb-3">
            <label for="nome" class="form-label"><b class="text-danger">*</b> Nome completo</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome">
          </div>
          <!-- Lista de sexo -->
          <div class="mb-3">
            <label for="sl_sexo" class="form-label"><b class="text-danger">*</b> Sexo</label>
            <select class="form-select" id="sexo" name="sexo">
              <option value="" disabled selected>Escolha um sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </select>
          </div>
          <!-- Lista de etnia -->
          <div class="mb-3">
            <label for="etnia" class="form-label"><b class="text-danger">*</b> Etnia/Raça/Cor</label>
            <select class="form-select" id="etnia" name="etnia">
              <option value="" disabled selected>Escolha sua etnia</option>
              <?php foreach ($result_query6 as $raca) {
                                echo "<option value=" . $raca['id'] . ">" . $raca['descricao'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- Lista de estado civil -->
          <div class="mb-3">
            <label for="sl_estado_civil" class="form-label"><b class="text-danger">*</b> Estado civil</label>
            <select class="form-select" id="estado_civil" name="estado_civil">
              <option value="" disabled selected>Escolha seu estado civil</option>
              <?php
                            foreach ($result_query8 as $est_civ) {
                                echo "<option value=" . $est_civ['id'] . ">" . $est_civ['descrcao'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- Lista de grau de instrução -->
          <div class="mb-3">
            <label for="grau_instrucao" class="form-label"><b class="text-danger">*</b> Grau de Instrução</label>
            <select class="form-select" id="grau_instrucao" name="grau_instrucao">
              <option value="" disabled selected>Selecione o grau de instrução...</option>
              <?php
                            foreach ($result_query9 as $grau) {
                                echo "<option value=" . $grau['id'] . ">" . $grau['descricao'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="dt_nasc" class="form-label"><b class="text-danger">*</b> Data de nascimento </label>
            <input type="date" class="form-control" id="dt_nasc" name="dt_nasc" placeholder="dd-mm-aaaa">
          </div>
          <!-- Lista de nascionalidade -->
          <div class="mb-3">
            <label for="nacionalidade" class="form-label"><b class="text-danger">*</b> Nacionalidade</label>
            <select class="form-select" id="nacionalidade" name="nacionalidade">
              <option value="" disabled selected>Selecione sua nacionalidade...</option>
              <?php
                            foreach ($result_query7 as $nacional) {
                                echo "<option value=" . $nacional['id'] . ">" . $nacional['descricao'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- Lista de deficiente -->
          <div class="mb-3">
            <label for="deficiente" class="form-label"><b class="text-danger">*</b> Deficiente Habilitado ou
              Reabilitado</label>
            <select class="form-select" id="deficiente" name="deficiente">
              <option value="" disabled selected>Escolha uma opção...</option>
              <option value="S">Sim</option>
              <option value="N">Não</option>
            </select>
          </div>
          <!-- campo do email -->
          <div class="mb-3">
            <label for="email" class="form-label"><b class="text-danger">*</b> E-mail</label>
            <input type="email" class="form-control" id="email" name="email" 
              placeholder="Digite seu e-mail">
            <span id="emailFeedback" class="error-message"></span>
          </div>
          <!-- campo do registro geral RG -->
          <div class="mb-3 row">
            <div class="col-md-3 col-12">
              <label for="nr_rg" class="form-label"> RG </label>
              <input type="text" class="form-control" id="nr_rg" name="nr_rg" maxlength="30">
            </div>
            <div class="col-md-3 col-12">
              <label for="emissor_rg" class="form-label"> Órgão emissor </label>
              <input type="text" class="form-control" id="emissor_rg" name="emissor_rg">
            </div>
            <!-- campo da uf -->
            <div class="col-md-3 col-12">
              <label for="uf_rg" class="form-label"> Estado de emissão </label>
              <select class="form-select" id="uf_rg" name="uf_rg">
                <option value="" disabled selected>Selecione o estado...</option>
                <option value="AC">Acre (AC)</option>
                <option value="AL">Alagoas (AL)</option>
                <option value="AP">Amapá (AP)</option>
                <option value="AM">Amazonas (AM)</option>
                <option value="BA">Bahia (BA)</option>
                <option value="CE">Ceará (CE)</option>
                <option value="DF">Distrito Federal (DF)</option>
                <option value="ES">Espírito Santo (ES)</option>
                <option value="GO">Goiás (GO)</option>
                <option value="MA">Maranhão (MA)</option>
                <option value="MT">Mato Grosso (MT)</option>
                <option value="MS">Mato Grosso do Sul (MS)</option>
                <option value="MG">Minas Gerais (MG)</option>
                <option value="PA">Pará (PA)</option>
                <option value="PB">Paraíba (PB)</option>
                <option value="PR">Paraná (PR)</option>
                <option value="PE">Pernambuco (PE)</option>
                <option value="PI">Piauí (PI)</option>
                <option value="RJ">Rio de Janeiro (RJ)</option>
                <option value="RN">Rio Grande do Norte (RN)</option>
                <option value="RS">Rio Grande do Sul (RS)</option>
                <option value="RO">Rondônia (RO)</option>
                <option value="RR">Roraima (RR)</option>
                <option value="SC">Santa Catarina (SC)</option>
                <option value="SP">São Paulo (SP)</option>
                <option value="SE">Sergipe (SE)</option>
                <option value="TO">Tocantins (TO)</option>
                <option value="OU">Outros</option>
              </select>
            </div>
            <div class="col-md-3 col-12">
              <label for="date_emissao_rg" class="form-label"> Data de emissão </label>
              <input type="date" class="form-control" id="date_emissao_rg" name="date_emissao_rg">
            </div>
          </div>

          <!-- Dropdown para seleção do código do país -->
          <div class="mb-3">
            <label for="countryCodeSelect" class="form-label">Código do País:</label>
            <select class="form-select" id="countryCodeSelect" name="country_code" onchange="updateCountryCode()">
              <option value="" disabled selected>Selecione o código do país...</option>
              <option value="55">Brasil (+55)</option>
              <option value="54">Argentina (+54)</option>
              <option value="591">Bolívia (+591)</option>
              <option value="56">Chile (+56)</option>
              <option value="57">Colômbia (+57)</option>
              <option value="593">Equador (+593)</option>
              <option value="592">Guiana (+592)</option>
              <option value="595">Paraguai (+595)</option>
              <option value="51">Peru (+51)</option>
              <option value="597">Suriname (+597)</option>
              <option value="598">Uruguai (+598)</option>
              <option value="58">Venezuela (+58)</option>
              <option value="1">EUA (+1)</option>
              <option value="1">Canadá (+1)</option>
              <option value="52">México (+52)</option>
              <option value="502">Guatemala (+502)</option>
              <option value="503">El Salvador (+503)</option>
              <option value="504">Honduras (+504)</option>
              <option value="505">Nicarágua (+505)</option>
              <option value="506">Costa Rica (+506)</option>
              <option value="507">Panamá (+507)</option>
              <option value="501">Belize (+501)</option>
              <option value="44">Reino Unido (+44)</option>
              <option value="33">França (+33)</option>
              <option value="49">Alemanha (+49)</option>
              <option value="34">Espanha (+34)</option>
              <option value="41">Suíça (+41)</option>
              <option value="32">Bélgica (+32)</option>
              <option value="31">Países Baixos (Holanda) (+31)</option>
              <option value="351">Portugal (+351)</option>
            </select>
          </div>
          <!-- campo do telefone -->
          <div class="mb-3 row">
            <div class="col-md-6 col-12">
              <label for="telefone" class="form-label"><b class="text-danger">*</b> Discagem Direta à Distância -
                DDD</label>
              <input type="text" class="form-control" id="ddd" name="ddd" placeholder="(99)">
            </div>
            <div class="col-md-6 col-12">
              <label for="telefone" class="form-label"><b class="text-danger">*</b> Telefone</label>
              <input type="tel" class="form-control" id="telefone" name="telefone" 
                placeholder="9.9999-9999">
            </div>
            <!-- Campo oculto para armazenar o código do país -->
            <input type="hidden" id="countryCode" name="country_code_hidden" value="">
          </div>
          <!-- campo do cep -->
          <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" class="form-control" id="cep" name="cep" 
              oninput="buscarEndereco(this.value)" maxlength="9" placeholder="Digite o CEP (apenas números)">
          </div>
          <!-- campo do endereço -->
          <div class="mb-3">
            <label for="endereco" class="form-label"><b class="text-danger">*</b> Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco">
          </div>
          <div class="mb-3">
            <label for="numero" class="form-label"><b class="text-danger">*</b> Número</label>
            <input type="text" class="form-control" id="numero" name="numero">
          </div>
          <!-- campo do bairro -->
          <div class="mb-3">
            <label for="bairro" class="form-label"><b class="text-danger">*</b> Bairro</label>
            <input type="text" class="form-control" id="bairro" name="bairro">
          </div>
          <!-- campo da cidade -->
          <div class="mb-3">
            <label for="cidade" class="form-label"><b class="text-danger">*</b> Cidade/Aldeia</label>
            <input type="text" class="form-control" id="cidade" name="cidade">
          </div>
          <!-- campo da uf -->
          <div class="mb-3">
            <label for="uf" class="form-label"><b class="text-danger">*</b> UF</label>
            <select class="form-select" id="ufSelect" name="estado">
              <option value="" disabled selected>Selecione o estado...</option>
              <option value="AC">Acre (AC)</option>
              <option value="AL">Alagoas (AL)</option>
              <option value="AP">Amapá (AP)</option>
              <option value="AM">Amazonas (AM)</option>
              <option value="BA">Bahia (BA)</option>
              <option value="CE">Ceará (CE)</option>
              <option value="DF">Distrito Federal (DF)</option>
              <option value="ES">Espírito Santo (ES)</option>
              <option value="GO">Goiás (GO)</option>
              <option value="MA">Maranhão (MA)</option>
              <option value="MT">Mato Grosso (MT)</option>
              <option value="MS">Mato Grosso do Sul (MS)</option>
              <option value="MG">Minas Gerais (MG)</option>
              <option value="PA">Pará (PA)</option>
              <option value="PB">Paraíba (PB)</option>
              <option value="PR">Paraná (PR)</option>
              <option value="PE">Pernambuco (PE)</option>
              <option value="PI">Piauí (PI)</option>
              <option value="RJ">Rio de Janeiro (RJ)</option>
              <option value="RN">Rio Grande do Norte (RN)</option>
              <option value="RS">Rio Grande do Sul (RS)</option>
              <option value="RO">Rondônia (RO)</option>
              <option value="RR">Roraima (RR)</option>
              <option value="SC">Santa Catarina (SC)</option>
              <option value="SP">São Paulo (SP)</option>
              <option value="SE">Sergipe (SE)</option>
              <option value="TO">Tocantins (TO)</option>
              <option value="OU">Outros</option>
            </select>
          </div>
          <!-- campo de seleção dos países -->
          <div class="mb-3">
            <label for="paises" class="form-label"><b class="text-danger">*</b> País</label>
            <select class="form-select" id="paises" name="paises">
              <option value="" disabled selected>Selecione o país...</option>
              <?php
                            foreach ($result_query5 as $pais) {
                                echo "<option value=" . $pais['id'] . ">" . $pais['nome'] . "</option>";
                            }
                            ?>
            </select>
          </div>
          <!-- campo select que lista os tipos de conta -->
          <div class="mb-3">
            <label class="form-check-label" for="contaBB"><b class="text-danger">*</b> Possui conta-corrente ou salário no Banco do Brasil?</label>
            <select class="form-select" id="slConta" name="contaBB">
              <option value="" disabled selected>Selecione o tipo de conta...</option>
              <option value="1">Conta Corrente</option>
              <option value="3">Conta Salário</option>
              <option value="nao">Não</option>
            </select>

          </div>
          <!-- campos de agência e conta -->
          <div id="contaInputs" style="display: none;">
            <div class="mb-3 row">
              <div class="col-12 col-md-6">
                <label for="agencia" class="form-label"><b class="text-danger">*</b> Agência</label>
                <input type="text" class="form-control" id="agencia" name="agencia" placeholder="Informe sua agência">
              </div>
              <div class="col-12 col-md-6">
                <label for="digito" class="form-label"> Digito</label>
                <input type="text" class="form-control" id="digitoAg" name="digitoAg" maxlength="1"
                  placeholder="Informe o digito da sua agência">
              </div>

            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-6">
                <label for="contaCorrente" class="form-label"><b class="text-danger">*</b> Conta</label>
                <input type="text" class="form-control" id="contaCorrente" name="conta" placeholder="Informe sua conta">
              </div>
              <div class="col-12 col-md-6">
                <label for="digito" class="form-label"> Digito</label>
                <input type="text" class="form-control" id="digitoConta" name="digitoConta" maxlength="1"
                  placeholder="Informe o digito da sua conta">
              </div>

            </div>
          </div>
          <!-- campo radio sim ou nao para abertura de conta salario -->
          <div id="casoNao" style="display: none;">
            <div class="mb-3">
              <label for="">Gostaria que a Agência providencie a abertura de uma conta salário para você?</label>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="radio3" name="casoNao" value="sim">Sim
                <label class="form-check-label" for="radio3"></label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="radio4" name="casoNao" value="nao">Não
                <label class="form-check-label" for="radio4"></label>
              </div>
            </div>
          </div>
          <!-- campo radio sim ou nao se possui chave pix vinculada ao cpf -->
          <div class="mb-3 form-check">
            <label class="form-check-label" for="contaBB"><b class="text-danger">*</b> Possui chave PIX vinculada ao
              CPF?</label>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="radio5" name="chpix" value="sim">Sim
              <label class="form-check-label" for="radio1"></label>
            </div>
            <div class="form-check">
              <input type="radio" class="form-check-input" id="radio6" name="chpix" value="nao">Não
              <label class="form-check-label" for="radio2"></label>
            </div>
            <p class="text-danger" id="obs" style="display: none;"><b>Obs:</b> Caso você ainda não tenha uma chave PIX
              cadastrada em seu CPF, será necessário realizar o cadastro, pois os pagamentos durante os três primeiros
              meses serão efetuados por meio dessa modalidade.</p>
          </div>
          <!-- resposta de validação de campos -->
          <div id="divValida"></div>
          <!-- botão de submissão -->
          <div class="row">
              <div class="col-md-10">
                <button type="button" class="btn btn-primary mb-2 col-9 form-control shadow-sm border-white" onclick="validaCampos()">CADASTRAR</button>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-secondary mb-2 col-3 form-control shadow-sm border-white" onclick="limpaCampos()">NOVO</button>
              </div>
          </div>
              <!-- Barra de progresso inicialmente escondida -->
          <div id="progress-container" class="progress" style="height: 10px; display: none;">
            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                 role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                0%
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
