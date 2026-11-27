# WildKeeper

Sistema web para gerenciamento de zoológicos e instituições de conservação animal.

## Tecnologias

- HTML
- CSS
- JavaScript
- PHP
- MySQL

## Configuração do ambiente local

### Chave do rate limiting de login

O mecanismo de proteção contra tentativas excessivas de login utiliza a variável de ambiente `WILKEEPER_LOGIN_RATE_LIMIT_KEY`. Essa chave é um segredo usado para gerar identificadores HMAC dos registros de limitação no banco de dados.

Cada ambiente de execução independente deve ter essa variável configurada. **Não coloque a chave neste repositório, em arquivos PHP versionados ou em commits.**

No Windows com XAMPP:

1. Gere uma chave aleatória de 32 bytes (64 caracteres hexadecimais) no terminal:

   ```powershell
   php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
   ```

   Se o comando `php` não for reconhecido, execute-o usando o caminho completo do PHP instalado.

2. Abra a configuração do Apache do XAMPP, normalmente em:

   `C:\xampp\apache\conf\httpd.conf`

3. Adicione uma linha com a chave gerada:

   ```apache
   SetEnv WILKEEPER_LOGIN_RATE_LIMIT_KEY "COLE_AQUI_SUA_CHAVE_GERADA"
   ```

4. Salve o arquivo e reinicie o Apache pelo painel do XAMPP.

5. Mantenha a chave em local privado. Não a compartilhe nem a envie ao GitHub.

**Importante sobre vários computadores:** a chave não é vinculada fisicamente ao hardware. Se cada computador tiver sua própria instalação e seu próprio banco de dados local, gere uma chave própria em cada computador. Se várias instalações do WildKeeper compartilharem o mesmo banco de dados, configure a mesma chave secreta em todas elas para que os identificadores HMAC sejam consistentes. Nunca publique essa chave.

A configuração acima é feita no Apache do ambiente local. Ela não exige alterar a URL ou a pasta `htdocs`, mas a variável fica disponível para os projetos atendidos por essa instância do Apache; somente o código que a consulta a utilizará.

### Banco de dados

Depois de criar/configurar o banco `wildkeeper`, importe o arquivo:

`banco/script.sql`

A tabela `login_rate_limits` já está incluída no arquivo `banco/script.sql`. Não existe um arquivo de migração separado para essa tabela.

Se estiver atualizando um banco existente, faça backup e verifique primeiro se a tabela `login_rate_limits` já existe. Caso não exista, execute somente o bloco `CREATE TABLE login_rate_limits` que está no próprio `banco/script.sql`; não execute o script inteiro sobre uma base com dados sem avaliar os comandos de inserção inicial.

## Equipe

Giovana Mendonça Raimundo

Giovanna Ramos de Oliveira

Turma 2B - SESI

## Status

🚧 Em desenvolvimento

## Documentação

A documentação do projeto está organizada em `docs/`:

- `docs/guia-do-projeto.md`: convenções e padrões.
- `docs/modulos.md`: módulos e escopo atual.
- `docs/requisitos.md`: requisitos funcionais e não funcionais.
- `docs/scrum.md`: organização do desenvolvimento.
- `docs/design-system.md`: identidade visual.
- `docs/manual-do-usuario.md`: orientação de uso.
- `docs/manuais/manual-do-projeto.md`: visão geral do projeto.
- `docs/manuais/manual-tecnico.md`: instalação e manutenção.
- `docs/diagramas/`: diagramas de arquitetura e dados.
- `docs/testes/`: casos de teste e registro de bugs.
- `docs/atas/`: modelo para registrar reuniões reais.
