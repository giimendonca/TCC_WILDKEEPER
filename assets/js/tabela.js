const pesquisas = document.querySelectorAll('[data-pesquisa]')

pesquisas.forEach(pesquisa => {
    const campos = pesquisa.dataset.campos.split(',')

    
    const linhas = document.querySelectorAll('#tabela tbody tr')
    
    pesquisa.addEventListener('input', function () {
        linhas.forEach(linha => {
            
            const encontrou = campos.some(campo => {
                const seletor = `[data-campo="${campo}"]`
                const celula = linha.querySelector(seletor)
                const texto = celula.textContent.toLowerCase()
        
                return texto.includes(pesquisa.value.toLowerCase())
            })
            
            if (encontrou) {
                linha.classList.remove('oculto')
            }
            else {
                linha.classList.add('oculto')
            }
        });
    })
});