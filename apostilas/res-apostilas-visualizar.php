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

if (!$apostila) {
    header("Location: res-apostilas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de apostilas-read</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-4">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4">Visualizar Apostila</h1>
                            <div class="mb-3">
                                <label class="form-label">Nome da Apostila</label>
                                <input name="nome" type="text" class="form-control"
                                value="<?= htmlspecialchars($apostila["name"]) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="dificuldade">Selecione a dificuldade da apostila</label>
                                <select class="form-select" id="dificuldade" disabled>
                                    <option <?= $apostila["nivel"] === "Iniciante" ? "selected" : "" ?>>Iniciante</option>
                                    <option <?= $apostila["nivel"] === "Intermediário" ? "selected" : "" ?>>Intermediário</option>
                                    <option <?= $apostila["nivel"] === "Avançado" ? "selected" : "" ?>>Avançado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <?php if ($apostila["arquivo"]): ?>
                                    <p>Arquivo atual da apostila: <a href="../uploads/apostilas/<?= urlencode($apostila["arquivo"]) ?>" 
                                    target="_blank">
                                        <?= htmlspecialchars($apostila["arquivo"]) ?>
                                    </a></p>

                                    <a href="../uploads/apostilas/<?= urlencode($apostila["arquivo"]) ?>"
                                    class="btn btn-primary w-10" download>
                                        Baixar arquivo
                                    </a>
                                <?php else: ?>
                                    <label for="fileApostila" class="form-label">Arquivo da apostila:</label>
                                    <p class="text-muted">Nenhum arquivo enviado</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <a href="res-apostilas.php" class="btn btn-success w-100 py-2 mt-2">Voltar</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>