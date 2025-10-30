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
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Adicionar Espécie</title>
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
        <li class="side-item"><a href="insert_especie.php"><i class="fa-solid fa-plus"></i><span>Adicionar Espécie</span></a></li>
        <li class="side-item"><a href="upload_artigo.php"><i class="fa-solid fa-file-circle-plus"></i><span>Adicionar Artigo</span></a></li>
        <li class="side-item"><a href="edit_especie.php"><i class="fa-solid fa-pen-to-square"></i><span>Gerenciar Espécies</span></a></li>
        <li class="side-item"><a href="edit_artigo.php"><i class="fa-solid fa-newspaper"></i><span>Gerenciar Artigos</span></a></li>
        <li class="side-item"><a href="users.php"><i class="fa-solid fa-users"></i><span>Gerenciar Usuários</span></a></li>

        <li class="section-title">Funções Comuns</li>
        <li class="side-item"><a href="especie.php"><i class="fa-solid fa-compass"></i><span>Catálogo de Espécies</span></a></li>
        <li class="side-item"><a href="artigos.php"><i class="fa-solid fa-flask"></i><span>Catálogo de Artigos</span></a></li>
        <li class="side-item"><a href="favoritos.php"><i class="fa-solid fa-star"></i><span>Favoritos</span></a></li>
        <li class="side-item"><a href="edit-adm.php?id=<?= $usuario['id']; ?>"><i class="fa-solid fa-user-gear"></i><span>Editar Perfil</span></a></li>
      </ul>
    </div>

    <form class="logout" action="sair.php" method="post">
      <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Sair</button>
    </form>
  </nav>

  <main class="container-form">
    <h2 class="txt-topo"><i class="fa-solid fa-leaf"></i> Adicionar Nova Espécie</h2>
    <div class="progress-bar">
      <div class="progress" id="progress"></div>
    </div>

    <?php include("mensagem.php"); ?>

    <form action="acoes.php" method="POST" enctype="multipart/form-data" id="form-especie">
      <input type="hidden" name="acao" value="cadastrar">

      <!-- Seção: Identificação -->
      <div class="form-section">
        <h3><i class="fa-solid fa-tag"></i> Identificação</h3>
        <div class="campo-duplo">
          <div>
            <label><i class="fa-solid fa-font"></i> Nome Comum</label>
            <input type="text" name="nome_comum" placeholder="Ex: Papagaio-do-mar" required>
          </div>
          <div>
            <label><i class="fa-solid fa-flask"></i> Nome Científico</label>
            <input type="text" name="nome_cientifico" placeholder="Ex: Ara ararauna" required>
          </div>
        </div>
        <div class="campo-duplo">
          <div>
            <label><i class="fa-solid fa-tree"></i> Família</label>
            <input type="text" name="familia" placeholder="Ex: Psittacidae" required>
          </div>
          <div>
            <label><i class="fa-solid fa-list"></i> Ordem</label>
            <input type="text" name="ordem" placeholder="Ex: Psittaciformes" required>
          </div>
        </div>
      </div>

      <!-- Seção: Descrição e Comportamento -->
      <div class="form-section">
        <h3><i class="fa-solid fa-info-circle"></i> Descrição e Comportamento</h3>
        <div>
          <label><i class="fa-solid fa-align-left"></i> Descrição</label>
          <textarea name="descricao" rows="3" placeholder="Descreva a espécie em detalhes..." required></textarea>
        </div>
        <div>
          <label><i class="fa-solid fa-brain"></i> Comportamento</label>
          <textarea name="comportamento" rows="3" placeholder="Ex: Vive em bandos, migra sazonalmente..." required></textarea>
        </div>
      </div>

      <!-- Seção: Características -->
      <div class="form-section">
        <h3><i class="fa-solid fa-cogs"></i> Características</h3>
        <div class="campo-triplo">
          <div>
            <label><i class="fa-solid fa-home"></i> Habitat</label>
            <input type="text" name="habitat" placeholder="Ex: Florestas tropicais" required>
          </div>
          <div>
            <label><i class="fa-solid fa-globe"></i> Distribuição Geográfica</label>
            <input type="text" name="distribuicao_geografica" placeholder="Ex: América do Sul" required>
          </div>
          <div>
            <label><i class="fa-solid fa-utensils"></i> Alimentação</label>
            <input type="text" name="alimentacao" placeholder="Ex: Frutas e sementes" required>
          </div>
        </div>
        <div class="campo-triplo">
          <div>
            <label><i class="fa-solid fa-ruler"></i> Envergadura das Asas</label>
            <input type="text" name="envergadura_alas" placeholder="Ex: 1.5m" required>
          </div>
          <div>
            <label><i class="fa-solid fa-clock"></i> Ciclo de Vida</label>
            <input type="text" name="ciclo_vida" placeholder="Ex: 20-30 anos" required>
          </div>
          <div>
            <label><i class="fa-solid fa-exclamation-triangle"></i> Status de Conservação</label>
            <input type="text" name="status_conservacao" placeholder="Ex: Vulnerável" required>
          </div>
        </div>
      </div>

      <!-- Seção: Imagem -->
      <div class="form-section">
        <h3><i class="fa-solid fa-image"></i> Imagem</h3>
        <div>
          <label><i class="fa-solid fa-upload"></i> Selecione uma Imagem</label>
          <input type="file" name="imagem" accept="image/*" required onchange="previewImage(event)">
        </div>
        <div class="image-preview" id="image-preview">
          <img id="preview-img" src="" alt="Preview da Imagem" style="display: none;">
        </div>
      </div>

      <div class="botoes-form">
        <button type="submit" class="botao-enviar"><i class="fa-solid fa-save"></i> Salvar Espécie</button>
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

    // Adicionar listeners para atualizar progresso
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
