<?php
session_start();
require 'conecta.php';

// INSERT
if (isset($_POST['criar'])) {
    $nome  = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

   if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = 'Preencha todos os campos!';
        header('Location: cadastro.php');
        exit;
    }

    $tipo = isset($_POST['tipo']) && ($_POST['tipo'] === 'admin') ? 'admin' : 'comum';

    $sql = "INSERT INTO usuario (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '$tipo')";
    mysqli_query($conn, $sql);
   
 

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Usuário cadastrado!';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao cadastrar!';
        header('Location: cadastro.php');
        exit;
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $usuario_id = mysqli_real_escape_string($conn, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']) );
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']) );

$sql = "UPDATE usuario set nome = '$nome', senha = '$senha', email = '$email'  WHERE id = '$usuario_id'";
mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn)>0) {
    $_SESSION['mensagem'] = 'Mudanças feitas com sucesso!!!';
    header('Location: index.php');
    exit;
}else {
    $_SESSION['mensagem'] = 'Erro ao atualizar perfil';
    header('Location: index.php');
    exit;
}
}
