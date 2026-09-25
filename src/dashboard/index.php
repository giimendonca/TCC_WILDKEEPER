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

// ============================================================
// DADOS DA INSTITUIÇÃO
// ============================================================

$idInstituicao = $_SESSION['instituicao_id'];

// ============================================================
// COUNT DAS TABELAS
// ============================================================

// COUNT das tabelas protegidas por instituicao_id
$qtdAnimais = countTabela($conexao, "animais", $idInstituicao);
$qtdHabitats = countTabela($conexao, "habitats", $idInstituicao);
$qtdEventos = countTabela($conexao, "eventos", $idInstituicao);
$qtdFuncionarios = countTabela($conexao, "users", $idInstituicao);
$qtdMedicamentos = countTabela($conexao, "medicamentos", $idInstituicao);

// COUNT de especies (tabela global)
$qtdEspecies = countTabelaGlobal($conexao, 'especies');

// COUNT de consultas
$qtdConsultas = countEventos($conexao, 'tipo', 'Consulta', $idInstituicao);

// ============================================================
// DADOS DOS EVENTOS
// ============================================================

// COUNT de eventos por status
$qtdEventosAgendados = countEventos($conexao, 'status', "Agendado", $idInstituicao);
$qtdEventosAndamento = countEventos($conexao, 'status', "Em andamento", $idInstituicao);
$qtdEventosConcluidos = countEventos($conexao, 'status', "Concluído", $idInstituicao);
$qtdEventosCancelados = countEventos($conexao, 'status', "Cancelado", $idInstituicao);

// Data de hoje e amanhã para SELECT e COUNT de eventos nesse período
$dataHoje = date('Y-m-d 00:00:00');
$dataAmanha = date('Y-m-d 00:00:00', strtotime('+1 day'));

// COUNT de eventos no período
$qtdConsultasHoje = countEventosTipoPeriodo($conexao, 'tipo', 'Consulta', $dataHoje, $dataAmanha, $idInstituicao);
$qtdEventosHoje = countEventosPeriodo($conexao, $dataHoje, $dataAmanha, $idInstituicao);

// Dados dos gráficos de eventos
$eventosPorStatus = countEventosPorColuna($conexao, 'status', $idInstituicao);
$eventosPorTipo = countEventosPorColuna($conexao, 'tipo', $idInstituicao);
$eventosPorMes = countEventosPorMes($conexao, $idInstituicao);

// Próximos eventos
$proximosEventos = listarProximosEventos($conexao, $idInstituicao);

// ============================================================
// DADOS DOS MEDICAMENTOS
// ============================================================

// COUNT de medicamentos com estoque abaixo do limite
$qtdEstoqueBaixo = countMedicamentosEstoque($conexao, 5, $idInstituicao);

// COUNT de medicamentos sem estoque
$qtdSemEstoque = countMedicamentosSemEstoque($conexao, $idInstituicao);

// SELECT de medicamentos com estoque abaixo do limite
$medicamentosEstoqueBaixo = listarMedicamentosEstoqueBaixo($conexao, 5, $idInstituicao);

// ============================================================
// DADOS DOS ANIMAIS
// ============================================================

// COUNT de animais saudáveis
$qtdAnimaisSaudaveis = countAnimaisSaudaveis($conexao, $idInstituicao);

// COUNT de animais por situação de saúde
$animaisPorSaude = countAnimaisPorSaude($conexao, $idInstituicao);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | WildKeeper</title>
</head>

<body>
    <?php include "../includes/dashboard-header.php" ?>

    <main>
        <!-- ============================================================
             CABEÇALHO DO DASHBOARD
        ============================================================= -->

        <section>
            <h1>Dashboard</h1>
            <p>Olá, <?= htmlspecialchars($_SESSION['nome']) ?>! Bem-vindo(a) novamente ao WildKeeper.</p>
        </section>

        <!-- ============================================================
             RESUMO
        ============================================================= -->

        <section>
            <h2>Resumo</h2>

            <article>
                <h3>Animais</h3>
                <p><?= htmlspecialchars($qtdAnimais) ?></p>
            </article>

            <article>
                <h3>Espécies</h3>
                <p><?= htmlspecialchars($qtdEspecies) ?></p>
            </article>

            <article>
                <h3>Habitats</h3>
                <p><?= htmlspecialchars($qtdHabitats) ?></p>
            </article>

            <article>
                <h3>Funcionários</h3>
                <p><?= htmlspecialchars($qtdFuncionarios) ?></p>
            </article>

            <article>
                <h3>Consultas</h3>
                <p><?= htmlspecialchars($qtdConsultas) ?></p>
            </article>

            <article>
                <h3>Eventos</h3>
                <p><?= htmlspecialchars($qtdEventos) ?></p>
            </article>
        </section>

        <!-- ============================================================
             SITUAÇÃO DOS EVENTOS
        ============================================================= -->

        <section>
            <h2>Situação dos Eventos</h2>

            <article>
                <h3>Agendados</h3>
                <p><?= htmlspecialchars($qtdEventosAgendados) ?></p>
            </article>

            <article>
                <h3>Em Andamento</h3>
                <p><?= htmlspecialchars($qtdEventosAndamento) ?></p>
            </article>

            <article>
                <h3>Concluídos</h3>
                <p><?= htmlspecialchars($qtdEventosConcluidos) ?></p>
            </article>

            <article>
                <h3>Cancelados</h3>
                <p><?= htmlspecialchars($qtdEventosCancelados) ?></p>
            </article>

            <!-- Gráficos de eventos -->
            <canvas id="graficoEventosStatus"></canvas>
            <canvas id="graficoEventosTipos"></canvas>
            <canvas id="graficoEventosMes"></canvas>
        </section>

        <!-- ============================================================
             SITUAÇÃO DA SAÚDE DOS ANIMAIS
        ============================================================= -->

        <section>
            <h2>Saúde dos Animais</h2>

            <article>
                <h3>Animais Saudáveis</h3>
                <p><?= htmlspecialchars($qtdAnimaisSaudaveis) ?></p>
            </article>

            <canvas id="graficoAnimaisSaude"></canvas>
        </section>

        <!-- ============================================================
             ALERTAS IMPORTANTES
        ============================================================= -->

        <section>
            <h2>Alertas Importantes</h2>

            <?php if ($qtdEventosCancelados > 0): ?>
                <div>
                    <article>
                        <h3>Eventos Cancelados</h3>
                        <p>Existem <?= htmlspecialchars($qtdEventosCancelados) ?> evento(s) cancelado(s).</p>
                        <a href="../eventos/index.php">Ver Eventos</a>
                    </article>
                </div>
            <?php else: ?>
                <div>
                    <p>Nenhum evento cancelado.</p>
                </div>
            <?php endif; ?>

            <?php if ($qtdEventosHoje > 0): ?>
                <div>
                    <article>
                        <h3>Eventos Hoje</h3>
                        <p>Existem <?= htmlspecialchars($qtdEventosHoje) ?> evento(s) hoje.</p>
                        <a href="../eventos/index.php">Ver Eventos</a>
                    </article>
                </div>
            <?php else: ?>
                <div>
                    <p>Não há nenhum evento hoje.</p>
                </div>
            <?php endif; ?>

            <?php if ($qtdConsultasHoje > 0): ?>
                <div>
                    <article>
                        <h3>Consultas Hoje</h3>
                        <p>Existem <?= htmlspecialchars($qtdConsultasHoje) ?> consulta(s) hoje.</p>
                        <a href="../consultas/index.php">Ver Consultas</a>
                    </article>
                </div>
            <?php else: ?>
                <div>
                    <p>Não há nenhuma consulta hoje.</p>
                </div>
            <?php endif; ?>

            <?php if ($qtdSemEstoque > 0): ?>
                <div>
                    <h3>Sem Estoque</h3>
                    <p>Existem <?= htmlspecialchars($qtdSemEstoque) ?> medicamento(s) sem estoque.</p>
                    <a href="../medicamentos/index.php">Ver Medicamentos</a>
                </div>
            <?php else: ?>
                <div>
                    <p>Não há nenhum medicamento sem estoque.</p>
                </div>
            <?php endif; ?>

            <?php if ($qtdEstoqueBaixo > 0): ?>
                <div>
                    <h3>Estoque Baixo</h3>
                    <p>Existem <?= htmlspecialchars($qtdEstoqueBaixo) ?> medicamento(s) com estoque baixo.</p>
                    <a href="../medicamentos/index.php">Ver Medicamentos</a>
                </div>
            <?php else: ?>
                <div>
                    <p>Não há nenhum medicamento com estoque baixo.</p>
                </div>
            <?php endif; ?>
        </section>


        <!-- ============================================================
            PÓXIMOS EVENTOS
        ============================================================= -->
        <section>
            <h2>Próximos Eventos na Agenda</h2>

            <?php if (empty($proximosEventos)): ?>
                <p>Nenhum evento próximo encontrado.</p>
            <?php else: ?>
                <?php foreach ($proximosEventos as $evento): ?>
                    <!-- Tratamento de data e horário de eventos -->
                    <?php $dataEvento = date('d/m/Y \à\s H:i', strtotime($evento['data_inicio'])); ?>
                    <article>
                        <div>
                            <span><?= htmlspecialchars($evento['tipo']) ?></span>
                            <h3>Título: <?= htmlspecialchars($evento['titulo']) ?></h3>
                        </div>
                        <p><?= htmlspecialchars($evento['data_inicio']) ?></p>
                        <?php if (!empty($evento['animal_nome'])): ?>
                            <p>Animal: <?= htmlspecialchars($evento['animal_nome']) ?></p>
                        <?php elseif (!empty($evento['habitat_nome'])): ?>
                            <p>Habitat: <?= htmlspecialchars($evento['habitat_nome']) ?></p>
                        <?php endif; ?>
                        <span><?= $evento['status'] ?></span>
                    </article>
                <?php endforeach; ?>
                <div>
                    <a href="../eventos/index.php">Ver Todos os Eventos</a>
                </div>
            <?php endif; ?>
        </section>


        <!-- ============================================================
             ACESSO RÁPIDO
        ============================================================= -->

        <section>
            <h2>Acesso Rápido</h2>

            <?php if (nivelMinimo(100)): ?>
                <article>
                    <h3>Administração</h3>

                    <a href="../funcionarios/index.php">Funcionários</a>
                    <a href="../perfil/index.php">Configurações</a>
                </article>
            <?php endif; ?>

            <?php if (nivelMinimo(60)): ?>
                <article>
                    <h3>Veterinária</h3>

                    <a href="../consultas/index.php">Consultas</a>
                    <a href="../vacinas/index.php">Vacinas</a>
                    <a href="../medicamentos/index.php">Medicamentos</a>
                </article>
            <?php endif; ?>

            <?php if (nivelMinimo(40)): ?>
                <article>
                    <h3>Manejo</h3>

                    <a href="../alimentacoes/index.php">Alimentação</a>
                    <a href="../habitats/index.php">Habitats</a>
                    <a href="../especies/index.php">Espécies</a>
                </article>
            <?php endif; ?>

            <?php if (nivelMinimo(20)): ?>
                <article>
                    <h3>Operações</h3>

                    <a href="../animais/index.php">Animais</a>
                    <a href="../eventos/index.php">Eventos</a>
                </article>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../includes/dashboard-footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ============================================================
        // GRÁFICOS DE EVENTOS
        // ============================================================

        // Gráfico de eventos por status
        const eventosStatus = <?= json_encode($eventosPorStatus) ?>;
        const graficoStatus = document.getElementById('graficoEventosStatus');

        const labelsStatus = eventosStatus.map(function(evento) {
            return evento.status;
        });

        const valoresStatus = eventosStatus.map(function(evento) {
            return evento.total;
        });

        new Chart(graficoStatus, {
            type: 'doughnut',
            data: {
                labels: labelsStatus,
                datasets: [{
                    data: valoresStatus
                }]
            }
        });

        // Gráfico de eventos por tipo
        const eventosTipos = <?= json_encode($eventosPorTipo) ?>;
        const graficoTipos = document.getElementById('graficoEventosTipos');

        const labelsTipos = eventosTipos.map(function(evento) {
            return evento.tipo;
        });

        const valoresTipos = eventosTipos.map(function(evento) {
            return evento.total;
        });

        new Chart(graficoTipos, {
            type: 'doughnut',
            data: {
                labels: labelsTipos,
                datasets: [{
                    data: valoresTipos
                }]
            }
        });

        // Gráfico de eventos por mês
        const eventosMes = <?= json_encode($eventosPorMes) ?>;
        const graficoMes = document.getElementById('graficoEventosMes');

        const labelsMes = eventosMes.map(function(evento) {
            return evento.mes;
        });

        const valoresMes = eventosMes.map(function(evento) {
            return evento.total;
        });

        new Chart(graficoMes, {
            type: 'line',
            data: {
                labels: labelsMes,
                datasets: [{
                    label: 'Eventos',
                    data: valoresMes
                }]
            }
        });

        // ============================================================
        // GRÁFICOS DE ANIMAIS
        // ============================================================

        // Gráfico de saúde dos animais
        const animaisSaude = <?= json_encode($animaisPorSaude) ?>;
        const graficoSaude = document.getElementById('graficoAnimaisSaude');

        const labelsSaude = animaisSaude.map(function(animal) {
            return animal.nome;
        });

        const valoresSaude = animaisSaude.map(function(animal) {
            return animal.total;
        });

        new Chart(graficoSaude, {
            type: 'doughnut',
            data: {
                labels: labelsSaude,
                datasets: [{
                    data: valoresSaude
                }]
            }
        });
    </script>
</body>

</html>