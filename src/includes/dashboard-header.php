<header class="header">
    <div class="header-esquerda">
        <a href="/TCC_WILDKEEPER/src/dashboard/index.php" class="logo">
            <img src="/TCC_WILDKEEPER/assets/img/icon_wildkeeper.webp" alt="Logo do WildKeeper">
            <span>WildKeeper</span>
        </a>
        <nav class="navegacao">
            <a href="/TCC_WILDKEEPER/src/dashboard/index.php">Dashboard</a>
            <div class="menu">
                <button class="menu-botao">Menu</button>

                <div class="menu-conteudo">
                    <?php if (nivelMinimo(100)): ?>
                        <a href="/TCC_WILDKEEPER/src/funcionarios/index.php">Funcionários</a>
                        <a href="/TCC_WILDKEEPER/src/instituicao/index.php">Minha Instituição</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(60)): ?>
                        <a href="/TCC_WILDKEEPER/src/consultas/index.php">Consultas</a>
                        <a href="/TCC_WILDKEEPER/src/vacinas/index.php">Vacinas</a>
                        <a href="/TCC_WILDKEEPER/src/medicamentos/index.php">Medicamentos</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(40)): ?>
                        <a href="/TCC_WILDKEEPER/src/alimentacoes/index.php">Alimentações</a>
                        <a href="/TCC_WILDKEEPER/src/habitats/index.php">Habitats</a>
                        <a href="/TCC_WILDKEEPER/src/manutencoes/index.php">Manutenção de Habitats</a>
                        <a href="/TCC_WILDKEEPER/src/especies/index.php">Espécies</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(20)): ?>
                        <a href="/TCC_WILDKEEPER/src/animais/index.php">Animais</a>
                        <a href="/TCC_WILDKEEPER/src/eventos/index.php">Eventos</a>
                    <?php endif; ?>

                </div>
            </div>
        </nav>
    </div>

    <div class="header-direita">
        <a class="instituicao" href="<?= nivelMinimo(100) ? '/TCC_WILDKEEPER/src/instituicao/index.php' : '/TCC_WILDKEEPER/src/dashboard/index.php' ; ?>"><?= htmlspecialchars($_SESSION['instituicao_nome']) ?></a>

        <div class="usuario">
            <a class="usuario-nome" href="/TCC_WILDKEEPER/src/perfil/index.php"><?= htmlspecialchars($_SESSION['nome']) ?></a>
            <a class="usuario-cargo" href="/TCC_WILDKEEPER/src/perfil/index.php"><?= htmlspecialchars($_SESSION['cargo_nome']) ?></a>
        </div>
    </div>


    <a href="/TCC_WILDKEEPER/src/auth/logout.php" class="sair">Sair</a>
</header>