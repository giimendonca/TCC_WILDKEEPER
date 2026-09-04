<?php
session_start();

include "../includes/conexao.php";
include "../includes/funcoes.php";
include "../includes/autenticacao.php";

// Verifica se há uma sessão ativa
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verifica a permissão que o usuário possui
requireNivel(40);

// Dados da novo animal
$animal = [
    "nome" => trim($_POST['nome'] ?? ''),
    "sexo" => trim($_POST['sexo'] ?? ''),
    "data_nascimento" => trim($_POST['data_nascimento'] ?? ''),
    "data_chegada" => trim($_POST['data_chegada'] ?? ''),
    "peso" => trim($_POST['peso'] ?? ''),
    "altura" => trim($_POST['altura'] ?? ''),
    "microchip" => trim($_POST['microchip'] ?? ''),
    "observacoes" => trim($_POST['observacoes'] ?? ''),
    "especie_id" => trim($_POST['especie_id'] ?? ''),
    "habitat_id" => trim($_POST['habitat_id'] ?? ''),
    "status_id" => trim($_POST['status_id'] ?? ''),
    "saude_status_id" => trim($_POST['saude_status_id'] ?? ''),
    "instituicao_id" => $_SESSION['instituicao_id']
];

// Campos obrigatórios
$camposObrigatorios = [
    "nome",
    "sexo",
    "data_nascimento",
    "data_chegada",
    "peso",
    "altura",
    "microchip",
    "observacoes",
    "especie_id",
    "habitat_id",
    "status_id",
    "saude_status_id",
    "instituicao_id"
];


// Verifica se os campos obrigatórios foram preenchidos
if (!verificarCamposObrigatorios($animal, $camposObrigatorios)) {
    die("Há campos obrigatórios não preenchidos.");
}

// Verifica se foram enviados mais de 5 arquivos
if(count($_FILES['fotos']['name']) > 5){
    die("Você pode selecionar no máximo 5 fotos.");
}

foreach($_POST['descricoes'] as $descricao){
    if(empty($descricao)){
        die("Há fotos sem descrição.");
    }
}

try {
    $conexao->begin_transaction();

    $sql = "INSERT INTO animais (nome, sexo, data_nascimento, data_chegada, peso, altura, microchip, observacoes, especie_id, habitat_id, status_id, saude_status_id, instituicao_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param("ssssddssiiiii", ...array_values($animal));
    $stmt->execute();

    // Pega o ID do animal que foi inserido
    $animalID = $conexao->insert_id;

    // verifica se foram enviados arquivos
    if(isset($_FILES['fotos'])){
        $pasta = "../../assets/img/animais/";

        $tipoPermitidos = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        $limite = 5 * 1024 * 1024; // 5 MB

        // percorre todas as fotos
        foreach($_FILES['fotos']['tmp_name'] as $indice => $tmpName){
            $nomeOriginal = $_FILES['fotos']['name'][$indice];
            $tamanho = $_FILES['fotos']['size'][$indice];
            $erro = $_FILES['fotos']['error'][$indice];
 
            // verifica se o upload terminou corretamente
            if($erro !== UPLOAD_ERR_OK){
                continue;
            }

            // verifica o tamanho do arquivo
            if($tamanho > $limite){
                continue;
            }

            // descobre o tipo real da imagem/arquivo
            $finfo =  new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpName);

            // verifica se o tipo é permitido
            if(!in_array($mime, $tipoPermitidos)){
                continue;
            }

            // relaciona o type com a extensão
            // descobre a extensão daquele type
            $extensoes = [
                "image/jpeg" => "jpeg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            $extensao = $extensoes[$mime];

            // gera o nome unico da imagem
            $nomeArquivo = uniqid() . "." . $extensao;

            // define onde vai salvar a imagem
            $destino = $pasta . $nomeArquivo;

            // move o arquivo
            if(move_uploaded_file($tmpName, $destino)){
                $caminho = "assets/img/animais/" . $nomeArquivo;

                // salva no banco de dados o caminho e a descricao
                $descricao = $_POST['descricoes'][$indice] ?? '';

                $stmtFoto = $conexao->prepare("INSERT INTO animais_fotos (animal_id, caminho_arquivo, descricao, instituicao_id) VALUE (?, ?, ?, ?)");
                $stmtFoto->bind_param("issi", $animalID, $caminho, $descricao, $_SESSION['instituicao_id']);
                $stmtFoto->execute();
            }
        }
    }

    $conexao->commit();

} catch (mysqli_sql_exception $e) {
    $conexao->rollback();

    die("Erro ao cadastrar: " . $e->getMessage());
}

header("Location: index.php");
exit();
?>