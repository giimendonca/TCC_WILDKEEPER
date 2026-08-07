<header>
    <div>
        <a href="/TCC_WILDKEEPER/dashboard/index.php">
            <img
                src="/TCC_WILDKEEPER/assets/img/icon_wildkeeper.webp"
                alt="Logo do WildKeeper"
            >
        </a>

        <span>
            <?= htmlspecialchars($_SESSION['instituicao_nome']) ?>
        </span>
    </div>

    <nav>
        <a href="/TCC_WILDKEEPER/src/dashboard/index.php">
            Dashboard
        </a>

        <a href="/TCC_WILDKEEPER/src/auth/logout.php">
            Sair
        </a>
    </nav>

    <div>
        <span>
            <?= htmlspecialchars($_SESSION['nome']) ?>
        </span>

        <span>
            <?= htmlspecialchars($_SESSION['cargo_nome']) ?>
        </span>
    </div>
</header>