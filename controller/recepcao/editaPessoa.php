<?php

if(isset($_POST['flag']) && $_POST['flag'] == "insereFilaEsperaMedico") {

	$dados['cliente_id'] = $_POST['cliente_id'];
	$dados['medico_id'] = $_POST['medico'];
	$dados['recepcionista_id'] = $_SESSION['usuario']['id'];
	$dados['hora_recepcao'] = date('h:i:s');
	$dados['data'] = date("Y-m-d");
	$SQL_consulta = 'SELECT count(id) as count FROM `fila_espera_consulta` WHERE cliente_id = \'' . $dados['cliente_id'] . '\' AND medico_id = \'' . $dados['medico_id'] . '\' AND data = \'' . $dados['data'] . '\' ;';
	
        $temp = mysqli_fetch_assoc(mysqli_query($SQL_consulta));
	if($temp['count'] > 0) {
		die('ja esta na fila o usuario');
	}
	if(inserir('fila_espera_consulta',$dados)) {
		die("1");
	} else {
		die("0");
	}

	die("null");

} else if(isset($_POST['flag']) && $_POST['flag'] == "adicionaImuno") {

	$data['status_id'] = 7;
	$data['servico_id'] = $_POST['idServico'];
	$data['data'] = date('Y-m-d');
	$data['qtd_ml'] = $_POST['qtd'] ;

	if($_POST['tipoQtd'] == "DOSE" ) {
		$material = materialById($_POST['idImuno']);
		$qtd_ml = $material['qtd_ml_por_dose'];
		$data['qtd_ml'] = $_POST['qtd']*$qtd_ml ;
	}

	if(insereHistorico($data)){
		$datas['sucesso'] = 1;
	}
	$datas['post'] = $_POST;
	$datas['data'] = $data;

	/* VERIFICAR SE JA TOMOU TODAS AS DOSES / ML */

	// Vou ter que pegar tds os historicos com o servico_id somar as quantidades 
	// em ML , verificar quantas doses dão, se for igual ao que tem em servico 
	// trocar servico para finalizado.

	
	$imunoterapias = buscaHistoricoImunoterapiaFicha($_POST['idServico']);
	$quantidade_tomada = 0;
	foreach($imunoterapias as $imuno){
		$quantidade_tomada += $imuno['qtd_ml'];
		$quantidade_total = $imuno['qtd_doses'] * $imuno['qtd_ml_por_dose'];
	}

	if($quantidade_tomada == $quantidade_total) {
		$dados['finalizado'] = 1;
		editarServico($dados['finalizado'],$data['servico_id']);
	}

	echo saidaJson($datas);
	exit;



} else if(isset($_POST['flag']) && $_POST['flag'] == "calculaQtd") {
	$imunoterapias = buscaHistoricoImunoterapia($_POST['idCliente'],$_POST['idImuno']);

	foreach($imunoterapias as $imuno) {
			$dose += $imuno['qtd_ml'];
			$qtd_doses = $imuno['qtd_doses'];
			$nome = $imuno['nome'];
			$idServico = $imuno['id'];
			$qtd_ml_por_dose = $imuno['qtd_ml_por_dose'];
	}
	$datas['id'] = $idServico;
	$datas['status'] = "Realizado";
	$mlsSobrando = ($qtd_doses*$qtd_ml_por_dose) - $dose; // mls sobrando
	$datas['dose'] = intval($mlsSobrando/$qtd_ml_por_dose);
	$datas['data'] = date('d/m/Y');
	$datas['nome'] = $nome;

	if($_POST['tipoQtd'] == 'ML') {
		$datas['dose'] = $mlsSobrando;
	}

	echo saidaJson($datas);
	exit;


} else if(isset($_POST['flag']) && $_POST['flag'] == "appendImuno") {
	$imunoterapias = buscaHistoricoImunoterapia($_POST['idCliente'],$_POST['idImuno']);

	foreach($imunoterapias as $imuno) {
			$dose += $imuno['qtd_ml'];
			$qtd_doses = $imuno['qtd_doses'];
			$nome = $imuno['nome'];
			$idServico = $imuno['id'];
			$qtd_ml_por_dose = $imuno['qtd_ml_por_dose'];
	}
	$datas['id'] = $idServico;
	$datas['status'] = "Realizado";
	$mlsSobrando = ($qtd_doses*$qtd_ml_por_dose) - $dose; // mls sobrando
	$datas['dose'] = intval($mlsSobrando/$qtd_ml_por_dose);
	$datas['data'] = date('d/m/Y');
	$datas['nome'] = $nome;
	$finalizado = ver('servico','*',"id = '$idServico'");
	if($finalizado){
		$datas['finalizado'] = $finalizado['finalizado'];
	} else {
		$datas['finalizado'] = '1';
	}

	echo saidaJson($datas);
	exit;

} else if(isset($_POST['flag']) && $_POST['flag'] == "carregaImunoterapia") {
	$j = 0;
	$imunoterapias = buscaHistoricoImunoterapia($_POST['idCliente'],$_POST['idImuno']);

	foreach($imunoterapias as $imuno) {
			$data[$j]['status'] = $imuno['status'];
			$data[$j]['nome'] = $imuno['nome'];
			$data[$j]['data'] = converteData($imuno['data']);
			$dose = $imuno['qtd_ml'] / $imuno['qtd_ml_por_dose'];
			$totalml += $imuno['qtd_ml']; 

	if(is_decimal($dose)) {
		$ml = $imuno['qtd_ml'] % $imuno['qtd_ml_por_dose'];
		$data[$j]['dose'] = intval($dose)." D ".($ml)." ML"; 
	} else {
		$data[$j]['dose'] = intval($dose)." D";
	}
			$qtd_doses = $imuno['qtd_doses'];
			$qtd_ml_por_dose = $imuno['qtd_ml_por_dose'];
			$j++;

	}

	$mlt = $totalml;
	if(($qtd_doses*$qtd_ml_por_dose) % $totalml != 0 && ($qtd_doses*$qtd_ml_por_dose) % $totalml >= $qtd_ml_por_dose) {
		for($i = $totalml; $i < $qtd_doses*$qtd_ml_por_dose; $i+=$qtd_ml_por_dose) {
			if((($qtd_doses*$qtd_ml_por_dose) - $i) >= $qtd_ml_por_dose ){
			$data[$j]['status'] = 'Não Realizado';
			$data[$j]['nome'] = $imuno['nome'];
			$data[$j]['data'] = '';
			$data[$j]['dose'] = '1 D';
			$j++;
			}
			$mlt = $i;
		}
	}
	if(($qtd_doses*$qtd_ml_por_dose) % $mlt != 0 && ($qtd_doses*$qtd_ml_por_dose) % $mlt < $qtd_ml_por_dose) {
			$data[$j]['status'] = 'Não Realizado';
			$data[$j]['nome'] = $imuno['nome'];
			$data[$j]['data'] = '';
			$data[$j]['dose'] = (($qtd_doses*$qtd_ml_por_dose) % $mlt).' ML';
	}


	echo saidaJson($data);

	exit;
} else if(isset($_POST['flag']) && $_POST['flag'] == "carregaImunoterapiaLista") {
	$j = 0;
	$imunoterapias = buscaHistoricoImunoterapiaFicha($_POST['id']);

	foreach($imunoterapias as $imuno) {
			$data[$j]['status'] = $imuno['status'];
			$data[$j]['nome'] = $imuno['nome'];
			$data[$j]['data'] = converteData($imuno['data']);
			$dose = $imuno['qtd_ml'] / $imuno['qtd_ml_por_dose'];

	if(is_decimal($dose)) {
		$ml = $imuno['qtd_ml'] % $imuno['qtd_ml_por_dose'];
		$data[$j]['dose'] = intval($dose)." D ".($ml)." ML"; 
	} else {
		$data[$j]['dose'] = intval($dose)." D";
	}
			$j++;
	}

	echo saidaJson($data);

	exit;
} else if(isset($_POST['flag']) && $_POST['flag'] == "buscaDadosRecibo") {
	$id = $_POST['id'];

	$dados = buscaClienteById($id);
//	print_r($dados);
	echo saidaJson($dados);
	exit;

} else if (isset($_POST['flag']) && $_POST['flag'] == 'verifica_titular') {

	$matricula = $_POST['matriculaTitular'];
	$id = titularExiste($matricula);

	if(!empty($id))
		echo saidaJson($id);
	else
		echo saidaJson(0);
exit;
}
if (isset($_POST['dadosUsuarios'])) {

			/* TABELA PESSOA */

	$dadosPessoa['nome'] = _INPUT('cad-nome','string','post');
	$dadosPessoa['sobrenome'] = _INPUT('cad-sobrenome', 'string', 'post');
	$dadosPessoa['data_nascimento'] = converteData(_INPUT('cad-nascimento', 'string', 'post'));
	$dadosPessoa['sexo'] = _INPUT('cad-sexo', 'string', 'post');
	$dadosPessoa['conjuge'] = _INPUT('cad-conjuge', 'string', 'post');
	$dadosPessoa['endereco'] = _INPUT('cad-endereco', 'string', 'post'); 
	$dadosPessoa['numero'] = _INPUT('cad-numero', 'string', 'post');
	$dadosPessoa['bairro'] = _INPUT('cad-bairro', 'string', 'post');
	$dadosPessoa['cidade'] = _INPUT('cad-cidade', 'string', 'post');
	$dadosPessoa['estado'] = _INPUT('cad-estado', 'string', 'post');
	$dadosPessoa['cep'] = _INPUT('cad-cep', 'string', 'post');
	$dadosPessoa['complemento'] = _INPUT('cad-complemento', 'string', 'post');
	$dadosPessoa['complemento2'] = _INPUT('cad-complemento2', 'string', 'post');
	$dadosPessoa['email'] = _INPUT('cad-email', 'string', 'post');
	$dadosPessoa['tel_residencial'] = _INPUT('tel-residencial', 'string', 'post');
	$dadosPessoa['tel_comercial'] = _INPUT('tel-comercial', 'string', 'post');
	$dadosPessoa['tel_apoio'] = _INPUT('tel-apoio', 'string', 'post');
	$dadosPessoa['facebook'] = _INPUT('facebook', 'string', 'post');
	$dadosPessoa['twitter'] = _INPUT('twitter', 'string', 'post');
	$dadosPessoa['ultima_modificacao'] = date("Y-m-d H:i:s");
	$dadosPessoa['pessoa_modificou'] = $_SESSION['usuario']['id'];
	$dadosPessoa['resp_residencial'] = _INPUT('dono-residencial', 'string', 'post');
	$dadosPessoa['resp_comercial'] = _INPUT('dono-comercial', 'string', 'post');
	$dadosPessoa['resp_apoio'] = _INPUT('dono-apoio', 'string', 'post');

			/* TABELA CLIENTE */
	$dadosCliente['imprimir_lembrete'] = 1;
	if(isset($_POST['cad-lembrete'])) {
		$dadosCliente['imprimir_lembrete'] = 0;
	}
	
	$dadosCliente['observacao'] = _INPUT('observacoes', 'string', 'post');
	$dadosCliente['medico_id'] = _INPUT('cad-selc-medico', 'int', 'post');
	$dadosCliente['fk_enfermeiro_id'] = _INPUT('cad-selc-vacinador', 'int', 'post');
	$dadosCliente['desdobramento'] = _INPUT('cad-desdobramento', 'string', 'post');

	if(empty($dadosCliente['fk_enfermeiro_id']))
		unset($dadosCliente['fk_enfermeiro_id']);

			/* TABELA TITULAR */

	$dadosTitular['nome_nf'] = _INPUT('nome-nf', 'string', 'post');
	$dadosTitular['doc_nf'] = _INPUT('doc-nf', 'string', 'post');
	$dadosTitular['categoria_id'] = _INPUT('cad-categoria', 'int', 'post');
	$dadosTitular['origem_id'] = _INPUT('cad-origem', 'int', 'post');
	$dadosTitular['data_pamp'] = converteData(_INPUT('cad-pamp', 'string', 'post'));

	$nome = $dadosPessoa['nome'];
	$sobrenome = $dadosPessoa['sobrenome'];
//	die(print_r($_POST));
//	$id = _INPUT('matricula', 'int', 'get');
	$id = $_POST['idClienteHidden'];

	$deps = buscaDependentesPorTitular($id);

	/* Pegando o id do membro 1 dependente que eh o mesmo
	 * registro do titular. Procedimento necessário para
	 * corrigir a gambiarra de se importar os clientes
	 * como titulares sem usar um titular.
	*/

	$idDepTitular = $deps[0]['id']; 

	if($nome && $sobrenome) {
		if (editarPessoa($dadosPessoa,$id) && editarCliente($dadosCliente,$id) && editarTitular($dadosTitular,$id)) {
				if(editarPessoa($dadosPessoa,$idDepTitular))
					alertaSucesso("Edição feita com sucesso!");
		} else {
			alertaErro("Ocorreu algum erro! ");
		}
	} else {
		alertaErro("Preencha todos os campos!");
	}
} else if(isset($_GET['flag']) && $_GET['flag'] == 1) {
	alertaSucesso($msgs['SUCESSO_CLIENTE_CADASTRADO']);
}

/**
 * A variável matricula contida na url (GET) 
 * é o Id do titular da família
 */
$matricula = _INPUT('matricula', 'int', 'get');

if(buscaTipoCliente($matricula) === 'dependente'){
    $matricula = buscaTitularPorDependente($matricula);
    $matricula = $matricula['id'];
}

$clientes = buscaCliente($matricula);
$id = $clientes['id'];

//var_dump($clientes);die;

$_SESSION['editaPessoaClienteDados'] = $clientes;
//$medico = buscaMedicoPorCliente($matricula);
//$vacinador = buscaEnfermeiroPorCliente($matricula);

$tituloPessoa = "Editar";
include('view/recepcao/cadastro.phtml');
