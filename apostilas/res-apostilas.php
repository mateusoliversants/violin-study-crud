<?php
require "../auth.php";
require "../database.php";

$userId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {

    $apostilaId = $_POST["delete_id"];

    $deleteSql = "DELETE FROM apostilas WHERE id = ? AND user_id = ?";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute([$apostilaId, $userId]);
}

$sql = "SELECT id, name FROM apostilas WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$apostilas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de apostilas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-3">
        <div class="row min-vh-100 justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                
                    <header class="d-flex justify-content-center py-3">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a href="../sessoes/res-sessoes.php" class="nav-link" aria-current="page">Sessões de Estudo</a></li>
                            <li class="nav-item"><a href="res-apostilas.php" class="nav-link active">&#127931; Apostilas &#127931;</a></li>
                            <li class="nav-item"><a href="../logout.php" class="nav-link">Sair</a></li>
                        </ul>
                    </header>

                <table class="table table-secondary table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nome da Apostila</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="apostilasTbody">
                        <?php if (count($apostilas) === 0): ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma apostila cadastrada</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($apostilas as $apostila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($apostila["name"]) ?></td>
                                    <td>
                                        <a href="res-apostilas-visualizar.php?id=<?= $apostila["id"] ?>" class="btn btn-sm btn-warning">Visualizar</a>
                                        <a href="res-apostilas-alterar.php?id=<?= $apostila["id"] ?>" class="btn btn-sm btn-primary">Editar</a>

                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="delete_id" value="<?= $apostila["id"] ?>">
                                            <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="res-apostilas-criar.php" class="btn btn-success w-100 py-2 mb-2">Criar nova Apostila</a>
            </div>
        </div>
    </div>
</body>

</html>