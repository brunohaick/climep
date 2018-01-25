<?php

	if (isset($_POST['submit'])) {
	
		/* TABELA PESSOA */
		$nome = $dadosPessoa['nome'] = _INPUT('cad-nome','string','post');
		$sobrenome = $dadosPessoa['sobrenome'] = _INPUT('cad-sobrenome', 'string', 'post');
		$dadosPessoa['data_nascimento'] = _INPUT('cad-nascimento', 'string', 'post');
		$dadosPessoa['sexo'] = _INPUT('cad-sexo', 'string', 'post');
		$dadosPessoa['conjuge'] = _INPUT('cad-conjuge', 'string', 'post');
		$dadosPessoa['endereco'] = _INPUT('cad-endereco', 'string', 'post');
		$dadosPessoa['numero'] = _INPUT('cad-numero', 'string', 'post');
		$dadosPessoa['bairro'] = _INPUT('cad-bairro', 'string', 'post');
		$dadosPessoa['cidade'] = _INPUT('cad-cidade', 'string', 'post');
		$dadosPessoa['estado'] = _INPUT('cad-estado', 'string', 'post');
		$dadosPessoa['cep'] = _INPUT('cad-cep', 'string', 'post');
		$dadosPessoa['complemento'] = _INPUT('cad-complemento', 'string', 'post');
		$dadosPessoa['email'] = _INPUT('cad-email', 'string', 'post');
		$dadosPessoa['tel_comercial'] = _INPUT('fone-comercial', 'string', 'post');
		$dadosPessoa['tel_residencial'] = _INPUT('fone-residencial', 'string', 'post');
		$dadosPessoa['tel_apoio'] = _INPUT('fone-apoio', 'string', 'post');
		
		/* TABELA USUARIO */
		// Tem o campo pessoa_id que vai pegar da tabela pessoa.
		$dadosUsuario['login'] = _INPUT('login', 'string', 'post');
		$dadosUsuario['senha'] = _INPUT('senha1', 'string', 'post');
		$dadosUsuario['tipo_usuario_id'] = 3;
		$dadosUsuario['ativo'] = _INPUT('cad-ativo', 'string', 'post');

		/* TABELA MEDICO */
		$crm = $dadosMedico['crm'] = _INPUT('cad-crm', 'string', 'post');
		$dadosMedico['especialidades_id_1'] = _INPUT('cad-selc-especialidade-1', 'int', 'post');
		$dadosMedico['especialidades_id_2'] = _INPUT('cad-selc-especialidade-2', 'int', 'post');
		// Tem o campo usuario_id que vai pegar da tabela usuario.


		if ($nome && $sobrenome && $crm) {
			$n = confereExistenciaPessoaPorNome($nome,$sobrenome);
			if ($n == 1) {
				alertaErro($msgs['ERRO_CLIENTE_CADASTRADO']);
			} else {
				if(inserePessoa($dadosPessoa)) {
					$dadosMedico['medico_id'] = $dadosUsuario['usuario_id'] = mysqli_insert_id(banco::$connection);


					if(insereUsuario($dadosUsuario) && insereMedico($dadosMedico)) {
							alertaSucesso($msgs['SUCESSO_CLIENTE_CADASTRADO']);
					} else {
						alertaErro($msgs['ERRO_GERAL']);
					}
				} else {
					alertaErro($msgs['ERRO_CADASTRAR_PESSOA']);
				}
			}
		} else {
			alertaErro($msgs['ERRO_PREENCHA_DADOS']);
		}

	}
	$tituloPessoa="Cadastrar";
	include('view/recepcao/cadastroMedicoAssistente.phtml');
