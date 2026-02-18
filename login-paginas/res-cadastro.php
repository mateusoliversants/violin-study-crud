<?php
require "../database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $_POST["email"] ?? "";
    $senha = $_POST["password"] ?? "";

    if (!$email || !$senha) {
        $error = "Preencha todos os campos";
    }
    else {
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$email]);

        if ($checkStmt->fetch()) {
            $error = "Este email já está cadastrado";
        }
        else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $senhaHash]);

            $success = "Cadastro realizado!";
            header("Refresh: 2; url=res-login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de cadastro</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">

            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p style="color:green"><?= $success ?></p>
            <?php endif; ?>

                <form method="POST">
                    <h1 class="h3 mb-3 fw-normal">Cadastro</h1>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name">
                        <label for="name">Usuário</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email mb-3" name="email" class="form-control" placeholder="name@example.com" id="email">
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" id="senha">
                        <label for="senha">Senha</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>