# Organização do desenvolvimento

## Metodologia adotada

O WildKeeper foi organizado com referências ao Scrum, adaptadas à realidade de uma equipe pequena, com participação concentrada em uma desenvolvedora e colaboração pontual da segunda integrante. Por isso, o processo não deve ser descrito como uma aplicação integral de todas as cerimônias e papéis formais do Scrum.

A organização foi feita por etapas de trabalho, priorização das funcionalidades, implementação incremental, validações e correções. O Git e o GitHub foram utilizados para versionamento e histórico das alterações.

## Papéis no projeto

- **Desenvolvimento e integração:** responsabilidade concentrada principalmente na desenvolvedora responsável pela implementação.
- **Colaboração:** apoio da segunda integrante em atividades do projeto, conforme disponibilidade.
- **Orientação:** acompanhamento do professor orientador.

Não foram atribuídos formalmente os papéis de Product Owner e Scrum Master, pois a estrutura real da equipe não correspondeu a uma equipe Scrum tradicional.

## Etapas de desenvolvimento

### Etapa 0: definição e organização
- Escolha do tema e definição do problema.
- Criação do repositório Git.
- Organização inicial das pastas.
- Definição da identidade visual.
- Levantamento inicial dos requisitos e do escopo.

### Etapa 1: modelagem e planejamento
- Planejamento do banco de dados.
- Definição das entidades e relacionamentos.
- Planejamento das telas e dos módulos.
- Organização dos documentos de referência.

### Etapa 2: estrutura e autenticação
- Configuração do ambiente PHP, Apache e MySQL.
- Implementação do cadastro inicial da instituição e do administrador.
- Implementação do login, logout e controle de acesso.

### Etapa 3: módulos de gerenciamento
- Desenvolvimento dos cadastros de espécies, habitats, animais e funcionários.
- Implementação de consultas, filtros, visualização e edição conforme cada módulo.
- Implementação do dashboard.

### Etapa 4: eventos e rotinas institucionais
- Centralização das atividades no módulo de eventos.
- Integração de consultas veterinárias, vacinas e manutenções.
- Associação de medicamentos às consultas.
- Registro de transferências e histórico de habitats.

### Etapa 5: refinamento, segurança e validação
- Ajustes de interface e navegação.
- Correções de consultas, formulários e regras de associação.
- Implementação de proteções CSRF em formulários e ações selecionados.
- Implementação do rate limiting de login com persistência no MySQL e identificadores HMAC.
- Execução de testes funcionais e de segurança.
- Atualização da documentação e preparação da apresentação.

## Como o trabalho foi acompanhado

O acompanhamento ocorreu pela divisão das funcionalidades em tarefas, validação manual das entregas e registro das alterações em commits. O histórico do GitHub complementa este documento como evidência da evolução do código.

## Limitações e adaptação

Como a equipe é reduzida e a execução técnica ficou concentrada em uma integrante, não houve aplicação rígida de reuniões diárias, papéis formais ou ciclos com duração fixa. A metodologia foi adaptada para manter organização, priorização e entregas incrementais.

## Encerramento

A etapa final concentra-se em estabilização, correção de problemas prioritários, testes de regressão, documentação, preparação do ambiente de demonstração e apresentação.
