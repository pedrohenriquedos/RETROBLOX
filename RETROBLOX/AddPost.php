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

// Buscar os valores de Tix, Bux, Admin e Avatar_img do usuário
$sql = "SELECT tix, bux, admin, avatar_img, id FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tix, $bux, $admin, $avatar_img, $id);
$stmt->fetch();
$stmt->close();

// Fechar a conexão
$conn->close();
?>

<?php
session_start();

// Configuração de conexão com o banco de dados de fórum
$servidor = 'sql103.ezyro.com'; 
$data_forum = 'ezyro_38183570_forum'; // Banco de dados do fórum
$dbuser = 'ezyro_38183570'; 
$dbpassword = ''; 

// Criando a conexão com o banco de dados de fórum
$conn_forum = new mysqli($servidor, $dbuser, $dbpassword, $data_forum);

// Verificando se a conexão foi bem-sucedida
if ($conn_forum->connect_error) {
    die("Conexão com o banco de dados do fórum falhou: " . $conn_forum->connect_error);
}

// Verificando se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Se não estiver logado, redireciona para o login
    exit();
}


// Processando o envio do post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $post_content = trim($_POST['post_content']);

    // Verificando se o título já existe
    $sql_check = "SELECT * FROM forum_posts WHERE title = ?";
    $stmt_check = $conn_forum->prepare($sql_check);
    $stmt_check->bind_param('s', $title);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "Already Exist.";
    } else {
        // Adicionando o post na tabela
        // O campo 'post_date' será preenchido automaticamente com o valor de current_timestamp()
        $sql_insert = "INSERT INTO forum_posts (user_id, title, post_content, user, avatar) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn_forum->prepare($sql_insert);
        
        // A string de tipos 'issss' corresponde a 5 parâmetros
        if ($stmt_insert === false) {
            die('Erro ao preparar a declaração: ' . $conn_forum->error);
        }

        $stmt_insert->bind_param('issss', $id, $title, $post_content, $username, $avatar_img);

        if ($stmt_insert->execute()) {
            header("location:ShowForum.php");
        } else {
            echo "Error: " . $conn_forum->error;
        }
    }
}

// Fechar a conexão
$conn_forum->close();
?>

<?php
// Função para exibir alertas
function exibirAlertas($conn) {
    // Consultar os alertas da tabela
    $sql = "SELECT text, color FROM alert";
    $result = $conn->query($sql);

    // Verificar se há alertas
if ($result->num_rows > 0) {
    // Exibir cada alerta
    while($row = $result->fetch_assoc()) {
        $text = $row['text'];
        $cor = $row['color'];
        
        // Gerar HTML do alerta com o tamanho fixo e layout desejado
        echo '<div class="SystemAlert" style="background-color: '.$cor.'; text-align: center; color: '.$cor.'; border: 2px solid #000; font: normal 8pt/normal \'Comic Sans MS\', Verdana, sans-serif; padding: 1px; border-top: 1.9px black solid; width: 892px; height: 33px; display: flex; justify-content: center; align-items: center; position: relative;">';
        echo '  <div class="SystemAlertText" style="font: normal 8pt/normal \'Comic Sans MS\', Verdana, sans-serif; font-size: 16px; font-weight: bold; padding: 2px; color: white; text-align: center; flex-grow: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">';
        echo '    <div>' . $text . '</div>';
        echo '  </div>';
        echo '  <div class="Exclamation" style="background: url(/images/exclamation.png) no-repeat; height: 16px; width: 16px; position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"></div>';
        echo '</div>';
    }
} else {
    // Caso não haja alertas, não faz nada
    echo '';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RETROBLOX - Virtual Playworld</title>
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
.container-add {
            border: 2px solid black;
            padding: 20px;
            width: 300px;
            margin: 20px auto;
        }
        .container-add input, .container-add textarea {
            width: 77%;
            margin-bottom: 10px;
            padding: 10px;
            border: 2px dashed #ccc;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <div class="banner">
            <a href="home.php"><img src="/images/Logo.png" alt="Logo" class="logo"></a>
         <div class="welcome-text">
            Logged:<?php echo htmlspecialchars($username); ?> 
            |<a href="logout.php" class="logout-link">Logout</a>
        </div>
          <div class="user-info-container">
            <!-- Tix -->
            <div class="user-info">
                <span><?php echo $tix; ?> Tix</span>
            </div>

            <!-- Bux -->
            <div class="user-info">
                <span><?php echo $bux; ?> Bux</span>
            </div>        
        </div>
        </div>

        <!-- Barra de navegação -->
        <div class="navbar">
            <a href="home.php">Home</a>
            <a href="games.php">Games</a>
            <a href="catalog.php">Catalog</a>
            <a href="people.php">People</a>
            <a href="forum.php">Forum</a>
            <a href="#">Help <img src="/images/Feed.png" alt="Feed" class="feed-image"></a>
        </div>
        <?php
// Definindo as credenciais de conexão
$host = 'sql103.ezyro.com';
$user = 'ezyro_38183570'; 
$pass = ''; 
$dbname = 'ezyro_38183570_site'; 

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar se há erros de conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Chamar a função para exibir alertas
exibirAlertas($conn);

// Fechar a conexão
$conn->close();
?>
        <div class="container-add">
    <p>Welcome, <?php echo $username; ?>!</p>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="post_content">Post:</label>
        <textarea name="post_content" id="post_content" rows="5" required></textarea>

        <button type="submit">Adicionar Post</button>
    </form>
</div>