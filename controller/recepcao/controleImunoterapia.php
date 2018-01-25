<?php

$idTitular = _INPUT('idTitular', 'int', 'post');
$idDepImuno = _INPUT('idDepImuno', 'int', 'post');
$idImuno = _INPUT('idImuno', 'int', 'post');
$idMembro = _INPUT('idMembro', 'int', 'post');

if (isset($_POST['tipoFormImuno']) && $_POST['tipoFormImuno'] == 'calculaPreco') {
	/*
	 * @author Bruno Haick
	 *
	 * Este if é utilizado quando o número de doses é modificado 
	 * no formulário de imunoterapia, para que se possa calcular o preço
	 * referente a esta quantidade.
	 *
	 * */

	$nomeImuno = _INPUT('nomeImuno', 'String', 'post');
	$nomeImuno = utf8_decode($nomeImuno);
	$imuno_id = idMaterialImunoByNome($nomeImuno);

	$qtdDoses = _INPUT('qtdDoses', 'int', 'post');

	$dados = dadosImunoterapia($imuno_id);

	if ($qtdDoses < $dados['quantidade_doses']) {
		$dados['preco'] = $qtdDoses * $dados['preco_por_dose'];
	}

	echo saidaJson($dados);
} else if ($_POST['tipoFormImuno'] == 'salvaNoBancoOGuiaControleEControle') {
	//inicio da funçao
	$usuario_id = $_SESSION['usuario']['id'];//captura usuario da sessao atual
	$usuario = dadosUsuario($usuario_id);

	$idControle = _INPUT('idControle','int','post'); // recebe id do controle via post
	$idTitular = _INPUT('idTitular','int','post'); //recebe id do titular via post
	$progs = _INPUT('progs','int','post'); //
	$hoje = _INPUT('hoje','int','post'); //
	$modulos = _INPUT('modulos','int','post'); // recebe a informacao se eh um modulo ou nao
	$hora = date('H:i:s'); // recebe a hora de confecç�o do modulo
	$_SESSION['controle']['titular'] = buscaCliente($idTitular); // Busca Id do titular, atravez do $idTitular q � o id do cliente
	$_SESSION['controle']['titular']['categoria'] = categoriaById($_SESSION['controle']['titular']['categoria_id']); // Busca o nome do convenio do ususario atravez do numero de categoria dele
	$novoNum = buscaNovoNumeroControle(); // Gera um novo numero de 
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
	$dadosGuiaControle['convenio_id'] = $_SESSION['controle']['titular']['categoria_id'];
	$dadosGuiaControle['usuario_id'] = $usuario_id;
	
	if($idControle > 0) {
		$idGuia = $idControle;
		deleteControlesPorGuia($idGuia);
		$bool = true;
		$novoNum = buscaNumeroControle($idGuia);
	} else {
		$bool = insereGuiaControle($dadosGuiaControle);
		$idGuia = buscaNovoIDControle()-1;
	}
	
	if($bool) {
		foreach($hoje as $hoj) {
			$a = explode("/",$hoj);
			$idCli = $a[1];
			$dadosControle['guia_controle_id'] = $idGuia;
			$dadosControle['cliente_id'] = $idCli;
			$dadosControle['numero_controle'] = $novoNum;
			$dadosControle['data'] = $atual;
			$dadosControle['hora'] = $hora;
			$dadosControle['modulo'] = 0;
			if($idCli == $tmp) {
				$dadosControle['material_id'] = $a[0];
				$dadosControle['servico_id'] = $a[2];
				$dadosControle['status'] = "A REALIZAR (HOJE)";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
			} else {
				$existeCliente[] = $idCli;
				$i++;
				$pes = dadosPessoaCliente($idCli);
				$dadosControle['material_id'] = $a[0];
				$dadosControle['servico_id'] = $a[2];
				$dadosControle['status'] = "A REALIZAR (HOJE)";
//				print_r($dadosControle);
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
				$tmp = $pes['id'];
			}
		}

		unset($dadosControle);
		foreach($progs as $prog) {
			$a = explode("/",$prog);
			$idCli = $a[1];
			$dadosControle['guia_controle_id'] = $idGuia;
			$dadosControle['cliente_id'] = $idCli;
			$dadosControle['numero_controle'] = $novoNum;
			$dadosControle['data'] = $atual;
			$dadosControle['hora'] = $hora;
			$dadosControle['modulo'] = 0;

			if($idCli == $tmp) {
				$dadosControle['material_id'] = $a[0];
				$dadosControle['servico_id'] = $a[2];
				$dadosControle['status'] = "PAGTO. ANTECIPADO";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
			} else {
				$existeCliente[] = $idCli;
				$i++;
				$pes = dadosPessoaCliente($idCli);
				$dadosControle['material_id'] = $a[0];
				$dadosControle['servico_id'] = $a[2];
				$dadosControle['status'] = "PAGTO. ANTECIPADO";
				insereControle($dadosControle);
				unset($dadosControle['material_id']);
				unset($dadosControle['status']);
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
//			die(print_r($itens));
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
		$idGuiaControle = $idGuia;
		$_SESSION['controle']['titular'] = buscaCliente($idTitular);
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
	} // fim if inserir guia controle

	$_SESSION['controle']['progs'] = $arr;
	
	die;
	
} else if (isset($_POST['idImuno'])) {
	/*
	 * @author Bruno Haick
	 *
	 * Este if é utilizado quando estiver adicionando serviços a lista
	 * de serviços desta ação.
	 *
	 * */
	$dados = dadosImunoterapia($idImuno);
	$dados['nomeMembro'] = buscaNomeCliente($idMembro);
	$dados['status'] = 'A Pagar';

	echo saidaJson($dados);
} else if ($_POST['tipoFormImuno'] == 'imuno-modal') {
	
	$idTitular = _INPUT('idcliente','int','post');
	$deps = buscaDependentesPorTitular($idTitular);
	foreach($deps as $dep) {
		$depsId[] = $dep['id'];
	}
	$data = date('Y-m-d');
	
	$hoje = gerarControleDeImunoTerapia($depsId, $data);
	$prog = gerarControleDeImunoTerapia($depsId, $data, true);
	$naotomados = gerarControleDeImunoTerapiaJaPago($depsId);
	
	foreach($deps as $dep) {
		$a = buscaModulosImunoterapiaPorCliente($dep['id']);
		$row = $a[0];

		if(!empty($row)) {
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
	
	$controles = buscaControlesPorTitularPorData($idTitular, $data);

	if(!empty($controles)) {
		foreach($controles as $controle) {
			$tmp['numero_controle'] = $controle['numero_controle'];
			$tmp['id'] = $controle['id'];
			$control[] = $tmp;
		}
		unset($tmp);
	}

	$arr['prog'] = $prog;
	$arr['hoje'] = $hoje;
	$arr['modulos'] = $mod;
	$arr['naotomados'] = $naotomados;
	$arr['controle'] = $control;
	echo saidaJson($arr);
	
	
	/*
	 * @author Bruno Haick
	 *
	 * Este if é utilizado quando o usuário inicia o processo de
	 * controle de imunoterapia, então este bloco monta a estrutura
	 * da tabela, assim como abre o modal com os dados necessários
	 * e já mostra os serviços em aberto pertencentes ao titular e
	 * seus dependentes.
	 *
	 * */

//	$depImuno = buscaNomeDependentesPorTitular($idTitular);
//	$titularImuno = buscaNomeTitular($idTitular);
//	$servicos = todasImunoterapias();
//
//	//$clientesControleImuno[] = $titularImuno;
//	//$arr_id[] = $titularImuno['id'];
//
//	foreach ($depImuno as $depIm) {
//		$clientesControleImuno[] = $depIm;
//		$arr_id[] = $depIm['id'];
//	}
//
//	$todosServicos = dosesRestantesServico($arr_id);
////print_r($todosServicos);
//	$clienteEscolhido = $titularImuno['id'] . " - " . $titularImuno['nome'] . " " . $titularImuno['sobrenome'];
//
//	if ($idTitular != $idDepImuno) {
//		foreach ($clientesControleImuno as $clientesControleIm) {
//			if ($clientesControleIm['id'] == $idDepImuno) {
//				$clienteEscolhido = $clientesControleIm['id'] . " - " . $clientesControleIm['nome'] . " " . $clientesControleIm['sobrenome'];
//			}
//		}
//	}
//
//	require('view/recepcao/controleImunoterapia.phtml');
} else if ($_POST['tipoFormImuno'] == 'imuno-busca') {

	$matricula = _INPUT('matricula', 'int', 'post');
	$imunos = todasImunoterapiasCliente($matricula);

	for ($i = 0; $i < count($imunos); $i++)
		$imunos[$i]['servico_data'] = converteData($imunos[$i]['servico_data']);

//print_r($imunos);
	echo saidaJson($imunos);
} else if ($_POST['tipoFormImuno'] == 'imuno-insert') {
	/*
	 * @author Bruno Haick
	 *
	 * Este if é utilizado quando o usuário submete o formulário 
	 * completo clicando no botão " OK ".
	 * Assim irá inserir os dados no banco de dados.
	 *
	 * */

	$tableImunoHist = _INPUT('hist', 'int', 'post');
	$statusNome = "A realizar (Hoje)";
	$tipoStatus = "imunoterapia";

	/* Bloco que insere um Historico */
	if (count($tableImunoHist) > 0) {
		foreach ($tableImunoHist as $imunoHist) {
			if (isset($imunoHist[5])) {

				$dadosHistorico['servico_id'] = $imunoHist[7];
				$dadosHistorico['status_id'] = statusIdPorNome($statusNome, $tipoStatus);
				$dadosHistorico['data'] = date('Y-m-d');

				$qtd_ml_serv = $imunoHist[6];
				$qtd_ml_hist = explode("/", $qtd_ml_serv);
				foreach ($qtd_ml_hist as $qtd_ml_div) {
					$dadosHistorico['qtd_ml'] = $qtd_ml_div;
					insereHistorico($dadosHistorico);
				}
			}
		}
	}

	/* APPEND */

	$tableImunoServ = _INPUT('serv', 'int', 'post');

	if (count($tableImunoServ) > 0) {
		foreach ($tableImunoServ as $imunoServ) {

			$idCliente = $imunoServ[0];
			$dadosServico['cliente_cliente_id'] = $idCliente;
			$dadosServico['material_id'] = $imunoServ[1];
			$dadosServico['status_id'] = idStatusServicoByNome($imunoServ[3]);
			$dadosServico['finalizado'] = 0;
			$dadosServico['preco'] = $imunoServ[2];
			$dadosServico['qtd_doses'] = $imunoServ[4];
			$dadosServico['data'] = date('Y-m-d');

			insereServico($dadosServico);

			if (isset($imunoServ[5])) {

				$dadosHistorico['servico_id'] = mysqli_insert_id(banco::$connection);
				$dadosHistorico['status_id'] = statusIdPorNome($statusNome, $tipoStatus);
				$dadosHistorico['data'] = date('Y-m-d');

				$qtd_ml_serv = $imunoServ[6];
				$qtd_ml_hist = explode("/", $qtd_ml_serv);
				foreach ($qtd_ml_hist as $qtd_ml_div) {
					$dadosHistorico['qtd_ml'] = $qtd_ml_div;
					insereHistorico($dadosHistorico);
				}
			}

			$tipo = buscaTipoCliente($idCliente);

			if ($tipo == "dependente") {
				$titular = buscaTitularPorDependente($idCliente);
				$id = $titular['id'];
			} else if ($tipo == "titular") {
				$id = $idCliente;
			}

			$fila = existeFilaEsperaCaixaPorTitular($id);
			$count = count($fila);

			if ($count == 0) {
				//$dadosFilaCaixa['id'] = ;
				$dadosFilaCaixa['cliente_cliente_id'] = $id;
				$dadosFilaCaixa['operador_id'] = $_SESSION['usuario']['id'];
				$dadosFilaCaixa['valor_total'] = 0;
				$dadosFilaCaixa['data'] = date('Y-m-d');
				$dadosFilaCaixa['finalizado'] = 0;
				//$dadosFilaCaixa['nota_fiscal'] = 0;

				if (insereFilaEspera($dadosFilaCaixa)) {
					$idFilaCaixa = mysqli_insert_id(banco::$connection);
				}
			} else if ($count == 1) {
				$idFilaCaixa = $fila[0]['id'];
			}

			$dadosFilaCaixaAtividade['cliente_id'] = $idCliente;
			$dadosFilaCaixaAtividade['fila_espera_caixa_id'] = $idFilaCaixa;
			$dadosFilaCaixaAtividade['categoria_transacao_caixa_id'] = buscaCategoriaTransacaoCaixaPorNome('imunoterapia');
			$dadosFilaCaixaAtividade['valor'] = $imunoServ[2];
			$dadosFilaCaixaAtividade['id_referencia'] = 0;

			insereAtividadesCaixa($dadosFilaCaixaAtividade);
		}
	}

//					if(insereHistoricoImunoterapia($dadosHistoricoImunoterapia)) {
//						alertaSucesso('Dependente cadastrado !');
//					} else {
//						alertaErro('Ocorreu algum erro!');
//					}
//				}
//			} else {
//				alertaErro('Ocorreu algum erro!');
//			}
//		} else {
//			alertaErro('Preencha todos os campos obrigatórios !');
//		}
//	}
//	require('view/recepcao/controleImunoterapia.phtml');
} else if ($_POST['tipoFormImuno'] == 'pegaGrupos') {
	$result = pegaGruposDemAterialDoUsuario($_POST['userId']);
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row;
	}
	die(saidaJson($data));
}
