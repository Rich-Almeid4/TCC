<?php
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Upload de Artigo</title>
</head>
<body>
    <a href="admin.php">Voltar</a>
    <h2>Enviar novo artigo</h2>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <p><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></p>
    <?php endif; ?>

    <form action="acoes.php" method="POST" enctype="multipart/form-data">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Autor:</label><br>
    <input type="text" name="autor" required><br><br>

    <label>Arquivo (PDF):</label><br>
    <input type="file" name="arquivo" accept="application/pdf" required><br><br>

    <label>Imagem de capa (JPG, PNG):</label><br>
    <input type="file" name="capa" accept="image/*" required><br><br>

    <button type="submit" name="upload_artigo">Enviar</button>
</form>

</body>
</html>
