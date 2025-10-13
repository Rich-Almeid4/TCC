<?php
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container py-4">

    <a href="admin.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Voltar</a>

    <h2 class="text-center mb-4">Gerenciar Artigos</h2>

    <?php include('mensagem.php'); ?>

    <?php
    // Busca todos os artigos
    $sql = "SELECT * FROM artigo ORDER BY data_publicacao DESC";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0):
    ?>
        <table class="table table-striped align-middle">
            <thead class="table-dark">
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
                        <td><?= htmlspecialchars($artigo['titulo']) ?></td>
                        <td><?= htmlspecialchars($artigo['autor']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($artigo['data_publicacao'])) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($artigo['caminho_arquivo']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Abrir
                            </a>
                        </td>
                        <td>
                            <form action="acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este artigo?');">
                                <input type="hidden" name="acao" value="excluir_artigo">
                                <input type="hidden" name="id" value="<?= $artigo['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">Nenhum artigo cadastrado.</div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
