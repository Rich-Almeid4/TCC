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
  <link rel="stylesheet" href="../../css/config.css"/>
  <meta charset="UTF-8">
  <title>Página Protegida</title>
  <script src="../js/config.js"></script>
</head>
<body>
   <?php
   include("mensagem.php");
   ?>
  <h2>Olá <?=$usuario['nome']?></h2>
  <img src="img/<?php echo $usuario['imagem']; ?>"><br>

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
  <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
        </tr>
<tbody>
    <tr>
        <td><p><?=($usuario['nome']); ?></p></td>
        <td><p><?=($usuario['email']); ?></p></td>
    </tr>
</tbody>
</table>
  <a href="edit.php?id=<?= $usuario['id']; ?>">Editar perfil</a><br>
    <a href="especie.php">catalogo - espécies</a><br>
    <a href="artigos.php">catalogo - artigos</a><br>
    <a href="favoritos.php">favoritos</a><br><br>

  <form action="sair.php" method="post">
      <button type="submit">Sair</button>
  </form>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
