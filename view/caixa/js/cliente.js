function clienteModel(dados) {
	this.cliente = 1;

	this.nome;
	this.data_nascimento;
	this.medico;
	this.matricula;
	this.clienteId;
	this.membro;
	this.cpf;
	this.cep;
	this.telefone;
	this.ddd;
	this.endereco;
	this.numeroEndereco;
	this.bairro;
	this.categoria;
	this.cidade;
	this.estado;
	this.email;

	this.getClienteId = function() {
		return this.clienteId;
	}

	this.getNome = function() {
		return this.nome;
	}

	this.getDataNascimento = function() {
		return this.data_nascimento;
	}

	this.getMedico = function() {
		return this.medico;
	}

	this.getMatricula = function() {
		return this.matricula;
	}

	this.getMembro = function() {
		return this.membro;
	}
	
	this.getCpf = function() {
		return this.cpf;
	}

	this.getCep = function() {
		return this.cep;
	}

	this.getTelefone = function() {
		return this.telefone;
	}

	this.getDdd = function() {
		return this.ddd;
	}

	this.getEndereco = function() {
		return this.endereco;
	}

	this.getBairro = function() {
		return this.bairro;
	}

	this.getCategoria = function() {
		return this.categoria;
	}
	
	this.getCidade = function() {
		return this.cidade;
	}
	
	this.getEstado = function() {
		return this.estado;
	}
	
	this.getEmail = function() {
		return this.email;
	}

	this.getIdadeString = function() {
		var dias = this.diasDecorridos(this.data_nascimento, new Date());
		var anos = dias / 365;
		var messes = (anos - parseInt(anos)) * 12;
		anos = parseInt(anos);
		messes = parseInt(messes);
		return anos + "a " + messes + "m";
	};

	this.diasDecorridos = function(dt1, dt2) {
		// variáveis auxiliares
		var minuto = 60000;
		var dia = minuto * 60 * 24;
		var horarioVerao = 0;

		// ajusta o horario de cada objeto Date
		dt1.setHours(0);
		dt1.setMinutes(0);
		dt1.setSeconds(0);
		dt2.setHours(0);
		dt2.setMinutes(0);
		dt2.setSeconds(0);

		// determina o fuso horário de cada objeto Date
		var fh1 = dt1.getTimezoneOffset();
		var fh2 = dt2.getTimezoneOffset();

		// retira a diferença do horário de verão
		if (dt2 > dt1) {
			horarioVerao = (fh2 - fh1) * minuto;
		}
		else {
			horarioVerao = (fh1 - fh2) * minuto;
		}

		var dif = Math.abs(dt2.getTime() - dt1.getTime()) - horarioVerao;
		return Math.ceil(dif / dia);
	};

	this.setClienteId = function(ClienteId) {
		this.clienteId = ClienteId;
		return this;
	};

	this.setMatricula = function(Matricula) {
		this.matricula = Matricula;
		return this;
	};

	this.setMedico = function(nome) {
		this.medico = nome;
		return this;
	};

	this.setDataNacimento = function(data) {
		if (typeof data === 'string') {
			var data = data.split('-');
			this.data_nascimento = new Date(data[0], parseInt(data[1]) - 1, data[2]);
		} else {
			this.data_nascimento = data;
		}
		return this;
	};

	this.setNome = function(nome) {
		this.nome = nome;
		return this;
	};

	this.setMembro = function(membro) {
		this.membro = membro;
		return this;
	}

	this.setCpf = function(cpf) {
		this.cpf = cpf;
		return this;
	}
	
	this.setCep = function(cep) {
		this.cep = cep;
		return this;
	}

	this.setTelefone = function(telefone) {
		this.telefone = telefone;
		return this;
	}

	this.setDdd = function(ddd) {
		this.ddd = ddd ;
		return this;
	}

	this.setEndereco = function(endereco) {
		this.endereco = endereco;
		return this;
	}
        this.setNumeroEndereco = function(numero) {
		this.numeroEndereco = numero;
		return this;
	}

	this.setBairro = function(bairro) {
		this.bairro = bairro;
		return this;
	}

	this.setCategoria = function(categoria) {
		this.categoria = categoria;
		return this;
	}

	this.setCidade = function(cidade) {
		this.cidade = cidade;
		return this;
	}


	this.setEstado = function(estado) {
		this.estado = estado;
		return this;
	}

	this.setEmail = function(email) {
		this.email = email;
		return this;
	}

	if (dados) {
		this.setClienteId(dados.cliente_id)
				.setNome(dados.clienteNome)
				.setDataNacimento(dados.data_nascimento)
				.setMedico(dados.medico)
				.setMatricula(dados.matricula)
				.setMembro(dados.membro)
				.setCpf(dados.cpf)
				.setCep(dados.cep)
				.setTelefone(dados.telefone_residencial)
				.setDdd(dados.ddd)
				.setEndereco(dados.endereco)
                                .setNumeroEndereco(dados.numero)
				.setBairro(dados.bairro)
				.setCategoria(dados.categoria)
				.setCidade(dados.cidade)
				.setEstado(dados.estado)
				.setEmail(dados.email)
	}
}