<?php
require 'conecta.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Artigos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .artigo { background: #fff; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        iframe { width: 100%; height: 600px; border: none; }
        .voltar { display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
<a href="index.php">&larr; Voltar</a>
<h1>Artigos</h1>

<?php
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM artigo WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if ($artigo = mysqli_fetch_assoc($result)) {
        echo "<div class='artigo'>";
        echo "<h2>" . htmlspecialchars($artigo['titulo']) . "</h2>";
        echo "<p><strong>Autor:</strong> " . htmlspecialchars($artigo['autor']) . "</p>";
        echo "<p><strong>Data:</strong> " . date('d/m/Y H:i', strtotime($artigo['data_publicacao'])) . "</p>";
        
        // Verifica se é PDF
        $ext = pathinfo($artigo['caminho_arquivo'], PATHINFO_EXTENSION);
        $caminho = 'documentos/' . $artigo['caminho_arquivo'];

        if (strtolower($ext) === 'pdf') {
            echo "<iframe src='$caminho'></iframe>";
        } else {
            echo "<p>Tipo de documento não suportado para visualização direta. <a href='$caminho' target='_blank'>Clique para abrir</a></p>";
        }

        echo "<a class='voltar' href='artigos.php'>&larr; Voltar</a>";
        echo "</div>";
    } else {
        echo "<p>Artigo não encontrado.</p>";
    }

} else {
    $sql = "SELECT * FROM artigo ORDER BY data_publicacao DESC";
    $result = mysqli_query($conn, $sql);

    while ($artigo = mysqli_fetch_assoc($result)) {
        echo "<div class='artigo'>";
        echo "<h2><a href='artigos.php?id=" . $artigo['id'] . "'>" . htmlspecialchars($artigo['titulo']) . "</a></h2>";
        echo "<p><strong>Autor:</strong> " . htmlspecialchars($artigo['autor']) . "</p>";
        echo "<p><strong>Data:</strong> " . date('d/m/Y H:i', strtotime($artigo['data_publicacao'])) . "</p>";
        echo "<p><a href='artigos.php?id=" . $artigo['id'] . "'>Visualizar documento</a></p>";
        echo "</div>";
    }
}
?>

</body>
</html>
