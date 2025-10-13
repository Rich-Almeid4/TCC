<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include('conecta.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Espécies</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<header>
  <?php
  include('mensagem.php');
  ?>
</header>
<body class="p-4">
  <?php
// Define o destino do botão "Voltar"
if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') {
    $link_voltar = 'admin.php'; // Página do admin
} else {
    $link_voltar = 'index.php'; // Página inicial do usuário comum
}
?>
<a href="<?= $link_voltar ?>" class="btn btn-secondary mb-4">&larr; Voltar</a>


<h2>Espécies Cadastradas</h2>

<?php
$sql = "SELECT * FROM especie ORDER BY nome_comum ASC";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0):
?>
<table>
  <thead>
    <tr>
      <th></th>
      <th>Nome</th>
      <th>Ver detalhes</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php while ($especie = mysqli_fetch_assoc($query)): ?>
    <tr>
 <td>
        <?php if (!empty($especie['imagem'])): ?>
          <img src="img/<?= htmlspecialchars($especie['imagem']) ?>" alt="Imagem da espécie" style="width:100px; height:auto; border-radius:8px;">
        <?php else: ?>
          <span class="text-muted">Sem imagem</span>
        <?php endif; ?>
      </td>      <td><?= htmlspecialchars($especie['nome_comum']) ?></td>
      <td>
        <a href="especie_detalhe.php?id=<?= $especie['id'] ?>">
          Ver mais
        </a>
      </td>
<td>
  <form action="acoes.php" method="POST">
    <input type="hidden" name="id_especie" value="<?= $especie['id'] ?>">
    <button type="submit" name="favoritar" class="btn btn-outline-primary btn-sm">
      ⭐ Favoritar
    </button>
  </form>
</td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php
else:
  echo "<p>Nenhuma espécie cadastrada.</p>";
endif;
?>

</body>
</html>
