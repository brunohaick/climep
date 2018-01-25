<?php

if (isset($_POST['logar'])) {

		// Escapa caracteres problemáticos
		if (!get_magic_quotes_gpc()) {
			$login = addslashes( _INPUT('login','string','post') );
			$senha = addslashes( _INPUT('senha','string','post') );
		}

		$dados = buscaUsuario($login,sha1($senha));

		if($dados) {
			$_SESSION['usuario']['nome'] = $dados['nome']; // nome da pessoa
			$_SESSION['usuario']['login'] = $dados['login']; // login do usuario
			$_SESSION['usuario']['tipo'] = $dados['nome_tipo_usuario']; // tipo: tem na tabela tipo_usuario
			$_SESSION['usuario']['id'] = $dados['usuario_id']; // id do usuario
?>

		<script type="text/javascript" >
			redirecionar("index.php");
		</script>
<?php
		} else {
			alertaErro($msgs['ERRO_LOGIN']);
		}
}
