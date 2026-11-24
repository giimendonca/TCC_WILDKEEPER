    <?php

    session_start();

    include "../includes/conexao.php";
    include "../includes/autenticacao.php";

    if (!isset($_SESSION['id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    requireNivel(60);

    $instituicao_id = $_SESSION['instituicao_id'];

    // Pega os dados enviados pelo formulário
    $evento_id = $_POST['evento_id'] ?? null;
    $diagnostico = trim($_POST['diagnostico'] ?? '');
    $tratamento = trim($_POST['tratamento'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    $data_retorno = trim($_POST['data_retorno'] ?? '');
    $medicamentos = $_POST['medicamentos'] ?? [];

    // Verifica os campos obrigatórios
    if (!$evento_id || !$diagnostico || !$tratamento) {
        die("Preencha todos os campos obrigatórios.");
    }

    if (empty($data_retorno)) {
        $data_retorno = null;
    }

    // ====================================
    // Busca e valida o evento
    // ====================================

    $sqlEvento = "SELECT id, status
FROM eventos
WHERE id = ?
AND tipo = 'Consulta'
AND instituicao_id = ?";

    $stmtEvento = $conexao->prepare($sqlEvento);
    $stmtEvento->bind_param("ii", $evento_id, $instituicao_id);
    $stmtEvento->execute();

    $resultEvento = $stmtEvento->get_result();
    $evento = $resultEvento->fetch_assoc();

    // Verifica se o evento existe
    if (!$evento) {
        die("Evento de consulta inválido.");
    }

    // Verifica se o evento pode ser realizado
    if ($evento['status'] != 'Agendado' && $evento['status'] != 'Em andamento') {
        die("Este evento não pode ser realizado.");
    }

    // ====================================
    // Verifica se a consulta já foi realizada
    // ====================================

    $sqlConsulta = "SELECT id
FROM consultas
WHERE evento_id = ?";

    $stmtConsulta = $conexao->prepare($sqlConsulta);
    $stmtConsulta->bind_param("i", $evento_id);
    $stmtConsulta->execute();

    $resultConsulta = $stmtConsulta->get_result();

    if ($resultConsulta->num_rows > 0) {
        die("Esta consulta já foi realizada.");
    }

    // Valida os medicamentos selecionados
if (!is_array($medicamentos)) {
    die("Dados dos medicamentos inválidos.");
}

$medicamentosSelecionados = [];

foreach ($medicamentos as $medicamento_id => $dados) {

    if (!is_array($dados) || !isset($dados['selecionado'])) {
        continue;
    }

    $medicamento_id = filter_var($medicamento_id, FILTER_VALIDATE_INT);

    if (!$medicamento_id || $medicamento_id <= 0) {
        die("Medicamento inválido.");
    }

    $sqlMedicamento = "SELECT id
    FROM medicamentos
    WHERE id = ?
    AND instituicao_id = ?";

    $stmtMedicamento = $conexao->prepare($sqlMedicamento);
    $stmtMedicamento->bind_param("ii", $medicamento_id, $instituicao_id);
    $stmtMedicamento->execute();

    if ($stmtMedicamento->get_result()->num_rows === 0) {
        die("Um dos medicamentos selecionados é inválido.");
    }

    $medicamentosSelecionados[] = [
        'id' => $medicamento_id,
        'dosagem' => trim($dados['dosagem'] ?? ''),
        'observacoes' => trim($dados['observacoes'] ?? '')
    ];
}

    // ====================================
    // Salva a consulta e conclui o evento
    // ====================================

    $conexao->begin_transaction();

    try {

        // Insere o resultado da consulta
        $sql = "INSERT INTO consultas
        (evento_id, diagnostico, tratamento, observacoes, data_retorno)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "issss",
            $evento_id,
            $diagnostico,
            $tratamento,
            $observacoes,
            $data_retorno
        );

        if (!$stmt->execute()) {
            throw new Exception("Erro ao registrar a consulta.");
        }

        // Recupera o ID da consulta recém-cadastrada
        $consulta_id = $conexao->insert_id;

        // Associa os medicamentos à consulta
        $sqlAssociacao = "INSERT INTO medicamentos_consulta
                (consulta_id, medicamento_id, dosagem, observacoes)
                VALUES (?, ?, ?, ?)";

        $stmtAssociacao = $conexao->prepare($sqlAssociacao);

        foreach ($medicamentosSelecionados as $medicamento) {
    $stmtAssociacao->bind_param(
        "iiss",
        $consulta_id,
        $medicamento['id'],
        $medicamento['dosagem'],
        $medicamento['observacoes']
    );

    if (!$stmtAssociacao->execute()) {
        throw new Exception("Erro ao associar medicamento à consulta.");
    }
}

        // Altera o status do evento para concluído
        $sqlEvento = "UPDATE eventos
    SET status = 'Concluído'
    WHERE id = ?
    AND instituicao_id = ?";

        $stmtEvento = $conexao->prepare($sqlEvento);
        $stmtEvento->bind_param("ii", $evento_id, $instituicao_id);

        if (!$stmtEvento->execute()) {
            throw new Exception("Erro ao concluir o evento.");
        }

        $conexao->commit();

        header("Location: index.php?sucesso=realizado");
        exit();
    } catch (Exception $e) {

        $conexao->rollback();

        die("Erro ao realizar consulta: " . $e->getMessage());
    }
