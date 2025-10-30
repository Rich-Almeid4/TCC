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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
<div class="container py-4">

    <h2 class="txt-topo"><strong>Gerenciar Espécies</strong></h2>
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

    <main class="container-form">
        <h2 class="txt-topo"><i class="fa-solid fa-leaf"></i>Editar Espécie</h2>
        <div class="progress-bar">
        <div class="progress" id="progress"></div>
        </div>
        
            <form action="acoes.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= $especie['id'] ?>">

 <!-- Seção: Identificação -->
 <div class="form-section">
        <h3><i class="fa-solid fa-tag"></i> Identificação</h3>
        <div class="campo-duplo">
          <div>
            <label><i class="fa-solid fa-font"></i> Nome Comum</label>
            <input type="text" name="nome_comum" value="<?= htmlspecialchars($especie['nome_comum']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-flask"></i> Nome Científico</label>
            <input type="text" name="nome_cientifico" value="<?= htmlspecialchars($especie['nome_cientifico']) ?>" required>
          </div>
        </div>
        <div class="campo-duplo">
          <div>
            <label><i class="fa-solid fa-tree"></i> Família</label>
            <input type="text" name="familia" value="<?= htmlspecialchars($especie['familia']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-list"></i> Ordem</label>
            <input type="text" name="ordem" value="<?= htmlspecialchars($especie['ordem']) ?>" required>
          </div>
        </div>
      </div>

      <!-- Seção: Descrição e Comportamento -->
      <div class="form-section">
        <h3><i class="fa-solid fa-info-circle"></i> Descrição e Comportamento</h3>
        <div>
          <label><i class="fa-solid fa-align-left"></i> Descrição</label>
          <input type="text" name="descricao" value="<?= htmlspecialchars($especie['descricao']) ?>" required>
        </div>
        <br>
        <div>
          <label><i class="fa-solid fa-brain"></i> Comportamento</label>
          <input type="text" name="comportamento" value="<?= htmlspecialchars($especie['comportamento']) ?>" required>
        </div>
      </div>

      <!-- Seção: Características -->
      <div class="form-section">
        <h3><i class="fa-solid fa-cogs"></i> Características</h3>
        <div class="campo-triplo">
          <div>
            <label><i class="fa-solid fa-home"></i> Habitat</label>
            <input type="text" name="habitat" value="<?= htmlspecialchars($especie['habitat']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-globe"></i> Distribuição Geográfica</label>
            <input type="text" name="distribuicao_geografica" value="<?= htmlspecialchars($especie['distribuicao_geografica']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-utensils"></i> Alimentação</label>
            <input type="text" name="alimentacao" value="<?= htmlspecialchars($especie['alimentacao']) ?>" required>
          </div>
        </div>
        <div class="campo-triplo">
          <div>
            <label><i class="fa-solid fa-ruler"></i> Envergadura das Asas</label>
            <input type="text" name="envergadura_alas" value="<?= htmlspecialchars($especie['envergadura_alas']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-clock"></i> Ciclo de Vida</label>
            <input type="text" name="ciclo_vida" value="<?= htmlspecialchars($especie['ciclo_vida']) ?>" required>
          </div>
          <div>
            <label><i class="fa-solid fa-exclamation-triangle"></i> Status de Conservação</label>
            <input type="text" name="status_conservacao" value="<?= htmlspecialchars($especie['status_conservacao']) ?>" required>
          </div>
        </div>
      </div>

      <!-- Seção: Imagem -->
      <div class="form-section">
        <h3><i class="fa-solid fa-image"></i> Imagem Atual</h3>
        <img src="img/<?= $especie['imagem'] ?>" width="150" alt=""><br><br>
        <input type="file" name="imagem" class="form-control">
      </div>


      ' <!-- CONTAINER DOS BOTÕES -->
      <div class="botoes-form">
          <!-- Formulário de edição -->
          <form action="acoes.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="acao" value="editar">
              <input type="hidden" name="id" value="<?= $especie['id'] ?>">

              <button type="submit" class="botao-enviar">
                  <i class="fa-solid fa-save"></i> Salvar Alterações
              </button>
          </form>

          <!-- Formulário de exclusão -->
          <form action="acoes.php" method="post" onsubmit="return confirm('Deseja realmente excluir esta espécie?');">
              <input type="hidden" name="acao" value="excluir">
              <input type="hidden" name="id" value="<?= $especie['id'] ?>">

              <button type="submit" class="limpar-btn">
                  <i class="fa-solid fa-trash"></i> Excluir
              </button>
          </form>

          <!-- Botão de voltar -->
          <a href="edit_especie.php" class="voltar-btn">
              <i class="fa-solid fa-arrow-left"></i> Cancelar
          </a>
      </div>'

        </main>

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
                    <div class="artigo">
                        <img src="img/<?= $esp['imagem'] ?>" class="artigo-img" alt="Imagem da espécie" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="artigo-titulo"><?= htmlspecialchars($esp['nome_comum']) ?></h5>
                            <p class="info"><em><?= htmlspecialchars($esp['nome_cientifico']) ?></em></p>
                            <div class="botoes-form">  
                            <a class="botao" href="edit_especie.php?id=<?= $esp['id'] ?>"> Editar</a>
                              <form action="acoes.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir esta espécie?');">
                                  <input type="hidden" name="acao" value="excluir">
                                  <input type="hidden" name="id" value="<?= $esp['id'] ?>">
                                  <button type="submit" class="limpar-btn"><i class="bi bi-trash"></i> Excluir</button>
                              </form>
                            </div>
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
