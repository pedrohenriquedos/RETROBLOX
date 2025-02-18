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
// Conexão com o banco de dados
$host = 'sql103.ezyro.com';
$dbname = 'ezyro_38183570_users';
$user = 'ezyro_38183570'; // ajuste se necessário
$password = ''; // ajuste se necessário

$conn = new mysqli($host, $user, $password, $dbname);

// Verifica se há erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obtém o ID do usuário a partir da URL
$userId = $_GET['id']; 

// Consulta para obter o nome de usuário, bio e avatar com base no ID
$stmt = $conn->prepare("SELECT username, bio, avatar_img, accept_request FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o ID existe no banco
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usernamee = $row['username']; // Guarda o nome de usuário
    $bio = $row['bio']; // Guarda a bio do usuário
    $avatar_img = $row['avatar_img']; // Guarda o avatar do usuário
    $accept = $row['accept_request']; // Guarda o avatar do usuário
} else {
    // Se o ID não existir, redireciona para a página de erro
    header("Location: dontexist.php");
    exit();
}
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
    .badges-container {
        width: 600px; /* Diminui o tamanho do container */
        background-color: #eeeeee;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
        border: 1px solid grey;
    }

    .badges-container h2 {
        color: black; /* Cor preta para o título 'Your Items' */
        margin: 0;
        font-size: 20px;
        margin-bottom: 10px;
    }

    .badges-container p {
        font-size: 16px;
        color: #555555;
    }

    /* Container do usuário */
.user-container {
    width: 600px; /* Tamanho fixo do container */
    background-color: #6e99c9; /* Cor de fundo */
    border: 2px solid black;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start; /* Alinhamento dos itens no começo */
    border-radius: 5px;
    text-align: center;
    box-sizing: border-box; /* Inclui padding e borda no cálculo do tamanho */
    max-width: 100%; /* Limita a largura máxima ao tamanho da tela */
    height: auto; /* Altura automática para não esticar */
}
    .user-container h1 {
        margin: 0;
        font-size: 24px;
        color: black;
    }

    .user-container p {
        font-size: 14px;
        margin-top: 10px;
    }

    .user-inner {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        width: 100%;
    }

    .user-inner img {
        width: 110px;
        height: auto;
        margin-right: 20px;
    }

    .user-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .user-actions a {
        color: white;
        text-decoration: none;
        margin-bottom: 5px;
    }

    /* Container de jogos */
    .games-container {
        width: 600px; /* Tamanho do container dos jogos */
        background-color: #eeeeee;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid grey;
        text-align: center;
    }

    .games-container h3 {
        font-size: 16px;
        color: #555555;
    }

    /* Wrapper para manter a posição dos containers */
    .content-wrapper {
        display: flex;
        justify-content: space-between; /* Coloca os containers lado a lado */
        gap: 20px;
        width: 100%;
        max-width: 900px; /* Limita a largura */
    }
    /* Estilo do container do jogo */
.gamee-container {
    text-align: center;
    width: 300px;
    margin: 20px auto;
    border: 1px solid #ccc;
    padding: 15px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Estilo da imagem do jogo */
.gamee-image {
    width: 70%;
    height: auto;
    border-radius: 8px;
        max-width: 300px; /* Limita o tamanho máximo da imagem */
}

/* Estilo do título e informações */
.gamee-container h2 {
    font-size: 18px;
    margin: 10px 0;
    color: #333;
}

.gamee-container p {
    font-size: 14px;
    color: #666;
}
/* Estilo do botão */
.play {
    background-color: #808080; /* Cor de fundo cinza */
    color: white; /* Cor do texto */
    border: 2px solid #808080; /* Cor da borda cinza */
    padding: 8px 10px; /* Espaçamento interno do botão */
    font-size: 10px; /* Tamanho da fonte */
    cursor: pointer; /* Cursor de ponteiro para indicar que é clicável */
    border-radius: 5px; /* Bordas arredondadas */
    transition: background-color 0.3s ease, border-color 0.3s ease; /* Transição suave para a cor de fundo e borda */
}

/* Efeito ao passar o mouse */
.play:hover {
    background-color: #007bff; /* Cor de fundo azul */
    border-color: #007bff; /* Borda azul */
}

    </style>
</head>
<body>
    <!-- Container principal -->
    <div class="container">
        <!-- Banner -->
        <div class="banner">
        <div class="welcome-text">
            Logged:<?php echo htmlspecialchars($user_n); ?>    |<a href="logout.php" class="logout-link">Logout</a>
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
            <a href="home.php"><img src="/images/Logo.png" alt="Logo" class="logo"></a>
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
       <!-- Conteúdo principal -->
        <div class="content-wrapper">
            <!-- User Container -->
            <div class="user-container">
                <!-- Nome do Jogador -->
                <h1><?php echo htmlspecialchars($usernamee); ?></h1>
                
                <!-- Link para o perfil -->
                <p><a href="http://retroblox.unaux.com/User.php?id=<?php echo $userId; ?>">http://retroblox.unaux.com/User.php?id=<?php echo $userId; ?></a></p>
                
                <!-- Imagem do Jogador e Bio -->
                <div class="user-inner" style="display: flex; align-items: center;">
                    <img src="<?php echo htmlspecialchars($avatar_img); ?>" alt="Player Avatar" title="<?php echo htmlspecialchars($usernamee); ?>" style="width: 110px; height: auto; margin-right: 20px;">
                    <p style="font-size: 14px; color: #555;"><?php echo nl2br(htmlspecialchars($bio)); ?></p> <!-- Exibe a bio -->
                </div>

                <!-- Ações do usuário -->
                <div class="user-actions">
                <?php if ($accept == "yes"){
                   echo '<a href="#">Send Friend Request</a>';
                }
                else{

                }?>
                <?php
// Verifique se $usernamee não é igual a $user_n
if ($usernamee != $user_n) {
    echo '<a href="SendMsg.php?user=' . htmlspecialchars($usernamee) . '">Send Private Message</a>';
}
else{
    echo '<a href="javascript:Cant()">Send Private Message</a>';
}
?>
                   <a href="#"">Report Abuse</a>
                </div>
            </div>

            <!-- Games Container -->
            <div class="games-container">
            <span><?php echo htmlspecialchars($usernamee); ?>'Games</span>
           <?php
// Configuração do servidor de banco de dados
$server = 'sql103.ezyro.com';
$database = 'ezyro_38183570_games';
$db_user = 'ezyro_38183570';
$db_password = '';

// Conectar ao banco de dados 'games'
$conn_games = new mysqli($server, $db_user, $db_password, $database);

// Verificar se a conexão foi bem-sucedida
if ($conn_games->connect_error) {
    die("Conexão falhou: " . $conn_games->connect_error);
}

// Pegar o 'id' diretamente da URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Verificar se o ID foi fornecido na URL
if ($id == 0) {
    echo "ID não fornecido.";
    exit;
}

// Conectar ao banco de dados 'users' (se necessário)
$conn_users = new mysqli($server, $db_user, $db_password, 'ezyro_38183570_users');

// Verificar se a conexão foi bem-sucedida
if ($conn_users->connect_error) {
    die("Conexão falhou: " . $conn_users->connect_error);
}

// Consultar os dados do jogo na tabela 'game' baseado no creator_id
$sql = "SELECT id, creator_name, creator_id, name, public 
        FROM game 
        WHERE creator_id = ?";

$stmt = $conn_games->prepare($sql);
$stmt->bind_param("i", $id);  // Usando o 'id' diretamente da URL
$stmt->execute();
$result = $stmt->get_result();

// Verificar se encontrou algum jogo
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Exibir os dados no formato desejado
        echo '<div class="gamee-container">';
        echo '<img src="/images/games/Default.png" alt="Imagem do jogo" class="gamee-image">';
        echo '<h2><a href="ShowGame.php?id=' . $row['id'] . '">'. htmlspecialchars($row['name']) .'</a></h2>';
        echo '<p>Creator: <a href="User.php?id=' . htmlspecialchars($row['creator_id']) . '">' . htmlspecialchars($row['creator_name']) . '</a></p>';
if ($row['public'] === 'yes') {
    echo '<button onclick="play()" class="play">Visit Online</button>';
}
else{
    echo '<p>Private Game</p>';
}

        echo '</div>';
    }
} else {
    echo "<br>This User Dont Have A Game.";
}

// Fechar conexões
$stmt->close();
$conn_games->close();
$conn_users->close();
?>

            </div>
        </div>

        <!-- Items Container -->
        <div class="badges-container">
            <h2><?php echo $usernamee; ?>'s Badges</h2>
            <p>Badges: We are working on it...</p>
        </div>
    </div>
    <script>
    function Cant(){
        alert("You Can Not Send Private Message To You.");
    }
    </script>
</body>
</html>