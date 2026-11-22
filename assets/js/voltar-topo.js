
const botaoTopo = document.querySelector("#voltar-topo");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        botaoTopo.classList.add("visivel");
    } else {
        botaoTopo.classList.remove("visivel");
    }
});

botaoTopo.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});
