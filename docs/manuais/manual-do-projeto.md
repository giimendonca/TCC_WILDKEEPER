# Manual do Projeto | WildKeeper

## 1. Apresentação

O WildKeeper é um sistema web voltado ao gerenciamento de zoológicos e instituições de conservação da fauna, como parques ecológicos, centros de reabilitação e santuários.

A proposta é centralizar informações administrativas e operacionais relacionadas à instituição, aos funcionários, aos animais, aos habitats e às atividades de acompanhamento.

## 2. Problema e justificativa

O gerenciamento de uma instituição de conservação envolve informações de diferentes áreas. Quando esses dados ficam dispersos em planilhas ou registros isolados, torna-se mais difícil consultar históricos, acompanhar atividades e manter informações consistentes.

O WildKeeper propõe reunir esses registros em um ambiente digital com controle de acesso e associação dos dados à instituição responsável.

## 3. Objetivos

- Centralizar informações da instituição.
- Organizar cadastros de animais, espécies, habitats e funcionários.
- Acompanhar eventos, consultas, vacinações e manutenções.
- Registrar informações de medicamentos e prescrições.
- Facilitar a visualização de indicadores por meio do dashboard.
- Restringir o acesso conforme o nível de permissão do usuário.

## 4. Público-alvo

Instituições de conservação da fauna, zoológicos, parques ecológicos, centros de reabilitação e santuários que necessitem organizar informações administrativas e operacionais.

## 5. Funcionalidades

O escopo atual está descrito em `docs/modulos.md` e inclui autenticação, instituição, funcionários, espécies, habitats, animais, dashboard, eventos, consultas veterinárias, medicamentos, vacinas, manutenções e histórico de habitats.

O módulo de alimentação foi descontinuado durante o desenvolvimento e não integra o escopo atual.

## 6. Tecnologias

- **Interface:** HTML5, CSS3 e JavaScript.
- **Servidor:** PHP.
- **Banco de dados:** MySQL.
- **Servidor web de desenvolvimento:** Apache, por meio do XAMPP.
- **Versionamento:** Git e GitHub.

## 7. Organização técnica

O código é organizado por módulos dentro de `src/`, com recursos estáticos em `assets/`, scripts SQL em `banco/` e documentação em `docs/`. Arquivos compartilhados, como conexão, autenticação e componentes de navegação, ficam em `src/includes/`.

A implementação é predominantemente procedural e organizada por funcionalidades. Não deve ser descrita como uma implementação MVC estrita.

## 8. Banco de dados

O banco principal chama-se `wildkeeper`. O modelo relacional e suas entidades estão documentados em `docs/diagramas/modelo-de-dados.md`. O script de criação e estrutura inicial é `banco/script.sql`.

## 9. Segurança

O projeto utiliza autenticação por sessão, hash de senhas, controle de acesso por nível, consultas preparadas em operações parametrizadas, validação de dados, proteção CSRF em formulários e ações selecionados e limitação de tentativas de login com persistência no banco.

A chave usada pelo rate limiting deve ser configurada no ambiente e nunca armazenada no repositório. As instruções estão no README.

## 10. Equipe e orientação

- Giovana Mendonça Raimundo
- Giovanna Ramos de Oliveira
- Turma 2B - SESI
- Professor orientador: Marcelo da Silva Soares

## 11. Documentos complementares

- `docs/manual-do-usuario.md`: instruções de utilização.
- `docs/manuais/manual-tecnico.md`: instalação e manutenção técnica.
- `docs/requisitos.md`: requisitos funcionais e não funcionais.
- `docs/testes/casos-de-teste.md`: cenários de validação.
- `docs/testes/evidencias/bugs.md`: registro de bugs e correções.
