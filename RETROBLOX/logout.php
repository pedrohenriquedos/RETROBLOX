<?php
session_start(); // Iniciar a sessão

session_unset();

session_destroy();

header("Location: index.php");
exit();
?>