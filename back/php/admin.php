<?php
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}

include('conecta.php');

// Pegando o usuário logado pelo nome (ou idealmente pelo ID, se armazenado na sessão)
$nome = $_SESSION['nome'];
$sql = "SELECT * FROM usuario WHERE nome = '$nome' LIMIT 2";
$query = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">  
  
  <meta charset="UTF-8">
  <title>Página Adm</title>
</head>
<body>
   <?php
   include("mensagem.php");
   ?>
  
<h3>OLÁ ADM</h3>
  <a href="edit.php?id=<?= $usuario['id']; ?>">Editar</a>

  <form action="sair.php" method="post">
      <button type="submit">Sair</button>
  </form>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
