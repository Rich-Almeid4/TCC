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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
</head>
<body>
     <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Categoria</th>
        </tr>
        <tbody>
            <?php
            $sql = 'SELECT * FROM usuario';
            $usuario = mysqli_query($conn, $sql);
            if (mysqli_num_rows($usuario) > 0) {
                foreach($usuario as $usuario) {
            ?>
            <tr>
            <td><?=$usuario['nome']?></td>
            <td><?=$usuario['email']?></td>
            <td><?=$usuario['tipo']?></td>
            <td>
            <form action="acoes.php" method="POST">
                  <button onclick="return confirm('Tem certeza que deseja excluir esse item?')"type="submit" name="delete" value="<?=$usuario['id'];?>">Excluir</button>
            </form>
            </td>
            </tr>
            <?php
                }
            }else{
                    echo '<h5><center>Nenhum produto cadastrado.</center></h5>';
                }?>
            </tbody>
    </thead>
</table>
           <a href="admin.php">Voltar</a>           
</body>
</html>