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
  <button class="btn-home" onclick="window.location.href='especie.php'">Tela principal</button>
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
        <input type="password" name="senha" value="">
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
      <button class="tab-btn active" onclick="window.SettingsManager.switchTab('gerais')">Configurações Gerais</button>
      <button class="tab-btn" onclick="window.SettingsManager.switchTab('acessibilidade')">Acessibilidade</button>
    </div>

    <div id="gerais" class="settings-content active">
      <div class="settings-group">
        <div class="settings-group-title">Aparência</div>
        
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

      <button class="btn-update btn-save-settings" onclick="window.SettingsManager.saveSettings()">Salvar Configurações</button>
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
          <label for="line-spacing">Espaçamento entre linhas</label>
          <select id="line-spacing" name="line-spacing">
            <option value="normal" selected>Normal</option>
            <option value="relaxed">Relaxado</option>
            <option value="loose">Amplo</option>
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

        <div class="setting-item">
          <label for="color-blind-mode">Modo para daltonismo</label>
          <select id="color-blind-mode" name="color-blind-mode">
            <option value="none" selected>Nenhum</option>
            <option value="protanopia">Protanopia (vermelho-verde)</option>
            <option value="deuteranopia">Deuteranopia (verde-vermelho)</option>
            <option value="tritanopia">Tritanopia (azul-amarelo)</option>
          </select>
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
      </div>

      <div class="settings-group">
        <div class="settings-group-title">Conteúdo</div>
        
        <div class="setting-item">
          <label>Suporte a leitor de tela</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="setting-item">
          <label>Descrições detalhadas de imagens</label>
          <label class="custom-checkbox">
            <input type="checkbox" checked>
            <span class="checkmark"></span>
          </label>
        </div>
      </div>

      <button class="btn-update btn-save-settings" onclick="window.SettingsManager.saveSettings()">Salvar Configurações</button>
    </div>
  </section>
</main>

</body>
</html>
