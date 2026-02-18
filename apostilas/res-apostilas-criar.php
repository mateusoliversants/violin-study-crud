<?php
require "../auth.php";
require "../database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nomeApostila"] ?? "";
    $dificuldade = $_POST["dificuldade"] ?? "";
    $fileApostila = null;

    if (!empty($_FILES["fileApostila"]["name"])) {
        
        $arqTipo = strtolower(pathinfo($_FILES["fileApostila"]["name"], PATHINFO_EXTENSION));

        if ($arqTipo !== "pdf") {
            $error = "Apenas arquivos PDF são permitidos";
        }

        $nomeArq = uniqid() . ".pdf";
        $caminho = "../uploads/apostilas/" . $nomeArq;

        if (move_uploaded_file($_FILES["fileApostila"]["tmp_name"], $caminho)) {
            $fileApostila = $nomeArq;
        }
        else {
            $error = "Erro ao salvar o arquivo";
        }
    }

    if ($nome === "" || $dificuldade === "") {
        $error = "Preencha os campos obrigatórios";
    }
    else {

        $sql = "INSERT INTO apostilas
        (user_id, name, nivel, arquivo)
        VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_SESSION["user_id"],
            $nome,
            $dificuldade,
            $fileApostila
        ]);

        header("Location: res-apostilas.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de apostilas-create</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-4">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4">Nova Apostila</h1>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nome da Apostila</label>
                                <input name="nomeApostila" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="dificuldade">Selecione a dificuldade da apostila</label>
                                <select class="form-select" name="dificuldade">
                                    <option value="Iniciante">Iniciante</option>
                                    <option value="Intermediário">Intermediário</option>
                                    <option value="Avançado">Avançado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fileApostila" class="form-label">Faça upload do arquivo pdf da apostila</label>
                                <input class="form-control" type="file" name="fileApostila">
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-success w-100 py-2 mt-2">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>