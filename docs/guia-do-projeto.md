# Guia do Projeto

## Objetivo

Manter um padrão de organização, nomenclatura, banco de dados, interface e versionamento para facilitar a manutenção do WildKeeper.

## Tecnologias

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- Apache/XAMPP para o ambiente local
- Git e GitHub para versionamento

## Convenções de nomenclatura

### Banco de dados
- Tabelas e colunas em `snake_case`.
- Chave primária: `id`.
- Chaves estrangeiras: nome no padrão `entidade_id`, por exemplo `instituicao_id`, `animal_id` e `evento_id`.
- Utilizar InnoDB e `utf8mb4` nas tabelas.
- Valores numéricos devem ser armazenados sem símbolos de unidade.

### PHP
- Variáveis e funções em `camelCase`.
- Arquivos nomeados de forma descritiva, normalmente em letras minúsculas e separados por underscore.
- Utilizar consultas preparadas para dados externos.
- Tratar exceções e evitar exibir detalhes internos do banco ao usuário.

### CSS
- Classes em `kebab-case`, por exemplo `.container-login`, `.card-animal` e `.btn-primary`.
- Variáveis globais de estilo centralizadas em `assets/css/reset.css`.
- Evitar duplicar regras globais em folhas específicas.

### JavaScript
- Variáveis e funções em `camelCase`.
- IDs e seletores devem ser claros e consistentes.
- Verificar a existência dos elementos antes de associar eventos quando o script for compartilhado entre páginas.

## Organização

- `assets/`: recursos estáticos, como CSS, JavaScript e imagens.
- `banco/`: scripts SQL e dados iniciais.
- `docs/`: documentação do projeto.
- `src/`: páginas, módulos e includes PHP.

Os módulos ficam organizados em diretórios próprios dentro de `src/`. Arquivos compartilhados devem ficar em `src/includes/`.

## Organização funcional

O sistema utiliza módulos por área de negócio. O módulo de eventos centraliza atividades como consultas, vacinações, transferências e manutenções. A lista atualizada está em `docs/modulos.md`.

## Segurança e qualidade

- Senhas devem ser armazenadas com hash seguro.
- Validar dados no servidor.
- Utilizar prepared statements.
- Escapar dados ao exibi-los em HTML.
- Restringir operações conforme autenticação, nível de acesso e instituição.
- Validar tokens CSRF nas operações protegidas.
- Aplicar limitação de tentativas no login.
- Não versionar senhas, chaves secretas, tokens ou configurações privadas.

## Unidades de medida

Os valores são armazenados sem símbolos de unidade e apresentados com a unidade correspondente na interface.

| Campo | Unidade |
|---|---|
| `vida_media` | anos |
| `peso_medio` | kg |
| `altura_media` | cm |
| `animais.peso` | kg |
| `animais.altura` | cm |
| `habitats.temperatura` | °C |
| `habitats.umidade` | % |
| `habitats.capacidade` | animais |
| `medicamentos.estoque` | unidades |
| Dosagem de medicamentos | Texto conforme prescrição |

### Regras
- Umidade relativa deve ser expressa em porcentagem, entre 0 e 100.
- Capacidade representa a quantidade máxima de animais no habitat.
- Estoque representa a quantidade disponível em unidades.
- A dosagem é texto para permitir diferentes unidades de prescrição, como mg ou mL.

## Padrão de commits

Utilizar, quando possível, prefixos que indiquem a natureza da alteração:

- `feat:` nova funcionalidade.
- `fix:` correção.
- `docs:` documentação.
- `style:` alterações de estilo sem mudança de lógica.
- `refactor:` reorganização interna sem mudança de comportamento.
- `chore:` manutenção e configuração.

## Documentos relacionados

- Requisitos: `docs/requisitos.md`
- Módulos: `docs/modulos.md`
- Metodologia: `docs/scrum.md`
- Identidade visual: `docs/design-system.md`
- Testes: `docs/testes/`
- Manuais: `docs/manuais/`
