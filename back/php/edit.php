<?php
require 'conecta.php';

session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}



if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3>ID inválido!</h3>";
    exit;
}

$usuario_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM `usuario` WHERE id='$usuario_id'";
$query = mysqli_query($conn, $sql);

if (!$query || mysqli_num_rows($query) == 0) {
    echo "<h5>Usuário não encontrado!</h5>";
    exit;
}

$usuario = mysqli_fetch_array($query);
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Perfil do Usuário</title>
<link rel="stylesheet" href="../../css/perfil.css"/>
<link rel="stylesheet" href="../../css/config.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="icon" href="logo.svg" type="image/png">
<script src="../js/config.js"></script>
</head>
<body>

<header>
 <img class="logo" src="logo.svg" alt="Logo Arthropoda">
  <button class="btn-home" onclick="window.location.href='../../front/catalogo.html'">Tela principal</button>

</header>

<main>
  <section class="perfil-info" aria-label="Informações básicas do usuário">

    <img  class="foto-perfil" id="foto-perfil" aria-label="Foto do perfil do usuário">
    <h2 id="username"><?=($usuario['nome']); ?></h2>
    <p id="idgmail"><?=($usuario['email']); ?></p>
  </section>

  <section class="perfil-editar" aria-label="Formulário para editar perfil">
    <h3>Editar Perfil</h3>
    <form action="acoes.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="usuario_id" value="<?= $usuario['id']; ?>">
      <div class="avatar-wrapper">
        <div class="avatar-preview" aria-label="Avatar atual"></div>
        <div>
          <label for="avatar-upload">Alterar Avatar</label>
          <input type="file" id="avatar-upload" name="avatar-upload" accept="image/*" />
        </div>
      </div>

      <div>
        <label for="inputName">Nome</label>
        <input type="text" id="inputName" name="nome" value="<?= $usuario['nome']; ?>">
      </div>

      <div>
        <label for="email">E-mail</label>
        <input type="text" id="email" name="email" value="<?= $usuario['email']; ?>">
      </div>

      <div>
        <label for="password">Senha</label>
        <input type="text" name="senha" value="<?= $usuario['senha']; ?>">
      </div>

      <div>
        <label for="password-confirm">Confirmação de Senha</label>
        <input type="password" id="password-confirm" name="password-confirm" placeholder="Confirme a nova senha" />
      </div>

      <div class="botoes">
      </div> 
      
       <button type="submit" name="update-comum"class="btn-update">Salvar</button>
      </form>
      <br>
       <a href="index.php"><button type="submit" name="update-comum"class="btn-update">Voltar</button></a>
 
  </section>

  <section class="configuracoes-section" aria-label="Configurações do sistema">
    <h3>Configurações</h3>
    
    <div class="settings-tabs">
      <button class="tab-btn active" onclick="switchTab('gerais')">Configurações Gerais</button>
      <button class="tab-btn" onclick="switchTab('acessibilidade')">Acessibilidade</button>
    </div>

    <div id="gerais" class="settings-content active">
      <div class="settings-group">
        <div class="settings-group-title">Preferências</div>
        
        <div class="setting-item">
          <label for="language">Idioma</label>
          <select id="language" name="language">
            <option value="pt-BR" selected>Português (BR)</option>
            <option value="en">English</option>
            <option value="es">Español</option>
          </select>
        </div>

        <div class="setting-item">
          <label for="theme">Tema</label>
          <select id="theme" name="theme">
            <option value="light" selected>Claro</option>
            <option value="dark">Escuro</option>
            <option value="auto">Automático</option>
          </select>
        </div>
      </div>

      <div class="settings-group">
        <div class="settings-group-title">Notificações</div>
        
        <div class="setting-item">
          <label>Notificações por e-mail</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Notificações de novas espécies</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Notificações de artigos científicos</label>
          <label class="custom-checkbox">
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <div class="settings-group">
        <div class="settings-group-title">Privacidade</div>
        
        <div class="setting-item">
          <label>Perfil público</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Mostrar histórico de visualizações</label>
          <label class="custom-checkbox">
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <button class="btn-update btn-save-settings" onclick="saveSettings()">Salvar Configurações</button>
    </div>

    <div id="acessibilidade" class="settings-content">
      <div class="settings-group">
        <div class="settings-group-title">Visual</div>
        
        <div class="setting-item">
          <label for="font-size">Tamanho da fonte</label>
          <select id="font-size" name="font-size">
            <option value="small">Pequeno</option>
            <option value="medium" selected>Médio</option>
            <option value="large">Grande</option>
            <option value="extra-large">Extra Grande</option>
          </select>
        </div>

        <div class="setting-item">
          <label>Alto contraste</label>
          <label class="custom-checkbox">
            <input type="checkbox" id="high-contrast">
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Reduzir animações</label>
          <label class="custom-checkbox">
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <div class="settings-group">
        <div class="settings-group-title">Navegação</div>
        
        <div class="setting-item">
          <label>Atalhos de teclado</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Suporte a leitor de tela</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Navegação por voz</label>
          <label class="custom-checkbox">
            <input type="checkbox">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <div class="settings-group">
        <div class="settings-group-title">Conteúdo</div>
        
        <div class="setting-item">
          <label>Descrições detalhadas de imagens</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Legendas em vídeos</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <button class="btn-update btn-save-settings" onclick="saveSettings()">Salvar Configurações</button>
    </div>
  </section>
</main>

   <script>
        function updateProfile() {
            const inputName = document.getElementById("inputName").value;
            if (inputName) {
                document.getElementById("username").innerText = inputName;
            }
              const email = document.getElementById("email").value;
            if (email) {
                document.getElementById("idgmail").innerText = email;
            }
        }

        document.getElementById("avatar-upload").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("foto-perfil").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        function switchTab(tabName) {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.settings-content');
            
            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        function saveSettings() {
            // Pega o valor do tema selecionado
            const themeSelect = document.getElementById('theme');
            const selectedTheme = themeSelect.value;
            
            // Pega o valor do tamanho de fonte selecionado
            const fontSizeSelect = document.getElementById('font-size');
            const selectedFontSize = fontSizeSelect.value;
            
            // Salva o tema usando o ThemeManager
            window.ThemeManager.save(selectedTheme);
            
            // Salva o tamanho de fonte usando o FontSizeManager
            window.FontSizeManager.save(selectedFontSize);
            
            // Mostra notificação elegante
            showNotification('Configurações salvas com sucesso!');
            
            // Aqui você pode adicionar código para salvar outras configurações
            // como idioma, notificações, etc.
        }

        function showNotification(message) {
            // Remove notificação anterior se existir
            const existingNotification = document.querySelector('.theme-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Cria nova notificação
            const notification = document.createElement('div');
            notification.className = 'theme-notification';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Remove após 3 segundos
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

    </script>
</body>
</html>
