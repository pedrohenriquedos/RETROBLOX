<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <style>
        /* Reset básico */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #6e99c9; /* Cor de fundo */
        }

        /* Container de manutenção */
        .maintenance-container {
            background-color: white;
            padding: 50px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            width: 700px; /* Aumentado o tamanho */
            max-width: 100%;
            height: 600px; /* Aumentado o tamanho */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Imagem de manutenção */
        .maintenance-container img {
            width: 100%; /* Ajusta o tamanho da imagem conforme o container */
            max-width: 500px; /* Limita a largura máxima da imagem */
            height: auto; /* Mantém a proporção da imagem */
        }

        /* Texto de manutenção */
        .maintenance-container p {
            font-size: 20px;
            color: #333;
            margin: 20px 0;
            font-weight: bold;
        }

        /* Caixa de entrada */
        .input-container {
            margin: 20px 0;
        }

        .input-container input {
            padding: 10px;
            width: 80%;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Container para os botões "RETROBLOX" */
        .buttons-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .buttons-container button {
            background-color: #6e99c9;
            border: none;
            color: white;
            font-size: 20px; /* Tamanho maior da fonte */
            font-weight: bold;
            padding: 10px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buttons-container button:hover {
            background-color: #5a87b8;
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .maintenance-container {
                width: 90%;
                padding: 20px;
            }

            .maintenance-container img {
                max-width: 300px;
            }

            .buttons-container button {
                font-size: 16px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <!-- Imagem de manutenção -->
        <img src="/images/maintenance/Maintenance.jpg" alt="Maintenance">

        <!-- Texto de manutenção -->
        <p>The site is currently offline for maintenance and upgrades. Please check back soon!</p>

        <!-- Caixa de entrada -->
        <div class="input-container">
            <input type="text" placeholder="">
        </div>

        <!-- Botões RETROBLOX -->
        <div class="buttons-container">
            <button>R</button>
            <button>E</button>
            <button>T</button>
            <button>R</button>
            <button>O</button>
            <button>B</button>
            <button>L</button>
            <button>O</button>
            <button>X</button>
        </div>
    </div>
    <script>
    // Função para redirecionar para a página index.php após 60 segundos
    setTimeout(function() {
        window.location.href = "index.php";
    }, 60000);  // 60000 ms = 60 segundos
</script>

</body>
</html>