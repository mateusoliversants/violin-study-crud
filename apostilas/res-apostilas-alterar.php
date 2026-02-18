<?php
require "../auth.php";
require "../database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: res-apostilas.php");
    exit;
}

$sql = "SELECT * FROM apostilas WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $_SESSION["user_id"]]);

$apostila = $stmt->fetch(PDO::FETCH_ASSOC);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nomeApostila"];
    $dificuldade = $_POST["dificuldade"];
    $fileApostila = $apostila["arquivo"];

    if (!empty($_FILES["fileApostila"]["name"])) {
        
        $arqTipo = strtolower(pathinfo($_FILES["fileApostila"]["name"], PATHINFO_EXTENSION));

        if ($arqTipo !== "pdf") {
            $error = "Apenas arquivos PDF são permitidos";
        }
        else {
            $nomeArq = uniqid() . ".pdf";
            $caminho = "../uploads/apostilas/" . $nomeArq;

            if (move_uploaded_file($_FILES["fileApostila"]["tmp_name"], $caminho)) {
                if ($apostila["arquivo"] && file_exists("../uploads/apostilas/" . $apostila["arquivo"])) {
                    unlink("../uploads/apostilas/" . $apostila["arquivo"]);
                }

                $fileApostila = $nomeArq;
            }
            else {
                $error = "Erro ao enviar o arquivo";
            }
        }
    }

    if ($nome === "" || $dificuldade === "") {
        $error = "Preencha os campos obrigatórios";
    }
    else {
        $sql = "UPDATE apostilas SET
        name =?,
        nivel = ?,
        arquivo = ?
        WHERE id = ? AND user_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nome,
            $dificuldade,
            $fileApostila,
            $id,
            $_SESSION["user_id"]
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
    <title>Página de apostilas-update</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-4">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4">Editar Apostila</h1>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nome da Apostila</label>
                                <input name="nomeApostila" type="text" class="form-control"
                                value="<?= htmlspecialchars($apostila["name"]) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="dificuldade">Selecione a dificuldade da apostila</label>
                                <select class="form-select" name="dificuldade">
                                    <option value="Iniciante" <?= $apostila["nivel"] === "Iniciante" ? "selected" : "" ?>>Iniciante</option>
                                    <option value="Intermediário"<?= $apostila["nivel"] === "Intermediário" ? "selected" : "" ?>>Intermediário</option>
                                    <option value="Avançado" <?= $apostila["nivel"] === "Avançado" ? "selected" : "" ?>>Avançado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <?php if ($apostila["arquivo"]): ?>
                                    <p>Arquivo atual: <a href="../uploads/apostilas/<?= urlencode($apostila["arquivo"]) ?>" target="_blank">
                                        <?= htmlspecialchars($apostila["arquivo"]) ?>
                                    </a></p>
                                <?php endif; ?>

                                <label for="fileApostila" id="arquivoAtual" class="form-label">Faça upload do arquivo pdf da apostila</label>
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