const pesquisas = document.querySelectorAll('[data-pesquisa]')

pesquisas.forEach(pesquisa => {
    const campo = pesquisa.dataset.pesquisa
    const seletor = `[data-campo="${campo}"]`

    const linhas = document.querySelectorAll('#tabela tbody tr')

    linhas.forEach(linha => {
        const celula = linha.querySelector(seletor)
        const texto = celula.textContent.toLowerCase()

        if(texto.includes(pesquisa.value.toLowerCase())){
            linha.classList.remove('oculto')
        }
        else{
            linha.classList.add('oculto')
        }
    });
});

function configurarPesquisaTabela(){
}