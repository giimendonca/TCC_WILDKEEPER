<?php
include "../includes/funcoes.php";
include "../includes/conexao.php";
session_start();

include "../includes/autenticacao.php";

// Verifica se já existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Busca os dados do usuário logado
$sql = "SELECT
    users.id,
    users.nome,
    users.cpf,
    users.data_nascimento,
    users.genero,
    users.telefone,
    users.email,
    users.status,
    cargos.nome AS cargo_nome
FROM users
INNER JOIN cargos ON cargos.id = users.cargo_id
WHERE users.id = ? AND users.instituicao_id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['id'], $_SESSION['instituicao_id']);
$stmt->execute();

$result = $stmt->get_result();

$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("Usuário não encontrado.");
}

// Busca os próximos eventos do usuário
$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.data_inicio,
    eventos.status
FROM eventos
WHERE eventos.instituicao_id = ?
AND eventos.funcionario_id = ?
AND eventos.data_inicio >= NOW()
AND eventos.status IN ('Agendado', 'Em andamento')
ORDER BY eventos.data_inicio ASC
LIMIT 5";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $_SESSION['id']);
$stmt->execute();

$proximosEventos = $stmt->get_result();

// Busca os últimos eventos registrados
$sql = "SELECT
    eventos.id,
    eventos.titulo,
    eventos.data_inicio,
    eventos.status
FROM eventos
WHERE eventos.instituicao_id = ?
AND eventos.funcionario_id = ?
ORDER BY eventos.data_inicio DESC
LIMIT 5";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $_SESSION['instituicao_id'], $_SESSION['id']);
$stmt->execute();

$ultimosEventos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <section>
            <h1>Olá, <?= htmlspecialchars($usuario['nome']) ?>!</h1>
            <p>Seja bem-vinda(o) ao seu perfil.</p>
        </section>

        <section>
            <h2>Seus próximos eventos</h2>

            <?php if ($proximosEventos->num_rows > 0): ?>

                <?php while ($evento = $proximosEventos->fetch_assoc()): ?>

                    <article>
                        <h3><?= htmlspecialchars($evento['titulo']) ?></h3>

                        <p><?= date('d/m/Y H:i', strtotime($evento['data_inicio'])) ?></p>

                        <p><?= htmlspecialchars($evento['status']) ?></p>
                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Você não possui próximos eventos.</p>

            <?php endif; ?>
        </section>

        <section>
            <h2>Últimos eventos registrados</h2>

            <?php if ($ultimosEventos->num_rows > 0): ?>

                <?php while ($evento = $ultimosEventos->fetch_assoc()): ?>

                    <article>
                        <h3><?= htmlspecialchars($evento['titulo']) ?></h3>

                        <p><?= date('d/m/Y H:i', strtotime($evento['data_inicio'])) ?></p>

                        <p><?= htmlspecialchars($evento['status']) ?></p>
                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Nenhum evento registrado.</p>

            <?php endif; ?>
        </section>

        <section>
            <h2>Suas informações</h2>

            <article>
                <h3>Nome</h3>
                <p><?= htmlspecialchars($usuario['nome']) ?></p>
            </article>

            <article>
                <h3>CPF</h3>
                <p><?= htmlspecialchars($usuario['cpf']) ?></p>
            </article>

            <article>
                <h3>Data de nascimento</h3>
                <p><?= htmlspecialchars($usuario['data_nascimento']) ?></p>
            </article>

            <article>
                <h3>Gênero</h3>
                <p><?= htmlspecialchars($usuario['genero']) ?></p>
            </article>

            <article>
                <h3>Telefone</h3>
                <p><?= htmlspecialchars($usuario['telefone']) ?></p>
            </article>

            <article>
                <h3>Email</h3>
                <p><?= htmlspecialchars($usuario['email']) ?></p>
            </article>

            <a href="editar_usuario.php">Editar informações</a>
        </section>

        <section>
            <h2>Informações profissionais</h2>

            <article>
                <h3>Cargo</h3>
                <p><?= htmlspecialchars($usuario['cargo_nome']) ?></p>
            </article>

            <article>
                <h3>Status</h3>
                <p><?= htmlspecialchars($usuario['status']) ?></p>
            </article>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>
</body>

</html>