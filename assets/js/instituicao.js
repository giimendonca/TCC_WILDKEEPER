const estado = document.querySelector("#estado");
const cep = document.querySelector("#cep");
const rua = document.querySelector("#rua");
const bairro = document.querySelector("#bairro");
const cidade = document.querySelector("#cidade");
const mensagemCep = document.querySelector("#mensagem-cep");


// ====================================
// BUSCAR ESTADOS NA API DO IBGE
// ====================================

async function carregarEstados() {
    try {
        const resposta = await fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados");

        if (!resposta.ok) {
            throw new Error("Erro ao buscar estados.");
        }

        const estados = await resposta.json();

        estado.innerHTML = '<option value="">Selecione o estado</option>';

        estados.sort((a, b) => a.nome.localeCompare(b.nome));

        estados.forEach(estadoApi => {
            const option = document.createElement("option");

            option.value = estadoApi.sigla;
            option.textContent = estadoApi.nome;

            estado.appendChild(option);
        });

        const estadoAtual = estado.dataset.estadoAtual;

        if (estadoAtual) {
            estado.value = estadoAtual;
        }

    } catch (erro) {
        console.error(erro);

        estado.innerHTML = '<option value="">Erro ao carregar estados</option>';
    }
}


// ====================================
// MÁSCARA DO CEP
// ====================================

cep.addEventListener("input", function () {
    let valor = cep.value.replace(/\D/g, "");

    if (valor.length > 5) {
        valor = valor.replace(/^(\d{5})(\d)/, "$1-$2");
    }

    cep.value = valor;
});


// ====================================
// BUSCAR ENDEREÇO PELO CEP
// ====================================

cep.addEventListener("blur", async function () {
    const cepNumeros = cep.value.replace(/\D/g, "");

    if (cepNumeros.length === 0) {
        return;
    }

    if (cepNumeros.length !== 8) {
        mensagemCep.textContent = "CEP inválido.";
        return;
    }

    mensagemCep.textContent = "Consultando CEP...";

    try {
        const resposta = await fetch(`https://viacep.com.br/ws/${cepNumeros}/json/`);

        if (!resposta.ok) {
            throw new Error("Erro ao consultar o CEP.");
        }

        const dados = await resposta.json();

        if (dados.erro) {
            mensagemCep.textContent = "CEP não encontrado.";
            return;
        }

        rua.value = dados.logradouro || "";
        bairro.value = dados.bairro || "";
        cidade.value = dados.localidade || "";
        estado.value = dados.uf || "";

        mensagemCep.textContent = "Endereço encontrado.";

    } catch (erro) {
        console.error(erro);
        mensagemCep.textContent = "Não foi possível consultar o CEP.";
    }
});


// ====================================
// INICIA O CARREGAMENTO DOS ESTADOS
// ====================================

carregarEstados();