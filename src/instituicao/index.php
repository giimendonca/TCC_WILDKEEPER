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
$qtdFuncionariosCargo = countFuncionariosPorColuna($conexao, 'cargo_id', $_SESSION['instituicao_id']);

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

            <article>
                <h3>Funcionários</h3>
                <p><?= htmlspecialchars($qtdFuncionarios) ?> registrados.</p>
            </article>

            <?php foreach($qtdFuncionariosStatus as $qtd): ?>
                <article>
                    <h3><?= $qtd['status'] ?></h3>
                    <p><?= $qtd['total'] ?></p>
                </article>
            <?php endforeach; ?>

            <canvas id="graficoFuncionariosStatus"></canvas>
            <canvas id="graficoFuncionariosCargos"></canvas>
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
    const valoresStatus = funcionariosStatus.map(status =>{
        return status.total
    })

    new Chart(graficoFuncionariosStatus, {
        type: 'doughnut',
        data:{
            labels: labelsStatus,
            datasets: [{
                data: valoresStatus
            }]
        }
    })
    
    // Gráfico de funcionários por cargo
    const graficoFuncionariosCargos = document.getElementById('graficoFuncionariosCargos')
    
    const funcionariosCargo = <?= json_encode($qtdFuncionariosCargo) ?>
    
    const labelsCargos = funcionariosCargo.map(cargo =>{
        return cargo.nome
    })
    
    const valoresCargos = funcionariosCargo.map(cargo =>{
        return cargo.total
    })
    
    new Chart(graficoFuncionariosCargos, {
        type: 'doughnut',
        data:{
            labels: labelsCargos,
            datasets: [{
                data: valoresCargos
            }]
        }
    })
    
</script>

</html>