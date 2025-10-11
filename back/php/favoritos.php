<?php
session_start();
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<header>

<?php
include('mensagem.php');
?>

</header>
<body class="p-4">
<a href="index.php" class="btn btn-secondary mb-4">&larr; Voltar</a>

<h2>Meus Favoritos</h2>

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
</body>
</html>
