<?php
include_once "conexao.php";

// Valida campos obrigatórios
function verificarCamposObrigatorios($dados, $camposObrigatorios)
{
    foreach ($camposObrigatorios as $campo) {
        if (empty($dados[$campo])) {
            return false;
        }
    }

    return true;
}

// Verifica se um registro já existe
function registroExiste($conexao, $tabela, $coluna, $valor)
{
    $sql = "SELECT 1 FROM $tabela WHERE $coluna = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $valor);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result->num_rows > 0;
}

// Verifica se existe um registro igual mas que não seja ele mesmo
function registroExisteOutro($conexao, $tabela, $coluna, $valor, $id)
{
    $sql = "SELECT 1 FROM $tabela WHERE $coluna = ? AND id != ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $valor, $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    return $result->num_rows > 0;
}

// Remove tudo o que não for numeros
function apenasNumeros($texto)
{
    return preg_replace('/\D/', '', $texto);
}

// Valida email
function emailValido($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Faz o SELECT inteiro de uma tabela
function selectTabela($conexao, $tabela)
{
    $sql = "SELECT * FROM $tabela";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

// Faz o COUNT de uma tabela protegida pela instituicao_id
function countTabela($conexao, $tabela, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM $tabela WHERE instituicao_id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// Faz o COUNT de uma tabela global
function countTabelaGlobal($conexao, $tabela)
{
    $sql = "SELECT COUNT(*) AS total FROM $tabela";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// COUNT de funcionários por coluna = valor
function countFuncionarios($conexao, $coluna, $valor, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM users WHERE instituicao_id = ? AND $coluna = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("is", $id_instituicao, $valor);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// COUNT de funionários com GROUP BY
function countFuncionariosPorColuna($conexao, $coluna, $id_instituicao)
{
    $sql = "SELECT $coluna, COUNT(*) as total FROM users WHERE instituicao_id = ? GROUP BY $coluna ";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();

    $dados = [];

    while ($r = $result->fetch_assoc()) {
        $dados[] = $r;
    }

    return $dados;
}

// COUNT de funionários com GROUP BY
function countFuncionariosPorCargo($conexao, $id_instituicao)
{
    $sql = "SELECT users.cargo_id, COUNT(*) AS total, cargos.nome FROM users 
            INNER JOIN cargos ON cargos.id = users.cargo_id
            WHERE users.instituicao_id = ? 
            GROUP BY users.cargo_id, cargos.nome;";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();

    $dados = [];

    while ($r = $result->fetch_assoc()) {
        $dados[] = $r;
    }

    return $dados;
}

// Faz o COUNT de eventos por coluna = valor
// especificamente para Status e Tipo 
function countEventos($conexao, $coluna, $valor, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM eventos WHERE instituicao_id = ? AND $coluna = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("is", $id_instituicao, $valor);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// Faz COUNT de eventos que são nesse periodo do tipoX
// $coluna, $valorColuna, $data_hoje, $data_amanha devem ser strings 
function countEventosTipoPeriodo($conexao, $coluna, $valorColuna, $data_hoje, $data_amanha, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM eventos WHERE instituicao_id = ? AND $coluna = ? AND data_inicio >= ? AND data_inicio < ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("isss", $id_instituicao, $valorColuna, $data_hoje, $data_amanha);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $result = $result['total'];

    return $result;
}

// COUNT de eventos no geral que são nesse pariodo
function countEventosPeriodo($conexao, $data_hoje, $data_amanha, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM eventos WHERE instituicao_id = ? AND data_inicio >= ? AND data_inicio < ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iss", $id_instituicao, $data_hoje, $data_amanha);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $result = $result['total'];

    return $result;
}

// COUNT de EVENTOS com GROUP BY
function countEventosPorColuna($conexao, $coluna, $id_instituicao)
{
    $sql = "SELECT $coluna, COUNT(*) as total FROM eventos WHERE instituicao_id = ? GROUP BY $coluna ";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();

    $dados = [];

    while ($r = $result->fetch_assoc()) {
        $dados[] = $r;
    }

    return $dados;
}

function countEventosPorMes($conexao, $id_instituicao)
{
    $sql = "SELECT DATE_FORMAT(data_inicio, '%Y-%m') AS mes, COUNT(*) AS total
            FROM eventos
            WHERE instituicao_id = ?
            GROUP BY DATE_FORMAT(data_inicio, '%Y-%m')
            ORDER BY DATE_FORMAT(data_inicio, '%Y-%m')";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = [];

    while ($linha = $result->fetch_assoc()) {
        $dados[] = $linha;
    }

    return $dados;
}

// Faz o COUNT de medicamentos com estoque até o limite informado
function countMedicamentosEstoque($conexao, $limite, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM medicamentos WHERE instituicao_id = ? AND estoque <= ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $id_instituicao, $limite);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// Faz o COUNT de medicamentos sem estoque
function countMedicamentosSemEstoque($conexao, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total FROM medicamentos WHERE instituicao_id = ? AND estoque = 0";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// SELECT dos medicamentos com estoque baixo
function listarMedicamentosEstoqueBaixo($conexao, $limite, $id_instituicao)
{
    $sql = "SELECT id, nome, fabricante, estoque, lote, vencimento
            FROM medicamentos
            WHERE instituicao_id = ? AND estoque <= ?
            ORDER BY estoque ASC, vencimento ASC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $id_instituicao, $limite);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = [];

    while ($medicamento = $result->fetch_assoc()) {
        $dados[] = $medicamento;
    }

    return $dados;
}

// COUNT animais saudáveis
function countAnimaisSaudaveis($conexao, $id_instituicao)
{
    $sql = "SELECT COUNT(*) AS total
            FROM animais
            INNER JOIN saude_status ON animais.saude_status_id = saude_status.id
            WHERE animais.instituicao_id = ? AND saude_status.sigla = 'SA'";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    return $result['total'];
}

// SELECT dos animais por situação de saúde
function countAnimaisPorSaude($conexao, $id_instituicao)
{
    $sql = "SELECT saude_status.nome, COUNT(*) AS total
            FROM animais
            INNER JOIN saude_status ON animais.saude_status_id = saude_status.id
            WHERE animais.instituicao_id = ?
            GROUP BY saude_status.id, saude_status.nome
            ORDER BY saude_status.id";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = [];

    while ($linha = $result->fetch_assoc()) {
        $dados[] = $linha;
    }

    return $dados;
}

// Lista os próximos eventos da instituição
function listarProximosEventos($conexao, $id_instituicao)
{
    $sql = "SELECT eventos.id, eventos.tipo, eventos.titulo, eventos.status, eventos.data_inicio, animais.nome AS animal_nome, habitats.nome AS habitat_nome
        FROM eventos
        LEFT JOIN animais ON eventos.animal_id = animais.id
        LEFT JOIN habitats ON eventos.habitat_id = habitats.id
        WHERE eventos.instituicao_id = ? AND eventos.data_inicio >= NOW()
        ORDER BY eventos.data_inicio ASC
        LIMIT 5";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_instituicao);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = [];

    while ($evento = $result->fetch_assoc()) {
        $dados[] = $evento;
    }

    return $dados;
}

// SELECT ultimo registro da tabela
function ultimoInsert($conexao, $coluna, $tabela, $instituicao_id){
    $sql = "SELECT $coluna, created_at FROM $tabela WHERE instituicao_id = ? ORDER BY created_at DESC
    LIMIT 1";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param('i', $instituicao_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}