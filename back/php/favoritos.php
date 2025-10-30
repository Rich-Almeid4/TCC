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
  <style>
    /* Frutiger Aero style for favorites page with purple palette */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  /* Updated gradient to match catalog page */
  background: linear-gradient(135deg, #d1c1fd 0%, #b8a5f5 50%, #9485c0 100%);
  display: flex;
  padding-left: 240px;
  transition: padding-left 0.3s ease-in-out;
  position: relative;
  overflow-x: hidden;
}

/* Updated floating bubbles to match catalog page style */
body::before,
body::after {
  content: "";
  position: fixed;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3), rgba(186, 169, 245, 0.1));
  backdrop-filter: blur(40px);
  pointer-events: none;
  z-index: 0;
  animation: float 20s ease-in-out infinite;
}

body::before {
  width: 400px;
  height: 400px;
  top: -100px;
  right: -100px;
  animation-delay: 0s;
}

body::after {
  width: 300px;
  height: 300px;
  bottom: -80px;
  left: 10%;
  animation-delay: -10s;
}

@keyframes float {
  0%,
  100% {
    transform: translate(0, 0) scale(1);
  }
  25% {
    transform: translate(30px, -30px) scale(1.05);
  }
  50% {
    transform: translate(-20px, 20px) scale(0.95);
  }
  75% {
    transform: translate(20px, 30px) scale(1.02);
  }
}

main {
  flex: 1;
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  z-index: 1;
}

.top-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  max-width: 1200px;
  padding: 20px;
  margin: 0 auto 40px;
}

/* Sidebar styling copied exactly from catalog page */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 240px;
  height: 100vh;
  background: rgba(130, 128, 193, 0.75);
  backdrop-filter: blur(20px);
  color: #fff;
  transition: width 0.3s ease;
  overflow: hidden;
  z-index: 1000;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1), inset -1px 0 0 rgba(255, 255, 255, 0.1);
}

.sidebar .sidebar-content {
  padding: 20px 10px;
}

.sidebar .user {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 40px;
  padding: 10px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.05);
}

.sidebar .name .item-name {
  display: inline;
  font-size: 0.9rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.side-items {
  list-style: none;
  padding: 0;
  margin: 0;
}

.section-title {
  font-size: 0.85rem;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.7);
  text-transform: uppercase;
  margin: 16px 0 8px 16px;
  opacity: 1;
  visibility: visible;
  transition: opacity 0.3s ease;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

#title {
  font-size: 1.4rem;
  text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.side-item {
  margin: 8px 0;
}

.side-item a {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 500;
  color: #fff;
  padding: 10px 8px;
  border-radius: 12px;
  transition: all 0.3s ease;
  white-space: nowrap;
  overflow: hidden;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.side-item i {
  font-size: 1rem;
  width: 28px;
  text-align: center;
  flex-shrink: 0;
}

.side-item .item-name {
  opacity: 1;
  width: auto;
  overflow: visible;
  transition: opacity 0.3s ease, width 0.3s ease;
}

.side-item a:hover {
  background: rgba(255, 255, 255, 0.15);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2);
  transform: translateX(4px);
}

.side-item a.active {
  background: rgba(255, 255, 255, 0.2);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.toggle-btn {
  position: fixed;
  top: 25px;
  left: 220px;
  width: 35px;
  height: 35px;
  background: rgba(130, 128, 193, 0.85);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #fff;
  font-size: 1rem;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1100;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.toggle-btn i {
  transition: transform 0.4s ease;
  transform: rotate(180deg);
}

.toggle-btn:hover {
  background: rgba(109, 107, 168, 0.9);
  transform: scale(1.05);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
  width: 100%;
  max-width: 1400px;
  padding: 0 10px;
  position: relative;
  z-index: 1;
}

/* Enhanced card styling with Frutiger Aero effects */
.card {
  border-radius: 20px;
  border: 2px solid rgba(186, 169, 245, 0.3);
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 24px rgba(118, 88, 214, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 1px 2px
    rgba(255, 255, 255, 0.5);
  padding: 16px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.card::before {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
  transform: translateX(-100%) translateY(-100%) rotate(45deg);
  transition: transform 0.6s ease;
  pointer-events: none;
}

.card:hover::before {
  transform: translateX(100%) translateY(100%) rotate(45deg);
}

.card img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 12px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.card h3 {
  font-size: 1.2rem;
  margin-bottom: 8px;
  color: #333;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3;
  min-height: 2.6rem;
  font-weight: 600;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
}

.card p {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 12px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.5;
  min-height: 3rem;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 32px rgba(118, 88, 214, 0.25), 0 0 20px rgba(157, 121, 255, 0.2), inset 0 1px 0
    rgba(255, 255, 255, 0.9);
  border-color: rgba(118, 88, 214, 0.5);
}

.card-actions {
  display: flex;
  gap: 10px;
}

.card button {
  background: linear-gradient(135deg, #9d79ff 0%, #7658d6 100%);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(118, 88, 214, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
  position: relative;
  overflow: hidden;
}

.card button::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s ease;
}

.card button:hover::before {
  left: 100%;
}

.card button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(118, 88, 214, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.favorite-btn {
  background: rgba(255, 255, 255, 0.7) !important;
  backdrop-filter: blur(10px) !important;
  border: 2px solid #7658d6 !important;
  color: #7658d6 !important;
  padding: 12px !important;
  width: 48px !important;
  flex: 0 0 48px !important;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(118, 88, 214, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.5) !important;
}

.favorite-btn:hover {
  background: linear-gradient(135deg, #9d79ff 0%, #7658d6 100%) !important;
  color: white !important;
  border-color: #7658d6 !important;
  box-shadow: 0 6px 16px rgba(118, 88, 214, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
}

.favorite-btn.favorited {
  background: linear-gradient(135deg, #ffd700 0%, #ffaa00 100%) !important;
  border-color: #ffaa00 !important;
  color: white !important;
  box-shadow: 0 4px 12px rgba(255, 170, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.4) !important;
}

/* Enhanced empty state styling */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 40px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  color: #fff;
}

.empty-icon {
  width: 120px;
  height: 120px;
  margin: 0 auto 30px;
  background: linear-gradient(135deg, rgba(157, 121, 255, 0.3), rgba(118, 88, 214, 0.2));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(118, 88, 214, 0.3), inset 0 2px 10px rgba(255, 255, 255, 0.3);
}

.empty-icon i {
  font-size: 4rem;
  color: rgba(255, 255, 255, 0.9);
  filter: drop-shadow(0 4px 8px rgba(118, 88, 214, 0.3));
}

.empty-state h3 {
  color: white;
  margin-bottom: 15px;
  font-size: 1.8rem;
  text-shadow: 0 2px 10px rgba(118, 88, 214, 0.3);
}

.empty-state p {
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 30px;
  font-size: 1.1rem;
  text-shadow: 0 1px 5px rgba(118, 88, 214, 0.2);
}

.cta-button {
  background: linear-gradient(135deg, #9d79ff 0%, #7658d6 100%);
  backdrop-filter: blur(10px);
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 15px 35px;
  border-radius: 15px;
  cursor: pointer;
  font-size: 1.1rem;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(118, 88, 214, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3);
  position: relative;
  overflow: hidden;
}

.cta-button::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s ease;
}

.cta-button:hover::before {
  left: 100%;
}

.cta-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(118, 88, 214, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: linear-gradient(135deg, #9d79ff 0%, #7658d6 100%);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  width: 55px;
  height: 55px;
  display: none;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(118, 88, 214, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.back-to-top.visible {
  display: flex;
}

.back-to-top:hover {
  transform: translateY(-4px) scale(1.05);
  box-shadow: 0 8px 24px rgba(118, 88, 214, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

@media (max-width: 1400px) {
  .cards-container {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 1050px) {
  .cards-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .cards-container {
    grid-template-columns: 1fr;
  }

  .card img {
    height: 200px;
  }
}

@media (max-width: 800px) {
  body {
    padding-left: 0;
  }

  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }

  .sidebar.open {
    transform: translateX(0);
  }

  .toggle-btn {
    left: 20px;
    top: 20px;
    background: rgba(118, 88, 214, 0.9);
    backdrop-filter: blur(10px);
  }

  .toggle-btn i {
    transform: rotate(0deg);
  }

  .back-to-top {
    width: 50px;
    height: 50px;
    bottom: 1rem;
    right: 1rem;
  }
}

  </style>
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
          <li class="side-item"><a href="especie.php"><i class="fa-solid fa-book"></i><span class="item-name">Espécies</span></a></li>
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
