# Diagrama de Arquitetura

O diagrama representa a organização lógica da aplicação no ambiente local. Ele não representa uma arquitetura MVC estrita.

```mermaid
flowchart TD
    U[Usuário] --> B[Navegador]
    B --> A[Apache]
    A --> P[Aplicação PHP]
    P --> I[Includes compartilhados]
    I --> C[Conexão e funções]
    P --> M[Módulos do sistema]
    M --> AU[Autenticação e autorização]
    M --> D[Dashboard]
    M --> R[Cadastros e rotinas]
    R --> E[Eventos, consultas, vacinas e manutenções]
    P --> DB[(MySQL: wildkeeper)]
    AU --> DB
    D --> DB
    R --> DB
```

## Componentes

- **Navegador:** apresenta a interface e envia requisições HTTP.
- **Apache:** recebe as requisições e encaminha a execução dos arquivos PHP.
- **Aplicação PHP:** processa regras de negócio, validações e operações.
- **Includes compartilhados:** concentram recursos reutilizados, como conexão, funções, autenticação e navegação.
- **MySQL:** armazena os dados da instituição e dos módulos.

Os detalhes das entidades e dos relacionamentos estão em [Modelo de Dados](modelo-de-dados.md).
