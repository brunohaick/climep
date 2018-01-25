<?php

$idMaterial = _INPUT('id','int','post');
$dataInicio = converteData(_INPUT('dataInicio','int','post'));
$dataFim = converteData(_INPUT('dataFim','int','post'));

$materiais = buscaMaterialHistEstoque($idMaterial,$dataInicio,$dataFim);

include('view/estoque/historicoestoque.phtml');
