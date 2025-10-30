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
    <title>Upload de Artigo</title>
    <!-- Updated CSS path to new organized structure -->
    <link rel="stylesheet" href="../css/add_especie.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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

  <main class="container-form">
    <h2 class="txt-topo"><i class="fa-solid fa-file-lines"></i> Adicionar Novo Artigo</h2>
    <div class="progress-bar">
      <div class="progress" id="progress"></div>
    </div>

    <?php include("mensagem.php"); ?>

    <form action="acoes.php" method="POST" enctype="multipart/form-data" id="form-especie">
      <!-- Removed conflicting hidden input that was causing articles to be inserted as species -->
      
      <!-- Seção: Informações -->
      <div class="form-section">
        <h3><i class="fa-solid fa-circle-info"></i> Informações</h3>

        <div>
          <label><i class="fa-solid fa-heading"></i> Título</label>
          <textarea name="titulo" rows="1" placeholder="O título do artigo..." required></textarea>
        </div>

        <div>
          <label><i class="fa-solid fa-user-pen"></i> Autor</label>
          <textarea name="autor" rows="1" placeholder="O nome do autor..." required></textarea>
        </div>

        <div>
          <label><i class="fa-solid fa-align-left"></i> Descrição</label>
          <textarea name="descricao" rows="3" placeholder="Uma introdução sobre o conteúdo do artigo..." required></textarea>
        </div>
      </div>

      <!-- Seção: PDF -->
      <div class="form-section">
        <h3><i class="fa-solid fa-file-pdf"></i> Documento PDF</h3>
        <div>
          <label><i class="fa-solid fa-upload"></i> Selecione o arquivo PDF</label>
          <input type="file" name="arquivo" accept="application/pdf" required>
        </div>
      </div>

      <!-- Seção: Imagem -->
      <div class="form-section">
        <h3><i class="fa-solid fa-image"></i> Imagem de capa</h3>
        <div>
          <label><i class="fa-solid fa-photo-film"></i> Selecione uma Imagem</label>
          <input type="file" name="capa" accept="image/*" required onchange="previewImage(event)">
        </div>
        <div class="image-preview" id="image-preview">
          <img id="preview-img" src="/placeholder.svg" alt="Preview da Imagem" style="display: none;">
        </div>
      </div>

      <div class="botoes-form">
        <button type="submit" name="upload_artigo" class="botao-enviar"><i class="fa-solid fa-floppy-disk"></i> Salvar Artigo</button>
        <button type="button" class="limpar-btn" onclick="limparFormulario()"><i class="fa-solid fa-eraser"></i> Limpar</button>
        <a href="admin.php" class="voltar-btn"><i class="fa-solid fa-arrow-left"></i> Cancelar</a>
      </div>
    </form>
  </main>

  <script>
    // Preview da imagem
    function previewImage(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('preview-img');
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none';
      }
    }

    // Limpar formulário
    function limparFormulario() {
      document.getElementById('form-especie').reset();
      document.getElementById('preview-img').style.display = 'none';
      updateProgress();
    }

    // Barra de progresso simulada
    function updateProgress() {
      const inputs = document.querySelectorAll('input[required], textarea[required]');
      let filled = 0;
      inputs.forEach(input => {
        if (input.value.trim() !== '') filled++;
      });
      const progress = (filled / inputs.length) * 100;
      document.getElementById('progress').style.width = progress + '%';
    }

    // Atualizar progresso em tempo real
    document.addEventListener('DOMContentLoaded', () => {
      const inputs = document.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
      });
      updateProgress();
    });
  </script>
</body>
</html>
