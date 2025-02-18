<?php
// Iniciar a sessão
session_start();

// Verificar se o motivo foi passado na URL
if (!isset($_GET['motive'])) {
    // Se não houver motivo, redirecionar para a home ou outra página
    header("Location: home.php");
    exit();
}

$motive_ban = htmlspecialchars($_GET['motive']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ban - RETROBLOX</title>
    <style>
        /* Estilos gerais */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            height: 100%;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Container do banimento */
        .ban-container {
            width: 80%;
            max-width: 600px;
            background-color: rgba(0, 0, 0, 0.7); /* Transparente */
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .ban-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .ban-container p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .logout-button {
            background-color: #6e99c9;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-button:hover {
            background-color: #4d7b99;
        }
    </style>
</head>
<body>
    <div class="ban-container">
        <h1>You have been banned!</h1>
        <p>Reason for ban:</p>
        <p><strong><?php echo $motive_ban; ?></strong></p>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>