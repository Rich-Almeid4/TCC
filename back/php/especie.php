<?php
session_start();

if (!isset($_SESSION['nome'])) {
    $_SESSION['mensagem'] = "Faça login para acessar o catálogo!";
    header("Location: login.php");
    exit;
}

include('conecta.php');

$usuario_id = $_SESSION['id'];

$sql_especies = "SELECT * FROM especie ORDER BY nome_comum ASC";
$query_especies = mysqli_query($conn, $sql_especies);

$sql_favoritos = "SELECT id_especie FROM favorito WHERE id_usuario = '$usuario_id'";
$query_favoritos = mysqli_query($conn, $sql_favoritos);
$favoritos = [];
while ($fav = mysqli_fetch_assoc($query_favoritos)) {
    $favoritos[] = $fav['id_especie'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Espécies - Arthropoda</title>
  <link rel="stylesheet" href="../css/especie.css">
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <?php include("mensagem.php"); ?>
  
  <nav class="sidebar">
    <div class="sidebar-content">
      <div class="sidebar-header">
        <div class="logo-circle">
          <i class="fa-solid fa-bug" style="font-size: 1.5rem; color: #7658d6;"></i>
        </div>
        <h1 class="sidebar-title">Arthropoda</h1>
      </div>

      <div class="menu-section">
        <h2 class="section-header">PESSOAL</h2>
        <ul class="side-items">
          <li class="side-item">
            <a href="index.php"><i class="fa-solid fa-user"></i><span>Perfil</span></a>
          </li>
          <li class="side-item">
            <a href="favoritos.php"><i class="fa-solid fa-star"></i><span>Favoritos</span></a>
          </li>
          <li class="side-item">
            <a href="#"><i class="fa-solid fa-clock-rotate-left"></i><span>Histórico</span></a>
          </li>
          <li class="side-item">
            <a href="#"><i class="fa-solid fa-bell"></i><span>Notificações</span></a>
          </li>
          <li class="side-item">
            <a href="edit.php"><i class="fa-solid fa-gear"></i><span>Configurações</span></a>
          </li>
        </ul>
      </div>

      <div class="menu-section">
        <h2 class="section-header">EXPLORE</h2>
        <ul class="side-items">
          <li class="side-item">
            <a href="#"><i class="fa-solid fa-compass"></i><span>Descobrir espécie</span></a>
          </li>
          <li class="side-item active">
            <a href="especie.php"><i class="fa-solid fa-book"></i><span>Espécies</span></a>
          </li>
          <li class="side-item">
            <a href="#"><i class="fa-solid fa-flask"></i><span>Artigos científicos</span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <div class="top-bar">
      <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>
      <div class="search-bar">
        <input type="text" id="search-input" placeholder="Pesquisar borboletas..." onkeyup="filtrarEspecies()">
      </div>
      <button class="profile-btn" onclick="window.location.href='index.php'">
        <i class="fa-solid fa-circle-user"></i>
      </button>
    </div>

    <div class="cards-container" id="cards-container">
      <?php if (mysqli_num_rows($query_especies) > 0): ?>
        <?php while ($especie = mysqli_fetch_assoc($query_especies)): ?>
          <?php $is_favorited = in_array($especie['id'], $favoritos); ?>
          <div class="card" data-nome="<?= strtolower(htmlspecialchars($especie['nome_comum'])) ?>">
            <?php if ($especie['imagem']): ?>
              <img src="img/<?= htmlspecialchars($especie['imagem']) ?>" alt="<?= htmlspecialchars($especie['nome_comum']) ?>">
            <?php else: ?>
              <img src="img/placeholder.jpg" alt="Sem imagem">
            <?php endif; ?>
            
            <h3><?= htmlspecialchars($especie['nome_comum']) ?></h3>
            <p><?= htmlspecialchars(substr($especie['descricao'], 0, 100)) ?>...</p>
            
            <div class="card-actions">
              <button onclick="window.location.href='especie_detalhe.php?id=<?= $especie['id'] ?>'">
                Saiba mais
              </button>
              <button class="favorite-btn <?= $is_favorited ? 'favorited' : '' ?>" 
                      onclick="toggleFavorite(<?= $especie['id'] ?>, this)"
                      title="<?= $is_favorited ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                <i class="fa-solid fa-heart"></i>
              </button>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="color: white; font-size: 1.2rem;">Nenhuma espécie cadastrada ainda.</p>
      <?php endif; ?>
    </div>
  </main>

  <button class="back-to-top" id="back-to-top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="../js/theme-manager.js"></script>
  <script>
    function filtrarEspecies() {
      const input = document.getElementById('search-input');
      const filter = input.value.toLowerCase();
      const cards = document.querySelectorAll('.card');
      
      cards.forEach(card => {
        const nome = card.getAttribute('data-nome');
        if (nome.includes(filter)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    }

    function toggleFavorite(especieId, button) {
      const isFavorited = button.classList.contains('favorited');
      const action = isFavorited ? 'remover_favorito' : 'adicionar_favorito';
      
      fetch('acoes.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `acao=${action}&id_especie=${especieId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          button.classList.toggle('favorited');
          button.title = button.classList.contains('favorited') ? 
            'Remover dos favoritos' : 'Adicionar aos favoritos';
        } else {
          alert(data.message || 'Erro ao processar favorito');
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar favorito');
      });
    }

    // Back to top button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
      backToTop.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });

    window.addEventListener('click', (e) => {
      if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  </script>
</body>
</html>
