<?php

function alertaErro($texto){
	
	echo "  <div class='alert alert-error'>
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<h4 class='alert-heading'><i class= 'icon-warning-sign '></i> Erro</h4>
			$texto
		</div>";

}
	
function alertaSucesso($texto){
	
	echo "  <div class='alert alert-success'>
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<h4 class='alert-heading'><i class= 'icon-ok'></i> Sucesso</h4>
			$texto
		</div>";

}
