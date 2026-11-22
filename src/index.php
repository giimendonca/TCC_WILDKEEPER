<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WildKeeper</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/landing.css">
    <link rel="stylesheet" href="../assets/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <svg width="0" height="0" aria-hidden="true">
        <defs>
            <clipPath id="forma-onca" clipPathUnits="objectBoundingBox">
                <path d="M 0.22,0 C 0.04,0.22 -0.02,0.52 0.05,0.78 C 0.08,0.89 0.13,0.96 0.18,1 L 1,1 L 1,0 Z" />
            </clipPath>
        </defs>
    </svg>
    <?php include "../src/includes/header.php" ?>
    <main>
        <section id="inicio" class="hero">
            <div class="hero-content">
                <span class="hero-tag"><i class="fa-solid fa-leaf"></i> GESTÃO PARA CONSERVAÇÃO</span>

                <h1>Bem-vindo ao <span class="verde">Wild</span><span class="azul">Keeper</span></h1>

                <p>A plataforma inteligente para gestão de zoológicos, centros de conservação e instituições de preservação animal.</p>

                <div class="hero-actions">
                    <a href="../src/setup/instituicao.php"><i class="fa-solid fa-caret-right"></i> Começar agora</a>
                    <a href="#sobre"><i class="fa-solid fa-circle-exclamation"></i> Conhecer o WildKeeper</a>
                </div>
            </div>

            <img src="../assets/img/onca-pintada.webp" alt="">
            <svg class="onda" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,45 C180,68 380,65 560,40 C740,15 920,25 1100,52 C1250,74 1360,55 1440,38 L1440,120 L0,120 Z" />
            </svg>
        </section>
        <section id="sobre">
            <img src="../assets/img/tucano.webp" alt="Tucano">

            <div class="sobre-titulo">
                <span class="line"></span>
                <h2>Gestão inteligente para instituições que cuidam da vida selvagem.</h2>
            </div>

            <p>O WildKeeper centraliza o gerenciamento de animais, habitats, equipes, consultas veterinárias, alimentações e eventos em uma única plataforma, oferecendo mais organização, segurança e eficiência para instituições de conservação.</p>
        </section>
        <section id="wildkeeper">
            <i class="fa-solid fa-earth-americas"></i>
            <div class="container">
                <h2>O que é o WildKeeper?</h2>
                <h3>Cuidar hoje, preservar sempre.</h3>
                <p>O WildKeeper é um sistema web desenvolvido para auxiliar zoológicos, parques ecológicos, centros de conservação e instituições de preservação animal na administração de suas atividades diárias. A plataforma reúne ferramentas para controlar informações dos animais, organizar equipes, registrar procedimentos veterinários e acompanhar eventos importantes de forma simples e intuitiva.</p>
            </div>
        </section>
        <section id="recursos">
            <div class="titulo">
                <span class="line"></span>
                <h2>Recursos</h2>
                <span class="line"></span>
            </div>
            <div class="cards-container">
                <article class="card">
                    <i class="fa-solid fa-paw verde"></i>
                    <div class="texto">
                        <h3>Cadastro de Animais</h3>
                        <p>Gerencie todas as informações dos animais da instituição.</p>
                    </div>
                </article>
                <article class="card">
                    <i class="fa-solid fa-signs-post azul"></i>
                    <div class="texto">
                        <h3>Habitats</h3>
                        <p>Organize habitats, capacidade e condições ambientais.</p>
                    </div>
                </article>
                <article class="card">
                    <i class="fa-solid fa-heart-pulse verde"></i>
                    <div class="texto">
                        <h3>Saúde Animal</h3>
                        <p>Controle consultas, vacinas e tratamentos.</p>
                    </div>
                </article>
                <article class="card">
                    <i class="fa-solid fa-utensils azul"></i>
                    <div class="texto">
                        <h3>Alimentação</h3>
                        <p>Registre alimentações e acompanhe históricos.</p>
                    </div>
                </article>
                <article class="card">
                    <i class="fa-solid fa-calendar-days verde"></i>
                    <div class="texto">
                        <h3>Agenda</h3>
                        <p>Organize eventos e atividades da equipe.</p>
                    </div>
                </article>
                <article class="card">
                    <i class="fa-solid fa-users azul"></i>
                    <div class="texto">
                        <h3>Funcionários</h3>
                        <p>Gerencie cargos e usuários do sistema.</p>
                    </div>
                </article>
            </div>
        </section>
        <section id="motivos">
            <div class="titulo">
                <span class="line"></span>
                <h2>Por que escolher o WildKeeper?</h2>
                <span class="line"></span>
            </div>
            <div class="cards-linha">
                <article class="card verde">
                    <i class="fa-solid fa-cloud"></i>
                    <p>Sistema 100% web</p>
                </article>
                <span class="line"></span>
                <article class="card verde">
                    <i class="fa-solid fa-paw"></i>
                    <p>Cadastro completo dos animais</p>
                </article>
                <span class="line"></span>
                <article class="card verde">
                    <i class="fa-solid fa-user-shield"></i>
                    <p>Controle veterinário</p>
                </article>
                <span class="line"></span>
                <article class="card verde">
                    <i class="fa-regular fa-calendar"></i>
                    <p>Agenda integrada</p>
                </article>
                <span class="line"></span>
                <article class="card verde">
                    <i class="fa-solid fa-arrow-pointer"></i>
                    <p>Interface intuitiva</p>
                </article>
                <span class="line"></span>
                <article class="card verde">
                    <i class="fa-solid fa-table-cells-row-lock"></i>
                    <p>Banco de dados seguro</p>
                </article>
            </div>
        </section>
        <section id="cadastro">
            <h2>Pronto para modernizar a gestão da sua instituição?</h2>
            <a href="../src/setup/instituicao.php"><i class="fa-solid fa-building-columns"></i> Cadastrar Instituição →</a>
        </section>
    </main>

    <button id="voltar-topo" aria-label="Voltar ao topo">
    <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
</button>
    <?php include "../src/includes/footer.php" ?>
    <script src="../assets/js/navbar.js"></script>
    <script src="../assets/js/voltar-topo.js"></script>
    <script src="../assets/js/animations.js"></script>
</body>

</html>