<?php
// Check if the 'id' parameter is present in the URL
$error_id = isset($_GET['id']) ? $_GET['id'] : null;

// Define error messages based on the id
switch ($error_id) {
    case '1':
        $message = "Page not found. Please check the URL.";
        break;
    case '2':
        $message = "Access denied. You don't have permission to view this page.";
        break;
    case '3':
        $message = "Internal server error. Something went wrong, please try again later.";
        break;
    default:
        $message = "Unknown error. Please contact support.";
        break;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <div class="banner">
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
        <div class="Error" style="text-align: center;"><h1 style="color:red;"><?php echo $message; ?></h1><br><p>Sorry For Occurred :( <a href="index.php">Back</a></div>