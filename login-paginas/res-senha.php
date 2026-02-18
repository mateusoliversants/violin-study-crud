<?php
require "../database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ?? "";
    $senha = $_POST["password"] ?? "";
    $confirmar = $_POST["confirm_pass"] ?? "";

    if (!$email || !$senha || !$confirmar) {
        $error = "Preencha todos os campos";
    }
    elseif ($senha !== $confirmar) {
        $error = "As senhas devem ser idênticas";
    }
    else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (!$user) {
            $error = "Email não encontrado";
        }
        else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $updateSql = "UPDATE users SET password = ? WHERE email = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$senhaHash, $email]);

            $success = "Senha atualizada!";
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
    <title>Página de esqueci a senha</title>

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
                    <h1 class="h3 mb-3 fw-normal">Redefinir senha</h1>

                    <div class="form-floating mb-3">
                        <input type="email mb-3" name="email" class="form-control" placeholder="name@example.com" id="email">
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="novaSenha" placeholder="Password" autocomplete="new-password">
                        <label for="novaSenha">Nova senha</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="confirm_pass" class="form-control" id="confirmSenha" placeholder="Password" autocomplete="new-password">
                        <label for="confirmSenha">Confirmar senha</label>
                    </div>

                    <div id = "mensagem" class="mb-3 text-center"></div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>