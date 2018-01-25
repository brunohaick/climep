<?php

if (isset($_POST['submitSenha'])) {

		// Escapa caracteres problemáticos
		if (!get_magic_quotes_gpc()) {
			$login = addslashes( _INPUT('login','string','post') );
			$senhaAtual = addslashes( _INPUT('senhaAtual','string','post') );
			$novaSenha = addslashes( _INPUT('novaSenha','string','post') );
			$novaSenha2 = addslashes( _INPUT('novaSenha2','string','post') );
		}
		$dados = buscaUsuario($login,sha1($senhaAtual));
		if($dados) {
			if($novaSenha == $novaSenha2) {
				$dadosUsuario['senha'] = sha1($novaSenha);
				if(editarSenhaUsuario($dadosUsuario,$dados['usuario_id'])) {
					alertaSucesso('Senha Alterada com Sucesso');
				} else {
					alertaErro('Ocorreu algum erro !');
				}
			} else {
				alertaErro('Nova Senha não foi digitada incorretamente nos dois campos !');
			}

		} else {
			alertaErro('Senha Atual foi digitada incorretamente !');
		}
}
