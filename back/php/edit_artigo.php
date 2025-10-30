<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
include('conecta.php');

// 🔒 Verifica se o usuário está logado e é admin
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'admin') {
    $_SESSION['mensagem'] = 'Acesso negado!';
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Artigos</title>
    <link rel="stylesheet" href="../css/gerenciar_artigos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
        
  <div class="container-form">
      <a class="voltar" href="admin.php">&larr; Voltar</a>
      <h2 class="txt-topo"><strong>Gerenciar Artigos</strong></h2>

      <?php include('mensagem.php'); ?>

      <?php
      $sql = "SELECT * FROM artigo ORDER BY data_publicacao DESC";
      $query = mysqli_query($conn, $sql);

      if (mysqli_num_rows($query) > 0):
      ?>
          <table class="tabela-artigos">
              <thead>
                  <tr>
                      <th>Título</th>
                      <th>Autor</th>
                      <th>Data</th>
                      <th>Arquivo</th>
                      <th>Ações</th>
                  </tr>
              </thead>
              <tbody>
                  <?php while ($artigo = mysqli_fetch_assoc($query)): ?>
                      <tr>
                          <td data-label="Título"><?= htmlspecialchars($artigo['titulo']) ?></td>
                          <td data-label="Autor"><?= htmlspecialchars($artigo['autor']) ?></td>
                          <td data-label="Data"><?= date('d/m/Y H:i', strtotime($artigo['data_publicacao'])) ?></td>
                          <td data-label="Arquivo">
                              <a href="<?= htmlspecialchars($artigo['caminho_arquivo']) ?>" target="_blank" class="btn-abrir">
                                  <i class="fa-solid fa-file-pdf"></i> Abrir
                              </a>
                          </td>
                          <td data-label="Ações">
                              <form action="acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este artigo?');">
                                  <input type="hidden" name="acao" value="excluir_artigo">
                                  <input type="hidden" name="id" value="<?= $artigo['id'] ?>">
                                  <button type="submit" class="btn-excluir">
                                      <i class="bi bi-trash"></i> Excluir
                                  </button>
                              </form>
                          </td>
                      </tr>
                  <?php endwhile; ?>
              </tbody>
          </table>
      <?php else: ?>
          <div class="alerta-vazio">Nenhum artigo cadastrado.</div>
      <?php endif; ?>
  </div>
</body>
