<?php

if (isset($_POST['pessoaExiste'])) {
	$nome = $_POST['nome'];
	$sobrenome = $_POST['sobrenome'];
	if ($nome && $sobrenome) {
		$n = confereExistenciaPessoaPorNome($nome,$sobrenome);
		if ($n == 1) {
			echo saidaJson(1);
		} else {
			echo saidaJson(0);
		}
	}
} else {

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
	$dadosPessoa['resp_residencial'] = _INPUT('dono-residencial', 'string', 'post');
	$dadosPessoa['tel_comercial'] = _INPUT('tel-comercial', 'string', 'post');
	$dadosPessoa['resp_comercial'] = _INPUT('dono-comercial', 'string', 'post');
	$dadosPessoa['tel_apoio'] = _INPUT('tel-apoio', 'string', 'post');
	$dadosPessoa['resp_apoio'] = _INPUT('dono-apoio', 'string', 'post');
	$dadosPessoa['facebook'] = _INPUT('facebook', 'string', 'post');
	$dadosPessoa['twitter'] = _INPUT('twitter', 'string', 'post');
	$dadosPessoa['ultima_modificacao'] = date("Y-m-d H:i:s");
	$dadosPessoa['pessoa_modificou'] = $_SESSION['usuario']['id'];
	$dadosPessoa['inscricao'] = date("Y-m-d");

//	die(print_r($_POST));
//	die(print_r($dadosPessoa));

			/* TABELA CLIENTE */

	$dadoscliente['imprimir_lembrete'] = 1;
	if(isset($_POST['cad-lembrete'])) {
		$dadosCliente['imprimir_lembrete'] = 0;
	}

	$dadoscliente['imunodeficiente'] = 0;
	if(isset($_POST['vacina-imuno'])) {
		$dadosCliente['imprimir_lembrete'] = 1;
	}

	$dadosCliente['observacao'] = _INPUT('observacoes', 'string', 'post');
	$medico_cli_id = _INPUT('cad-selc-medico', 'int', 'post');
	if(!empty($medico_cli_id)) {
		$dadosCliente['medico_id'] = $medico_cli_id;
	}
	
	$enf_cli_id = _INPUT('cad-selc-vacinador', 'int', 'post');
	if(!empty($enf_cli_id)) {
		$dadosCliente['fk_enfermeiro_id'] = $enf_cli_id;
	}
	$dadosCliente['parto_id'] = 1;
	$dadosCliente['gestacao_id'] = 1;
	$dadosCliente['peso_nascimento'] = '';
	$dadosCliente['flag'] = "T";
	$dadosCliente['matricula'] = buscaNovaMatricula();
	$dadosCliente['membro'] = 1;
	$dadosCliente['desdobramento'] = _INPUT('cad-desdobramento', 'string', 'post');

			/* TABELA TITULAR */

	$dadosTitular['nome_nf'] = _INPUT('nome-nf', 'string', 'post');
	$dadosTitular['doc_nf'] = _INPUT('doc-nf', 'string', 'post');
	
	$categoria_cli_id = _INPUT('cad-categoria', 'int', 'post');
	if(!empty($categoria_cli_id)) {
		$dadosTitular['categoria_id'] = $categoria_cli_id;
	}

	$origem_cli_id = _INPUT('cad-origem', 'int', 'post');
	if(!empty($origem_cli_id)) {
		$dadosTitular['origem_id'] = $origem_cli_id;
	}

	$dadosTitular['data_pamp'] = converteData(_INPUT('cad-pamp', 'string', 'post'));

	$nome = $dadosPessoa['nome'];
	$sobrenome = $dadosPessoa['sobrenome'];

	if ($nome && $sobrenome) {
		if(inserePessoa($dadosPessoa)) {
			$dadosCliente['cliente_id'] = mysqli_insert_id(banco::$connection);
			$dadosTitular['titular_id'] = $dadosCliente['cliente_id'];

			$ultimaMat = ultimaMatriculaCliente();

			$dadosCliente['matricula'] = $ultimaMat + 1;
			$dadosTitular['titular_id'] = $dadosCliente['cliente_id'];
			if(insereCliente($dadosCliente) && insereTitular($dadosTitular)) {
				$idCadastrado = $dadosCliente['cliente_id'];
			} else {
//				alertaErro($msgs['ERRO_GERAL']);
				alertaErro('ERRO AO INSERIR TITULAR (CLIENTE / TITULAR)');
			}
		} else {
			alertaErro($msgs['ERRO_CADASTRAR_PESSOA']);
		}

		/* Gambiarra
		 * Já que a importação do banco foi feita com gambiarra, 
		 * os titulares não são usuários válidos só usamos os 
		 * dependentes, tem que inserir duas pessoas um titular
		 * e um dependente com os mesmos dados.
		 */

		if(inserePessoa($dadosPessoa)) {

			$dadosCliente['flag'] = "D";
			$dadosDependente['fk_titular_id'] = $dadosCliente['cliente_id'];
			$dadosCliente['cliente_id'] = mysqli_insert_id(banco::$connection);
			$dadosDependente['dependente_id'] = $dadosCliente['cliente_id'];
			$dadosDependente['vacina_casa'] = 0;
			unset($dadosCliente['matricula']);

			if(insereCliente($dadosCliente) && insereDependente($dadosDependente)) {
				alertaSucesso("Cadastro feita com sucesso!");
				header('location: index.php?module=editar&matricula='.$idCadastrado.'&flag=1');
			} else {
				alertaErro('ERRO AO INSERIR TITULAR COMO DEPENDENTE (CLIENTE / DEPENDENTE)');
			}
		} else {
			alertaErro($msgs['ERRO_CADASTRAR_PESSOA']);
		}
	} else {
		alertaErro($msgs['ERRO_PREENCHA_DADOS']);
	}
}

$tituloPessoa = "Cadastro";
include('view/recepcao/cadastro.phtml');
}
