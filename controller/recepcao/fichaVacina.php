<?php
//session_start();
$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "insere_vacina_cliente") {

	$nomeMaterial = _INPUT('nomeVac','int','post');
//	$dadosVac = dadosVacina($idMaterial);
	$dadosVac = dadosVacinaPorNome($nomeMaterial);
//	die(print_r($dadosVac));
	$idCliente = _INPUT('idClienteVacina','int','post');
	$dadosServico['cliente_cliente_id'] = $idCliente;
	$dadosServico['usuario_id'] = $_SESSION['usuario']['id'];
	$dadosServico['material_id'] = $dadosVac['id'];
	$dadosServico['status_id'] = idStatusServicoByNome("A Pagar");
	$dadosServico['finalizado'] = 0;
	$dadosServico['preco'] = $dadosVac['preco'];
	$dadosServico['qtd_doses'] = $dadosVac['quantidade_doses'];

	if(insereServico($dadosServico)) {

		$dadosHistorico['servico_id'] = mysqli_insert_id(banco::$connection);
		$dadosHistorico['data'] = date('Y-m-d');
		if(isset($_POST['data'])) {
			$dadosHistorico['data'] = converteData($_POST['data']);
		}

		if(diferenca_data_dias(date('Y-m-d'), $dadosHistorico['data']) > 0) { //se não for hoje
			$idStatusHist = statusByNome('Programado');
			$dadosItem['status'] = 1;
		} else {
			$idStatusHist = statusByNome('A Realizar (Hoje)');
			$dadosItem['status'] = 0;
		}

		$dadosHistorico['status_id'] = $idStatusHist;
		$dadosHistorico['qtd_ml'] = $dadosVac['qtd_ml_por_dose'];
		$dadosHistorico['agrupado'] = 0;
		$dadosHistorico['modulo'] = 0;

		if(isset($_GET['isModulo'])) {
			$dadosHistorico['modulo'] = 1;
			atualizaModulo($_GET['isModulo']);
			$idItem = $_POST['itemId'];
			$dadosAtualizaServico['modulo_has_material_id'] = $idItem;
			editarModulosItem($dadosItem, $idItem);
			atualizaServico($dadosAtualizaServico, $dadosHistorico['servico_id']);
			unset($dadosItem);
		}
		$dadosHistorico['tipo'] = 'comum';

		if(insereHistorico($dadosHistorico)){
			$tipo = buscaTipoCliente($idCliente);

			if($tipo == "dependente") {
				$titular = buscaTitularPorDependente($idCliente);
				$id = $titular['id'];
			} else if($tipo == "titular") {
				$id = $idCliente;
			}
		}
	} else {
		die('Erro de Inserção de Servico');
	}

} else if($tipoForm == "edita_vacina_cliente") {

	$idHistorico = _INPUT('idHistorico','int','post');
	$nomeStatus = _INPUT('nomeStatus','string','post');

	$status = statusHistoricoPorId($idHistorico);
	if($status == 'Realizado') {
		echo saidaJson(1);
		exit;
	}

	$tipo = tipoHistoricoPorHistorico($idHistorico);

	if($tipo == 'comum') {
		$idStatusHistorico = statusByNome($nomeStatus);
		$dadosHistorico['status_id'] = $idStatusHistorico;

		editarHistorico($dadosHistorico, $idHistorico);
	}


} else if($tipoForm == "agrupa_vacina_cliente") {

	$idHistorico = _INPUT('idHistorico','int','post');

	$_SESSION['agrupar'][] = $idHistorico;

//print_r($_SESSION['agrupar']);

} else if($tipoForm == "finaliza_agrupa_vacina_cliente") {

	//$idHistorico = _INPUT('idHistorico','int','post');
	$grupo = $_SESSION['agrupar'];
	$str_ids = implode(",", $grupo);
	$histdados = dadosHistoricoPorIds($str_ids);
	$tmp = 0;

//print_r($histdados);exit;

	foreach($histdados as $hist) {
		if($hist['tipo'] == "grupo") {
				$dadosGrupo['historico_id_agrupador'] = $hist['id'];
				$tmp++;
		}
	}

	if($tmp == 1) { // Agrupar uma vacina com um grupo

		foreach($histdados as $hist) {
			if($hist['tipo'] != "grupo") {

				$dadosHistorico['agrupado'] = 1;
				editarHistorico($dadosHistorico, $hist['id']);

				$dadosGrupo['historico_id'] = $hist['id'];
				$dadosGrupo['data'] = $hist['data'];
				insereGrupoHistorico($dadosGrupo);
			}
		}
	} else if($tmp == 0) { // Agrupar somente vacinas

		foreach($grupo as $idHistorico) {
			
			$dadosHistorico['agrupado'] = 1;
			editarHistorico($dadosHistorico, $idHistorico);
		}

		$dadosServico['cliente_cliente_id'] = $histdados[0]['cliente_id'];
		$dadosServico['material_id'] = $histdados[0]['material_id'];
		$dadosServico['status_id'] = idStatusServicoByNome("Pago");
		$dadosServico['finalizado'] = 1;

		if(insereServico($dadosServico)) {

			$dadosHistorico['servico_id'] = mysqli_insert_id(banco::$connection);
			$dadosHistorico['status_id'] = $histdados[0]['status_id'];
			$dadosHistorico['data'] = $histdados[0]['data'];
			$dadosHistorico['tipo'] = 'grupo';
			$dadosHistorico['agrupado'] = 0;

			if(insereHistorico($dadosHistorico)) {

				$dadosGrupo['historico_id_agrupador'] = mysqli_insert_id(banco::$connection);
				foreach($histdados as $hist) {
					$dadosGrupo['historico_id'] = $hist['id'];
					$dadosGrupo['data'] = $hist['data'];
					insereGrupoHistorico($dadosGrupo);
				}
			}
		}
	} else if($tmp > 1) { // Agrupar mais de um grupo - Proibido
		echo saidaJson(1);
		exit;
	}

	unset($_SESSION['agrupar']);

} else if($tipoForm == "gravar_resultado_teste") {

	echo $tipo = _INPUT('tipo','int','post');
	echo $valor = _INPUT('valor','int','post');
	echo $nome = _INPUT('nome','int','post');

	echo $dados['historico_id'] = _INPUT('idHistorico','int','post');
	echo $dados['unidade'] = _INPUT('unidade','int','post');
	echo $dados['usuario_id'] = $_SESSION['usuario']['id'];

	if($tipo == 1 || $tipo == 2) {  // 1 - mitsuda e mantoux leit | 2 - imunodeficiencia
		echo $dados['valor'] = $valor;
		echo $dados['nome'] = $nome;
		echo $dados['data'] = date('Y-m-d');
		insereResultadoTestes($dados);
	} else if($tipo == 3) { // 3 - testes pesinho(plus, master e ampliado) e hiv
		echo $dados['select'] = $valor;
		echo $dados['nome'] = $nome;
		insereResultadoTestes($dados);
	} else if($tipo == 4) { // 4 - tca imunidade celular
		$dataLeit = _INPUT('dataLeit','int','post');
		for($i = 0; $i < count($valor); $i++) {
			echo $dados['data'] = converteData($dataLeit[$i]);
			echo $dados['valor'] = $valor[$i];
			echo $dados['nome'] = $nome[$i];
			insereResultadoTestes($dados);
		}
	}

} else if($tipoForm == "busca_dados_resultado_tca") {

	$idHistorico = _INPUT('idHistorico','string','post');
	$idCliente = _INPUT('idCliente','string','post');

	$arr['teste'] = buscaResutadoTestePorHistorico($idHistorico);
	$arr['pessoa'] = dadosPessoaCliente($idCliente);
	
//print_r($arr);
	echo saidaJson($arr);
	exit;

} else if($tipoForm == "imprimir_laudo_tca_imunidade") {

	$idHistorico = _INPUT('idHistorico','string','post');
	$idCliente = _INPUT('idCliente','string','post');

	$teste = buscaResutadoTesteTcaPorHistorico($idHistorico);
	$pessoa = dadosPessoaCliente($idCliente);
	
	$_SESSION['laudotca']['nomePessoa'] = $pessoa['nome'].' '.$pessoa['sobrenome'];
	$_SESSION['laudotca']['teste'] = $teste;

	exit;

} else if($tipoForm == "edita_data_vacina_cliente") {

	$status = statusHistoricoPorId($idHistorico);
	if($status == 'Realizado') {
		echo saidaJson(1);
		exit;
	}

	$idHistorico = _INPUT('idHistorico','int','post');
	$data = _INPUT('data','string','post');

	$dadosHistorico['data'] = converteData($data);
	$diff = diferenca_data_dias(date('Y-m-d'), $dadosHistorico['data']);
  
	if($diff > 0) {
		$idStatusHist = statusByNome('Programado');
		$dadosHistorico['status_id'] = $idStatusHist;
		if(editarHistorico($dadosHistorico, $idHistorico))
			echo saidaJson(2);
	} else if($diff == 0){
		$idStatusHist = statusByNome('A Realizar (Hoje)');
		$dadosHistorico['status_id'] = $idStatusHist;
		if(editarHistorico($dadosHistorico, $idHistorico))
			echo saidaJson(2);
	} else {
		$idStatusHist = statusByNome('Realizado');
		$dadosHistorico['status_id'] = $idStatusHist;
		if(editarHistorico($dadosHistorico, $idHistorico))
			echo saidaJson(2);
	}

} else if($tipoForm == "insere_eventos_adversos") {

	$idHistorico = _INPUT('idHistorico','int','post');
	$idEvento = _INPUT('idEvento','int','post');
	$data = converteData(_INPUT('data','int','post'));
	$conduta = _INPUT('conduta','int','post');
	$evolucao = _INPUT('evolucao','int','post');

	$dadosHistorico['eventos_adversos_id'] = $idEvento;
	$dadosHistorico['data_queixa'] = $data;
	$dadosHistorico['conduta'] = $conduta;
	$dadosHistorico['evolucao'] = $evolucao;

	if(editarHistorico($dadosHistorico, $idHistorico)) {
		echo saidaJson(1);
	} else {
		echo saidaJson(0);
	}

} else if($tipoForm == "exclui_eventos_adversos") {

	$idHistorico = _INPUT('idHistorico','int','post');

	if(editarHistoricoExcluirEventoAdverso($idHistorico))
		echo saidaJson(1);
	else
		echo saidaJson(0);

} else if($tipoForm == "imprimir_laudo_imunodeficiencia") {

	$idCliente = _INPUT('idCliente','int','post');
	$idTitular = _INPUT('idTitular','int','post');
	$data = _INPUT('data','int','post');
	$idHistorico = _INPUT('idHist','int','post');

	$_SESSION['laudoimu']['titular'] = dadosPessoaCliente($idTitular);
	$_SESSION['laudoimu']['cliente'] = dadosPessoaCliente($idCliente);
	$_SESSION['laudoimu']['dataEnsaio'] = $data;
	$h = buscaResutadoTesteImuPorHistorico($idHistorico);
	$_SESSION['laudoimu']['dataColeta'] = converteData($h['data_coleta']);
	$_SESSION['laudoimu']['valor'] = $h['valor'];

	exit;

} else if($tipoForm == "exclui_vacina_cliente") {

	$idHistorico = _INPUT('idHistorico','int','post');
	$idServico = _INPUT('idServico','int','post');

	$status = statusHistoricoPorId($idHistorico);
	//if($status == 'Realizado') {
	//	echo saidaJson(1);
	//	exit;
	//}

	$tipo = tipoHistoricoPorHistorico($idHistorico);

	if($tipo == 'grupo') {
		$grupo = grupoHistoricoPorIdHistorico($idHistorico);
			
		if(excluiGrupoHistorico($idHistorico)) {
			foreach($grupo as $gr) {
				$dadosHistorico['agrupado'] = 0;
				editarHistorico($dadosHistorico, $gr['historico_id']);
			}
		}
	}

	if(excluiHistorico($idHistorico))
		if(excluiServico($idServico))
			echo saidaJson(0);

} else if($tipoForm == "busca_dados_vacina"){

	$corStatus = _INPUT('cor','string','post');
	$nomeVac = _INPUT('nomeVac','string','post');

	$result['idStat'] = statusByCor($corStatus);
	$result['idVac'] = idVacinaByName($nomeVac);

	echo saidaJson($result);

} else if($tipoForm == "busca_dados_vacina_insere"){

	$nomeVac = _INPUT('nomeVac','string','post');

	$result['idVac'] = idVacinaByName($nomeVac);

	echo saidaJson($result);

} else if($tipoForm == "vacina_busca"){ // certificado de vacinação

	$idCliente = _INPUT('idcliente','int','post');
	$vacinas = vacinasPorCliente($idCliente);

//print_r($vacinas);
	echo saidaJson($vacinas);

} else if($tipoForm == "vacina_busca_controle") { // controle interno novo

	$idTitular = _INPUT('idcliente','int','post');
	$deps = buscaDependentesPorTitular($idTitular);
	$data = date('Y-m-d');
	$controles = buscaControlesPorTitularPorData($idTitular, $data);
	if(!empty($controles)) {
		foreach($controles as $controle) {
			$tmp['numero_controle'] = $controle['numero_controle'];
			$tmp['id'] = $controle['id'];
			$control[] = $tmp;
		}
		unset($tmp);
	}
	foreach($deps as $dep) {
		$idCliente = $dep['id'];
		$vacinasProgramadas = vacinasPorClientePorStatus($idCliente, 3); //'Programado ou Pagto Antecipado'
		foreach($vacinasProgramadas as $vacs) {
			if($vacs['modulo'] == 0) {
				$tmp['idCliente'] = $dep['id'];
				$tmp['membro'] = $dep['membro'];
				$tmp['cliente'] = $dep['nome']." ".$dep['sobrenome'];
				$tmp['material'] = $vacs['vacinaNome'];
				$tmp['idmaterial'] = $vacs['vacinaId'];
				$tmp['idServico'] = $vacs['servico_id'];
				$prog[] = $tmp;
				unset($tmp);
			}
		}
	}

	foreach($deps as $dep) {
		$idCliente = $dep['id'];
		$vacinasHoje = vacinasPorClientePorStatus($idCliente, 1); //'A Realizar (Hoje)'

		foreach($vacinasHoje as $vacs2) {
			$tmp2['idCliente'] = $idCliente;
			$tmp2['membro'] = $dep['membro'];
			$tmp2['cliente'] = $dep['nome']." ".$dep['sobrenome'];
			$tmp2['material'] = $vacs2['vacinaNome'];
			$tmp2['idmaterial'] = $vacs2['vacinaId'];
			$tmp2['modulo'] = $vacs2['modulo'];
			$tmp2['idServico'] = $vacs2['servico_id'];
			$hoje[] = $tmp2;
			unset($tmp2);
		}
	}

	foreach($deps as $dep) {
		$a = buscaModulosVacinasPorCliente($dep['id']);
		foreach($a as $row){
		    if(!empty($row)) {
			//var_dump($row);die('morreu');
			$tmp3['id'] = $row['id'];
			$tmp3['cliente_id'] = $row['cliente_id'];
			$tmp3['nome'] = $dep['nome']." ".$dep['sobrenome'];

			if($row['preco'] == $row['preco_vista']) {
				$tmp3['preco'] = $row['preco_vista'];
			} else {
				$tmp3['preco'] = formata_dinheiro2($row['preco_cartao']);
			}
			$mod[] = $tmp3;
			unset($tmp3);
		    }
		}
	}

	$arr['prog'] = $prog;
	$arr['hoje'] = $hoje;
	$arr['modulos'] = $mod;
	$arr['controle'] = $control;
	die(saidaJson($arr));

} else if($tipoForm == "controle_busca") {

	$idControle = _INPUT('idControle','int','post');

	$dados = buscaDadosControle($idControle);

	foreach($dados as $a) {

		$cliente = buscaClienteById($a['cliente_id']);

		$tmp['nome'] = $cliente['nome']." ".$cliente['sobrenome'];
		$tmp['membro'] = $cliente['membro'];
		$tmp['material'] = nomeMaterialPorId($a['material_id']);
		$tmp['tipo'] = "A Realizar";
		$arr[] = $tmp;
		unset($tmp);
	}

	echo saidaJson($arr);

} else if($tipoForm == "busca_controles") { // controle interno Reimprimir

	$idTitular = _INPUT('idTitular','int','post');

	$atual = date('Y-m-d');
	$controles = buscaControlesPorTitular($idTitular);

	for($i = 0; $i < count($controles); $i++) {

		$qtddias = diferenca_data_dias($controles[$i]['data'], $atual);
		$controles[$i]['strdata'] = $qtddias;
		$controles[$i]['data2'] = converteData($controles[$i]['data']);

		if($controles[$i]['strdata'] = 1)
			$controles[$i]['strfin'] = " >> Já Finalizado";
		else
			$controles[$i]['strfin'] = " >> Não Finalizado";
	}

	echo saidaJson($controles);

} else if($tipoForm == "imprimir_fichavacina"){  // carteirinha de vacinação

	$idCliente = _INPUT('idClienteVacina','int','post');

	require('view/recepcao/fichaVacinaImprimir.phtml');
exit;

} else if($tipoForm == "reimprimir_controle"){ //controle interno reimprimir

	$idTitular = _INPUT('idTitular','int','post');
	$_SESSION['controle']['titular'] = buscaCliente($idTitular);

	$idGuiaControle = _INPUT('idGuiaControle','int','post');

	$dadosGuia = buscaGuiaControlePorId($idGuiaControle);

	$usuario = dadosUsuario($dadosGuia['usuario_id']);

	$_SESSION['controle']['usuario'] = $usuario['nome']." ".$usuario['sobrenome'];

	$_SESSION['controle']['data'] = $dadosGuia['data1'];
	$_SESSION['controle']['data2'] = $dadosGuia['data2'];
	$_SESSION['controle']['hora'] = $dadosGuia['hora'];
	$_SESSION['controle']['numeroControle'] = $dadosGuia['numero_controle'];
	$_SESSION['controle']['titular']['categoria'] = categoriaById($dadosGuia['convenio_id']);

	$dados = buscaDadosControle($idGuiaControle);

	$tmp = "";
	$i = -1;
	$jaColocouLinhaModulo = false;
	foreach($dados as $a) {

		$cliente = buscaClienteById($a['cliente_id']);
		$idCli = $a['cliente_id'];

		if($idCli == $tmp) {
			if(!$jaColocouLinhaModulo && $a['modulo'] === '1') {
				$arr[$i]['material'][] = 'Modulo/PAGTO. ANTECIPADO';
				$jaColocouLinhaModulo = true;
			}
			$n = nomeMaterialPorId($a['material_id']);
			$arr[$i]['material'][] = $n."/".$a['status'];
		} else {
		    $jaColocouLinhaModulo = false;
			$i++;
			$arr[$i]['membro'] = $cliente['membro'];
			$arr[$i]['nome'] = $cliente['nome']." ".$cliente['sobrenome'];

			$atual = $a['data'];
			$antiga = $cliente['data_nascimento'];
			$diff = diferenca_data($antiga, $atual);
			$arr[$i]['idade'] = $diff['ano'] . "a " . $diff['mes'] . " m";
			$n = nomeMaterialPorId($a['material_id']);

			if($a['modulo'] === '1') {
				$arr[$i]['material'][] = 'Modulo/PAGTO. ANTECIPADO';
				$jaColocouLinhaModulo= true;
			}
			$arr[$i]['material'][] = $n."/".$a['status'];

			$tmp = $cliente['id'];
		}
	}

	$_SESSION['controle']['progs'] = $arr;

	exit;

} else if($tipoForm == "imprimir_controlenovo"){ //controle interno

	$usuario_id = $_SESSION['usuario']['id'];
	$usuario = dadosUsuario($usuario_id);

	$idControle = _INPUT('idControle','int','post');
	$idTitular = _INPUT('idTitular','int','post');
	$progs = _INPUT('progs','int','post');
	$hoje = _INPUT('hoje','int','post');
	$modulos = _INPUT('modulos','int','post');
	$hora = date('H:i:s');

	$_SESSION['controle']['titular'] = buscaCliente($idTitular);
	$idCategoria = $_SESSION['controle']['titular']['categoria_id'];
	$_SESSION['controle']['titular']['categoria'] = categoriaById($idCategoria);
	$novoNum = buscaNovoNumeroControle();
	$_SESSION['controle']['numeroControle'] = $novoNum;

	$_SESSION['controle']['data'] = date('d/m/y');
	$_SESSION['controle']['data2'] = date('d/m/Y');
	$_SESSION['controle']['hora'] = $hora;

	$atual = date('Y-m-d');
	$tmp = "";
	$i = -1;

	$dadosGuiaControle['data'] = $atual;
	$dadosGuiaControle['hora'] = $hora;
	$dadosGuiaControle['titular_id'] = $idTitular;
	$dadosGuiaControle['finalizado'] = 0;
	$dadosGuiaControle['numero_controle'] = $novoNum;
	$dadosGuiaControle['convenio_id'] = $idCategoria;
	$dadosGuiaControle['usuario_id'] = $usuario_id;

	if($idControle > 0) {
		$idGuia = $idControle;
		deleteControlesPorGuia($idGuia);
		$bool = true;
		$novoNum = buscaNumeroControle($idGuia);
		atualizaCategoriaGuiaControle($idCategoria, $idGuia);
	} else {
		$bool = insereGuiaControle($dadosGuiaControle);
		$idGuia = mysqli_insert_id(banco::$connection);
	}

	if($bool) {
		foreach($hoje as $hoj) {
			//$a = explode("/",$hoj);
			$idCli = $hoj[1];

			$dadosControle['guia_controle_id'] = $idGuia;
			$dadosControle['cliente_id'] = $idCli;
			$dadosControle['numero_controle'] = $novoNum;
			$dadosControle['data'] = $atual;
			$dadosControle['hora'] = $hora;
			$dadosControle['modulo'] = 0;

			if($idCli == $tmp) {
				$dadosControle['material_id'] = $hoj[0];
				$dadosControle['servico_id'] = $hoj[2];
				$dadosControle['status'] = "A REALIZAR (HOJE)";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
				unset($dadosControle['servico_id']);
			} else {
				$existeCliente[] = $idCli;
				$i++;
				$pes = dadosPessoaCliente($idCli);
				$dadosControle['material_id'] = $hoj[0];
				$dadosControle['servico_id'] = $hoj[2];
				$dadosControle['status'] = "A REALIZAR (HOJE)";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
				unset($dadosControle['servico_id']);
				$tmp = $pes['id'];
			}
		}

		unset($dadosControle);
		foreach($progs as $prog) {
			//$a = explode("/",$prog);
			$idCli = $prog[1];
			$dadosControle['guia_controle_id'] = $idGuia;
			$dadosControle['cliente_id'] = $idCli;
			$dadosControle['numero_controle'] = $novoNum;
			$dadosControle['data'] = $atual;
			$dadosControle['hora'] = $hora;
			$dadosControle['modulo'] = 0;

			if($idCli == $tmp) {
				$dadosControle['material_id'] = $prog[0];
				$dadosControle['servico_id'] = $prog[2];
				$dadosControle['status'] = "PAGTO. ANTECIPADO";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
				unset($dadosControle['servico_id']);
			} else {
				$existeCliente[] = $idCli;
				$i++;
				$pes = dadosPessoaCliente($idCli);
				$dadosControle['material_id'] = $prog[0];
				$dadosControle['servico_id'] = $prog[2];
				$dadosControle['status'] = "PAGTO. ANTECIPADO";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
				unset($dadosControle['servico_id']);
				$tmp = $pes['id'];
			}
		}

		// Inserindo os itens do modulo marcado no banco
		unset($dadosControle);
		foreach($modulos as $modulo_id) {
			$dadosControle['guia_controle_id'] = $idGuia;
			$dadosControle['numero_controle'] = $novoNum;
			$dadosControle['data'] = $atual;
			$dadosControle['hora'] = $hora;
			$dadosControle['modulo'] = 1;

			$itens = buscaModulosHasMaterialPorIdModulo($modulo_id);
			//die(print_r($itens));
			foreach($itens as $item) {
				$dadosControle['cliente_id'] = $item['cliente_id'];
				$dadosControle['material_id'] = $item['material_id'];
				$dadosControle['servico_id'] = $item['servico_id'];
				$dadosControle['modulo'] = '1';

				if($item['status'] == 0) {
					$dadosControle['status'] = "A REALIZAR (HOJE)";
				} else if($item['status'] == 1) {
					$dadosControle['status'] = "PAGTO. ANTECIPADO";
				}
				insereControle($dadosControle);
			}
		}

		unset($dadosControle);
		unset($arr);
		$idGuiaControle = $idGuia;
		$_SESSION['controle']['titular'] = buscaCliente($idTitular);
		$dadosGuia = buscaGuiaControlePorId($idGuiaControle);
		$usuario = dadosUsuario($dadosGuia['usuario_id']);
		$_SESSION['controle']['usuario'] = encurtar2($usuario['nome'], 20);
		$_SESSION['controle']['data'] = $dadosGuia['data1'];
		$_SESSION['controle']['data2'] = $dadosGuia['data2'];
		$_SESSION['controle']['hora'] = $dadosGuia['hora'];
		$_SESSION['controle']['numeroControle'] = $dadosGuia['numero_controle'];
		$_SESSION['controle']['titular']['categoria'] = categoriaById($dadosGuia['convenio_id']);

		$dados = buscaDadosControle($idGuiaControle);
		$tmp = "";
		$i = -1;
		$jaColocouLinhaModulo = false;
		foreach($dados as $a) {

			$cliente = buscaClienteById($a['cliente_id']);
			$idCli = $a['cliente_id'];

			if($idCli == $tmp) {
				if(!$jaColocouLinhaModulo && $a['modulo'] === '1') {
					$arr[$i]['material'][] = 'Modulo/PAGTO. ANTECIPADO';
					$jaColocouLinhaModulo = true;
				}
				$n = nomeMaterialPorId($a['material_id']);
				$arr[$i]['material'][] = $n."/".$a['status'];
			} else {
				$jaColocouLinhaModulo = false;
				$i++;
				$arr[$i]['membro'] = $cliente['membro'];
				$arr[$i]['nome'] = $cliente['nome']." ".$cliente['sobrenome'];

				$atual = $a['data'];
				$antiga = $cliente['data_nascimento'];
				$diff = diferenca_data($antiga, $atual);
				$arr[$i]['idade'] = $diff['ano'] . "a " . $diff['mes'] . " m";
				$n = nomeMaterialPorId($a['material_id']);

				if($a['modulo'] === '1') {
					$arr[$i]['material'][] = 'Modulo/PAGTO. ANTECIPADO';
					$jaColocouLinhaModulo= true;
				}
				$arr[$i]['material'][] = $n."/".$a['status'];

				$tmp = $cliente['id'];
			}
		}
	} // fim if inserir guia controle

	$_SESSION['controle']['progs'] = $arr;

	die();
} else if($tipoForm == "imprimir_certificadovacina"){ // certificado de vacinação

        $idCliente = _INPUT('idClienteVacina','int','post');
        $idTitular = _INPUT('idTitular','int','post');
        $_SESSION['certificado']['language'] = _INPUT('language','int','post');

		$_SESSION['certificado']['titular'] = dadosPessoaCliente($idTitular);
        $_SESSION['certificado']['cliente'] = dadosPessoaCliente($idCliente);
        $_SESSION['certificado']['vacinas'] = vacinasPorCliente($idCliente);

} else if($tipoForm == "imprimir_declaracao_comparecimento"){

	$idCliente = _INPUT('idClienteDec','int','post');
	$idAcompanhante = _INPUT('idAcompanhante','int','post');
	$nomeCliente = _INPUT('nomeClienteDec','int','post');
	$nomeAcompanhante = _INPUT('nomeAcompanhante','int','post');

	$data = date('Y-m-d');
	$vacs = vacinasPorClienteRealizadasPorData($idCliente, $data);

	foreach($vacs as $vac) {
		$arr[] = $vac['vacinaNome'];
	}

	$str_vacs = implode(", ", $arr);
	$usuario_id = $_SESSION['usuario']['id'];
	$usuario = dadosUsuario($usuario_id);
	$_SESSION['declaracao']['usuario'] = $usuario['nome']." ".$usuario['sobrenome'];

	$_SESSION['declaracao']['strvacs'] = $str_vacs;
	$_SESSION['declaracao']['nomeCliente'] = $nomeCliente;
	$_SESSION['declaracao']['nomeAcompanhante'] = $nomeAcompanhante;

} else {

	$idTitular = _INPUT('idTitular','int','post');
	//$titular = buscaCliente($idTitular);
	$idCategoria = buscaCategoriaTitular($idTitular);
	$dependentes = buscaDependentesPorTitular($idTitular);
	//$clientesVacina[] = $titular;

	foreach($dependentes as $dep) {
		$dep['categoria_id'] = $idCategoria;
		$clientesVacina[] = $dep;
	}

	$idClienteVacina = _INPUT('idClienteVacina','int','post');
	$membro = _INPUT('membroVacina','int','post');

	//$clienteVacina = $titular;

	//if($idTitular != $idClienteVacina) {
	if($membro == 1) {
		$clienteVacina = $dependentes[0];
	} else {
		foreach($dependentes as $dep) {
			if($dep['id'] == $idClienteVacina) {
				$clienteVacina = $dep;
			}
		}
	}
	$clienteVacina['categoria_id'] = $idCategoria;

	$medico = buscaMedicoPorCliente($clienteVacina['id']);
	$idade = diferenca_data($clienteVacina['data_nascimento'],date("Y-m-d"));

	$depImuno = buscaNomeDependentesPorTitular($idTitular);
	$titularImuno = buscaNomeTitular($idTitular);

	$clientesControleImuno[] = $titularImuno;
//	$arr_id[] = $titularImuno['id'];

	foreach($depImuno as $depIm) {
		$clientesControleImuno[] = $depIm;
//		$arr_id[] = $depIm['id'];
	}
	
	require('view/recepcao/fichaVacina.phtml');
}
