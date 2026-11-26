document.addEventListener("DOMContentLoaded", () => {
    const tipo = document.querySelector("#tipo");
    const campoAnimal = document.querySelector("#campo-animal");
    const campoHabitat = document.querySelector("#campo-habitat");
    const animal = document.querySelector("#animal_id");
    const habitat = document.querySelector("#habitat_id");

    if (!tipo || !campoAnimal || !campoHabitat || !animal || !habitat) {
        return;
    }

    function atualizarCampos() {
        const regras = {
            "Consulta": { animal: true, habitat: false },
            "Vacinação": { animal: true, habitat: false },
            "Manutenção": { animal: false, habitat: true },
            "Transferência": { animal: true, habitat: true },
            "Outro": { animal: false, habitat: false }
        };

        const regra = regras[tipo.value] || { animal: false, habitat: false };

        campoAnimal.hidden = !regra.animal && tipo.value !== "Outro";
        campoHabitat.hidden = !regra.habitat && tipo.value !== "Outro";

        animal.required = regra.animal;
        habitat.required = regra.habitat;

        campoAnimal.querySelector(".campo-obrigatorio").hidden = !regra.animal;
        campoHabitat.querySelector(".campo-obrigatorio").hidden = !regra.habitat;

        if (campoAnimal.hidden) {
            animal.value = "";
        }

        if (campoHabitat.hidden) {
            habitat.value = "";
        }
    }

    tipo.addEventListener("change", atualizarCampos);
    atualizarCampos();
});
