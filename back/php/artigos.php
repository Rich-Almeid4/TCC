<?php
date_default_timezone_set('America/Sao_Paulo');
require 'conecta.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Artigos</title>
  <link rel="stylesheet" href="../css/artigo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <nav class="sidebar" id="sidebar">
      <div class="sidebar-content">
        <div class="user">
          <img class="logo" src="img/logo.svg" alt="Logo Arthropoda">
          <h1 class="name"><span class="item-name" id="title">Arthropoda</span></h1>
        </div>
        <ul class="side-items">
          <li class="side-item"></liclass><a href="#"><i class="fa-solid fa-house"></i><span class="item-name">Home</span></a></li>
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



  <div class="container">
    <h1 class="txt-topo" >Artigos</h1>

    <div class="lista-artigos">
    <?php
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $sql = "SELECT * FROM artigo WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        
        if ($artigo = mysqli_fetch_assoc($result)) {
            echo "<div class='artigo'>";
            echo "<p class='info-data'>" . date('d/m/Y', strtotime($artigo['data_publicacao'])) . "</p>";
            echo "<h2>" . htmlspecialchars($artigo['titulo']) . "</h2>";
            echo "<p class='info'>" . htmlspecialchars($artigo['autor']) . "</p>";
            
            $ext = pathinfo($artigo['caminho_arquivo'], PATHINFO_EXTENSION);
            $caminho = 'documentos/' . $artigo['caminho_arquivo'];

            if (strtolower($ext) === 'pdf') {
                echo "<iframe src='$caminho'></iframe>";
            } else {
                echo "<p>Tipo de documento não suportado. <a href='$caminho' target='_blank'>Clique para abrir</a></p>";
            }

            // 🟢 Botão de favoritar
            if (isset($_SESSION['id'])) {
              echo "
              <form action='acoes.php' method='POST'>
                  <input type='hidden' name='id_artigo' value='{$artigo['id']}'>
                  <button type='submit' name='favoritar_artigo'>
                      ⭐ Adicionar aos Favoritos
                  </button>
              </form>
              ";
            }

            echo "<a class='voltar-btn' href='artigos.php'>&larr; Voltar à lista</a>";
            echo "</div>";
        } else {
            echo "<p>Artigo não encontrado.</p>";
        }

    } else {
        $sql = "SELECT * FROM artigo ORDER BY data_publicacao DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($artigo = mysqli_fetch_assoc($result)) {
                echo "<div class='artigo'>";
                if (!empty($artigo['capa'])) {
                  echo "<img class='artigo-img' src='" . htmlspecialchars($artigo['capa']) . "' alt='Capa do artigo'>";
                }
                echo "<p class='info-data'>" . date('d/m/Y', strtotime($artigo['data_publicacao'])) . "</p>";
                echo "<h2><strong><a class='artigo-titulo' href='artigos.php?id=" . $artigo['id'] . "'>" . htmlspecialchars($artigo['titulo']) . "</a></strong></h2>";
                echo "<p class='info'> " . htmlspecialchars($artigo['autor']) . "</p>";
                echo "<a class='botao' href='artigos.php?id=" . $artigo['id'] . "'>Visualizar documento</a>";
                echo "</div>";                
            }
        }
    }
    ?>
    </div>
  </div>


  <script>
  const sidebar = document.querySelector(".sidebar");
  const toggleBtn = document.querySelector(".toggle-btn");
  const toggleIcon = toggleBtn.querySelector("i");
  const body = document.body;

  let isOpen = true;

  function toggleSidebar() {
    if (isOpen) {
      sidebar.classList.remove("open");
      sidebar.style.width = "55px";
      body.style.paddingLeft = "55px";
      toggleBtn.style.left = "40px";
      toggleIcon.style.transform = "rotate(0deg)";

      document.querySelectorAll(".item-name, .section-title").forEach(el => {
        el.style.opacity = "0";
        el.style.width = "0";
        el.style.visibility = "hidden";
        el.style.overflow = "hidden";
      });

      isOpen = false;
    } else {
      sidebar.classList.add("open");
      sidebar.style.width = "240px";
      body.style.paddingLeft = "240px";
      toggleBtn.style.left = "220px";
      toggleIcon.style.transform = "rotate(180deg)";

      document.querySelectorAll(".item-name, .section-title").forEach(el => {
        el.style.opacity = "1";
        el.style.width = "auto";
        el.style.visibility = "visible";
        el.style.overflow = "visible";
      });

      isOpen = true;
    }
  }

  if (menuToggle) {
    menuToggle.addEventListener("click", () => {
      sidebar.classList.toggle("open");
    });

    window.addEventListener("click", (e) => {
      if (
        window.innerWidth <= 768 &&
        !sidebar.contains(e.target) &&
        !menuToggle.contains(e.target)
      ) {
        sidebar.classList.remove("open");
      }
    });
  }
</script>

</body>
</html>