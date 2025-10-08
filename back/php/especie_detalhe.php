<?php
session_start();
include('conecta.php');

$especie = null;
$galeria = [];

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Busca os dados da espécie
    $sql = "SELECT * FROM especie WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $especie = mysqli_fetch_assoc($query);

        // Busca imagens da galeria relacionadas a essa espécie
        $sql_galeria = "SELECT * FROM galeria_imagens WHERE id_especie = '$id'";
        $result_galeria = mysqli_query($conn, $sql_galeria);

        if (mysqli_num_rows($result_galeria) > 0) {
            $galeria = mysqli_fetch_all($result_galeria, MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Detalhes da Espécie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<a href="especie.php" class="btn btn-secondary mb-4">&larr; Voltar</a>

<?php if ($especie): ?>
  <div class="container">
    <div class="text-center mb-4">
      <?php if (!empty($especie['imagem'])): ?>
        <img src="img/<?= htmlspecialchars($especie['imagem']) ?>" 
             alt="Imagem da espécie" 
             class="img-fluid rounded shadow" 
             style="max-height: 300px;">
      <?php else: ?>
        <p class="text-muted">Sem imagem principal.</p>
      <?php endif; ?>
    </div>

    <h2 class="mb-3 text-center"><?= htmlspecialchars($especie['nome_comum']) ?></h2>
    <h5 class="text-center text-muted"><em><?= htmlspecialchars($especie['nome_cientifico']) ?></em></h5>
    <hr>

    <table class="table table-bordered">
      <tr><th>Família</th><td><?= htmlspecialchars($especie['familia']) ?></td></tr>
      <tr><th>Ordem</th><td><?= htmlspecialchars($especie['ordem']) ?></td></tr>
      <tr><th>Habitat</th><td><?= htmlspecialchars($especie['habitat']) ?></td></tr>
      <tr><th>Distribuição Geográfica</th><td><?= htmlspecialchars($especie['distribuicao_geografica']) ?></td></tr>
      <tr><th>Alimentação</th><td><?= htmlspecialchars($especie['alimentacao']) ?></td></tr>
      <tr><th>Envergadura das Asas</th><td><?= htmlspecialchars($especie['envergadura_alas']) ?></td></tr>
      <tr><th>Ciclo de Vida</th><td><?= htmlspecialchars($especie['ciclo_vida']) ?></td></tr>
      <tr><th>Comportamento</th><td><?= nl2br(htmlspecialchars($especie['comportamento'])) ?></td></tr>
      <tr><th>Status de Conservação</th><td><?= htmlspecialchars($especie['status_conservacao']) ?></td></tr>
      <tr><th>Descrição</th><td><?= nl2br(htmlspecialchars($especie['descricao'])) ?></td></tr>
    </table>

    <?php if (!empty($galeria)): ?>
      <h4 class="mt-5 mb-3">Galeria de Imagens</h4>
      <div class="row">
        <?php foreach ($galeria as $img): ?>
          <div class="col-md-3 mb-3 text-center">
            <img src="<?= htmlspecialchars($img['imagem']) ?>" 
                 alt="<?= htmlspecialchars($img['descricao']) ?>" 
                 class="img-fluid rounded shadow-sm"
                 style="max-height: 200px;">
            <?php if (!empty($img['descricao'])): ?>
              <p class="text-muted small mt-2"><?= htmlspecialchars($img['descricao']) ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

<?php else: ?>
  <div class="alert alert-warning">Espécie não encontrada.</div>
<?php endif; ?>

</body>
</html>
