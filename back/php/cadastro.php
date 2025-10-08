<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script></head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
</head>
<header>
    <nav></nav>
</header>

<body>
<?php include('mensagem.php');?>
<form action="acoes.php" method="POST">
    <label for="email">Email:</label>
        <input autocomplete="off" type="email" id="email" name="email" placeholder="Email" require>
    <label for="senha">Senha:</label>
        <input autocomplete="off"id="senha"type="password" name="senha" id="" placeholder="Senha" require>
    <label for="nome">Usuário:</label>
        <input autocomplete="off"type="text" id="nome" name="nome" placeholder="Usuário" require>
     <!-- <label for="tipo">Tipo:</label>
      <select name="tipo" id="tipo">
      <option value=""></option>
      <option value="comum">comum</option>
      <option value="admin">admin</option>
    </select> -->
    <label for="imagem">Foto de perfil:</label>
    <input type="file" name="imagem"> 
<button type="submit" name="criar" class="">Cadastrar</button>
<a href="login.php">Login</a>
</form>
</body>
<footer>
   
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

</html>