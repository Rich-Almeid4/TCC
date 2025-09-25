<?php
session_start();
require 'conecta.php';

if (!isset($_SESSION['nome']) || $_SESSION['nivel'] != 2) {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3>ID inválido!</h3>";
    exit;
}

$usuario_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM `usuario` WHERE id='$usuario_id'";
$query = mysqli_query($conn, $sql);

if (!$query || mysqli_num_rows($query) == 0) {
    echo "<h5>Usuário não encontrado!</h5>";
    exit;
}

$usuario = mysqli_fetch_array($query);
?>
<!doctype html>
<html lang="pt-br">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">  
  
  <head>
    <meta charset="utf-8">
    <title>Editar Perfil</title>
  </head>
  <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <form action="acoes.php" method="POST">
        <input type="hidden" name="usuario_id" value="<?= $usuario['id']; ?>">
        
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $usuario['nome']; ?>">

        <label>Senha:</label>
        <input type="text" name="senha" value="<?= $usuario['senha']; ?>">

        <label>Email:</label>
        <input type="text" name="email" value="<?= $usuario['email']; ?>">

        <button type="submit" name="update">Salvar</button>
    </form>
  </body>
</html>