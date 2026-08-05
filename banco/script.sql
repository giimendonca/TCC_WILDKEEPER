CREATE DATABASE IF NOT EXISTS wildkeeper;
USE wildkeeper;

CREATE TABLE IF NOT EXISTS instituicoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    cnpj VARCHAR(18) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL UNIQUE,
    website VARCHAR(255) DEFAULT NULL,
    
    rua VARCHAR(150) NOT NULL,
    numero VARCHAR(20) DEFAULT 'S/N',
    bairro VARCHAR(80) NOT NULL,
    cidade VARCHAR(80) NOT NULL,
    estado CHAR(2) NOT NULL,
    cep VARCHAR(9) NOT NULL,

    logo VARCHAR(255) DEFAULT NULL,
    descricao TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cargos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(80) NOT NULL ,
    nivel INT NOT NULL,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO cargos (nome, nivel) VALUES
('Administrador', 100),
('Veterinário', 60),
('Tratador', 40),
('Biólogo', 50),
('Recepcionista', 20);

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    genero ENUM('Masculino', 'Feminino', 'Não-binário', 'Outro', 'Prefiro não informar') NOT NULL,
    telefone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    cargo_id INT NOT NULL,
    status ENUM('Ativo', 'Férias', 'Afastado', 'Desligado') DEFAULT 'Ativo',
    instituicao_id INT NOT NULL,

    FOREIGN KEY (cargo_id) REFERENCES cargos(id),
    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    sigla CHAR(2) NOT NULL UNIQUE,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categorias (nome, sigla) VALUES
('Mamíferos', 'MA'),
('Aves', 'AV'),
('Répteis', 'RE'),
('Anfíbios', 'AN'),
('Peixes', 'PE'),
('Invertebrados', 'IN');

CREATE TABLE IF NOT EXISTS classificacao_alimentar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    sigla CHAR(2) NOT NULL UNIQUE,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO classificacao_alimentar (nome, sigla) VALUES
('Carnívoro', 'CA'),
('Herbívoro', 'HE'),
('Onívoro', 'ON'),
('Insetívoro', 'IS'),
('Frugívoro', 'FR'),
('Piscívoro', 'PI');

CREATE TABLE IF NOT EXISTS risco_extincao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    sigla CHAR(2) NOT NULL UNIQUE,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO risco_extincao (nome, sigla) VALUES
('Pouco Preocupante', 'LC'),
('Quase Ameaçado', 'NT'),
('Vulnerável', 'VU'),
('Em Perigo', 'EN'),
('Criticamente em Perigo', 'CR'),
('Extinto na Natureza', 'EW'),
('Extinto', 'EX');

CREATE TABLE IF NOT EXISTS especies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_popular VARCHAR(255) NOT NULL,
    nome_cientifico VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    origem VARCHAR(255) NOT NULL,
    vida_media INT NOT NULL,
    peso_medio DECIMAL(10,3) NOT NULL,
    altura_media DECIMAL(10,3) NOT NULL,
    categoria_id INT NOT NULL,
    classificacao_alimentar_id INT NOT NULL,
    risco_extincao_id INT NOT NULL,

    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (classificacao_alimentar_id) REFERENCES classificacao_alimentar(id),
    FOREIGN KEY (risco_extincao_id) REFERENCES risco_extincao(id),
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS habitats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    bioma VARCHAR(50) NOT NULL,
    temperatura DECIMAL(5,2) NOT NULL,
    umidade DECIMAL(5,2) NOT NULL,
    capacidade INT NOT NULL,
    status ENUM('Ativo', 'Em manutenção', 'Interditado') DEFAULT 'Ativo',
    instituicao_id INT NOT NULL,

    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS status_animais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    sigla CHAR(2) NOT NULL,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO status_animais (nome, sigla) VALUES
('Em Exposição', 'EE'),
('Em Tratamento', 'ET'),
('Em Quarentena', 'EQ'),
('Em Adaptação', 'EA'),
('Transferido', 'TR');

CREATE TABLE IF NOT EXISTS saude_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    sigla CHAR(2) NOT NULL,
        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO saude_status (nome, sigla) VALUES
('Saudável', 'SA'),
('Em Observação', 'EO'),
('Doente', 'DO'),
('Em Recuperação', 'ER'),
('Crítico', 'CR');

CREATE TABLE IF NOT EXISTS animais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    sexo ENUM('Masculino', 'Feminino', 'Indeterminado'),
    data_nascimento DATE DEFAULT NULL,
    data_chegada DATE DEFAULT NULL,
    peso DECIMAL(10,3) NOT NULL,
    altura DECIMAL(10,3) NOT NULL,
    microchip VARCHAR(20) NOT NULL UNIQUE,
    observacoes TEXT,
    especie_id INT NOT NULL,
    habitat_id INT NOT NULL,
    status_id INT NOT NULL,
    saude_status_id INT NOT NULL,
    instituicao_id INT NOT NULL,
    
    FOREIGN KEY (especie_id) REFERENCES especies(id),
    FOREIGN KEY (habitat_id) REFERENCES habitats(id),
    FOREIGN KEY (status_id) REFERENCES status_animais(id),
    FOREIGN KEY (saude_status_id) REFERENCES saude_status(id),
    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS animais_fotos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    caminho_arquivo VARCHAR(255) NOT NULL,
    instituicao_id INT NOT NULL,

    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE,
    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS consultas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    data_consulta DATE NOT NULL,
    diagnostico TEXT NOT NULL,
    tratamento TEXT NOT NULL,
    observacoes TEXT,
    data_retorno DATE NOT NULL,

    FOREIGN KEY (animal_id) REFERENCES animais(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS medicamentos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    fabricante VARCHAR(50) NOT NULL,
    estoque INT NOT NULL,
    lote VARCHAR(50) NOT NULL,
    vencimento DATE NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS medicamentos_consulta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    consulta_id INT NOT NULL,
    medicamento_id INT NOT NULL,
    dosagem VARCHAR(50) NOT NULL,
    observacoes TEXT DEFAULT NULL,

    FOREIGN KEY (consulta_id) REFERENCES consultas(id) ON DELETE CASCADE,
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS alimentacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    descricao_alimento VARCHAR(100) NOT NULL,
    quantidade DECIMAL(8,2) NOT NULL,
    data_hora DATETIME NOT NULL,
    observacoes TEXT,

    FOREIGN KEY (animal_id) REFERENCES animais(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vacinas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    nome_vacina VARCHAR(50) NOT NULL,
    data_aplicacao DATE NOT NULL,
    proxima_aplicacao DATE DEFAULT NULL,
    observacoes TEXT DEFAULT NULL,
    funcionario_id INT NOT NULL,

    FOREIGN KEY (animal_id) REFERENCES animais(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS manutencao_habitats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    habitat_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    data_manutencao DATE NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('Pendente', 'Em andamento', 'Concluída', 'Cancelada') DEFAULT 'Pendente',

    FOREIGN KEY (habitat_id) REFERENCES habitats(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS historico_habitats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    habitat_anterior_id INT NOT NULL,
    habitat_novo_id INT NOT NULL,
    data_mudanca DATE NOT NULL,
    motivo TEXT NOT NULL,
    funcionario_id INT NOT NULL,

    FOREIGN KEY (animal_id) REFERENCES animais(id),
    FOREIGN KEY (habitat_anterior_id) REFERENCES habitats(id),
    FOREIGN KEY (habitat_novo_id) REFERENCES habitats(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS eventos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    tipo ENUM('Consulta', 'Vacinação', 'Alimentação', 'Manutenção', 'Transferência', 'Outro'),
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL,
    animal_id INT DEFAULT NULL,
    funcionario_id INT NOT NULL,
    status ENUM('Agendado', 'Em andamento', 'Concluído', 'Cancelado') DEFAULT 'Agendado',
    instituicao_id INT NOT NULL,

    FOREIGN KEY (animal_id) REFERENCES animais(id),
    FOREIGN KEY (funcionario_id) REFERENCES users(id),
    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;