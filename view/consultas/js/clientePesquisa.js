function clientePesquisa(response) {

    var __idade;
    var __clienteId;
    var __data;
    var __matricula;
    var __membro;
    var __nome;
    /*@type $*/
    var $tr;

    this.getAsTableLine = function() {
        if ($tr) {
            $tr.html('');
        } else {
            $tr = $('<tr/>');
            $tr.data('pesquisa', this);
        }
        $tr.append($('<th/>').html(this.getMatricula()));
        $tr.append($('<th/>').html(this.getMembro()));
        $tr.append($('<th/>').html(this.getNome()));
        $tr.append($('<th/>').html(this.getIdade()));
        $tr.append($('<th/>').html(this.getData()));


        $tr.attr('class', 'pointer-cursor');
        return $tr;
    };

    this.setNome = function(nome) {
        __nome = nome;
        return this;
    };

    this.getNome = function() {
        return __nome;
    };

    this.setMembro = function(membro) {
        __membro = membro;
        return this;
    };

    this.getMembro = function() {
        return __membro;
    };

    this.setMatricula = function(matricula) {
        __matricula = matricula;
        return this;
    };

    this.getMatricula = function() {
        return __matricula;
    };

    this.setIdeade = function(idade) {
        __idade = idade;
        return this;
    };

    this.getIdade = function() {
        return __idade;
    };

    this.setClienteId = function(clienteId) {
        __clienteId = clienteId;
        return this;
    };

    this.getClienteId = function() {
        return __clienteId;
    };

    this.setData = function(data) {
        __data = data;
        return this;
    };

    this.getData = function() {
        return __data;
    };

    if (response) {
        this.setClienteId(response.cliente_id);
        this.setData(response.data);
        this.setIdeade(response.Idade);
        this.setMatricula(response.matricula);
        this.setMembro(response.membro);
        this.setNome(response.nome);
    }
}