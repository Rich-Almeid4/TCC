<?php
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}

include('conecta.php');

// Pegando o usuário logado pelo ID da sessão
$usuario_id = $_SESSION['id'];
$sql = "SELECT * FROM usuario WHERE id = '$usuario_id' LIMIT 1";
$query = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="../css/artigo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <nav class="sidebar" id="sidebar">
    <div class="sidebar-content">
      <div class="user">
        <h1>Olá, <?php echo htmlspecialchars($usuario['nome']); ?></h1>
      </div>
      <ul class="side-items">
        <li class="section-title">Funções Admin</li>
        <li class="side-item"><a href="insert_especie.php"><i class="fa-solid fa-plus"></i>Adicionar Espécie</a></li>
        <li class="side-item"><a href="upload_artigo.php"><i class="fa-solid fa-file-circle-plus"></i>Adicionar Artigo</a></li>
        <li class="side-item"><a href="edit_especie.php"><i class="fa-solid fa-pen-to-square"></i>Gerenciar Espécies</a></li>
        <li class="side-item"><a href="edit_artigo.php"><i class="fa-solid fa-newspaper"></i>Gerenciar Artigos</a></li>
        <li class="side-item"><a href="users.php"><i class="fa-solid fa-users"></i>Gerenciar Usuários</a></li>

        <li class="section-title">Funções Comuns</li>
        <li class="side-item"><a href="especie.php"><i class="fa-solid fa-compass"></i>Catálogo de Espécies</a></li>
        <li class="side-item"><a href="artigos.php"><i class="fa-solid fa-flask"></i>Catálogo de Artigos</a></li>
        <li class="side-item"><a href="favoritos.php"><i class="fa-solid fa-star"></i>Favoritos</a></li>
        <li class="side-item"><a href="edit-adm.php?id=<?= $usuario['id']; ?>"><i class="fa-solid fa-user-gear"></i>Editar Perfil</a></li>
      </ul>
    </div>

    <form class="logout" action="sair.php" method="post">
      <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Sair</button>
    </form>
  </nav>

  <main class="content">
    <header>
      <h1 class="txt-topo">Bem-vindo ao Painel Administrativo</h1>
      <p class="info">Selecione uma das ações abaixo para começar.</p>
    </header>

    <div class="cards">
      <div class="card">
        <h3>Adicionar Espécie</h3>
        <p>Cadastre uma nova espécie no sistema.</p>
        <a class="button" href="insert_especie.php">Ir para página</a>
      </div>
      <div class="card">
        <h3>Adicionar Artigo</h3>
        <p>Publique um novo artigo informativo.</p>
        <a class="button" href="upload_artigo.php">Ir para página</a>
      </div>
      <div class="card">
        <h3>Gerenciar Espécies</h3>
        <p>Visualize, edite ou exclua espécies existentes.</p>
        <a class="button" href="edit_especie.php">Ir para página</a>
      </div>
      <div class="card">
        <h3>Gerenciar Artigos</h3>
        <p>Gerencie todos os artigos publicados.</p>
        <a class="button" href="edit_artigo.php">Ir para página</a>
      </div>
      <div class="card">
        <h3>Gerenciar Usuários</h3>
        <p>Controle usuários e permissões do sistema.</p>
        <a class="button" href="users.php">Ir para página</a>
      </div>
    </div>
  </main>

</body>
</html>
