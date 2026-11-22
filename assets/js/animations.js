
const elementos = document.querySelectorAll(
    "#sobre, #wildkeeper, #recursos, #motivos, #cadastro"
);

const observador = new IntersectionObserver((entradas, observer) => {
    entradas.forEach(entrada => {
        if (entrada.isIntersecting) {
            entrada.target.classList.add("visivel");
            observer.unobserve(entrada.target);
        }
    });
}, {
    threshold: 0.3
});

elementos.forEach(elemento => {
    elemento.classList.add("revelar");
    observador.observe(elemento);
});
