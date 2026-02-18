<?php
require "../auth.php";
require "../database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: res-sessoes.php");
    exit;
}

$sql = "SELECT sessoes.*, apostilas.name AS nomeApostila
        FROM sessoes LEFT JOIN apostilas 
        ON apostilas.id = sessoes.id_apostila
        WHERE sessoes.id = ? AND sessoes.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $_SESSION["user_id"]]);

$sessao = $stmt->fetch(PDO::FETCH_ASSOC);


$inicio = new DateTime($sessao["start_time"]);
$fim = new DateTime($sessao["end_time"]);
$intervalo = $inicio->diff($fim);

$horas = $intervalo->h + ($intervalo->i / 60);

if (!$sessao) {
    header("Location: res-sessoes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de sessões-read</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-4">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4">Visualizar Sessão</h1>
                            <div class="mb-3">
                                <label class="form-label">Nome da Sessão</label>
                                <input name="nome" type="text" class="form-control" 
                                value="<?= htmlspecialchars($sessao["name"]) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Data</label>
                                <input name="data" type="date" class="form-control"
                                value="<?= $sessao["sessao_date"] ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora inicial</label>
                                <input name="inicial" type="time" class="form-control"
                                value="<?= $sessao["start_time"] ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora final</label>
                                <input name="final" type="time" class="form-control"
                                value="<?= $sessao["end_time"] ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Horas estudadas</label>
                                <input name="horas" type="number" class="form-control" 
                                value="<?= number_format($horas, 2) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apostila selecionada</label>
                                <input type="text" class="form-control"
                                value="<?= htmlspecialchars($sessao["nomeApostila"] ?? "Nenhuma") ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Conteúdo</label>
                                <textarea name="conteudo" class="form-control" rows="3" readonly><?= htmlspecialchars($sessao["conteudo"]) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Objetivo</label>
                                <textarea name="objetivo" class="form-control" rows="3" readonly><?= htmlspecialchars($sessao["objetivo"]) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <a href="res-sessoes.php" class="btn btn-success w-100 py-2 mt-2">Voltar</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>