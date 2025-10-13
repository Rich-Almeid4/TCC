<?php
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
include('conecta.php');

// Verifica se o usuário é admin
if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}

// Função principal: exibir lista ou formulário de edição
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Espécies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">  
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="admin.php">Voltar</a>

    <h2 class="mb-4 text-center">Gerenciar Espécies</h2>
    <?php include("mensagem.php"); ?>

    <?php
    // Se for passado um ID, exibe o formulário de edição
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $sql = "SELECT * FROM especie WHERE id = $id LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $especie = mysqli_fetch_assoc($query);

        if (!$especie) {
            echo "<div class='alert alert-danger'>Espécie não encontrada.</div>";
        } else {
    ?>
        <div class="card shadow p-4">
            <h4>Editar Espécie</h4>
            <form action="acoes.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= $especie['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nome Comum:</label>
                    <input type="text" name="nome_comum" class="form-control" value="<?= htmlspecialchars($especie['nome_comum']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nome Científico:</label>
                    <input type="text" name="nome_cientifico" class="form-control" value="<?= htmlspecialchars($especie['nome_cientifico']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Família:</label>
                    <input type="text" name="familia" class="form-control" value="<?= htmlspecialchars($especie['familia']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ordem:</label>
                    <input type="text" name="ordem" class="form-control" value="<?= htmlspecialchars($especie['ordem']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição:</label>
                    <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($especie['descricao']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagem atual:</label><br>
                    <img src="img/<?= $especie['imagem'] ?>" width="150" alt=""><br><br>
                    <input type="file" name="imagem" class="form-control">
                </div>

                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar Alterações</button>
                <a href="edit_especie.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
            </form>

            <hr>

            <form action="acoes.php" method="post" onsubmit="return confirm('Deseja realmente excluir esta espécie?');">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" value="<?= $especie['id'] ?>">
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Excluir Espécie</button>
            </form>
        </div>

    <?php
        }
    } else {
        // Lista todas as espécies com botões de ação
        $sql = "SELECT id, nome_comum, nome_cientifico, imagem FROM especie ORDER BY nome_comum ASC";
        $query = mysqli_query($conn, $sql);

        if (mysqli_num_rows($query) > 0) {
            echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
            while ($esp = mysqli_fetch_assoc($query)) {
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="img/<?= $esp['imagem'] ?>" class="card-img-top" alt="Imagem da espécie" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($esp['nome_comum']) ?></h5>
                            <p class="card-text"><em><?= htmlspecialchars($esp['nome_cientifico']) ?></em></p>
                            <a href="edit_especie.php?id=<?= $esp['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Editar</a>
                            <form action="acoes.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir esta espécie?');">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $esp['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo "</div>";
        } else {
            echo "<div class='alert alert-info'>Nenhuma espécie cadastrada.</div>";
        }
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
