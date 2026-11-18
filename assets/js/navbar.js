const menuToggle = document.querySelector(".menu-toggle")
const headerMenu = document.querySelector(".header-menu")
const menuLinks = document.querySelectorAll(".header-menu a")

menuToggle.addEventListener("click", () => {
    headerMenu.classList.toggle("active")

    const menuAberto = headerMenu.classList.contains("active")
    menuToggle.setAttribute("aria-expanded", menuAberto)
})

menuLinks.forEach((link) => {
    link.addEventListener("click", () => {
        headerMenu.classList.remove("active")
        menuToggle.setAttribute("aria-expanded", "false")
    })
});