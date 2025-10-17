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
  <link rel="stylesheet" href="../../css/config.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <?php include("mensagem.php"); ?>
  
  <nav class="sidebar" id="sidebar">
      <div class="sidebar-content">
        <div class="user">
          <img class="logo" src="assets/logo.svg" alt="Logo Arthropoda">
          <h1 class="name"><span class="item-name" id="title">Arthropoda</span></h1>
        </div>
        <ul class="side-items">
          <li class="side-item"><a href="homepage.html"><i class="fa-solid fa-house"></i><span class="item-name">Home</span></a></li>
          <li class="section-title">Pessoal</li>
          <li class="side-item"><a href="edit.php"><i class="fa-solid fa-user"></i><span class="item-name">Perfil</span></a></li>
          <li class="side-item"><a href="favoritos.php"><i class="fa-solid fa-star"></i><span class="item-name">Favoritos</span></a></li>
          <li class="side-item"><a href="#"><i class="fa-solid fa-clock-rotate-left"></i><span class="item-name">Histórico</span></a></li>
        
          <li class="section-title">Explore</li>
          <li class="side-item"><a href="#"><i class="fa-solid fa-compass"></i><span class="item-name">Descobrir espécie</span></a></li>
          <li class="side-item"><a href="catalogo.html"><i class="fa-solid fa-book"></i><span class="item-name">Espécies</span></a></li>
          <li class="side-item"><a href="artigos.html"><i class="fa-solid fa-flask"></i><span class="item-name">Artigos científicos</span></a></li>
        </ul>
        
      </div>
    </nav>

  <!-- Botão toggle unificado para desktop e mobile -->
  <button class="toggle-btn" id="toggle-btn">
    <i class="fa-solid fa-chevron-left"></i>
  </button>

  <main>
    <div class="top-bar">
      <!-- Removido o menu-toggle separado -->
      <div class="search-bar">
        <input type="text" id="search-input" placeholder="Pesquisar borboletas..." onkeyup="filtrarEspecies()">
      </div>
      <!-- Removido o botão de perfil -->
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

  <script src="../js/config.js"></script>
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

    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');
    const toggleIcon = toggleBtn.querySelector('i');
    const body = document.body;

    let isOpen = true;

    function toggleSidebar() {
      if (window.innerWidth <= 800) {
        // Comportamento mobile: abre/fecha completamente
        sidebar.classList.toggle('open');
      } else {
        // Comportamento desktop: minimiza/expande
        if (isOpen) {
          sidebar.style.width = '55px';
          body.style.paddingLeft = '55px';
          toggleBtn.style.left = '40px';
          toggleIcon.style.transform = 'rotate(0deg)';

          document.querySelectorAll('.item-name, .section-title').forEach(el => {
            el.style.opacity = '0';
            el.style.width = '0';
            el.style.visibility = 'hidden';
          });

          isOpen = false;
        } else {
          sidebar.style.width = '240px';
          body.style.paddingLeft = '240px';
          toggleBtn.style.left = '220px';
          toggleIcon.style.transform = 'rotate(180deg)';

          document.querySelectorAll('.item-name, .section-title').forEach(el => {
            el.style.opacity = '1';
            el.style.width = 'auto';
            el.style.visibility = 'visible';
          });

          isOpen = true;
        }
      }
    }

    toggleBtn.addEventListener('click', toggleSidebar);

    // Fecha sidebar ao clicar fora (apenas mobile)
    window.addEventListener('click', (e) => {
      if (window.innerWidth <= 800 && 
          !sidebar.contains(e.target) && 
          !toggleBtn.contains(e.target) &&
          sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
      }
    });

    // Back to top button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
      backToTop.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  </script>
</body>
</html>
