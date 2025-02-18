<?php
// Inicie a sessão para capturar o nome do jogador e o ID
session_start();

// Verifique se o jogador está logado, caso contrário, redirecione para a página de login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
?>
<?php
$server = 'sql103.ezyro.com';  // Novo nome para a variável de host
$database = 'ezyro_38183570_users';    // Novo nome para o nome do banco de dados
$db_user = 'ezyro_38183570';           // Novo nome para o usuário
$db_password = '';        // Novo nome para a senha

// Conectar ao banco de dados
$conn = new mysqli($server, $db_user, $db_password, $database);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o usuário está logado
session_start();
if (isset($_SESSION['username'])) {
    $usernameee = $_SESSION['username'];

    // Consulta para verificar o status de banimento e o motivo
    $query = "SELECT ban, motive_ban FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usernameee);
    $stmt->execute();
    $stmt->bind_result($ban, $motive_ban);
    $stmt->fetch();
    $stmt->close();

    if ($ban == 1) {
        // Usuário banido, redirecionar para a página de banimento
        header("Location: Ban.php?motive=" . urlencode($motive_ban));
        exit();
    }
} else {
    // Usuário não logado
    header("Location: login.php");
    exit();
}
?>
<?php
// Configuração de conexão com o banco de dados
$servidor = 'sql103.ezyro.com'; 
$data = 'ezyro_38183570_users'; 
$db = 'ezyro_38183570'; 
$db_pass = ''; 

// Conectar ao banco de dados
$conn = new mysqli($servidor, $db, $db_pass, $data);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Se não estiver logado, redireciona para o login
    exit();
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Buscar os valores de Tix e Bux do usuário
$sql = "SELECT tix, bux FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tix, $bux);
$stmt->fetch();
$stmt->close();
// Fechar a conexão
$conn->close();
?>
<?php
// Conectar ao banco de dados
$servidor = 'sql103.ezyro.com'; 
$data = 'ezyro_38183570_users'; 
$db = 'ezyro_38183570'; 
$db_pass = ''; 

$conn = new mysqli($servidor, $db, $db_pass, $data);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Se não estiver logado, redireciona para o login
    exit();
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Verificar se o usuário é administrador na base de dados
$sql = "SELECT admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($admin);
$stmt->fetch();
$stmt->close();

// Se o usuário não for admin, redireciona para a página de login
if ($admin != 1) {
    header("Location: error.php?id=2");
    exit();
}

$mensagem = '';
$mensagem_erro = '';

// Adicionar ou alterar Bux ou Tix de um usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adicionar_bux'])) {
        $username = trim($_POST['username']);
        $quantidade_bux = $_POST['quantidade_bux'];

        // Verificar se o nome de usuário existe na base de dados
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Atualizar a quantidade de Bux
            $update_sql = "UPDATE users SET bux = bux + ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("ii", $quantidade_bux, $user_id);
            $stmt_update->execute();
            $mensagem = "Bux added successfully!";
        } else {
            $mensagem_erro = "User not found!";
        }
        $stmt->close();
    }

    if (isset($_POST['adicionar_tix'])) {
        $username = trim($_POST['username_tix']);
        $quantidade_tix = $_POST['quantidade_tix'];

        // Verificar se o nome de usuário existe na base de dados
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Atualizar a quantidade de Tix
            $update_sql = "UPDATE users SET tix = tix + ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("ii", $quantidade_tix, $user_id);
            $stmt_update->execute();
            $mensagem = "Tix added successfully!";
        } else {
            $mensagem_erro = "User not found!";
        }
        $stmt->close();
    }

    // Banir usuário
    if (isset($_POST['banir_usuario'])) {
        $username_banir = trim($_POST['username_banir']);
        $motive_ban = $_POST['motive_ban'];

        // Verificar se o nome de usuário existe na base de dados
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_banir);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Atualizar status de banimento e motivo
            $update_sql = "UPDATE users SET ban = 1, motive_ban = ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("si", $motive_ban, $user_id);
            $stmt_update->execute();
            $mensagem = "User banned successfully!";
        } else {
            $mensagem_erro = "User not found!";
        }
        $stmt->close();
    }

    // Desbanir usuário
    if (isset($_POST['desbanir_usuario'])) {
        $username_desbanir = trim($_POST['username_desbanir']);

        // Verificar se o nome de usuário existe na base de dados
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_desbanir);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Atualizar status de banimento
            $update_sql = "UPDATE users SET ban = 0, motive_ban = NULL WHERE id = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("i", $user_id);
            $stmt_update->execute();
            $mensagem = "User unbanned successfully!";
        } else {
            $mensagem_erro = "User not found!";
        }
        $stmt->close();
    }
}

// Buscar usuários banidos
$sql_banidos = "SELECT username, motive_ban FROM users WHERE ban = 1";
$result_banidos = $conn->query($sql_banidos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RETROBLOX - Games</title>
 <style>
        /* Reset básico */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        
        /* Container principal */
        .container {
           width: 100%;
           height: 100vh;
           display: flex;
           flex-direction: column;
           justify-content: flex-start;  /* Alinha o conteúdo ao topo */
           align-items: center;
           background-color: #f1f1f1;
        }

        /* Banner */
        .banner {
            height: 72px;
            width: 900px;
            background-image: url('/images/Banner.png');
            background-size: cover;
            position: relative;
            border: 1px solid grey;
        }

        /* Logo no banner */
        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);  /* Move a logo para o centro */
            height: 50px;
       }

        /* Barra de navegação */
        .navbar {
            background-color: #6e99c9;
            width: 900px;
            height: 40px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border: 1px solid grey;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
        }

        .navbar a:hover {
            background-color: #6e99c9;
        }

        /* Lado direito do conteúdo */
        .right-container {
            width: 400px;
            background-color: #eeeeee;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            border: 1px solid grey;
        }

        .right-container h3 {
            margin-top: 0;
        }

        .right-container p {
            font-size: 14px;
        }

        /* Lado esquerdo - Login */
        .login-container {
            width: 400px;
            background-color: white;
            padding: 20px;
            border: 1px solid grey;
            border-radius: 5px;
            margin-top: 20px;
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
        }

        .login-container input {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: dashed 2px gray;
            border-radius: 3px;
        }

        .login-container input[type="submit"] {
            background-color: #eeeeee;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            padding:3px 10px 3px 10px;
            font:normal 11px/normal Verdana, sans-serif;
        }

        .login-container input[type="submit"]:hover {
            background-color: #6e99c9;
        }

        /* Feed image */
        .feed-image {
            
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
            .banner, .navbar, .login-container, .right-container {
                width: 100%;
                margin: 10px 0;
            }
        }
        /* Texto de Welcome e Logout */
    .welcome-text {
        color: white; /* Cor branca para o texto de welcome */
        position: absolute;
        left: 20px; /* Alinha o texto à esquerda */
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
    }

 /* Logout Link */
        .logout-link {
            color: white; 
            font-size: 16px;
            text-decoration: none;
            padding-left: 10px;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
   /* Container de Tix e Bux dentro do Banner (Menor e com fundo branco) */
        .user-info-container {
            position: absolute;
            right: 20px; /* Alinha o container à extrema direita */
            top: 50%; /* Alinha verticalmente no meio */
            transform: translateY(-50%); /* Centraliza verticalmente */
            text-align: center;
            color: black;
            background-color: white; /* Fundo branco para o container */
            padding: 6px; /* Padding ajustado para 6px */
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Sombra suave para destacar */
            font-size: 14px;
        }

        .user-info {
            margin-bottom: 5px;
        }

        .user-info img {
            width: 30px;
            height: 30px;
            vertical-align: middle;
        }

        .user-info span {
            font-size: 16px;
            font-weight: bold;
            margin-left: 10px;
        }
/* Container do jogador */
.player-container {
    width: 600px; /* Diminui o tamanho do container */
    background-color: #6e99c9; /* Mesma cor da navbar */
    border: 2px solid black;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Centraliza todos os itens no container */
    border-radius: 5px;
    margin-top: 20px;
    text-align: center; /* Centraliza o texto dentro do container */
}

.player-container h1 {
    margin: 0;
    font-size: 24px;
    color: black; /* Cor preta para o 'Welcome' */
}

.player-container p {
    font-size: 14px;
    margin-top: 10px;
}

.player-inner {
    display: flex;
    justify-content: center; /* Centraliza o conteúdo dentro do player-inner */
    align-items: center;
    margin-top: 20px;
    width: 100%;
}

.player-inner img {
    width: 110px; /* Ajusta o tamanho do avatar */
    height: auto;
    margin-right: 20px;
}

.player-actions {
    display: flex;
    flex-direction: column;
    align-items: center; /* Centraliza os links de ação */
}

.player-actions a {
    color: white;
    text-decoration: none;
    margin-bottom: 5px;
}

/* Barra de itens */
.items-container {
    width: 600px; /* Diminui o tamanho do container */
    background-color: #eeeeee;
    padding: 20px;
    margin-top: 20px;
    border-radius: 5px;
    border: 1px solid grey;
}

.items-container h2 {
    color: black; /* Cor preta para o título 'Your Items' */
    margin: 0;
    font-size: 20px;
    margin-bottom: 10px;
}

.items-container p {
    font-size: 16px;
    color: #555555;
}
 .admin-container {
            background-color: white;
            border: 2px solid black;
            padding: 20px;
            width: 500px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 2px dashed #000;
            border-radius: 5px;
        }
        .button-group {
            text-align: center;
        }
        .button-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-group button:hover {
            background-color: #0056b3;
        }
        .banidos-list {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .banidos-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .banidos-list table, .banidos-list th, .banidos-list td {
            border: 1px solid black;
        }
        .banidos-list th, .banidos-list td {
            padding: 8px;
            text-align: left;
        }
        .top-bar {
    width: 100%;
    background-color: #3e81cd; /* Cor de fundo da linha */
    padding: 10px 0; /* Espaçamento acima e abaixo da logo */
    text-align: left; /* Alinha o conteúdo à esquerda */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000; /* Garante que a barra fique em cima de outros elementos */
}

.logo-t {
    height: 25px; /* Ajuste o tamanho da logo conforme necessário */
    margin-left: 20px; /* Margem à esquerda da logo */
}

    </style>
</head>
<body>
    <div class="container">
       <div class="top-bar">
    <a href="home.php"><img src="/images/Logo.png" alt="Logo" class="logo-t"></a>
</div>
        <br>
        <br>
       <div class="admin-container">
        <h2>Admin Panel</h2>

        <!-- Display success or error message -->
        <?php if ($mensagem): ?>
            <div class="success"><?= $mensagem ?></div>
        <?php endif; ?>
        <?php if ($mensagem_erro): ?>
            <div class="error"><?= $mensagem_erro ?></div>
        <?php endif; ?>

        <!-- Form to add Bux -->
        <form method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="quantidade_bux">Bux Quantity</label>
                <input type="number" name="quantidade_bux" id="quantidade_bux" required>
            </div>
            <div class="button-group">
                <button type="submit" name="adicionar_bux">Add Bux</button>
            </div>
        </form>

        <!-- Form to add Tix -->
        <form method="POST">
            <div class="input-group">
                <label for="username_tix">Username</label>
                <input type="text" name="username_tix" id="username_tix" required>
            </div>
            <div class="input-group">
                <label for="quantidade_tix">Tix Quantity</label>
                <input type="number" name="quantidade_tix" id="quantidade_tix" required>
            </div>
            <div class="button-group">
                <button type="submit" name="adicionar_tix">Add Tix</button>
            </div>
        </form>

        <!-- Form to ban user -->
        <form method="POST">
            <div class="input-group">
                <label for="username_banir">Username to Ban</label>
                <input type="text" name="username_banir" id="username_banir" required>
            </div>
            <div class="input-group">
                <label for="motive_ban">Ban Motive</label>
                <input type="text" name="motive_ban" id="motive_ban" required>
            </div>
            <div class="button-group">
                <button type="submit" name="banir_usuario">Ban User</button>
            </div>
        </form>

        <!-- Form to unban user -->
        <form method="POST">
            <div class="input-group">
                <label for="username_desbanir">Username to Unban</label>
                <input type="text" name="username_desbanir" id="username_desbanir" required>
            </div>
            <div class="button-group">
                <button type="submit" name="desbanir_usuario">Unban User</button>
            </div>
        </form>

        <!-- Display banned users -->
        <div class="banidos-list">
            <h3>Banned Users</h3>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Ban Motive</th>
                </tr>
                <?php
                if ($result_banidos->num_rows > 0) {
                    while ($row = $result_banidos->fetch_assoc()) {
                        echo "<tr><td>" . $row['username'] . "</td><td>" . $row['motive_ban'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No banned users</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>