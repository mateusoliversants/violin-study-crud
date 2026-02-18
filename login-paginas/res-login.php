<?php
session_start();
require "../database.php";

$error = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $email = $_POST["email"] ?? "";
    $senha = $_POST["password"] ?? "";

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($senha, $user["password"]))  {
        $_SESSION["user_id"] = $user["id"];
        header("Location: ../sessoes/res-sessoes.php");
        exit;
    }
    else {
        $error = "Email ou senha inválidos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">

            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>

                <form method="POST">
                    <h1 class="h3 mb-3 fw-normal">Login</h1>

                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="loginEmail" placeholder="name@example.com">
                        <label for="loginEmail">Email</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="loginSenha" placeholder="Password">
                        <label for="loginSenha">Senha</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-2 mt-2">Entrar</button>

                    <a href="res-senha.php" class="d-block mb-2">Esqueci a senha</a>
                    <a href="res-cadastro.php" class="d-block">Ainda não sou cadastrado</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>