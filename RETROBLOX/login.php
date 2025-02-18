<?php
// Conexão com o banco de dados
$host = 'sql103.ezyro.com';
$dbname = 'ezyro_38183570_users';
$username = 'ezyro_38183570'; // ajuste se necessário
$password = ''; // ajuste se necessário

$conn = new mysqli($host, $username, $password, $dbname);

// Verifica se há erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$msg = "";
session_start(); // Iniciar a sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Verificar se o usuário existe no banco de dados
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar a senha usando password_verify
        if (password_verify($pass, $row['password'])) {
            // Criar a sessão para o usuário
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: home.php"); // Redireciona para a página home.php
            exit(); // Impede a execução do código abaixo
        } else {
            $msg = "Wrong Password.";
        }
    } else {
        $msg = "User Not Found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Resetando margens e padding, e aplicando a fonte Comic Sans */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            border: 2px solid #000; /* Borda preta */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px dashed #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .input-field:focus {
            border-color: #007bff;
            outline: none;
        }

        .forgot-password {
            margin-top: 20px;
            font-size: 14px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #007bff;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <?php if ($msg) echo "<p style='color:red;'>$msg</p>"; ?>
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="btn-submit">Log in</button>
        </form>
        <div class="forgot-password">
            <a href="forgotpass.php">Forgot Password?</a>
        </div>
    </div>

</body>
</html>