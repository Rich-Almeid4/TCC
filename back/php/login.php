<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login e Cadastro - Arthropoda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>
  <?php if (isset($_SESSION['mensagem'])): ?>
    <div class="alert-message">
      <?php 
        echo $_SESSION['mensagem']; 
        unset($_SESSION['mensagem']);
      ?>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="logo">
      <img src="img/logo.svg" alt="Arthropoda Logo">
    </div>

    <!-- Forms Container -->
    <div class="forms-container">
      <div class="signin-signup">
        <!-- Login Form -->
        <form action="entrar.php" method="POST" class="sign-in-form">
          <h2 class="title">Login</h2>
          
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="nome" placeholder="Nome de Usuário" required>
          </div>
          
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="senha" placeholder="Senha" required>
          </div>
          
          <button type="submit" name="logar" class="btn solid">Entrar</button>
          
          <p class="social-text">Não possui uma conta? <a href="#" id="sign-up-btn" class="btn-cadastro">Cadastre-se</a></p>
        </form>

        <!-- Cadastro Form -->
        <form action="acoes.php" method="POST" enctype="multipart/form-data" class="sign-up-form">
          <h2 class="title">Cadastro</h2>
          
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="nome" placeholder="Nome de Usuário" required>
          </div>
          
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
          </div>
          
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="senha" placeholder="Senha" required>
          </div>
          
          <div class="file-input-wrapper">
            <label for="imagem" class="file-label">
              <i class="fas fa-camera"></i> Foto de perfil (opcional)
            </label>
            <input type="file" name="imagem" id="imagem" accept="image/*">
          </div>
          
          <button type="submit" name="criar" class="btn">Cadastrar</button>
          
          <p class="social-text">Já possui uma conta? <a href="#" id="sign-in-btn" class="btn-cadastro">Entre</a></p>
        </form>
      </div>
    </div>

    <!-- Painel de imagens movido para fora do forms-container para ficar por cima -->
    <div class="panels-container">
      <div class="panel left-panel">
        <img src="img/img-painel.jpg" alt="">
      </div>
    </div>
  </div>

 <script>
  // Alternar entre login e cadastro
  const container = document.querySelector(".container");
  const signUpBtn = document.querySelector("#sign-up-btn");
  const signInBtn = document.querySelector("#sign-in-btn");
  const panelImage = document.querySelector(".left-panel img");

  // Ao clicar em "Cadastrar-se"
  signUpBtn.addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.add("sign-up-mode");
    // Espelhar a imagem da borboleta
    panelImage.style.transform = "rotateY(180deg)";
  });

  // Ao clicar em "Entrar"
  signInBtn.addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.remove("sign-up-mode");
    // Retornar a imagem ao normal
    panelImage.style.transform = "rotateY(0deg)";
  });

  // Esconder alerta automaticamente
  const alertMessage = document.querySelector(".alert-message");
  if (alertMessage) {
    setTimeout(() => {
      alertMessage.style.opacity = "0";
      setTimeout(() => alertMessage.remove(), 300);
    }, 5000);
  }
</script>
</body>
</html>
