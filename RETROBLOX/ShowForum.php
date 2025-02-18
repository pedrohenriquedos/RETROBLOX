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


/* Estilo do container principal */
.forum {
    max-width: 800px;
    width: 100%;
    margin: 20px auto;
    font-family:'Comic Sans MS', cursive, sans-serif;
    background-color: #f4f7fc;
    border-radius: 8px;
    overflow: hidden;
}

/* Estilo do cabeçalho */
.header {
    background: url('http://retroblox.unaux.com/images/forum/forumHeaderBackground.gif') no-repeat center center;
    background-size: cover;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 2em;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

/* Estilo dos posts */
.posts {
    padding: 20px;
}

/* Estilo de cada postagem */
.post {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.post:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
}

/* Estilo do cabeçalho de cada postagem */
.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

/* Estilo do título da postagem */
.post-header .post-title {
    font-size: 1.5em;
    font-weight: 600;
    color: #2c3e50;
    margin-right: 15px;
}

/* Estilo do nome do usuário */
.post-header .post-user {
    font-size: 0.9em;
    color: #8e44ad;
    font-weight: bold;
}

/* Estilo da data */
.post-header .post-date {
    font-size: 0.8em;
    color: #7f8c8d;
}

/* Estilo do conteúdo do post */
.post-content {
    font-size: 1em;
    color: #34495e;
    line-height: 1.6;
}

/* Estilo da data de postagem */
.post-date {
    font-size: 0.75em;
    color: #95a5a6;
    text-align: right;
    margin-top: 15px;
}

/* Estilo do botão de resposta */
.reply-button {
    background-color: #8e44ad;
    color: white;
    padding: 8px 15px;
    font-size: 1em;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.reply-button:hover {
    background-color: #732d91;
}

/* Adicionando efeitos no hover para links */
a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #2980b9;
    text-decoration: underline;
}
.font-size: 1.5em;{
    font-weight: bold;
    color: #e74c3c; /* Cor vermelha para destaque */
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semi-transparente */
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block; /* Para o texto se alinhar melhor */
}
.post-title-container {
    display: flex;
    align-items: center; /* Centraliza a imagem e o título verticalmente */
}
.post-icon {
    width: 24px; /* Tamanho ajustável da imagem */
    height: 24px;
    margin-right: 10px; /* Espaço entre a imagem e o título */
    display: inline-block;
}
.new-post{
        margin-right: 683px; /* Espaço entre a imagem e o título */
        margin-top: 15px; /* Espaço entre a imagem e o título */
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
<div class="new-post"><A href="AddPost.php"><img src="/images/forum/newtopic.gif" align="left"></a></div>
         <div class="forum">
        <div class="header">
            <div class="thread">Thread</div>
        </div>
        
        <div class="posts">
            <?php
// Credenciais de conexão com o banco de dados
$servidor = 'sql103.ezyro.com';  
$data_base = 'ezyro_38183570_forum';    
$dbuser = 'ezyro_38183570';         
$dbpassword = '';         

// Conectando ao banco de dados
$conn = new mysqli($servidor, $dbuser, $dbpassword, $data_base);

// Verificando se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para pegar os dados das postagens (sem JOIN)
$sql = "SELECT id, title, post_content, post_date, user_id, user FROM forum_posts"; 

// Executando a consulta
$result = $conn->query($sql);

// Verificando se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Exibindo os resultados
    while($row = $result->fetch_assoc()) {
      echo "<div class='post'>";
echo "<div class='post-header'>";
echo "<div class='post-title-container'>";
echo "<img src='/images/forum/topic_notread.gif' alt='Ícone do tópico' class='post-icon'"; // A imagem agora fica ao lado do título
echo "<h2 class='post-title'><a href='ShowPost.php?id=" . $row["id"] . "'>" . $row["title"] . "</a></h2>";
echo "</div>"; // Fechando o container da imagem + título
echo "<div class='post-user'>";
echo "<a href='User.php?id=" . $row['user_id'] . "'>" . $row["user"] . "</a>";
echo "</div>";
echo "</div>";
echo "<div class='post-date'>" . $row["post_date"] . "</div>";
echo "</div>";
    }
} else {
    echo "<p>Não há postagens disponíveis.</p>";
}

// Fechando a conexão
$conn->close();
?>

        </div>
    </div>