# Requisitos do Sistema

Este documento reúne os requisitos do WildKeeper de acordo com o escopo atual. Requisitos marcados como implementados devem ser confirmados por execução dos respectivos casos de teste antes da apresentação.

## Requisitos funcionais

### Autenticação e acesso
- **RF01:** O sistema deve permitir que usuários autenticados acessem o ambiente interno.
- **RF02:** O sistema deve permitir o encerramento da sessão por meio do logout.
- **RF03:** O sistema deve restringir funcionalidades de acordo com o nível de acesso do cargo.
- **RF04:** O sistema deve impedir a autenticação de contas inativas.
- **RF05:** O sistema deve limitar tentativas consecutivas de autenticação por combinação de e-mail/IP e por endereço IP.
- **RF06:** O sistema deve informar falhas de autenticação sem revelar se o e-mail está cadastrado.

### Instituição e usuários
- **RF07:** O sistema deve permitir o cadastro inicial de uma instituição e de seu usuário administrador.
- **RF08:** O sistema deve validar campos obrigatórios e impedir duplicidade de identificadores definidos como únicos.
- **RF09:** O sistema deve permitir consultar e atualizar os dados da instituição vinculada ao usuário.
- **RF10:** O sistema deve permitir cadastrar e consultar funcionários vinculados à instituição.
- **RF11:** O sistema deve permitir filtrar funcionários por nome, cargo e situação.

### Cadastros de conservação
- **RF12:** O sistema deve permitir cadastrar e consultar espécies, incluindo classificação e características.
- **RF13:** O sistema deve permitir cadastrar e consultar habitats vinculados à instituição.
- **RF14:** O sistema deve permitir cadastrar e consultar animais vinculados à instituição, espécie e habitat.
- **RF15:** O sistema deve permitir consultar as informações detalhadas de um animal.
- **RF16:** O sistema deve permitir associar, visualizar e gerenciar fotografias dos animais.
- **RF17:** O sistema deve registrar o histórico de alterações de habitat dos animais, quando aplicável.

### Eventos e atividades
- **RF18:** O sistema deve permitir organizar eventos por tipo, período, responsável e situação.
- **RF19:** O sistema deve permitir consultar e filtrar eventos.
- **RF20:** O sistema deve associar consultas e vacinações a animais.
- **RF21:** O sistema deve associar manutenções a habitats.
- **RF22:** O sistema deve permitir registrar transferências relacionando o animal e o habitat envolvido.
- **RF23:** O sistema deve manter situações de evento: Agendado, Em andamento, Concluído e Cancelado.

### Saúde e medicamentos
- **RF24:** O sistema deve permitir registrar consultas veterinárias e suas informações clínicas.
- **RF25:** O sistema deve permitir associar medicamentos a uma consulta, registrando dosagem e observações.
- **RF26:** O sistema deve permitir cadastrar e consultar medicamentos por instituição.
- **RF27:** O sistema deve apresentar informações de lote, vencimento e estoque dos medicamentos.
- **RF28:** O sistema deve permitir registrar vacinações e suas datas de aplicação e próxima aplicação.
- **RF29:** O sistema deve permitir registrar e consultar manutenções de habitats.

### Dashboard
- **RF30:** O sistema deve apresentar indicadores gerais da instituição autenticada.
- **RF31:** O sistema deve apresentar informações resumidas sobre eventos, saúde dos animais e estoque de medicamentos.

## Requisitos não funcionais

- **RNF01:** O sistema deve utilizar HTML5, CSS3, JavaScript, PHP e MySQL.
- **RNF02:** A interface deve se adaptar a diferentes tamanhos de tela.
- **RNF03:** O sistema deve ser utilizável nos navegadores modernos Google Chrome, Microsoft Edge e Mozilla Firefox.
- **RNF04:** As senhas devem ser armazenadas com algoritmo apropriado de hash, nunca em texto puro.
- **RNF05:** As entradas devem ser validadas no servidor, mesmo quando também houver validação no navegador.
- **RNF06:** As consultas que recebem dados do usuário devem utilizar parâmetros preparados.
- **RNF07:** O banco deve preservar integridade referencial por meio de chaves e restrições.
- **RNF08:** As saídas com dados fornecidos por usuários devem ser tratadas para evitar interpretação indevida de HTML.
- **RNF09:** As operações devem apresentar mensagens compreensíveis de sucesso ou falha, sem expor detalhes internos sensíveis.
- **RNF10:** As funcionalidades internas devem respeitar autenticação, autorização e isolamento dos dados por instituição.
- **RNF11:** Formulários e ações de alteração de dados devem possuir proteção contra CSRF, conforme a implementação e os testes de segurança.
- **RNF12:** O processo de autenticação deve possuir limitação de tentativas e resposta adequada durante bloqueios.
- **RNF13:** O código deve manter organização por módulos e convenções descritas no Guia do Projeto.

## Fora do escopo atual

- Módulo independente de alimentação de animais.
- Módulo independente de emissão de relatórios.
- Módulo geral de configurações separado da área da instituição.

## Observação de validação

A lista representa o escopo funcional documentado, não substitui a execução dos testes. Atualizar o status de cada requisito após a conferência final da aplicação.
