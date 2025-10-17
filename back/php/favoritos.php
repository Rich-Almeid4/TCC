<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include('conecta.php');

// Usuário logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Busca favoritos do usuário
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
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Favoritos</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">  
  <link rel="stylesheet" href="../../css/config.css"/>
    <script src="../js/config.js"></script>
</head>
<header>


</header>
<body class="p-4">
 <?php
   include("mensagem.php");
   ?>
    <?php
// Define o destino do botão "Voltar"
if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') {
    $link_voltar = 'admin.php'; // Página do admin
} else {
    $link_voltar = 'index.php'; // Página inicial do usuário comum
}
?>
<a href="<?= $link_voltar ?>" class="btn btn-secondary mb-4">&larr; Voltar</a>


<h2>Espécies Favoritas</h2>

<?php if (mysqli_num_rows($query) > 0): ?>
<table class="table table-striped">
<thead>
<tr>
<th>Imagem</th>
<th>Nome</th>
<th>Detalhes</th>
<th>Remover</th>
</tr>
</thead>
<tbody>
<?php while ($especie = mysqli_fetch_assoc($query)): ?>
<tr>
<td>
<?php if ($especie['imagem']): ?>
<img src="img/<?= htmlspecialchars($especie['imagem']) ?>" style="width:100px; border-radius:5px;">
<?php else: ?>
<span class="text-muted">Sem imagem</span>
<?php endif; ?>
</td>
<td><?= htmlspecialchars($especie['nome_comum']) ?></td>
<td><a href="especie_detalhe.php?id=<?= $especie['id'] ?>">Ver mais</a></td>
<td>
<form action="acoes.php" method="POST">
<input type="hidden" name="id_favorito" value="<?= $especie['favorito_id'] ?>">
<button type="submit" name="remover-favorito" class="btn btn-danger btn-sm">Remover</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>Você ainda não favoritou nenhuma espécie.</p>
<?php endif; ?>
<hr class="my-5">

<h2>Artigos Favoritos</h2>

<?php
$sql_artigos = "
SELECT fa.id AS favorito_id, a.*
FROM favorito_artigo fa
JOIN artigo a ON fa.id_artigo = a.id
WHERE fa.id_usuario = '$id_usuario'
ORDER BY fa.data_salvo DESC
";
$query_artigos = mysqli_query($conn, $sql_artigos);
?>

<?php if (mysqli_num_rows($query_artigos) > 0): ?>
<table class="table table-striped">
<thead>
<tr>
<th>Título</th>
<th>Autor</th>
<th>Data</th>
<th>Visualizar</th>
<th>Remover</th>
</tr>
</thead>
<tbody>
<?php while ($artigo = mysqli_fetch_assoc($query_artigos)): ?>
<tr>
<td><?= htmlspecialchars($artigo['titulo']) ?></td>
<td><?= htmlspecialchars($artigo['autor']) ?></td>
<td><?= date('d/m/Y H:i', strtotime($artigo['data_publicacao'])) ?></td>
<td><a href="artigos.php?id=<?= $artigo['id'] ?>" target="_blank">Abrir</a></td>
<td>
<form action="acoes.php" method="POST">
<input type="hidden" name="id_favorito" value="<?= $artigo['favorito_id'] ?>">
<button type="submit" name="remover-favorito-artigo" class="btn btn-danger btn-sm">Remover</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>Você ainda não favoritou nenhum artigo.</p>
<?php endif; ?>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
