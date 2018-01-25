<?php

if ($_POST['flag'] == "buscaCPF") {

	$matricula = $_POST['matricula'];
	$tipo = ver('cliente', 'flag', 'cliente_id=' . $matricula);

	$tipo = $tipo['flag'];
	if ($tipo == "T") {
		$cpf = ver('titular', 'doc_nf', 'titular_id=' . $matricula);
		$cpf = $cpf['doc_nf'];
	} else { // == D
		$titular = buscaTitularPorDependente($matricula);
		$cpf = ver('titular', 'doc_nf', 'titular_id=' . $titular['id']);
		$cpf = $cpf['doc_nf'];
	}
	echo saidaJson($cpf);
} else if ($_POST['flag'] === 'PegaInformacoesDoCliente') {
	if (buscaTipoCliente($_POST['cliente_id']) === 'dependente') {
		$temp = buscaTitularPorDependente($_POST['cliente_id']);
	} else {
		$temp = buscaCliente($_POST['cliente_id']);
	}
	die(saidaJson($temp));
} else if ($_POST['flag'] === 'PegaMatriculaTitular') {
	if (buscaTipoCliente($_POST['cliente_id']) === 'dependente') {
		$temp = buscaTitularPorDependente($_POST['cliente_id']);
	} else {
		$temp = buscaCliente($_POST['cliente_id']);
	}
	die(saidaJson($temp['matricula']));
} else if ($_POST['flag'] === 'CountPesquisa') {
	$nome = _INPUT('nome', 'string', 'post');
	$nascimento = _INPUT('nascimento', 'string', 'post');
	$dados_outro = _INPUT('outro', 'string', 'post');
	$outro = _INPUT('opcao', 'string', 'post');
	$tipo = _INPUT('tipo', 'string', 'post');
	$pagina = _INPUT('pagina', 'string', 'post');
	$arquivoMorto = _INPUT('arquivoMorto', 'boolean', 'post');
	
	$arr = pesquisaClienteCount($nome, $nascimento, $dados_outro, $outro, $tipo, $pagina, $arquivoMorto);

	die(saidaJson($arr));
}else if ($_POST['flag'] === 'AtenderUsuario') {
                $id = $_POST['filaid'];
		confirmaHorariodeAtendimento($id, $_SESSION['usuario']['id']);
		die();
} else if (isset($_POST['procurar'])) {

	$nome = _INPUT('nome', 'string', 'post');
	$nascimento = _INPUT('nascimento', 'string', 'post');
	$dados_outro = _INPUT('outro', 'string', 'post');
	$outro = _INPUT('opcao', 'string', 'post');
	$tipo = _INPUT('tipo', 'string', 'post');
	$pagina = _INPUT('pagina', 'string', 'post');
	$arquivoMorto = _INPUT('arquivoMorto', 'boolean', 'post');

	$arr = pesquisaCliente($nome, $nascimento, $dados_outro, $outro, $tipo, $pagina, $arquivoMorto);

	die(saidaJson($arr));
//	include('view/recepcao/pesquisa.phtml');
}

	
