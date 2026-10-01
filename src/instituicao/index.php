<?php
include "../includes/funcoes.php";
include "../includes/conexao.php";
session_start();

include "../includes/autenticacao.php";

// Verifica se ja existe uma sessão
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(100);

// select da tabela instituicao
$sql = "SELECT * FROM instituicoes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $_SESSION['instituicao_id']);

$stmt->execute();
$result = $stmt->get_result();

$instituicao = $result->fetch_assoc();

// COUNT funcionários
$qtdFuncionarios = countTabela($conexao, 'users', $_SESSION['instituicao_id']);

// COUNT funcionários por Status
$qtdFuncionariosStatus = countFuncionariosPorColuna($conexao, 'status', $_SESSION['instituicao_id']);
$qtdFuncionariosCargo = countFuncionariosPorCargo($conexao, $_SESSION['instituicao_id']);

// Resumos 
$qtdAnimais = countTabela($conexao, "animais", $_SESSION['instituicao_id']);
$qtdHabitats = countTabela($conexao, "habitats", $_SESSION['instituicao_id']);
$qtdEventos = countTabela($conexao, "eventos", $_SESSION['instituicao_id']);
$qtdMedicamentos = countTabela($conexao, "medicamentos", $_SESSION['instituicao_id']);
$qtdConsultas = countEventos($conexao, 'tipo', 'Consulta', $_SESSION['instituicao_id']);

// Ultimos Registros
$ultimoFuncionario = ultimoInsert($conexao, 'nome', 'users', $_SESSION['instituicao_id']);
$ultimoEvento = ultimoInsert($conexao, 'titulo', 'eventos', $_SESSION['instituicao_id']);
$ultimoAnimal = ultimoInsert($conexao, 'nome', 'animais', $_SESSION['instituicao_id']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituição | WildKeeper</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/dashboard_global.css">
    <?php include "../includes/fonte.php" ?>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>
    <main>
        <section>
            <h1><?= htmlspecialchars($_SESSION['instituicao_nome']) ?></h1>
        </section>

        <section>
            <h2>Dados Principais</h2>

            <article>
                <h3>CNPJ</h3>
                <p><?= htmlspecialchars($instituicao['cnpj']) ?></p>
            </article>

            <article>
                <h3>Email</h3>
                <p><?= htmlspecialchars($instituicao['email']) ?></p>
            </article>

            <article>
                <h3>Telefone</h3>
                <p><?= htmlspecialchars($instituicao['telefone']) ?></p>
            </article>

            <article>
                <h3>Website</h3>
                <a href="<?= htmlspecialchars($instituicao['website']) ?>" target="_blank"><?= htmlspecialchars($instituicao['website']) ?></a>
            </article>

            <article>
                <h3>Endereço</h3>
                <p><?= htmlspecialchars($instituicao['rua']) ?>, <?= htmlspecialchars($instituicao['numero']) ?> - <?= htmlspecialchars($instituicao['bairro']) ?>, <?= htmlspecialchars($instituicao['cidade']) ?> - <?= htmlspecialchars($instituicao['estado']) ?>, <?= htmlspecialchars($instituicao['cep']) ?></p>
            </article>

            <a href="editar_instituicao.php?id=<?= $_SESSION['instituicao_id'] ?>">Editar Informações</a>
        </section>

        <section>
            <h2>Equipe</h2>
            <article>
                <h3>Funcionários</h3>
                <p><?= htmlspecialchars($qtdFuncionarios) ?> funcionários registrados.</p>
            </article>

            <?php foreach ($qtdFuncionariosStatus as $qtd): ?>
                <article>
                    <h3><?= $qtd['status'] ?></h3>
                    <p><?= $qtd['total'] ?></p>
                </article>
            <?php endforeach; ?>
            
            <canvas id="graficoFuncionariosStatus"></canvas>
            
                        <?php foreach ($qtdFuncionariosCargo as $qtd): ?>
                            <article>
                                <h3><?= $qtd['nome'] ?></h3>
                                <p><?= $qtd['total'] ?></p>
                            </article>
                        <?php endforeach; ?>
            <canvas id="graficoFuncionariosCargos"></canvas>
        </section>

        <section>
            <h2>Resumo da Instituição</h2>
            <article>
                <h3>Animais</h3>
                <p><?= htmlspecialchars($qtdAnimais) ?></p>
            </article>

            <article>
                <h3>Habitats</h3>
                <p><?= htmlspecialchars($qtdHabitats) ?></p>
            </article>

            <article>
                <h3>Consultas</h3>
                <p><?= htmlspecialchars($qtdConsultas) ?></p>
            </article>

            <article>
                <h3>Eventos</h3>
                <p><?= htmlspecialchars($qtdEventos) ?></p>
            </article>

            <article>
                <h3>Consultas</h3>
                <p><?= htmlspecialchars($qtdConsultas) ?></p>
            </article>
        </section>

        <section>
            <h2>Útimos Registros</h2>

            <article>
                <h3>Funcionário cadastrado mais recentemente:</h3>
                <p><?= htmlspecialchars($ultimoFuncionario['nome']) ?></p>
            </article>

            <article>
                <h3>Último evento cadastrado:</h3>
                <p><?= htmlspecialchars($ultimoEvento['titulo']) ?></p>
            </article>

            <article>
                <h3>Última animal cadastrado:</h3>
                <p><?= htmlspecialchars($ultimoAnimal['nome']) ?></p>
            </article>
        </section>
    </main>
    <?php include "../includes/dashboard-footer.php" ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de funcionários por status
    const graficoFuncionariosStatus = document.getElementById('graficoFuncionariosStatus')

    const funcionariosStatus = <?= json_encode($qtdFuncionariosStatus) ?>

    const labelsStatus = funcionariosStatus.map(status => {
        return status.status
    })
    const valoresStatus = funcionariosStatus.map(status => {
        return status.total
    })

    new Chart(graficoFuncionariosStatus, {
        type: 'doughnut',
        data: {
            labels: labelsStatus,
            datasets: [{
                data: valoresStatus
            }]
        }
    })

    // Gráfico de funcionários por cargo
    const graficoFuncionariosCargos = document.getElementById('graficoFuncionariosCargos')

    const funcionariosCargo = <?= json_encode($qtdFuncionariosCargo) ?>

    const labelsCargos = funcionariosCargo.map(cargo => {
        return cargo.nome
    })

    const valoresCargos = funcionariosCargo.map(cargo => {
        return cargo.total
    })

    new Chart(graficoFuncionariosCargos, {
        type: 'doughnut',
        data: {
            labels: labelsCargos,
            datasets: [{
                data: valoresCargos
            }]
        }
    })
</script>

</html>