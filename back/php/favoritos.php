<?php
session_start();
include('conecta.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

$sql = "
SELECT f.id AS favorito_id, e.* 
FROM favorito f
JOIN especie e ON f.id_especie = e.id
WHERE f.id_usuario = '$id_usuario'
ORDER BY f.data_salvo DESC
";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meus Favoritos - Arthropoda</title>
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

  
  <button class="toggle-btn" id="toggle-btn">
    <i class="fa-solid fa-chevron-left"></i>
  </button>

  <main>
    
    <div class="top-bar">
      <div style="flex: 1; text-align: center;">
        <h2 style="color: white; margin: 0;">Meus Favoritos</h2>
      </div>
    </div>

    <div class="cards-container">
      <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($especie = mysqli_fetch_assoc($query)): ?>
          <div class="card">
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
              <form action="acoes.php" method="POST" style="margin: 0;">
                <input type="hidden" name="id_favorito" value="<?= $especie['favorito_id'] ?>">
                <button type="submit" name="remover-favorito" class="favorite-btn favorited" 
                        title="Remover dos favoritos"
                        onclick="return confirm('Deseja remover dos favoritos?')">
                  <i class="fa-solid fa-heart"></i>
                </button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
          <i class="fa-solid fa-heart-crack" style="font-size: 4rem; color: rgba(255, 255, 255, 0.5); margin-bottom: 20px;"></i>
          <h3 style="color: white; margin-bottom: 10px;">Nenhum favorito ainda</h3>
          <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 20px;">
            Explore o catálogo e adicione suas espécies favoritas!
          </p>
          <button onclick="window.location.href='especie.php'" 
                  style="background-image: linear-gradient(-45deg, #9d79ff 0%, #7658d6 70%); 
                         color: white; border: none; padding: 12px 24px; 
                         border-radius: 10px; cursor: pointer; font-size: 1rem;">
            Ir para o Catálogo
          </button>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <button class="back-to-top" id="back-to-top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="../js/config.js"></script>
  <script>
    // Back to top button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
      backToTop.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

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
