<?php
ob_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require '../../vendor/autoload.php';
include '../../includes/config.php';
include '../../includes/database.php';

header('Content-Type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);


$response = [
    'success' => false,
    'message' => 'Ocorreu um erro desconhecido.'
];

try{
    // instância do banco
    $db = new database();
    $query_candidato = "SELECT * FROM candidatos";
    $result_query3 = $db->EXE_QUERY($query_candidato);
    $query_dependente = "SELECT * FROM dependente";
    $result_query4 = $db->EXE_QUERY($query_dependente);
    $erro = 0;
    $uploadOk = 1;
    $uploadOk2 = 1;
    $target_file=$target_file2=$upload=$comprovante=$c=$u=$msgtxt1=$msgtxt2=$datatxt=$fone='';
    
    // gerar um numero rendomico
    define("INFERIOR", 0001);
    define("SUPERIOR", 1000);
    $rand = rand(INFERIOR, SUPERIOR);
    date_default_timezone_set('America/Sao_Paulo');
    $data = date('Y-m-d H:i:s');
    
    // recebe os campos
    $nr_rg = isset($_POST['nr_rg']) && !empty(trim($_POST['nr_rg'])) ? trim($_POST['nr_rg']) : "";
    $emissor_rg = isset($_POST['emissor_rg']) && !empty(trim($_POST['emissor_rg'])) ? trim($_POST['emissor_rg']) : "";
    $uf_rg = isset($_POST['uf_rg']) && !empty(trim($_POST['uf_rg'])) ? trim($_POST['uf_rg']) : "";
    $date_emissao_rg = isset($_POST['date_emissao_rg']) && !empty($_POST['date_emissao_rg']) ? $_POST['date_emissao_rg'] : null;
    $paises = isset($_POST['paises']) && !empty(trim($_POST['paises'])) ? trim($_POST['paises']) : "";
    $estado_civil = isset($_POST['estado_civil']) && !empty(trim($_POST['estado_civil'])) ? trim($_POST['estado_civil']) : "";
    $grau_instrucao = isset($_POST['grau_instrucao']) && !empty(trim($_POST['grau_instrucao'])) ? trim($_POST['grau_instrucao']) : "";
    $dt_nasc = isset($_POST['dt_nasc']) && !empty($_POST['dt_nasc']) ? $_POST['dt_nasc'] : null;
    $nacionalidade = isset($_POST['nacionalidade']) && !empty(trim($_POST['nacionalidade'])) ? trim($_POST['nacionalidade']) : "";
    $etnia = isset($_POST['etnia']) && !empty(trim($_POST['etnia'])) ? trim($_POST['etnia']) : "";
    $sexo = isset($_POST['sexo']) && !empty(trim($_POST['sexo'])) ? trim($_POST['sexo']) : "";
    $afastamento = isset($_POST['afastado']) && !empty(trim($_POST['afastado'])) ? trim($_POST['afastado']) : "";
    $deficiente = isset($_POST['deficiente']) && !empty(trim($_POST['deficiente'])) ? trim($_POST['deficiente']) : "";
    $cpf = isset($_POST['cpf']) && !empty(trim($_POST['cpf'])) ? trim($_POST['cpf']) : "";
    $nome = isset($_POST['nome']) && !empty(trim($_POST['nome'])) ? trim($_POST['nome']) : "";
    $email = isset($_POST['email']) && !empty(trim($_POST['email'])) ? trim($_POST['email']) : "";
    $telefone = isset($_POST['telefone']) && !empty(trim($_POST['telefone'])) ? trim($_POST['telefone']) : "";
    $ddd = isset($_POST['ddd']) && !empty(trim($_POST['ddd'])) ? trim($_POST['ddd']) : "";
    $country_code = isset($_POST['country_code']) && !empty(trim($_POST['country_code'])) ? trim($_POST['country_code']) : "";
    $cep = isset($_POST['cep']) && !empty(trim($_POST['cep'])) ? trim($_POST['cep']) : "";
    $numero = isset($_POST['numero']) && !empty(trim($_POST['numero'])) ? trim($_POST['numero']) : "";
    $endereco = isset($_POST['endereco']) && !empty(trim($_POST['endereco'])) ? trim($_POST['endereco']) : "";
    $bairro = isset($_POST['bairro']) && !empty(trim($_POST['bairro'])) ? trim($_POST['bairro']) : "";
    $cidade = isset($_POST['cidade']) && !empty(trim($_POST['cidade'])) ? trim($_POST['cidade']) : "";
    $uf = isset($_POST['estado']) && !empty(trim($_POST['estado'])) ? trim($_POST['estado']) : "";
    $organizacao = isset($_POST['organizacao']) && !empty(trim($_POST['organizacao'])) ? trim($_POST['organizacao']) : "";
    $profissao = isset($_POST['profissao']) && !empty(trim($_POST['profissao'])) ? trim($_POST['profissao']) : "";
    $dseis = isset($_POST['dseis']) && !empty(trim($_POST['dseis'])) ? trim($_POST['dseis']) : "";
    $ag = isset($_POST['agencia']) && !empty(trim($_POST['agencia'])) ? trim($_POST['agencia']) : "";
    $digito_ag = isset($_POST['digitoAg']) && !empty(trim($_POST['digitoAg'])) ? trim($_POST['digitoAg']) : "";
    $conta = isset($_POST['contaBB']) && !empty(trim($_POST['contaBB'])) ? trim($_POST['contaBB']) : "";
    $contaCorrente = isset($_POST['conta']) && !empty(trim($_POST['conta'])) ? trim($_POST['conta']) : "";
    $digito_c = isset($_POST['digitoConta']) && !empty(trim($_POST['digitoConta'])) ? trim($_POST['digitoConta']) : "";
    $casoNao = isset($_POST['casoNao']) && !empty(trim($_POST['casoNao'])) ? trim($_POST['casoNao']) : "";
    $pix = isset($_POST['chpix']) && !empty(trim($_POST['chpix'])) ? trim($_POST['chpix']) : "";
    $clinica = isset($_POST['clinica']) && !empty(trim($_POST['clinica'])) ? trim($_POST['clinica']) : "";
    $date_return = isset($_POST['date_return']) && !empty(trim($_POST['date_return'])) ? trim($_POST['date_return']) : null;
    //novos campos
    $dtchegada = isset($_POST['dtchegada']) && !empty(trim($_POST['dtchegada'])) ? trim($_POST['dtchegada']) : null;
    $rne = isset($_POST['rne']) && !empty(trim($_POST['rne'])) ? trim($_POST['rne']) : '';
    $orgemissorrne = isset($_POST['orgemissorrne']) && !empty(trim($_POST['orgemissorrne'])) ? trim($_POST['orgemissorrne']) : '';
    $tipopermanencia = isset($_POST['tipopermanencia']) && !empty(trim($_POST['tipopermanencia'])) ? trim($_POST['tipopermanencia']) : '';
    $rddep = isset($_POST['rddep']) && !empty(trim($_POST['rddep'])) ? trim($_POST['rddep']) : '';
    $contadep = isset($_POST['contadep']) && !empty(trim($_POST['contadep'])) ? trim($_POST['contadep']) : '0';
    $contadep = (int)$contadep;
    
    
    //validando os campos obrigatórios de dependentes
    if ($rddep === '') {
        $erro = 1;
        throw new Exception("Marque o campo: Possui dependente?");
    }
    if ($rddep === 'S') {
        if($contadep > 0){
            $tipodep = $nomedep = $sexodep = $dtnascdep = $cpfdep = $idadedep = $declarairpfdep = array();
            for ($i=0; $i < $contadep; $i++){
                $tipodep[$i] = isset($_POST["tipodep$i"]) && !empty(trim($_POST["tipodep$i"])) ? trim($_POST["tipodep$i"]) : '';
                $nomedep[$i] = isset($_POST["nomedep$i"]) && !empty(trim($_POST["nomedep$i"])) ? trim($_POST["nomedep$i"]) : '';
                $sexodep[$i] = isset($_POST["sexodep$i"]) && !empty(trim($_POST["sexodep$i"])) ? trim($_POST["sexodep$i"]) : '';
                $dtnascdep[$i] = isset($_POST["dtnascdep$i"]) && !empty(trim($_POST["dtnascdep$i"])) ? trim($_POST["dtnascdep$i"]) : '';
                $cpfdep[$i] = isset($_POST["cpfdep$i"]) && !empty(trim($_POST["cpfdep$i"])) ? trim($_POST["cpfdep$i"]) : '';
                $idadedep[$i] = isset($_POST["idadedep$i"]) && !empty(trim($_POST["idadedep$i"])) ? trim($_POST["idadedep$i"]) : '';
                $pjdep[$i] = isset($_POST["pjdep$i"]) && !empty(trim($_POST["pjdep$i"])) ? trim($_POST["pjdep$i"]) : '';
                $declarairpfdep[$i] = isset($_POST["declarairpfdep$i"]) && !empty(trim($_POST["declarairpfdep$i"])) ? trim($_POST["declarairpfdep$i"]) : '';

                if ($tipodep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Selecione o campo: Tipo de dependente");
                }
                if ($nomedep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo: Nome completo do dependentes");
                }
                if ($sexodep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo: Sexo do dependentes");
                }
                if ($dtnascdep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo: Data de nascimento do dependente");
                }
                if ($cpfdep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo: CPF do dependente");
                } else{
                    $cpfdep[$i] = trim($cpfdep[$i]);
                    $cpfdep[$i] = str_replace(".", "", $cpfdep[$i]);
                    $cpfdep[$i] = str_replace("-", "", $cpfdep[$i]);
                    // verifica se já existe um cpf cadastrado 
                    $cpfbooldep = false;
                    foreach ($result_query4 as $value) {
                        if ($value['cpf'] == $cpfdep[$i]) {
                            $cpfbooldep = true;
                            break;
                        }
                    }
                    if ($cpfbooldep === true) {
                        $erro = 1;
                        throw new Exception("Já existe um cadastro de dependente com este CPF");
                    }
                }
                if ($idadedep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo Data de nascimento do dependente para preenchimento automático do campo Idade do dependente");
                }
                if ($pjdep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo Pensão Judicial?");
                }
                if ($declarairpfdep[$i] === '') {
                    $erro = 1;
                    throw new Exception("Preencha o campo Declara o dependente no IRPF");
                }
            }
        }
    }
    
    //validação dos campos obrigatórios ou sequênciais obrigatórios
    if ($paises === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: País");
    }
    if ($sexo === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Sexo");
    }
    if ($estado_civil === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Estado civil");
    }
    if ($grau_instrucao === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Grau de Instrução");
    }
    if ($dt_nasc === "") {
        $erro = 1;
        throw new Exception("Preencha o campo: Data de nascimento");
    }
    if ($nacionalidade === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Nacionalidade");
    }else{
        //validando os campos novos relativos aos estrangeiros
        if ($nacionalidade !== '10') {
            if ($dtchegada === '') {
                $erro = 1;
                throw new Exception("Preencha o campo: Data de Chegada ao Brasil");
            }
            if ($rne === '') {
                $erro = 1;
                throw new Exception("Preencha o campo: Registro Nacional de Estrangeiro - RNE");
            }
            if ($orgemissorrne === '') {
                $erro = 1;
                throw new Exception("Preencha o campo: Órgão Emissor (RNE)");
            }
            if ($tipopermanencia === '') {
                $erro = 1;
                throw new Exception("Preencha o campo: Tipo de permanência");
            }
        }
    }
    if ($etnia === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Etnia/Raça/Cor");
    }
    if ($afastamento === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Está afastado no momento? ...");
    }
    if ($deficiente === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Deficiente Habilitado ou Reabilitado");
    }
    if ($cpf === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: CPF");
    }else{
        $cpf = trim($_POST["cpf"]);
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);
        // verifica se já existe um cpf cadastrado 
        $cpfbool = false;
        foreach ($result_query3 as $value) {
            if ($value['cpf_candidato'] == $cpf) {
                $cpfbool = true;
                break;
            }
        }
        if ($cpfbool === true) {
            $erro = 1;
            throw new Exception("Já existe um cadastro com este CPF");
        }
    }
    if ($nome === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Nome completo");
    }elseif (mb_strlen($nome, 'UTF-8') < 8) {
        $erro = 1;
        throw new Exception("Preencha o campo: Nome completo");
    }
    if ($email === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: E-mail");
    }
    if ($telefone === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Telefone");
    }else {
        if ($ddd === '') {
            $erro = 1;
            throw new Exception("Preencha o campo: Discagem Direta à Distância - DDD");
        }
        if ($country_code === '') {
            $erro = 1;
            throw new Exception("Preencha o campo: Código do País");
        }
        $fone = $country_code . " " . $ddd . $telefone;
    }
    if ($numero === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Número");
    }
    if ($endereco === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Endereço");
    }
    if ($bairro === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Bairro");
    }
    if ($cidade === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: Cidade/Aldeia");
    }
    if ($uf === '') {
        $erro = 1;
        throw new Exception("Preencha o campo: UF");
    }
    if ($organizacao === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Conveniada pela qual está contratado(a)");
    }
    if ($profissao === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Cargo ocupado atualmente");
    }
    if ($dseis === '') {
        $erro = 1;
        throw new Exception("Selecione o campo: Lista dos DSEIs elegíveis");
    }
    if ($conta === '') {
        $erro = 1;
        throw new Exception("Marque o campo: Possui conta-corrente ou salário no Banco do Brasil?");
    }
    if ($conta !== 'nao') {
        if ($ag === '') {
            $erro = 1;
            throw new Exception("Preencha o campo: Agência");
        }
        if ($contaCorrente === '') {
            $erro = 1;
            throw new Exception("Preencha o campo: Conta");
        }
    }
    if ($pix === '') {
        $erro = 1;
        throw new Exception("Marque o campo: Possui chave PIX vinculada ao CPF?");
    }
    if ($afastamento === 'N') {
        if ($dseis !== '10') {
            // valida a clinica conveniada
            if ($clinica === '') {
                $erro = 1;
                throw new Exception("Selecione o campo: Seu exame admissional foi realizado pela Clínica credenciada ou particular?");
            }
            // Tratamento do upload do Exame Admissional
            if (isset($_FILES['exameAdmissional'])) {
                $datatxt = "" . $data . $rand;
                $datatxt = substr($datatxt, 2);
                $datatxt = str_replace("-", "", $datatxt);
                $datatxt = str_replace(" ", "", $datatxt);
                $datatxt = str_replace(":", "", $datatxt);
                $uploadDir = "uploads/$datatxt";
                $target_file = $uploadDir . basename($_FILES['exameAdmissional']['name']);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                if (basename($_FILES['exameAdmissional']['name']) == '' || empty($_FILES['exameAdmissional']['name'])) {
                    $uploadOk = 0;
                    throw new Exception("Você precisa anexar um exame admissional.");
                }
                //
                if ($_FILES['exameAdmissional']['name'] == '') {
                    $imageFileType = "jpg";
                }
                //
                if (file_exists($target_file)) { // Check if file already exists
                    $uploadOk = 0;
                    throw new Exception("Desculpe, o arquivo já existe.");
                } elseif ($_FILES["exameAdmissional"]["size"] > 100000000) { // Check file size < 100mb
                    $uploadOk = 0;
                    throw new Exception("Desculpe, seu arquivo é muito grande. Limite 100Mb");
                } elseif ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType != "jpeg" && $imageFileType !== "pdf") {
                    // Allow certain file formats
                    $uploadOk = 0;
                    throw new Exception("Desculpe, apenas arquivos (PDF, JPG, JPEG & PNG)");
                }
            }

            // Tratamento do upload do Recibo de Pagamento
            if ($clinica === 'particular') {
                $datatxt = "" . $data . $rand;
                $datatxt = substr($datatxt, 2);
                $datatxt = str_replace("-", "", $datatxt);
                $datatxt = str_replace(" ", "", $datatxt);
                $datatxt = str_replace(":", "", $datatxt);
                $comprovantesDir = "comprovantes/$datatxt";
                $target_file2 = $comprovantesDir . basename($_FILES['reciboPagamento']['name']);
                $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

                if (basename($_FILES['reciboPagamento']['name']) == '' || empty($_FILES['reciboPagamento']['name'])) {
                    $uploadOk2 = 0;
                    throw new Exception("Você precisa anexar um comprovante de pagamento.");
                }
                if (file_exists($target_file2)) { // Check if file already exists
                    $uploadOk2 = 0;
                    throw new Exception("Desculpe, o arquivo já existe.");
                } elseif ($_FILES["reciboPagamento"]["size"] > 100000000) { // Check file size < 100mb
                    $uploadOk2 = 0;
                    throw new Exception("Desculpe, seu arquivo é muito grande. Limite 100Mb");
                } elseif ($imageFileType2 !== "jpg" && $imageFileType2 !== "png" && $imageFileType2 != "jpeg" && $imageFileType2 !== "pdf") {
                    // Allow certain file formats
                    $uploadOk2 = 0;
                    throw new Exception("Você precisa anexar um comprovante de pagamento válido (PDF, JPG, JPEG & PNG)");
                }
            } else {
                $c = "A Clínica é credenciada.";
            }
        }else{
            if($clinica !== ''){
                // Tratamento do upload do Exame Admissional
                if (isset($_FILES['exameAdmissional'])) {
                    $datatxt = "" . $data . $rand;
                    $datatxt = substr($datatxt, 2);
                    $datatxt = str_replace("-", "", $datatxt);
                    $datatxt = str_replace(" ", "", $datatxt);
                    $datatxt = str_replace(":", "", $datatxt);
                    $uploadDir = "uploads/$datatxt";
                    $target_file = $uploadDir . basename($_FILES['exameAdmissional']['name']);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    if (basename($_FILES['exameAdmissional']['name']) == '' || empty($_FILES['exameAdmissional']['name'])) {
                        $uploadOk = 0;
                        throw new Exception("Você precisa anexar um exame admissional.");
                    }
                    //
                    if ($_FILES['exameAdmissional']['name'] == '') {
                        $imageFileType = "jpg";
                    }
                    //
                    if (file_exists($target_file)) { // Check if file already exists
                        $uploadOk = 0;
                        throw new Exception("Desculpe, o arquivo já existe.");
                    } elseif ($_FILES["exameAdmissional"]["size"] > 100000000) { // Check file size < 100mb
                        $uploadOk = 0;
                        throw new Exception("Desculpe, seu arquivo é muito grande. Limite 100Mb");
                    } elseif ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType != "jpeg" && $imageFileType !== "pdf") {
                        // Allow certain file formats
                        $uploadOk = 0;
                        throw new Exception("Desculpe, apenas arquivos (PDF, JPG, JPEG & PNG)");
                    }
                }

                // Tratamento do upload do Recibo de Pagamento
                if ($clinica === 'particular') {
                    $datatxt = "" . $data . $rand;
                    $datatxt = substr($datatxt, 2);
                    $datatxt = str_replace("-", "", $datatxt);
                    $datatxt = str_replace(" ", "", $datatxt);
                    $datatxt = str_replace(":", "", $datatxt);
                    $comprovantesDir = "comprovantes/$datatxt";
                    $target_file2 = $comprovantesDir . basename($_FILES['reciboPagamento']['name']);
                    $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

                    if (basename($_FILES['reciboPagamento']['name']) == '' || empty($_FILES['reciboPagamento']['name'])) {
                        $uploadOk2 = 0;
                        throw new Exception("Você precisa anexar um comprovante de pagamento.");
                    }
                    if (file_exists($target_file2)) { // Check if file already exists
                        $uploadOk2 = 0;
                        throw new Exception("Desculpe, o arquivo já existe.");
                    } elseif ($_FILES["reciboPagamento"]["size"] > 100000000) { // Check file size < 100mb
                        $uploadOk2 = 0;
                        throw new Exception("Desculpe, seu arquivo é muito grande. Limite 100Mb");
                    } elseif ($imageFileType2 !== "jpg" && $imageFileType2 !== "png" && $imageFileType2 != "jpeg" && $imageFileType2 !== "pdf") {
                        // Allow certain file formats
                        $uploadOk2 = 0;
                        throw new Exception("Você precisa anexar um comprovante de pagamento válido (PDF, JPG, JPEG & PNG)");
                    }
                } else {
                    $c = "A Clínica é credenciada.";
                }
            }
        }
    } else {
        $clinica = "Está afastado por mais de 15 dias";
        if ($date_return === '') {
            $erro = 1;
            throw new Exception("Preencha o campo: Informe a data de retorno");
        }
    }
        // se não houver nenhum erro fazer o insert no canco e dispara um email de confirmação
    if ($erro == 0 && $uploadOk == 1 && $uploadOk2 == 1) {
        try{
//             // move para o diretório views/uploads
            if(strlen($target_file) > 0){
                if (move_uploaded_file($_FILES["exameAdmissional"]["tmp_name"], '../'. $target_file)) {
                    $u = $target_file;
                    $upload = "ok";
                    $msgtxt1 .= "O arquivo " . htmlspecialchars(basename($_FILES["exameAdmissional"]["name"])) . " foi carregado. ";
                }else{
                    throw new Exception("Upload do Exame Admissional NÃO EXECUTADO!");
                }
            }
            if(strlen($target_file2) > 0){
                if (move_uploaded_file($_FILES["reciboPagamento"]["tmp_name"], '../'. $target_file2)) {
                    $c = $target_file2;
                    $comprovante = "ok";
                    $msgtxt2 .= "O arquivo " . htmlspecialchars(basename($_FILES["reciboPagamento"]["name"])) . " foi carregado.";
                }else{
                    throw new Exception("Upload do Recibo de Pagamento NÃO EXECUTADO!");
                }
            }
            //preparando o objeto para inserir no bd
            $parametros = [
                ':nome' => $nome,
                ':cpf' => $cpf,
                ':email' => $email,
                ':fone' => $fone,
                ':cep' => $cep,
                ':endereco' => $endereco,
                ':numero' => $numero,
                ':bairro' => $bairro,
                ':cidade' => $cidade,
                ':estado' => $uf,
                ':contaBB' => $conta,
                ':agencia' => $ag,
                ':digito_ag' => $digito_ag,
                ':conta_corrente' => $contaCorrente,
                ':digito_c' => $digito_c,
                ':casoNao' => $casoNao,
                ':cpf_pix' => $pix,
                ':clinica' => $clinica,
                ':arquivoExame' => $upload ? "https://agsusbrasil.org/precadastro/views/$u" : $u,
                ':comprovantePgto' => $comprovante ? "https://agsusbrasil.org/precadastro/views/$c" : $c,
                ':orgao_id' => $organizacao,
                ':profissao_id' => $profissao,
                ':sexo' => $sexo,
                ':estado_civil' => $estado_civil,
                ':grau_instrucao' => $grau_instrucao,
                ':dt_nasc' => $dt_nasc,
                ':nacionalidade' => $nacionalidade,
                ':dtchegada' => $dtchegada,
                ':rne' => $rne,
                ':orgemissorrne' => $orgemissorrne,
                ':tipopermanencia' => $tipopermanencia,
                ':etnia' => $etnia,
                ':deficiente' => $deficiente,
                ':afastamento' => $afastamento,
                ':paises_id' => $paises,
                ':dsei_id' => $dseis,
                ':nr_rg' => $nr_rg,
                ':emissor_rg' => $emissor_rg,
                ':uf_rg' => $uf_rg,
                ':date_emissao_rg' => $date_emissao_rg,
                ':create_at' => $data,
                ':date_return' => $date_return
            ];
            
            $insert = "INSERT INTO candidatos (nome_candidato,cpf_candidato,email_candidato,fone_candidato,
                cep,endereco,numero,bairro,cidade,estado,contaBB,agencia,digito_ag,conta_corrente,digito_c,
                casoNao,cpf_pix,clinica,arquivoExame,comprovantePgto,orgao_id,profissao_id,sexo,estado_civil,
                grau_instrucao,dt_nasc,nacionalidade,dtchegada,rne,orgemissorrne,tipopermanencia,etnia,deficiente,
                afastamento,paises_id,dsei_id,nr_rg,emissor_rg,uf_rg,date_emissao_rg,create_at,date_return) 
                VALUES (:nome,:cpf,:email,:fone,:cep,:endereco,:numero,:bairro,:cidade,:estado,:contaBB,
                :agencia,:digito_ag,:conta_corrente,:digito_c,:casoNao,:cpf_pix,:clinica,:arquivoExame,
                :comprovantePgto,:orgao_id,:profissao_id,:sexo,:estado_civil,:grau_instrucao,:dt_nasc,
                :nacionalidade,:dtchegada,:rne,:orgemissorrne,:tipopermanencia,:etnia,:deficiente,:afastamento,
                :paises_id,:dsei_id,:nr_rg,:emissor_rg,:uf_rg,:date_emissao_rg,:create_at,:date_return)";
            $result_insert = $db->EXE_NON_QUERY($insert, $parametros);
            
//            // se i insert for true dispara um e-mail de confirmação
            if ($result_insert) {
                //preparando o objeto dependente para inserir no bd
                if($contadep > 0){
                    $query_cand2 = "SELECT max(id) as id FROM candidatos";
                    $result_q2 = $db->EXE_QUERY($query_cand2);
                    $fkcandidato = "";
                    foreach ($result_q2 as $value) {
                        $fkcandidato = $value['id'];
                        break;
                    }
                    for($x=0; $x < $contadep; $x++){
                        $parametrosdep = [
                            ':nomecompleto' => $nomedep[$x],
                            ':sexo' => $sexodep[$x],
                            ':dtnascimento' => $dtnascdep[$x],
                            ':cpf' => $cpfdep[$x],
                            ':idade' => $idadedep[$x],
                            ':pjdep' => $pjdep[$x],
                            ':depirpf' => $declarairpfdep[$x],
                            ':fkcandidato' => $fkcandidato,
                            ':fktipodependente' => $tipodep[$x]
                        ];
                        //inserindo no bd
                        $insertdep = "INSERT INTO dependente (nomecompleto,sexo,dtnascimento,cpf,idade,pjdep,depirpf,fkcandidato,fktipodependente) 
                            VALUES (:nomecompleto,:sexo,:dtnascimento,:cpf,:idade,:pjdep,:depirpf,:fkcandidato,:fktipodependente)";
                        $result_insertdep = $db->EXE_NON_QUERY($insertdep, $parametrosdep);
                    }
                }
                //E-Mail para o inscrito.
                $assunto = "Pré-cadastro para o DSEIs";
                $msg = "Caro(a) Trabalhador(a), <br> Informamos que você concluiu com sucesso todas as etapas do seu pré-cadastro da Agência Brasileira de Apoio à Gestão do SUS (AgSUS)! ";
                $msg .= "<p>Agradecemos por dedicar seu tempo ao preenchimento do formulário. Assim que recebermos o retorno da SESAI sobre as admissões, forneceremos novas orientações.</p>";
                $msg .= "<p>Essa etapa é fundamental para a regularização dos dados cadastrais e o andamento dos nossos processos.</p>";
                $msg .= "<p>Queremos reforçar que seus dados serão tratados com total segurança e confidencialidade, seguindo as normas da Lei Geral de Proteção de Dados (LGPD).</p>";
                $msg .= "<p> Caso precise de qualquer assistência, nossa equipe está à disposição para ajudar./p>";
                $msg .= "<p>Segue abaixo os canais oficiais de comunicação disponíveis:</p>";

                $subject = "Pré-cadastro para empregados dos DSEIs";

                $body = "<h2>Cópia de confirmação do formulário de pré-cadastro - Mensagem Automática - Favor não responda!</h2>";
                $body .= "<hr>";
                $body .= "<b>Olá! Recebemos sua solicitação de pré-cadastro</b><br>";
                $body .= "<p><b>Nome do usuário:</b> $nome.</p>";
                $body .= "<p><b>CPF do usuário:</b> $cpf.</p>";
                $body .= "<p><b>E-mail do usuário:</b> $email.</p>";
                $body .= "<p><b>Telefone do usuário:</b> $fone.</p>";
                $body .= "<p><b>Assunto:</b> $assunto.</p>";
                $body .= "<p><b>Mensagem:</b> $msg.</p>";
                $body .= "<hr>";
                $body .= "<p>CANAIS DE COMUNICAÇÃO DA AgSUS</p>";
                $body .= "<p>E-mail: ugpsaudeindigena@agenciasus.org.br</p>";
                $body .= "<p>Telefone: (61) 9.9893-8612</p>";
                $body .= "<p>WhatsApp: (61) (61) 9.9893-8612</p>";
                $body .= "<p>Site/Plataforma oficial: https://agenciasus.org.br	</p>";
                $body .= "<p>Atenciosamente,</p>";
                $body .= "<p>Equipe AgSUS</p>";
                $body .= "<p>Unidade de Gestão de Pessoas (UGP)</p>";
                $body .= "<p><b>Observação:</b> Caso não tenha sido você que fez a solicitação, desconsidere esta mensagem.</p>";

                $altBody = "<p>Recebemos uma solicitação via formulário de pré-cadastro. Esteremos analisando sua demanda. Peço que aguarde nosso retorno.</p>";
                try{ 
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

                    $mail->isSMTP();
                    $mail->Host = CONF_MAIL_HOST;
                    $mail->SMTPAuth = CONF_MAIL_OPTION_AUTH;
                    $mail->Username = CONF_MAIL_USER;
                    $mail->Password = CONF_MAIL_PASS;
                    $mail->SMTPSecure = CONF_MAIL_OPTION_SECURE; //PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = CONF_MAIL_PORT;
                    $mail->CharSet = CONF_MAIL_OPTION_CHARSET;

                    //Recipients
                    $mail->setFrom('noreply@agenciasus.org.br', 'AgSUS');
                    $mail->addAddress($email, 'Pré-cadastro dos DSEIs');
                    //$mail->addAddress('ellen@example.com');
                    $mail->addReplyTo('noreply@agenciasus.org.br', 'AgSUS');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');
                    //Attachments se vai enviar algum arquivo junto com a mensagem
                    //$mail->addAttachment('/var/tmp/file.tar.gz');
                    $mail->addAttachment('../../img/AgSUS_2023.png', 'logo_agsus');

                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;
                    $mail->AltBody = $altBody;
                    $response['success'] = true;
                    $response['message'] = "OK";
//                    if($mail->send()){
//                        $response['success'] = true;
//                        $response['message'] = "Seu pedido foi registrado com sucesso. Um email com uma cópia da sua solicitação foi enviada para $email. $msgtxt1 $msgtxt2";
//                    }else{
//                        throw new Exception("Não foi possível enviar o e-mail.");
//                    }
                } catch (Exception $e) {
                    $response['message'] = $e->getMessage();
                }
            } else {
                throw new Exception("Problemas na transmissão. Os dados não foram gravados! Faça um novo preenchimento.");
            }
        }catch (Exception  $er) {
            $response['message'] = $er->getMessage();
        } 
    }
} catch (Exception  $e2) {
    $response['message'] = $e2->getMessage();
} 
echo json_encode($response);