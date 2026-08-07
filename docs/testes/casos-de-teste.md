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

