<?php
session_start();

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] !== "admin") {
    $_SESSION['mensagem'] = "Acesso negado!";
    header("Location: login.php");
    exit;
}
require 'conecta.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../css/gerenciar_artigos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <div class="container-form">
        <a href="admin.php" class="voltar"><i class="bi bi-arrow-left"></i> Voltar</a>
        <h2 class="txt-topo"><i class="bi bi-people-fill"></i> Gerenciar Usuários</h2>

        <?php include('mensagem.php'); ?>

        <?php
        $sql = 'SELECT * FROM usuario ORDER BY nome ASC';
        $usuarios = mysqli_query($conn, $sql);

        if (mysqli_num_rows($usuarios) > 0):
        ?>
            <table class="tabela-artigos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = mysqli_fetch_assoc($usuarios)): ?>
                        <tr>
                            <td data-label="Nome"><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td data-label="Email"><?= htmlspecialchars($usuario['email']) ?></td>
                            <td data-label="Categoria"><?= htmlspecialchars($usuario['tipo']) ?></td>
                            <td data-label="Ações">
                                <form action="acoes.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    <input type="hidden" name="acao" value="excluir_usuario">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <button type="submit" class="btn-excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alerta-vazio">Nenhum usuário cadastrado.</div>
        <?php endif; ?>
    </div>

</body>
</html>
