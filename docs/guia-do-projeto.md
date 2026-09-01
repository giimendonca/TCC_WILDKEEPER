# Guia do Projeto

## Convenções

### Linguagem

Português.

---

### Banco

- Tabelas em snake_case
- Chaves primárias: id
- Chaves estrangeiras: id_nomeTabela

users

animais

habitats

especies

---

### PHP

- Variáveis em camelCase
- Classes em PascalCase

---

### CSS

- Classes em kebab-case

.container-login

.card-animal

.btn-primary

---

### JavaScript

- IDs em camelCase

abrirModal()

listarAnimais()

---

### Estrutura

Os módulos devem seguir, quando aplicável, as seguintes operações:

- listar
- cadastrar
- editar
- excluir

---

### Padrão de Commits

feat:

fix:

docs:

style:

refactor:

chore:

---

## Unidades de Medida

As unidades de medida utilizadas pelo sistema são padronizadas para
garantir consistência no armazenamento e na apresentação dos dados.

Os valores são armazenados no banco de dados sem símbolos de unidade.
As unidades são apresentadas na interface do sistema.

| Campo | Unidade |
|---|---|
| vida_media | anos |
| peso_medio | kg |
| altura_media | cm |
| animais.peso | kg |
| animais.altura | cm |
| habitats.temperatura | °C |
| habitats.umidade | % |
| habitats.capacidade | animais |
| medicamentos.estoque | unidades |
| alimentacoes.quantidade | kg |

### Regras

- Peso deve ser informado em quilogramas (kg).
- Altura deve ser informada em centímetros (cm).
- Temperatura deve ser informada em graus Celsius (°C).
- Umidade deve ser informada em porcentagem (%), entre 0 e 100.
- Capacidade representa a quantidade máxima de animais no habitat.
- Estoque de medicamentos representa a quantidade disponível em unidades.
- Quantidade de alimento é registrada em quilogramas (kg).
- A dosagem de medicamentos é armazenada como texto, permitindo diferentes
  unidades de prescrição, como mg ou mL.