<?php
session_start();

require 'conecta.php';

if (isset($_POST['criar'])) {
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']) );
    $email = mysqli_real_escape_string($conn, trim($_POST['email']) );
    $tipo = mysqli_real_escape_string($conn, trim($_POST['tipo']) );

$sql = "INSERT INTO usuario (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '$tipo')";
mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn)>0) {
     $_SESSION['mensagem'] = 'Usuário cadastrado!';
    header('Location: login.php');
    exit;
}else {
    $_SESSION['mensagem'] = 'ERROR!';
    header('Location: cadastro.php');
    exit;
}
}
