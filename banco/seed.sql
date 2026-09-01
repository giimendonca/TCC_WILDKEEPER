-- ============================================================
-- WILDKEEPER - SEED DE DADOS DE DEMONSTRAÇÃO
-- ============================================================
-- Este arquivo NÃO insere novamente as tabelas fixas/de referência:
-- cargos, categorias, classificacao_alimentar, risco_extincao,
-- status_animais e saude_status.
--
-- ATENÇÃO: o bloco de limpeza abaixo apaga TODOS os dados das
-- tabelas variáveis. Use apenas no banco de desenvolvimento/teste.
-- ============================================================

USE wildkeeper;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE medicamentos_consulta;
TRUNCATE TABLE eventos;
TRUNCATE TABLE historico_habitats;
TRUNCATE TABLE manutencao_habitats;
TRUNCATE TABLE vacinas;
TRUNCATE TABLE alimentacoes;
TRUNCATE TABLE consultas;
TRUNCATE TABLE animais_fotos;
TRUNCATE TABLE animais;
TRUNCATE TABLE medicamentos;
TRUNCATE TABLE habitats;
TRUNCATE TABLE users;
TRUNCATE TABLE especies;
TRUNCATE TABLE instituicoes;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- INSTITUIÇÕES
-- ============================================================

INSERT INTO instituicoes
(id, nome, cnpj, email, telefone, website, rua, numero, bairro, cidade, estado, cep, logo, descricao)
VALUES
(1, 'Instituto Vida Selvagem Paulista', '00.000.000/0001-01', 'contato@ivsp.example', '(11) 3000-1001', 'https://ivsp.example', 'Rua das Palmeiras', '120', 'Jardim Verde', 'São Paulo', 'SP', '01000-001', NULL, 'Instituição fictícia destinada à conservação, pesquisa e manejo de animais silvestres.'),
(2, 'Centro de Conservação Mata Atlântica', '00.000.000/0002-02', 'contato@ccma.example', '(11) 3000-1002', 'https://ccma.example', 'Avenida da Mata', '450', 'Vila das Árvores', 'Campinas', 'SP', '13000-002', NULL, 'Instituição fictícia voltada à conservação da fauna e educação ambiental.');

-- ============================================================
-- USUÁRIOS
-- Senha de todos: 123456
-- Hash bcrypt fornecido pelo projeto.
-- 5 cargos por instituição.
-- ============================================================

INSERT INTO users
(id, nome, cpf, data_nascimento, genero, telefone, email, senha_hash, cargo_id, status, instituicao_id)
VALUES
(1, 'Marina Alves', '111.111.111-01', '1988-04-12', 'Feminino', '(11) 91000-1001', 'marina.alves@ivsp.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 1, 'Ativo', 1),
(2, 'Rafael Mendes', '111.111.111-02', '1987-09-23', 'Masculino', '(11) 91000-1002', 'rafael.mendes@ivsp.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 2, 'Ativo', 1),
(3, 'Camila Rocha', '111.111.111-03', '1992-01-18', 'Feminino', '(11) 91000-1003', 'camila.rocha@ivsp.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 3, 'Ativo', 1),
(4, 'Bruno Martins', '111.111.111-04', '1990-07-05', 'Masculino', '(11) 91000-1004', 'bruno.martins@ivsp.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 4, 'Férias', 1),
(5, 'Larissa Costa', '111.111.111-05', '1995-11-30', 'Feminino', '(11) 91000-1005', 'larissa.costa@ivsp.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 5, 'Ativo', 1),
(6, 'Eduardo Lima', '222.222.222-01', '1985-03-14', 'Masculino', '(19) 92000-2001', 'eduardo.lima@ccma.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 1, 'Ativo', 2),
(7, 'Juliana Ferreira', '222.222.222-02', '1989-06-27', 'Feminino', '(19) 92000-2002', 'juliana.ferreira@ccma.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 2, 'Ativo', 2),
(8, 'Diego Nunes', '222.222.222-03', '1991-10-09', 'Masculino', '(19) 92000-2003', 'diego.nunes@ccma.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 3, 'Ativo', 2),
(9, 'Bianca Souza', '222.222.222-04', '1993-02-21', 'Feminino', '(19) 92000-2004', 'bianca.souza@ccma.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 4, 'Afastado', 2),
(10, 'Felipe Oliveira', '222.222.222-05', '1996-08-16', 'Masculino', '(19) 92000-2005', 'felipe.oliveira@ccma.example', '$2y$10$gsfGxmNjZDLE75cZ/LISzOrfSR6IRlWIctTNwQsfSP955tAB/F/x2', 5, 'Ativo', 2);

-- ============================================================
-- ESPÉCIES
-- IDs 1-15 serão utilizadas na instituição 1.
-- IDs 16-30 serão utilizadas na instituição 2.
-- A tabela especies é global e não possui instituicao_id.
-- ============================================================

INSERT INTO especies
(id, nome_popular, nome_cientifico, descricao, origem, vida_media, peso_medio, altura_media, categoria_id, classificacao_alimentar_id, risco_extincao_id)
VALUES
(1, 'Leão', 'Panthera leo', 'Grande felino social que vive em grupos e possui hábitos predominantemente crepusculares.', 'África', 15, 190.000, 1.200, 1, 1, 3),
(2, 'Onça-pintada', 'Panthera onca', 'Maior felino das Américas, excelente nadador e importante predador de ecossistemas florestais.', 'Américas', 15, 85.000, 0.800, 1, 1, 3),
(3, 'Girafa', 'Giraffa camelopardalis', 'Mamífero de grande porte reconhecido pelo pescoço longo e alimentação baseada em folhas.', 'África', 25, 800.000, 5.000, 1, 2, 3),
(4, 'Elefante-africano', 'Loxodonta africana', 'Maior animal terrestre atual, com estrutura social complexa e forte vínculo entre indivíduos.', 'África', 60, 4500.000, 3.200, 1, 2, 3),
(5, 'Lobo-guará', 'Chrysocyon brachyurus', 'Canídeo brasileiro de pernas longas, de hábitos solitários e dieta variada.', 'América do Sul', 15, 25.000, 0.900, 1, 3, 2),
(6, 'Macaco-prego', 'Sapajus libidinosus', 'Primata inteligente e adaptável, encontrado em diferentes ambientes brasileiros.', 'América do Sul', 25, 3.500, 0.450, 1, 3, 1),
(7, 'Tamanduá-bandeira', 'Myrmecophaga tridactyla', 'Mamífero especializado no consumo de formigas e cupins, com focinho alongado.', 'América do Sul', 20, 35.000, 0.700, 1, 4, 3),
(8, 'Arara-azul', 'Anodorhynchus hyacinthinus', 'Grande ave brasileira de plumagem azul, conhecida por formar pares duradouros.', 'América do Sul', 50, 1.400, 1.000, 2, 3, 3),
(9, 'Tucano-toco', 'Ramphastos toco', 'Ave de grande bico e dieta variada, comum em áreas abertas e bordas de florestas.', 'América do Sul', 20, 0.700, 0.600, 2, 5, 1),
(10, 'Pinguim-de-magalhães', 'Spheniscus magellanicus', 'Ave marinha adaptada à vida aquática e capaz de percorrer longas distâncias em busca de alimento.', 'América do Sul', 25, 4.000, 0.450, 2, 6, 1),
(11, 'Jiboia', 'Boa constrictor', 'Serpente não peçonhenta que captura suas presas por constrição.', 'América do Sul', 25, 18.000, 2.500, 3, 1, 1),
(12, 'Iguana-verde', 'Iguana iguana', 'Répteis arborícolas que utilizam principalmente vegetação como fonte de alimento.', 'América Central e do Sul', 20, 5.000, 1.500, 3, 2, 1),
(13, 'Jacaré-do-pantanal', 'Caiman yacare', 'Crocodiliano associado a ambientes alagados e importante predador aquático.', 'América do Sul', 35, 35.000, 2.500, 3, 1, 1),
(14, 'Sapo-cururu', 'Rhinella icterica', 'Anfíbio de grande porte encontrado em diversos ambientes brasileiros.', 'América do Sul', 10, 0.600, 0.200, 4, 4, 1),
(15, 'Capivara', 'Hydrochoerus hydrochaeris', 'Maior roedor atual, de hábitos semiaquáticos e comportamento social.', 'América do Sul', 12, 55.000, 0.600, 1, 2, 1),
(16, 'Urso-pardo', 'Ursus arctos', 'Grande mamífero onívoro encontrado em diferentes regiões do hemisfério norte.', 'Europa, Ásia e América do Norte', 25, 300.000, 1.500, 1, 3, 1),
(17, 'Panda-vermelho', 'Ailurus fulgens', 'Pequeno mamífero arborícola de pelagem avermelhada e hábitos predominantemente crepusculares.', 'Ásia', 14, 5.000, 0.600, 1, 3, 3),
(18, 'Hipopótamo', 'Hippopotamus amphibius', 'Grande mamífero semiaquático que passa boa parte do dia em ambientes aquáticos.', 'África', 40, 1500.000, 1.500, 1, 2, 1),
(19, 'Zebra-de-planície', 'Equus quagga', 'Equídeo social reconhecido pelo padrão único de listras no corpo.', 'África', 25, 320.000, 1.300, 1, 2, 1),
(20, 'Suricato', 'Suricata suricatta', 'Pequeno mamífero social que vive em grupos e utiliza tocas subterrâneas.', 'África', 12, 0.800, 0.300, 1, 3, 1),
(21, 'Gorila-ocidental', 'Gorilla gorilla', 'Grande primata social que vive em grupos familiares e se alimenta principalmente de vegetação.', 'África', 40, 160.000, 1.700, 1, 2, 4),
(22, 'Orangotango', 'Pongo pygmaeus', 'Primata arborícola de grande porte, conhecido por sua inteligência e hábitos solitários.', 'Ásia', 45, 75.000, 1.300, 1, 3, 5),
(23, 'Flamingo', 'Phoenicopterus roseus', 'Ave aquática de pernas longas e plumagem rosada, adaptada à alimentação em áreas rasas.', 'Europa, África e Ásia', 40, 3.000, 1.400, 2, 3, 1),
(24, 'Águia-careca', 'Haliaeetus leucocephalus', 'Ave de rapina conhecida pela cabeça branca nos indivíduos adultos.', 'América do Norte', 30, 5.000, 0.900, 2, 1, 1),
(25, 'Coruja-buraqueira', 'Athene cunicularia', 'Pequena coruja terrestre que utiliza tocas e apresenta atividade principalmente crepuscular e noturna.', 'Américas', 10, 0.150, 0.250, 2, 1, 1),
(26, 'Dragão-de-komodo', 'Varanus komodoensis', 'Grande lagarto predador encontrado em poucas ilhas da Indonésia.', 'Ásia', 30, 70.000, 0.800, 3, 1, 3),
(27, 'Tartaruga-verde', 'Chelonia mydas', 'Tartaruga marinha que utiliza diferentes ambientes ao longo do ciclo de vida.', 'Oceanos tropicais e subtropicais', 80, 120.000, 1.000, 3, 2, 4),
(28, 'Axolote', 'Ambystoma mexicanum', 'Anfíbio aquático conhecido por conservar características larvais durante a vida adulta.', 'México', 15, 0.300, 0.250, 4, 4, 5),
(29, 'Peixe-palhaço', 'Amphiprion ocellaris', 'Pequeno peixe marinho associado a anêmonas e conhecido por sua coloração alaranjada.', 'Oceano Indo-Pacífico', 10, 0.250, 0.150, 5, 6, 1),
(30, 'Pirarucu', 'Arapaima gigas', 'Grande peixe de água doce da Amazônia, capaz de realizar respiração aérea periódica.', 'América do Sul', 20, 150.000, 2.500, 5, 6, 1);

-- ============================================================
-- HABITATS
-- ============================================================

INSERT INTO habitats
(id, nome, descricao, bioma, temperatura, umidade, capacidade, status, instituicao_id)
VALUES
(1, 'Savana Africana', 'Área ampla com vegetação rasteira, árvores esparsas e espaço para grandes mamíferos.', 'Savana', 26.00, 55.00, 8, 'Ativo', 1),
(2, 'Floresta Amazônica', 'Ambiente arborizado e úmido com estruturas para animais arborícolas.', 'Floresta tropical', 25.00, 85.00, 10, 'Ativo', 1),
(3, 'Pantanal', 'Área com vegetação aquática e espaço de acesso à água.', 'Pantanal', 27.00, 75.00, 8, 'Ativo', 1),
(4, 'Aviário Tropical', 'Viveiro com árvores, poleiros e áreas abertas para aves.', 'Floresta tropical', 24.00, 70.00, 12, 'Ativo', 1),
(5, 'Terrário Tropical', 'Ambiente controlado para répteis e anfíbios tropicais.', 'Floresta tropical', 25.00, 80.00, 10, 'Em manutenção', 1),
(6, 'Área Semiaquática', 'Espaço com tanque e área terrestre para espécies semiaquáticas.', 'Zona úmida', 26.00, 78.00, 8, 'Ativo', 1),
(7, 'Floresta Temperada', 'Ambiente arborizado com áreas de sombra e estruturas de escalada.', 'Floresta temperada', 19.00, 65.00, 8, 'Ativo', 2),
(8, 'Recinto Asiático', 'Área temática com vegetação e estruturas para espécies asiáticas.', 'Floresta tropical', 23.00, 72.00, 8, 'Ativo', 2),
(9, 'Planície Africana', 'Recinto aberto com vegetação rasteira e abrigo para grandes herbívoros.', 'Savana', 25.00, 58.00, 10, 'Ativo', 2),
(10, 'Lago das Aves', 'Lago artificial com margens rasas e áreas de descanso.', 'Zona úmida', 22.00, 75.00, 12, 'Ativo', 2),
(11, 'Terrário de Répteis', 'Ambiente climatizado para grandes répteis.', 'Floresta tropical', 28.00, 70.00, 8, 'Em manutenção', 2),
(12, 'Aquário Tropical', 'Tanques de água doce e salgada para espécies aquáticas.', 'Ambiente aquático', 24.00, 80.00, 20, 'Ativo', 2);

-- ============================================================
-- ANIMAIS
-- 15 por instituição, cada um associado a uma espécie diferente.
-- ============================================================

INSERT INTO animais
(id, nome, sexo, data_nascimento, data_chegada, peso, altura, microchip, observacoes, especie_id, habitat_id, status_id, saude_status_id, instituicao_id)
VALUES
(1, 'Simba', 'Masculino', '2021-05-10', '2022-01-15', 185.500, 1.180, 'WK000000000001', 'Animal ativo e sociável. Acompanha rotina de alimentação normalmente.', 1, 1, 1, 1, 1),
(2, 'Juma', 'Feminino', '2019-08-22', '2020-03-10', 78.300, 0.790, 'WK000000000002', 'Apresenta bom comportamento durante os manejos.', 2, 2, 1, 1, 1),
(3, 'Amélia', 'Feminino', '2018-02-14', '2020-06-18', 760.000, 4.850, 'WK000000000003', 'Monitoramento periódico de peso recomendado.', 3, 1, 1, 2, 1),
(4, 'Bento', 'Masculino', '2016-11-03', '2019-04-20', 4200.000, 3.050, 'WK000000000004', 'Recebe alimentação distribuída em diferentes pontos do recinto.', 4, 1, 1, 1, 1),
(5, 'Guará', 'Masculino', '2022-01-12', '2023-02-02', 24.200, 0.880, 'WK000000000005', 'Animal em acompanhamento comportamental.', 5, 3, 1, 1, 1),
(6, 'Pipoca', 'Feminino', '2020-07-19', '2021-01-11', 3.200, 0.430, 'WK000000000006', 'Demonstra comportamento exploratório frequente.', 6, 2, 1, 1, 1),
(7, 'Teca', 'Feminino', '2019-03-27', '2021-08-09', 33.800, 0.680, 'WK000000000007', 'Em observação após alteração de apetite.', 7, 3, 2, 2, 1),
(8, 'Azul', 'Masculino', '2020-10-05', '2022-05-17', 1.350, 0.980, 'WK000000000008', 'Ave ativa e com alimentação regular.', 8, 4, 1, 1, 1),
(9, 'Tico', 'Masculino', '2021-01-30', '2022-09-12', 0.680, 0.580, 'WK000000000009', 'Sem alterações clínicas recentes.', 9, 4, 1, 1, 1),
(10, 'Maré', 'Feminino', '2019-12-11', '2021-02-25', 3.900, 0.440, 'WK000000000010', 'Em adaptação ao recinto após mudança de área.', 10, 6, 4, 4, 1),
(11, 'Jibo', 'Masculino', '2017-06-16', '2020-11-01', 17.500, 2.300, 'WK000000000011', 'Alimentação monitorada semanalmente.', 11, 5, 1, 1, 1),
(12, 'Lima', 'Feminino', '2021-09-08', '2023-01-18', 4.700, 1.420, 'WK000000000012', 'Em acompanhamento de crescimento.', 12, 5, 1, 2, 1),
(13, 'Pantã', 'Masculino', '2018-04-25', '2020-07-03', 32.000, 2.350, 'WK000000000013', 'Apresenta comportamento alimentar normal.', 13, 3, 1, 1, 1),
(14, 'Cururu', 'Indeterminado', '2023-01-20', '2023-03-15', 0.520, 0.190, 'WK000000000014', 'Animal jovem em adaptação ao recinto.', 14, 5, 4, 4, 1),
(15, 'Capi', 'Feminino', '2020-05-13', '2021-06-28', 52.500, 0.580, 'WK000000000015', 'Animal sociável. Integra grupo estável.', 15, 6, 1, 1, 1),
(16, 'Bruno', 'Masculino', '2018-02-07', '2021-03-11', 285.000, 1.470, 'WK000000000016', 'Animal ativo. Monitoramento de peso trimestral.', 16, 7, 1, 1, 2),
(17, 'Nina', 'Feminino', '2021-09-15', '2022-04-19', 4.800, 0.580, 'WK000000000017', 'Apresenta boa adaptação ao ambiente arborizado.', 17, 8, 1, 1, 2),
(18, 'Hippo', 'Masculino', '2017-05-22', '2020-02-14', 1480.000, 1.480, 'WK000000000018', 'Em tratamento preventivo de rotina.', 18, 9, 2, 2, 2),
(19, 'Listrado', 'Masculino', '2020-11-02', '2022-01-07', 315.000, 1.280, 'WK000000000019', 'Comportamento social estável.', 19, 9, 1, 1, 2),
(20, 'Kiko', 'Masculino', '2022-03-11', '2023-01-09', 0.780, 0.290, 'WK000000000020', 'Animal jovem. Alimentação fracionada.', 20, 9, 1, 1, 2),
(21, 'Kong', 'Masculino', '2015-06-18', '2019-08-21', 155.000, 1.680, 'WK000000000021', 'Em acompanhamento veterinário por alteração articular.', 21, 7, 2, 3, 2),
(22, 'Mimi', 'Feminino', '2014-12-05', '2019-11-30', 72.000, 1.280, 'WK000000000022', 'Necessita enriquecimento ambiental frequente.', 22, 8, 1, 2, 2),
(23, 'Rosa', 'Feminino', '2020-07-27', '2022-06-10', 2.900, 1.350, 'WK000000000023', 'Sem alterações clínicas.', 23, 10, 1, 1, 2),
(24, 'Sky', 'Feminino', '2019-03-09', '2021-09-14', 4.600, 0.880, 'WK000000000024', 'Animal em recuperação após procedimento veterinário.', 24, 10, 2, 4, 2),
(25, 'Lua', 'Feminino', '2022-01-25', '2023-02-22', 0.140, 0.240, 'WK000000000025', 'Atividade noturna normal.', 25, 10, 1, 1, 2),
(26, 'Komodo', 'Masculino', '2016-10-18', '2020-05-12', 68.500, 0.780, 'WK000000000026', 'Alimentação controlada e manejo realizado por equipe treinada.', 26, 11, 1, 1, 2),
(27, 'Marina', 'Feminino', '2018-06-04', '2021-01-29', 115.000, 0.980, 'WK000000000027', 'Animal em recuperação após avaliação clínica.', 27, 12, 2, 4, 2),
(28, 'Axel', 'Masculino', '2022-08-12', '2023-02-06', 0.280, 0.240, 'WK000000000028', 'Em observação devido a baixa atividade.', 28, 12, 2, 2, 2),
(29, 'Nemo', 'Masculino', '2023-01-14', '2023-04-03', 0.230, 0.140, 'WK000000000029', 'Animal jovem, alimentação monitorada.', 29, 12, 4, 4, 2),
(30, 'Açu', 'Masculino', '2019-05-26', '2021-07-16', 145.000, 2.420, 'WK000000000030', 'Animal ativo e com boa resposta à alimentação.', 30, 12, 1, 1, 2);

-- ============================================================
-- FOTOS
-- Os caminhos são exemplos. Coloque as imagens correspondentes
-- na pasta do projeto ou altere os caminhos conforme sua estrutura.
-- ============================================================

INSERT INTO animais_fotos (id, animal_id, caminho_arquivo, instituicao_id) VALUES
(1, 1, 'assets/img/animais/leao-simba.jpg', 1),
(2, 2, 'assets/img/animais/onca-juma.jpg', 1),
(3, 3, 'assets/img/animais/girafa-amelia.jpg', 1),
(4, 4, 'assets/img/animais/elefante-bento.jpg', 1),
(5, 5, 'assets/img/animais/lobo-guara-guara.jpg', 1),
(6, 6, 'assets/img/animais/macaco-pipoca.jpg', 1),
(7, 7, 'assets/img/animais/tamandua-teca.jpg', 1),
(8, 8, 'assets/img/animais/arara-azul.jpg', 1),
(9, 9, 'assets/img/animais/tucano-tico.jpg', 1),
(10, 10, 'assets/img/animais/pinguim-mare.jpg', 1),
(11, 11, 'assets/img/animais/jiboia-jibo.jpg', 1),
(12, 12, 'assets/img/animais/iguana-lima.jpg', 1),
(13, 13, 'assets/img/animais/jacare-panta.jpg', 1),
(14, 14, 'assets/img/animais/sapo-cururu.jpg', 1),
(15, 15, 'assets/img/animais/capivara-capi.jpg', 1),
(16, 16, 'assets/img/animais/urso-bruno.jpg', 2),
(17, 17, 'assets/img/animais/panda-nina.jpg', 2),
(18, 18, 'assets/img/animais/hipopotamo-hippo.jpg', 2),
(19, 19, 'assets/img/animais/zebra-listrado.jpg', 2),
(20, 20, 'assets/img/animais/suricato-kiko.jpg', 2),
(21, 21, 'assets/img/animais/gorila-kong.jpg', 2),
(22, 22, 'assets/img/animais/orangotango-mimi.jpg', 2),
(23, 23, 'assets/img/animais/flamingo-rosa.jpg', 2),
(24, 24, 'assets/img/animais/aguia-sky.jpg', 2),
(25, 25, 'assets/img/animais/coruja-lua.jpg', 2),
(26, 26, 'assets/img/animais/dragao-komodo.jpg', 2),
(27, 27, 'assets/img/animais/tartaruga-marina.jpg', 2),
(28, 28, 'assets/img/animais/axolote-axel.jpg', 2),
(29, 29, 'assets/img/animais/peixe-nemo.jpg', 2),
(30, 30, 'assets/img/animais/pirarucu-acu.jpg', 2);

-- ============================================================
-- MEDICAMENTOS
-- ============================================================

INSERT INTO medicamentos
(id, nome, descricao, fabricante, estoque, lote, vencimento)
VALUES
(1, 'Amoxicilina', 'Antibiótico utilizado em tratamentos prescritos pela equipe veterinária.', 'VetPharma', 42, 'AMX-2601', '2027-05-30'),
(2, 'Meloxicam', 'Anti-inflamatório não esteroidal para uso veterinário.', 'AnimalMed', 35, 'MEL-2602', '2027-08-15'),
(3, 'Ivermectina', 'Medicamento antiparasitário utilizado conforme avaliação veterinária.', 'VetPharma', 28, 'IVE-2603', '2027-11-20'),
(4, 'Enrofloxacino', 'Antimicrobiano de uso veterinário.', 'BioVet', 31, 'ENR-2604', '2028-01-10'),
(5, 'Dipirona', 'Analgésico e antitérmico utilizado sob prescrição veterinária.', 'AnimalMed', 50, 'DIP-2605', '2027-04-18'),
(6, 'Prednisolona', 'Corticosteroide utilizado em situações específicas de tratamento.', 'BioVet', 22, 'PRE-2606', '2027-09-25'),
(7, 'Clorexidina', 'Antisséptico para higienização e cuidados locais.', 'VetCare', 60, 'CLO-2607', '2028-02-28'),
(8, 'Suplemento vitamínico', 'Suplementação nutricional utilizada quando indicada pela equipe.', 'NutriVet', 45, 'SUP-2608', '2027-12-12');

-- ============================================================
-- CONSULTAS
-- ============================================================

INSERT INTO consultas
(id, animal_id, funcionario_id, data_consulta, diagnostico, tratamento, observacoes, data_retorno)
VALUES
(1, 1, 2, '2026-01-12', 'Avaliação clínica de rotina sem alterações relevantes.', 'Manter rotina alimentar e enriquecimento ambiental.', 'Peso dentro do esperado.', '2026-04-12'),
(2, 2, 2, '2026-01-20', 'Boa condição clínica geral.', 'Manter acompanhamento preventivo.', 'Reavaliar peso no próximo trimestre.', '2026-04-20'),
(3, 3, 2, '2026-02-05', 'Leve alteração de peso em comparação ao registro anterior.', 'Ajuste da quantidade de alimento conforme protocolo.', 'Nova pesagem recomendada.', '2026-03-05'),
(4, 7, 2, '2026-02-18', 'Redução de apetite e comportamento menos ativo.', 'Observação clínica e ajuste alimentar.', 'Animal mantido em observação.', '2026-02-25'),
(5, 10, 2, '2026-03-02', 'Adaptação ao recinto em andamento.', 'Manter rotina gradual de adaptação.', 'Monitorar interação com ambiente.', '2026-03-16'),
(6, 12, 2, '2026-03-10', 'Desenvolvimento corporal dentro do esperado.', 'Manter alimentação e acompanhamento.', 'Sem alterações importantes.', '2026-06-10'),
(7, 18, 7, '2026-01-16', 'Necessidade de acompanhamento preventivo.', 'Cuidados de rotina e observação.', 'Sem sinais de agravamento.', '2026-04-16'),
(8, 21, 7, '2026-02-11', 'Alteração articular em acompanhamento.', 'Repouso relativo e tratamento prescrito.', 'Evitar atividades de impacto.', '2026-02-25'),
(9, 22, 7, '2026-02-27', 'Boa condição geral com necessidade de enriquecimento ambiental.', 'Manter atividades de enriquecimento.', 'Acompanhar comportamento.', '2026-05-27'),
(10, 24, 7, '2026-03-05', 'Animal em recuperação após procedimento.', 'Repouso e acompanhamento clínico.', 'Evolução considerada positiva.', '2026-03-19'),
(11, 27, 7, '2026-03-12', 'Recuperação clínica em andamento.', 'Manter protocolo de recuperação.', 'Monitorar alimentação e atividade.', '2026-03-26'),
(12, 28, 7, '2026-03-20', 'Atividade abaixo do padrão observado.', 'Observação e monitoramento.', 'Reavaliar caso não haja melhora.', '2026-03-27');

-- ============================================================
-- MEDICAMENTOS DAS CONSULTAS
-- ============================================================

INSERT INTO medicamentos_consulta
(id, consulta_id, medicamento_id, dosagem, observacoes)
VALUES
(1, 4, 5, 'Conforme prescrição', 'Administrar somente durante o período indicado.'),
(2, 4, 7, 'Aplicação tópica', 'Higienização conforme protocolo.'),
(3, 8, 2, 'Conforme prescrição', 'Observar resposta ao tratamento.'),
(4, 8, 5, 'Conforme prescrição', 'Uso sob acompanhamento veterinário.'),
(5, 10, 2, 'Conforme prescrição', 'Manter repouso durante recuperação.'),
(6, 10, 7, 'Aplicação tópica', 'Realizar limpeza conforme orientação.'),
(7, 11, 6, 'Conforme prescrição', 'Acompanhar evolução clínica.'),
(8, 12, 5, 'Conforme prescrição', 'Reavaliar caso os sinais persistam.'),
(9, 3, 3, 'Conforme prescrição', 'Administrar conforme protocolo antiparasitário.'),
(10, 7, 3, 'Conforme prescrição', 'Uso preventivo conforme avaliação.'),
(11, 6, 8, 'Conforme prescrição', 'Suplementação conforme necessidade.'),
(12, 9, 8, 'Conforme prescrição', 'Associado ao plano nutricional.');

-- ============================================================
-- ALIMENTAÇÕES
-- ============================================================

INSERT INTO alimentacoes
(id, animal_id, funcionario_id, descricao_alimento, quantidade, data_hora, observacoes)
VALUES
(1, 1, 3, 'Carne bovina', 8.50, '2026-03-01 08:00:00', 'Porção dividida em dois pontos do recinto.'),
(2, 2, 3, 'Carne bovina', 4.20, '2026-03-01 08:30:00', 'Alimentação consumida normalmente.'),
(3, 3, 3, 'Folhas e vegetais', 18.00, '2026-03-01 09:00:00', 'Distribuição em diferentes pontos.'),
(4, 4, 3, 'Frutas e vegetais', 45.00, '2026-03-01 09:30:00', 'Quantidade conforme plano alimentar.'),
(5, 5, 3, 'Frutas e pequenos alimentos', 3.50, '2026-03-01 10:00:00', 'Animal apresentou boa aceitação.'),
(6, 7, 3, 'Formigas e cupins', 2.20, '2026-03-01 10:30:00', 'Alimentação monitorada.'),
(7, 8, 3, 'Frutas e sementes', 0.35, '2026-03-01 11:00:00', 'Boa aceitação.'),
(8, 15, 3, 'Capim e vegetais', 6.50, '2026-03-01 12:00:00', 'Grupo alimentado em área externa.'),
(9, 16, 8, 'Carne e frutas', 9.00, '2026-03-02 08:00:00', 'Porção individual.'),
(10, 18, 8, 'Vegetais e frutas', 25.00, '2026-03-02 09:00:00', 'Alimentação supervisionada.'),
(11, 19, 8, 'Capim e feno', 12.00, '2026-03-02 09:30:00', 'Animal consumiu normalmente.'),
(12, 20, 8, 'Insetos e pequenos alimentos', 0.25, '2026-03-02 10:00:00', 'Porção individual.'),
(13, 21, 8, 'Vegetais e frutas', 14.00, '2026-03-02 10:30:00', 'Alimentação acompanhada pela equipe.'),
(14, 23, 8, 'Peixes e pequenos crustáceos', 0.60, '2026-03-02 11:00:00', 'Alimentação distribuída no lago.'),
(15, 26, 8, 'Carne', 2.50, '2026-03-02 12:00:00', 'Manejo realizado conforme protocolo.'),
(16, 30, 8, 'Peixes', 3.00, '2026-03-02 13:00:00', 'Alimentação realizada no tanque.');

-- ============================================================
-- VACINAS
-- ============================================================

INSERT INTO vacinas
(id, animal_id, nome_vacina, data_aplicacao, proxima_aplicacao, observacoes, funcionario_id)
VALUES
(1, 1, 'Vacina preventiva A', '2026-01-15', '2027-01-15', 'Aplicação sem intercorrências.', 2),
(2, 2, 'Vacina preventiva A', '2026-01-18', '2027-01-18', 'Animal colaborativo durante o procedimento.', 2),
(3, 5, 'Vacina preventiva B', '2026-02-03', '2027-02-03', 'Aplicação registrada no prontuário.', 2),
(4, 6, 'Vacina preventiva B', '2026-02-10', '2027-02-10', 'Sem observações adicionais.', 2),
(5, 15, 'Vacina preventiva C', '2026-02-20', '2027-02-20', 'Procedimento concluído normalmente.', 2),
(6, 16, 'Vacina preventiva A', '2026-01-22', '2027-01-22', 'Sem intercorrências.', 7),
(7, 18, 'Vacina preventiva C', '2026-02-05', '2027-02-05', 'Animal monitorado após aplicação.', 7),
(8, 21, 'Vacina preventiva A', '2026-02-15', '2027-02-15', 'Aplicação registrada.', 7),
(9, 23, 'Vacina preventiva B', '2026-02-18', '2027-02-18', 'Sem alterações.', 7),
(10, 26, 'Vacina preventiva C', '2026-03-01', '2027-03-01', 'Aplicação concluída.', 7),
(11, 27, 'Vacina preventiva B', '2026-03-03', '2027-03-03', 'Animal permaneceu em observação após procedimento.', 7),
(12, 30, 'Vacina preventiva A', '2026-03-08', '2027-03-08', 'Sem intercorrências.', 7);

-- ============================================================
-- MANUTENÇÃO DE HABITATS
-- ============================================================

INSERT INTO manutencao_habitats
(id, habitat_id, funcionario_id, data_manutencao, descricao, status)
VALUES
(1, 1, 4, '2026-01-10', 'Revisão das cercas e dos portões de acesso.', 'Concluída'),
(2, 2, 4, '2026-01-22', 'Limpeza e revisão das estruturas de escalada.', 'Concluída'),
(3, 5, 4, '2026-02-05', 'Manutenção do sistema de climatização.', 'Em andamento'),
(4, 6, 3, '2026-02-18', 'Limpeza do tanque e revisão do sistema hidráulico.', 'Concluída'),
(5, 7, 9, '2026-01-28', 'Revisão de árvores e estruturas internas.', 'Concluída'),
(6, 10, 9, '2026-02-12', 'Limpeza das margens do lago e revisão de filtros.', 'Em andamento'),
(7, 11, 8, '2026-02-25', 'Manutenção do sistema de controle de temperatura.', 'Pendente'),
(8, 12, 8, '2026-03-05', 'Limpeza dos tanques e revisão de bombas.', 'Concluída');

-- ============================================================
-- HISTÓRICO DE HABITATS
-- ============================================================

INSERT INTO historico_habitats
(id, animal_id, habitat_anterior_id, habitat_novo_id, data_mudanca, motivo, funcionario_id)
VALUES
(1, 5, 3, 2, '2025-11-10', 'Adequação do ambiente às necessidades comportamentais do animal.', 4),
(2, 7, 2, 3, '2026-01-08', 'Mudança para área com estrutura de observação clínica.', 3),
(3, 10, 4, 6, '2026-02-15', 'Adaptação gradual a ambiente semiaquático.', 3),
(4, 18, 9, 7, '2025-12-02', 'Reorganização dos recintos para manutenção da área anterior.', 9),
(5, 24, 10, 7, '2026-01-20', 'Transferência temporária para observação e recuperação.', 7),
(6, 27, 11, 12, '2026-02-01', 'Adequação do recinto ao acompanhamento de recuperação.', 7);

-- ============================================================
-- EVENTOS
-- ============================================================

INSERT INTO eventos
(id, titulo, descricao, tipo, data_inicio, data_fim, animal_id, funcionario_id, status, instituicao_id)
VALUES
(1, 'Consulta de rotina - Simba', 'Avaliação veterinária periódica.', 'Consulta', '2026-03-10 09:00:00', '2026-03-10 10:00:00', 1, 2, 'Agendado', 1),
(2, 'Vacinação - Juma', 'Aplicação de vacina preventiva.', 'Vacinação', '2026-03-11 10:00:00', '2026-03-11 10:30:00', 2, 2, 'Agendado', 1),
(3, 'Alimentação especial - Teca', 'Alimentação acompanhada pela equipe.', 'Alimentação', '2026-03-12 11:00:00', '2026-03-12 11:30:00', 7, 3, 'Concluído', 1),
(4, 'Manutenção do Terrário Tropical', 'Revisão da climatização e limpeza.', 'Manutenção', '2026-03-13 08:00:00', '2026-03-13 12:00:00', NULL, 4, 'Em andamento', 1),
(5, 'Transferência temporária - Maré', 'Mudança de recinto para adaptação.', 'Transferência', '2026-03-14 14:00:00', '2026-03-14 15:00:00', 10, 3, 'Concluído', 1),
(6, 'Avaliação veterinária - Capi', 'Avaliação clínica preventiva.', 'Consulta', '2026-03-15 09:30:00', '2026-03-15 10:30:00', 15, 2, 'Agendado', 1),
(7, 'Consulta de rotina - Bruno', 'Avaliação veterinária periódica.', 'Consulta', '2026-03-10 09:00:00', '2026-03-10 10:00:00', 16, 7, 'Agendado', 2),
(8, 'Vacinação - Kong', 'Aplicação de vacina preventiva.', 'Vacinação', '2026-03-11 10:00:00', '2026-03-11 10:30:00', 21, 7, 'Concluído', 2),
(9, 'Alimentação - Rosa', 'Alimentação supervisionada no lago.', 'Alimentação', '2026-03-12 11:00:00', '2026-03-12 11:30:00', 23, 8, 'Concluído', 2),
(10, 'Manutenção do Aquário Tropical', 'Revisão de filtros e bombas.', 'Manutenção', '2026-03-13 08:00:00', '2026-03-13 12:00:00', NULL, 8, 'Cancelado', 2),
(11, 'Transferência - Marina', 'Transferência para recinto de recuperação.', 'Transferência', '2026-03-14 14:00:00', '2026-03-14 15:00:00', 27, 7, 'Concluído', 2),
(12, 'Avaliação do Axolote', 'Reavaliação do estado de saúde.', 'Consulta', '2026-03-16 09:00:00', '2026-03-16 09:45:00', 28, 7, 'Agendado', 2);

-- ============================================================
-- FIM DO SEED
-- ============================================================
