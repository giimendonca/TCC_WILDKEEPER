
const pesquisa = document.querySelector("#pesquisa-medicamento");
const itens = document.querySelectorAll(".medicamento-item");

pesquisa.addEventListener("input", () => {
    const termo = pesquisa.value.toLowerCase().trim();

    itens.forEach(item => {
        const nome = item.dataset.nome;
        item.hidden = !nome.includes(termo);
    });
});

document.querySelectorAll(".medicamento-checkbox").forEach(checkbox => {
    checkbox.addEventListener("change", () => {
        const item = checkbox.closest(".medicamento-item");
        const detalhes = item.querySelector(".medicamento-detalhes");

        detalhes.hidden = !checkbox.checked;
    });
});
