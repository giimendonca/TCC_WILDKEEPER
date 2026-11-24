<header class="header">
    <div class="header-esquerda">
        <a href="/TCC_WILDKEEPER/src/dashboard/index.php" class="logo">
            <img src="/TCC_WILDKEEPER/assets/img/icon_wildkeeper.webp" alt="Logo do WildKeeper">
            <span>WildKeeper</span>
        </a>
        <nav class="navegacao">
            <a href="/TCC_WILDKEEPER/src/dashboard/index.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <div class="menu">
                <button class="menu-botao">Menu</button>

                <div class="menu-conteudo">
                    <?php if (nivelMinimo(100)): ?>
                        <a href="/TCC_WILDKEEPER/src/funcionarios/index.php"><i class="fa-solid fa-users"></i> Funcionários</a>
                        <a href="/TCC_WILDKEEPER/src/instituicao/index.php"><i class="fa-solid fa-house"></i> Minha Instituição</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(60)): ?>
                        <a href="/TCC_WILDKEEPER/src/consultas/index.php"><i class="fa-solid fa-hospital"></i> Consultas</a>
                        <a href="/TCC_WILDKEEPER/src/vacinas/index.php"><i class="fa-solid fa-syringe"></i> Vacinas</a>
                        <a href="/TCC_WILDKEEPER/src/medicamentos/index.php"><i class="fa-solid fa-kit-medical"></i> Medicamentos</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(40)): ?>
                        <a href="/TCC_WILDKEEPER/src/habitats/index.php"><i class="fa-solid fa-signs-post"></i> Habitats</a>
                        <a href="/TCC_WILDKEEPER/src/especies/index.php"><i class="fa-solid fa-paw"></i> Espécies</a>
                    <?php endif; ?>

                    <?php if (nivelMinimo(20)): ?>
                        <a href="/TCC_WILDKEEPER/src/animais/index.php"><i class="fa-solid fa-paw"></i> Animais</a>
                        <a href="/TCC_WILDKEEPER/src/eventos/index.php"><i class="fa-solid fa-calendar"></i> Eventos</a>
                    <?php endif; ?>

                </div>
            </div>
        </nav>
    </div>

    <div class="header-direita">
        <a class="instituicao" href="<?= nivelMinimo(100) ? '/TCC_WILDKEEPER/src/instituicao/index.php' : '/TCC_WILDKEEPER/src/dashboard/index.php' ; ?>"><?= htmlspecialchars($_SESSION['instituicao_nome']) ?></a>

        <div class="usuario">
            <a class="usuario-nome" href="/TCC_WILDKEEPER/src/perfil/index.php"><?= htmlspecialchars($_SESSION['nome']) ?> <i class="fa-solid fa-circle-user"></i></a>
            <a class="usuario-cargo" href="/TCC_WILDKEEPER/src/perfil/index.php"><?= htmlspecialchars($_SESSION['cargo_nome']) ?></a>
        </div>
    </div>


    <a href="/TCC_WILDKEEPER/src/auth/logout.php" class="sair"><i class="fa-solid fa-right-from-bracket"></i>Sair</a>
</header>