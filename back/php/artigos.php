<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
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
<body><?php
// Define o destino do botão "Voltar"
if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') {
    $link_voltar = 'admin.php'; // Página do admin
} else {
    $link_voltar = 'index.php'; // Página inicial do usuário comum
}
?>
<a href="<?= $link_voltar ?>" class="btn btn-secondary mb-4">&larr; Voltar</a>
<h1>Artigos</h1>
<?php
include("mensagem.php");
?>
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
        $caminho = $artigo['caminho_arquivo'];

        if (strtolower($ext) === 'pdf') {
            echo "<iframe src='$caminho'></iframe>";
        } else {
            echo "<p>Tipo de documento não suportado para visualização direta. <a href='$caminho' target='_blank'>Clique para abrir</a></p>";
        }

        // 🟢 Botão de favoritar
        if (isset($_SESSION['id'])) {
            echo "
            <form action='acoes.php' method='POST' style='margin-top:10px;'>
                <input type='hidden' name='id_artigo' value='{$artigo['id']}'>
                <button type='submit' name='favoritar_artigo'>
                    ⭐ Adicionar aos Favoritos
                </button>
            </form>
            ";
        }

        echo "<a class='voltar' href='artigos.php'>&larr; Voltar</a>";
        echo "</div>";
    } else {
        echo "<p>Artigo não encontrado.</p>";
    }

} else {
    // Lista todos os artigos
    $sql = "SELECT * FROM artigo ORDER BY data_publicacao DESC";
    $result = mysqli_query($conn, $sql);

   while ($artigo = mysqli_fetch_assoc($result)) {
    echo "<div class='artigo'>";
    if (!empty($artigo['capa'])) {
        echo "<img src='" . htmlspecialchars($artigo['capa']) . "' alt='Capa do artigo' style='width:100%; max-width:400px; border-radius:10px; margin-bottom:10px;'>";
    }
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
