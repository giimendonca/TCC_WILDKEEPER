# Correções de Bugs

## BUG-001

### Data

06/08/2026

### Problema

A função registroExiste() retornava verdadeiro mesmo quando não existiam registros na tabela.

### Causa

A função verificava apenas se o objeto retornado por get_result() existia.

Como get_result() sempre retorna um objeto de resultado válido, a função sempre retornava verdadeiro.

### Correção

Alteração da lógica para utilizar:

return $result->num_rows > 0;

### Status

Corrigido

## BUG-002

### Data

06/08/2026

### Problema

Erro:

Data too long for column telefone.

### Causa

Incompatibilidade entre o formato enviado para o banco e o tipo definido na tabela.

### Correção

Ajuste do banco de dados e normalização do telefone utilizando apenasNumeros() antes da inserção.

### Status

Corrigido

## BUG-003

### Problema

Erro de validação do telefone.

### Causa

Foi utilizado:

strlen($telefone !== 11)

ao invés de

strlen($telefone) !== 11

### Correção

Correção da condição.

### Status

Corrigido