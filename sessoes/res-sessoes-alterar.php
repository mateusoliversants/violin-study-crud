<?php
require "../auth.php";
require "../database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: res-sessoes.php");
    exit;
}

$sql = "SELECT * FROM sessoes WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $_SESSION["user_id"]]);

$sessao = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT id, name FROM apostilas WHERE user_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$apostilas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$sessao) {
    header("Location: res-sessoes.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nome"] ?? "";
    $data = $_POST["data"] ?? "";
    $inicial = $_POST["inicial"] ?? "";
    $final = $_POST["final"] ?? "";
    $selecao = $_POST["id_apostila"] ?? null;
    $conteudo = $_POST["conteudo"] ?? "";
    $objetivo = $_POST["objetivo"] ?? "";

    if ($nome === "" || $data === "" || $inicial === "" || $final === "") {
        $error = "Preencha os campos obrigatórios";
    }
    else {
        $sql = "UPDATE sessoes SET
        name = ?,
        sessao_date = ?,
        start_time = ?,
        end_time = ?,
        id_apostila = ?,
        conteudo = ?,
        objetivo = ?
        WHERE id = ? AND user_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nome,
            $data,
            $inicial,
            $final,
            $selecao,
            $conteudo,
            $objetivo,
            $id,
            $_SESSION["user_id"]
        ]);

        header("Location: res-sessoes.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de sessões-update</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-4">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4">Editar Sessão</h1>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nome da Sessão</label>
                                <input name="nome" type="text" class="form-control"
                                value="<?= htmlspecialchars($sessao["name"]) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Data</label>
                                <input name="data" type="date" class="form-control"
                                value="<?= $sessao["sessao_date"] ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora inicial</label>
                                <input name="inicial" type="time" class="form-control"
                                value="<?= $sessao["start_time"] ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora final</label>
                                <input name="final" type="time" class="form-control"
                                value="<?= $sessao["end_time"] ?>">
                            </div>

                            <div class="mb-3">
                                <input name="horas" class="form-control" type="hidden">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apostila selecionada</label>
                                <select name="id_apostila" class="form-select">
                                    <option value="">Nenhuma apostila selecionada</option>
                                    
                                    <?php foreach ($apostilas as $x): ?>
                                        <option value="<?= $x["id"] ?>">
                                            <?= $sessao["id_apostila"] == $x["id"] ? "Atual:" : "" ?>
                                            <?= htmlspecialchars($x["name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Conteúdo</label>
                                <textarea name="conteudo" class="form-control" rows="3"><?= htmlspecialchars($sessao["conteudo"]) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Objetivo</label>
                                <textarea name="objetivo" class="form-control" rows="3"><?= htmlspecialchars($sessao["objetivo"]) ?></textarea>
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