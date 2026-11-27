# Casos de Teste

## CT-001 - Cadastro de Instituição com dados válidos

### Objetivo
Verificar se uma instituição e seu usuário administrador são cadastrados corretamente.

### Pré-condições
- Banco de dados disponível.
- Não existir instituição com o mesmo CNPJ, telefone, nome ou e-mail.
- Não existir usuário com o mesmo CPF, telefone ou e-mail.

### Dados utilizados

Instituição

- Nome: Zoológico Municipal da Cidade de Bauruzinho
- CNPJ: 11.111.111/0001-11
- Telefone: (11) 11111-1111
- E-mail: zoologicoBauruzinho@email.com
- Website: https://zoologico.bauruzinho.com.br
- CEP: 11111-111
- Rua: Avenida dos Bauruzinhos Mineiros
- Número: 111
- Bairro: Parque dos Bauruzinhos
- Cidade: Bauruzinho
- Estado: SP

Administrador

- Nome: Maria Clara Caputo Hamburguão
- CPF: 111.111.111-11
- Telefone: (11) 11111-1111
- Email: mariaHamburgao@email.com
- Data de nascimento: 01/01/2006
- Gênero: Feminino
- Senha: ********

### Resultado esperado

- Instituição cadastrada.
- Usuário administrador criado.
- Relacionamento entre usuário e instituição criado corretamente.

### Resultado obtido

Cadastro realizado com sucesso.

### Status

✅ Aprovado

## CT-002 - Campos obrigatórios

### Objetivo

Verificar se o sistema impede cadastro com campos vazios.

### Resultado esperado

Exibir mensagem informando que existem campos obrigatórios não preenchidos.

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-003 - CNPJ duplicado

Objetivo

Verificar se o sistema impede duas instituições com o mesmo CNPJ.

Resultado esperado

Mensagem:

"CNPJ já cadastrado."

Resultado obtido

Sistema bloqueou o cadastro.

Status

✅ Aprovado

## CT-004 - Nome da instituição duplicado

### Objetivo

Verificar se o sistema impede o cadastro de duas instituições com o mesmo nome.

### Resultado esperado

Exibir a mensagem:

"Nome da instituição já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-005 - E-mail da instituição duplicado

### Objetivo

Verificar se o sistema impede o cadastro de duas instituições utilizando o mesmo e-mail.

### Resultado esperado

Exibir a mensagem:

"Email já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-006 - Telefone da instituição duplicado

### Objetivo

Verificar se o sistema impede o cadastro de duas instituições utilizando o mesmo telefone.

### Resultado esperado

Exibir a mensagem:

"Telefone já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-007 - CPF duplicado

### Objetivo

Verificar se o sistema impede o cadastro de dois usuários administradores com o mesmo CPF.

### Resultado esperado

Exibir a mensagem:

"CPF já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-008 - E-mail do administrador duplicado

### Objetivo

Verificar se o sistema impede o cadastro de dois administradores utilizando o mesmo e-mail.

### Resultado esperado

Exibir a mensagem:

"Email do usuário já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-009 - Telefone do administrador duplicado

### Objetivo

Verificar se o sistema impede o cadastro de dois administradores utilizando o mesmo telefone.

### Resultado esperado

Exibir a mensagem:

"Telefone já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-010 - CNPJ inválido

### Objetivo

Verificar se o sistema valida o tamanho do CNPJ informado.

### Resultado esperado

Exibir a mensagem:

"CNPJ inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-011 - CPF inválido

### Objetivo

Verificar se o sistema valida o tamanho do CPF informado.

### Resultado esperado

Exibir a mensagem:

"CPF inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-012 - CEP inválido

### Objetivo

Verificar se o sistema valida o tamanho do CEP informado.

### Resultado esperado

Exibir a mensagem:

"CEP inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-013 - Estado inválido

### Objetivo

Verificar se o sistema aceita apenas siglas de estado com duas letras.

### Resultado esperado

Exibir a mensagem:

"Estado inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado



## CT-014 - Senhas diferentes

### Objetivo

Verificar se o sistema impede o cadastro quando as senhas informadas são diferentes.

### Resultado esperado

Exibir a mensagem:

"As senhas devem ser iguais."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-015 - E-mail inválido da instituição

### Objetivo

Verificar se o sistema valida corretamente o formato do e-mail da instituição.

### Resultado esperado

Exibir a mensagem:

"Email da instituição inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-016 - E-mail inválido do administrador

### Objetivo

Verificar se o sistema valida corretamente o formato do e-mail do administrador.

### Resultado esperado

Exibir a mensagem:

"Email do usuário inválido."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-017 - Cadastro de funcionário com dados válidos
### Objetivo

Verificar se um novo funcionário é cadastrado corretamente no sistema.

Pré-condições
Usuário administrador autenticado.
Instituição cadastrada.
Cargo disponível para seleção.
E-mail, CPF e telefone do funcionário ainda não cadastrados.
Dados utilizados
Nome: João da Silva
CPF: 222.222.222-22
Data de nascimento: 10/05/1995
Gênero: Masculino
Telefone: (11) 22222-2222
E-mail: joao.silva@email.com
Cargo: Tratador
Senha: ********
Confirmação de senha: ********

### Resultado esperado
Funcionário cadastrado com sucesso.
Senha armazenada utilizando hash.
Funcionário associado à instituição do administrador autenticado.
Funcionário associado ao cargo selecionado.
Usuário criado com status padrão "Ativo".

### Resultado obtido

Funcionário cadastrado com sucesso.

### Status

✅ Aprovado

## CT-018 - Campos obrigatórios do funcionário
### Objetivo

Verificar se o sistema impede o cadastro de um funcionário com campos obrigatórios não preenchidos.

### Resultado esperado

Exibir a mensagem:

"Há campos obrigatórios não preenchidos."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-019 - CPF do funcionário duplicado
### Objetivo

Verificar se o sistema impede o cadastro de um funcionário utilizando um CPF já cadastrado.

### Resultado esperado

Exibir a mensagem:

"CPF já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-020 - Telefone do funcionário duplicado
### Objetivo

Verificar se o sistema impede o cadastro de um funcionário utilizando um telefone já cadastrado.

### Resultado esperado

Exibir a mensagem:

"Telefone já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-021 - E-mail do funcionário duplicado
### Objetivo

Verificar se o sistema impede o cadastro de um funcionário utilizando um e-mail já cadastrado.

### Resultado esperado

Exibir a mensagem:

"Email já cadastrado."

### Resultado obtido

Sistema bloqueou o cadastro.

### Status

✅ Aprovado

## CT-022 - E-mail inválido no cadastro de funcionário

### Objetivo
Verificar se o sistema impede o cadastro de funcionário quando o endereço de e-mail possui formato inválido.

### Resultado esperado
O sistema deve rejeitar o endereço inválido e informar que o e-mail precisa ser corrigido.

### Resultado obtido
O sistema bloqueou o cadastro com o e-mail inválido, conforme registro de teste existente.

### Status
✅ Aprovado


## CT-023 - Tentativas inválidas de login

### Objetivo
Verificar se o sistema registra tentativas consecutivas de autenticação inválidas para a mesma combinação de e-mail e endereço IP.

### Resultado esperado
As tentativas devem ser contabilizadas sem revelar se a conta existe. Ao atingir o limite configurado para a combinação, o sistema deve responder com HTTP 429.

### Resultado obtido
O limite foi atingido na quinta tentativa inválida e o sistema retornou HTTP 429.

### Status
✅ Aprovado

## CT-024 - Cabeçalho Retry-After durante bloqueio

### Objetivo
Verificar se o sistema informa ao cliente por quanto tempo deve aguardar após o bloqueio por excesso de tentativas.

### Resultado esperado
A resposta HTTP 429 deve incluir o cabeçalho Retry-After com o tempo restante do bloqueio em segundos.

### Resultado obtido
O cabeçalho Retry-After foi recebido com o tempo restante do bloqueio.

### Status
✅ Aprovado

## CT-025 - Recuperação do login após o bloqueio

### Objetivo
Verificar se uma conta pode realizar autenticação normalmente após o encerramento do período de bloqueio.

### Resultado esperado
Após o período de 15 minutos, uma tentativa com credenciais válidas deve ser processada normalmente.

### Resultado obtido
Após aguardar o período de bloqueio, o login foi realizado com sucesso.

### Status
✅ Aprovado

## CT-026 - Rejeição de requisição sem token CSRF em ação protegida

### Objetivo
Verificar se uma ação protegida rejeita uma requisição POST sem token CSRF válido.

### Resultado esperado
O sistema deve responder com HTTP 403 e não deve executar a alteração solicitada.

### Resultado obtido
Pendente de execução após a revisão final dos formulários protegidos.

### Status
⏳ Pendente

## CT-027 - Rejeição de token CSRF inválido

### Objetivo
Verificar se uma ação protegida rejeita um token CSRF incorreto.

### Resultado esperado
O sistema deve responder com HTTP 403 e não deve executar a alteração solicitada.

### Resultado obtido
Pendente de execução.

### Status
⏳ Pendente
