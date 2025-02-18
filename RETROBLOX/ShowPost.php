<?php
// Inicie a sessão para capturar o nome do jogador e o ID
session_start();

// Verifique se o jogador está logado, caso contrário, redirecione para a página de login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_n = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
?>
<?php
$server = 'sql103.ezyro.com';  // Novo nome para a variável de host
$database = 'ezyro_38183570_users';    // Novo nome para o nome do banco de dados
$db_user = 'ezyro_38183570';           // Novo nome para o usuário
$db_password = '';

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
$sql = "SELECT tix, bux, avatar_img FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($tix, $bux, $avatar_img);
$stmt->fetch();
$stmt->close();
// Fechar a conexão
$conn->close();
?>
<?php
// Configuração do banco de dados
$servidor = 'sql103.ezyro.com';
$data_base = 'ezyro_38183570_forum';
$dbuser = 'ezyro_38183570';
$dbpassword = '';

// Conectando ao banco de dados
$conn = new mysqli($servidor, $dbuser, $dbpassword, $data_base);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Pegar o ID da URL e garantir que seja um número válido
$id_post = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar se o ID é válido
if ($id_post <= 0) {
    header("Location: error.php?id=1");
    exit();
}

// Consultar a tabela forum_posts para pegar os dados do post
$sql_post = "SELECT title, post_content, post_date, user_id, user FROM forum_posts WHERE id = ?";
$stmt = $conn->prepare($sql_post);
$stmt->bind_param('i', $id_post);
$stmt->execute();
$post_result = $stmt->get_result();

// Verificar se o post foi encontrado
if ($post_result->num_rows == 0) {
    header("Location: error.php?id=1");
    exit();
}

$post = $post_result->fetch_assoc();

// Consultar a tabela forum_replies para pegar as respostas
$sql_replies = "SELECT response_text, response_date, user_id, user_reply FROM forum_replies WHERE forum_id = ?";
$stmt_replies = $conn->prepare($sql_replies);
$stmt_replies->bind_param('i', $id_post);
$stmt_replies->execute();
$replies_result = $stmt_replies->get_result();

// Fechar a conexão
$stmt->close();
$stmt_replies->close();
$conn->close();
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
           justify-content: flex-start;
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
            transform: translate(-50%, -50%);
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

        /* Barra de pesquisa para jogadores */
        .search-container {
            width: 900px;
            margin-top: 20px;
            text-align: center;
        }

        .search-container input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            width: 60%;
        }

        /* Container de usuários */
        .users-container {
            width: 900px;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid black;
            border-radius: 5px;
            background-color: #fff;
            text-align: center; /* Centraliza o conteúdo */
        }

        /* Título comum de Avatar & Username */
        .users-container .header {
            text-align: center;
            background-color: #6e99c9;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        /* Cada jogador (um por vez) */
        .user-card {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #000;
            text-align: center;
            margin: 10px 0;
        }

        .user-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px; /* Deixa uma margem entre o avatar e as informações */
        }

        .user-card .user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .user-card .user-info a {
            text-decoration: none;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .user-card p {
            font-size: 14px;
            color: #666;
            margin: 0; /* Remove margem extra */
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
        .welcome-text {
        color: white; /* Cor branca para o texto de welcome */
        position: absolute;
        left: 20px; /* Alinha o texto à esquerda */
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
    }
.post-container {
    width: 80%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.post-header img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.post-header .user-name {
    font-weight: bold;
    font-size: 18px;
    color: #333;
}

.post-header .post-date {
    margin-left: auto;
    font-size: 14px;
    color: #999;
}

.post-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
}

.post-content {
    font-size: 16px;
    color: #444;
    margin-bottom: 30px;
}
/* Seção de Respostas */
.reply-section {
    background-color: #fff;
    border-radius: 8px;
    margin: 20px auto;
    padding: 20px;
    width: 90%;
    max-width: 900px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.reply {
    display: flex;
    margin-bottom: 20px;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.reply:last-child {
    border-bottom: none;
}

/* Avatar do usuário */
.reply-img {
    width: 100px;
    
}

/* Conteúdo da resposta */
.reply-content {
    flex: 1;
}

/* Nome do usuário */
.user-name {
    font-weight: bold;
    font-size: 1.1em;
    color: #333;
}

/* Texto da resposta */
.response-text {
    margin-top: 8px;
    font-size: 1em;
    color: #555;
    line-height: 1.5;
}

/* Data da resposta */
.reply-date {
    font-size: 0.9em;
    color: #888;
    margin-top: 10px;
}

/* Responsividade */
@media (max-width: 768px) {
    .reply-section {
        padding: 15px;
    }

    .reply {
        flex-direction: column;
        align-items: flex-start;
    }

    .reply-img {
        margin-bottom: 10px;
    }

    .reply-date {
        margin-top: 10px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <div class="banner">
            <a href="home.php"><img src="/images/Logo.png" alt="Logo" class="logo"></a>
            <div class="welcome-text">
            Logged:<?php echo htmlspecialchars($user_n); ?>
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
        <br>
  <div class="post-container">
        <!-- Cabeçalho do Post -->
        <div class="post-header">
            <img src="/images/characters/default.png" alt="<?php echo htmlspecialchars($post['user']); ?>'s Avatar">
            <div class="user-name"><a href="User.php?id=<?php echo $post['user_id']; ?>"><?php echo htmlspecialchars($post['user']); ?></a></div>
            <div class="post-date"><?php echo date('d/m/Y - H:i', strtotime($post['post_date'])); ?></div>
        </div>

        <!-- Título e Conteúdo do Post -->
        <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
        <div class="post-content">
           <?php echo nl2br(htmlspecialchars($post['post_content'])); ?>
        </div>

        <!-- Imagem para responder (ainda sem imagem) -->
        <a href="AddReply.php?id=<?php echo $id_post; ?>"><img src="/images/forum/newpost.gif" alt="reply" class="reply-img"></a>
       <?php if ($replies_result->num_rows > 0): ?>
    <?php while ($reply = $replies_result->fetch_assoc()) : ?>
        <div class="reply">
            <img src="/images/characters/default.png" alt="<?php echo htmlspecialchars($post['user']); ?>'s Avatar" width="50">
            <div class="reply-content">
                <div class="user-name"><A href="User.php?id=<?php echo $reply['user_id']; ?>"><?php echo htmlspecialchars($reply['user_reply']); ?></a></div>
                <div class="response-text">
                    <?php echo nl2br(htmlspecialchars($reply['response_text'])); ?>
                </div>
            </div>
            <div class="reply-date"><?php echo date('d/m/Y - H:i', strtotime($reply['response_date'])); ?></div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
<?php endif; ?>

            </div>
        </body>
        </html>
        <?php
// Fechar a conexão com o banco de dados
$conn->close();
?>