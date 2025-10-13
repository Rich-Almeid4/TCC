<?php

session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Adicionar Espécie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

<div class="container">
  <h2 class="mb-4">Adicionar Nova Espécie</h2>

  <?php include("mensagem.php"); ?>

  <form action="acoes.php" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white rounded">

    <!-- AÇÃO CORRETA -->
    <input type="hidden" name="acao" value="cadastrar">

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Nome Comum</label>
        <input type="text" name="nome_comum" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nome Científico</label>
        <input type="text" name="nome_cientifico" class="form-control" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Família</label>
        <input type="text" name="familia" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Ordem</label>
        <input type="text" name="ordem" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" rows="3" class="form-control" required></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Habitat</label>
        <input type="text" name="habitat" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Distribuição Geográfica</label>
        <input type="text" name="distribuicao_geografica" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Alimentação</label>
        <input type="text" name="alimentacao" class="form-control" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Envergadura das Asas</label>
        <input type="text" name="envergadura_alas" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Ciclo de Vida</label>
        <input type="text" name="ciclo_vida" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status de Conservação</label>
        <input type="text" name="status_conservacao" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Comportamento</label>
      <textarea name="comportamento" rows="3" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem (arquivo)</label>
      <input type="file" name="imagem" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Espécie</button>
    <a href="admin.php" class="btn btn-secondary">Cancelar</a>
  
  </form>

</div>

</body>
</html>
