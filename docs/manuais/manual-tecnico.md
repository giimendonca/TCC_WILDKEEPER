# Manual Técnico | WildKeeper

## 1. Visão geral

O WildKeeper é uma aplicação web desenvolvida com PHP, MySQL, HTML, CSS e JavaScript. O ambiente local de desenvolvimento utiliza Apache por meio do XAMPP.

A implementação é organizada por módulos e inclui arquivos PHP procedurais. Não se trata de uma arquitetura MVC estrita.

## 2. Estrutura do repositório

- `assets/`: CSS, JavaScript e imagens.
- `banco/`: scripts SQL e dados de apoio.
- `docs/`: documentação.
- `src/`: páginas e módulos da aplicação.
- `src/includes/`: conexão, autenticação, funções e componentes compartilhados.

## 3. Pré-requisitos

- Windows com XAMPP ou ambiente equivalente.
- Apache habilitado.
- PHP compatível com os recursos utilizados pelo projeto.
- MySQL/MariaDB disponível.
- Navegador moderno.
- Git, caso seja necessário clonar ou atualizar o repositório.

## 4. Instalação local

1. Instale e inicie Apache e MySQL pelo painel do XAMPP.
2. Coloque o repositório na pasta de projetos do servidor local, normalmente `htdocs`.
3. Crie o banco de dados executando `banco/script.sql` no MySQL/phpMyAdmin.
4. Confira as configurações de conexão em `src/includes/conexao.php` e ajuste host, usuário, senha e nome do banco ao ambiente local.
5. Configure a chave secreta do rate limiting conforme a seção 6.
6. Reinicie o Apache após alterar sua configuração.
7. Acesse a aplicação pelo endereço local correspondente à pasta do projeto.
8. Realize o cadastro inicial da instituição e do usuário administrador.

O arquivo `banco/script.sql` contém a estrutura principal do banco, incluindo a tabela `login_rate_limits`. Para uma instalação nova, este é o script SQL de referência.

## 5. Banco de dados

O banco principal é `wildkeeper`. As tabelas utilizam InnoDB e codificação `utf8mb4`.

O modelo de dados de alto nível está em `docs/diagramas/modelo-de-dados.md`. O script de criação está em `banco/script.sql`.

O arquivo `banco/seed.sql`, quando utilizado, contém dados de apoio para popular o ambiente de demonstração. Avalie seu conteúdo antes de executá-lo em uma base que já possua dados.

**Atenção:** não recrie nem sobrescreva um banco com dados importantes sem realizar backup. O script principal é destinado à criação/estruturação de uma instalação; não deve ser tratado como uma migração incremental universal.

## 6. Configuração do rate limiting de login

O mecanismo de limitação de tentativas utiliza a variável de ambiente `WILKEEPER_LOGIN_RATE_LIMIT_KEY`.

- Gere uma chave aleatória de 32 bytes, representada por 64 caracteres hexadecimais.
- Configure-a no Apache do XAMPP com `SetEnv WILKEEPER_LOGIN_RATE_LIMIT_KEY "CHAVE_GERADA"`.
- Reinicie o Apache.
- Não coloque a chave no código-fonte, no banco, no README ou em commits.
- Cada instalação independente com banco próprio deve possuir uma chave própria.
- Instalações que compartilham o mesmo banco devem utilizar a mesma chave.

O passo a passo completo de geração e configuração está no README do repositório.

## 7. Autenticação e segurança

O fluxo de autenticação utiliza sessões PHP, regeneração do identificador de sessão após login bem-sucedido, hash de senha com `password_verify()`, resposta genérica para falha de autenticação e limitação de tentativas por combinação de e-mail/IP e por IP.

Os identificadores de rate limiting são gerados com HMAC e armazenados como dados binários no banco. Durante bloqueios, o sistema retorna HTTP 429 e o cabeçalho `Retry-After`.

A proteção CSRF foi implementada em formulários e ações selecionados. A revisão de configuração global dos cookies e parâmetros de sessão PHP e a validação específica de CSRF no login devem ser concluídas na etapa final de segurança. Não considerar esses pontos concluídos apenas porque a regeneração do ID após login já existe.

## 8. Diagnóstico

1. Verifique se Apache e MySQL estão ativos.
2. Confira o nome do banco e as credenciais em `src/includes/conexao.php`.
3. Consulte os logs do Apache e do PHP.
4. Verifique se a variável `WILKEEPER_LOGIN_RATE_LIMIT_KEY` está configurada no ambiente do Apache.
5. Confira se a tabela `login_rate_limits` existe.
6. Verifique o console do navegador para erros de JavaScript e carregamento de recursos.

Não exiba mensagens internas, senhas, tokens ou chaves secretas ao usuário final.

## 9. Versionamento e manutenção

- Realize commits pequenos e descritivos.
- Não versione arquivos com segredos ou credenciais.
- Atualize os documentos quando funcionalidades, banco ou configurações mudarem.
- Antes da apresentação, valide a instalação em ambiente limpo e execute os casos de teste prioritários.
