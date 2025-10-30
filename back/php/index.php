<?php
session_start();

// Proteção da página
if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "comum") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
include('conecta.php');



// Pegando o usuário logado pelo nome (ou idealmente pelo ID, se armazenado na sessão)
$usuario_id = $_SESSION['id'];
$sql = "SELECT * FROM usuario WHERE id = '$usuario_id' LIMIT 1";
$query = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($query);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">  
    <link rel="stylesheet" href="../css/artigo2.css">
    <link rel="stylesheet" href="../../css/config.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <meta charset="UTF-8">
  <title>Página Protegida</title>
  <script src="../js/config.js"></script>
</head>
<body>
   <?php
   include("mensagem.php");
   ?>
  <h2>Olá <?=$usuario['nome']?></h2>

  <?php
                    if (isset($_GET['id'])) {
                    $usuario_id = mysqli_real_escape_string($conn, $_GET['id']);
                    $sql = "SELECT * FROM `usuario` WHERE id='$usuario_id'";
                    $query = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($query) > 0) {
                    $usuario = mysqli_fetch_array($query);
                    ?>
                                    
                      
                      <?php
                    }else{
                        echo "<h5>Produto não encontrado!</h5>";
                    }
                }
                        ?>
  <table>



    <nav class="sidebar" id="sidebar">
      <div class="sidebar-content">
        <div class="user">
          <img class="logo" src="img/logo.svg" alt="Logo Arthropoda">
          <h1 class="name"><span class="item-name" id="title">Arthropoda</span></h1>
        </div>
        <ul class="side-items">
          <li class="side-item"></liclass><a href="homepage.html"><i class="fa-solid fa-house"></i><span class="item-name">Home</span></a></li>
          <li class="section-title">Pessoal</li>
          <li class="side-item"><a href="edit.php?id=<?= $usuario['id']; ?>"><i class="fa-solid fa-user"></i><span class="item-name">Perfil</span></a></li>
          <li class="side-item"><a href="favoritos.php"><i class="fa-solid fa-star"></i><span class="item-name">Favoritos</span></a></li>
        
          <li class="section-title">Explore</li>
          <li class="side-item"><a href="#"><i class="fa-solid fa-compass"></i><span class="item-name">Curiosidades</span></a></li>
          <li class="side-item"><a href="especie.php"><i class="fa-solid fa-book"></i><span class="item-name">Espécies</span></a></li>
          <li class="side-item"><a href="artigos.php"><i class="fa-solid fa-flask"></i><span class="item-name">Artigos científicos</span></a></li>
        </ul>
        
      </div>
    </nav>

</body>
</html>
