# Modelo de Dados

O diagrama abaixo apresenta as principais entidades e relacionamentos identificados em `banco/script.sql`. É uma visão de alto nível, não substitui a leitura do script SQL para conferir todos os campos, índices e restrições.

```mermaid
erDiagram
    INSTITUICOES ||--o{ USERS : possui
    INSTITUICOES ||--o{ HABITATS : possui
    INSTITUICOES ||--o{ ANIMAIS : registra
    INSTITUICOES ||--o{ MEDICAMENTOS : possui
    CARGOS ||--o{ USERS : define
    CATEGORIAS ||--o{ ESPECIES : classifica
    CLASSIFICACAO_ALIMENTAR ||--o{ ESPECIES : classifica
    RISCO_EXTINCAO ||--o{ ESPECIES : classifica
    ESPECIES ||--o{ ANIMAIS : identifica
    HABITATS ||--o{ ANIMAIS : abriga
    STATUS_ANIMAIS ||--o{ ANIMAIS : define
    SAUDE_STATUS ||--o{ ANIMAIS : define
    ANIMAIS ||--o{ ANIMAIS_FOTOS : possui
    ANIMAIS ||--o{ EVENTOS : relacionado
    HABITATS ||--o{ EVENTOS : relacionado
    USERS ||--o{ EVENTOS : responsavel
    EVENTOS ||--o| CONSULTAS : detalha
    EVENTOS ||--o| VACINAS : detalha
    EVENTOS ||--o| MANUTENCAO_HABITATS : detalha
    CONSULTAS ||--o{ MEDICAMENTOS_CONSULTA : prescreve
    MEDICAMENTOS ||--o{ MEDICAMENTOS_CONSULTA : utilizado
    ANIMAIS ||--o{ HISTORICO_HABITATS : possui
    HABITATS ||--o{ HISTORICO_HABITATS : habitat_anterior
    HABITATS ||--o{ HISTORICO_HABITATS : habitat_novo
    USERS ||--o{ HISTORICO_HABITATS : registra
    EVENTOS ||--o{ HISTORICO_HABITATS : referencia

    INSTITUICOES {
        int id PK
        varchar nome
        varchar cnpj
        varchar email
    }
    USERS {
        int id PK
        int cargo_id FK
        int instituicao_id FK
        varchar email
        varchar senha_hash
    }
    CARGOS {
        int id PK
        varchar nome
        int nivel
    }
    ESPECIES {
        int id PK
        int categoria_id FK
        int classificacao_alimentar_id FK
        int risco_extincao_id FK
    }
    HABITATS {
        int id PK
        int instituicao_id FK
        varchar nome
    }
    ANIMAIS {
        int id PK
        int especie_id FK
        int habitat_id FK
        int instituicao_id FK
        int status_id FK
        int saude_status_id FK
    }
    EVENTOS {
        int id PK
        int animal_id FK
        int habitat_id FK
        int funcionario_id FK
        int instituicao_id FK
        varchar tipo
        varchar status
    }
    CONSULTAS {
        int id PK
        int evento_id FK
    }
    MEDICAMENTOS {
        int id PK
        int instituicao_id FK
    }
    MEDICAMENTOS_CONSULTA {
        int id PK
        int consulta_id FK
        int medicamento_id FK
    }
    VACINAS {
        int id PK
        int evento_id FK
    }
    MANUTENCAO_HABITATS {
        int id PK
        int evento_id FK
    }
    ANIMAIS_FOTOS {
        int id PK
        int animal_id FK
        int instituicao_id FK
    }
    HISTORICO_HABITATS {
        int id PK
        int animal_id FK
        int habitat_anterior_id FK
        int habitat_novo_id FK
        int funcionario_id FK
        int evento_id FK
        int instituicao_id FK
    }
```

## Tabela de controle de tentativas de login

A tabela `login_rate_limits` armazena contadores e períodos de bloqueio. Os identificadores são HMAC binários e não representam diretamente e-mail ou endereço IP.

## Observações

- Espécies e tabelas de classificação são tabelas de referência compartilhadas.
- Dados operacionais são associados à instituição conforme as chaves estrangeiras definidas no banco.
- O tipo do evento determina quais associações devem estar preenchidas.
- A tabela de alimentação não faz parte do modelo atual.
- Para detalhes completos, consultar `banco/script.sql`.
