<?php
session_start();
require 'conecta.php';

$nome = filter_input(INPUT_POST, 'nome');
$senha = filter_input(INPUT_POST, 'senha');

$_SESSION['nome'] = $nome;
$_SESSION['senha'] = $senha;

// Query usando placeholders de mysqli (?)
$stmt = $conn->prepare("SELECT * FROM usuario WHERE nome = ? AND senha = ?");
$stmt->bind_param("ss", $nome, $senha); // "ss" = 2 strings
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['mensagem'] = 'Logado com sucesso!';
    $_SESSION['nivel'] = 10;
    header('Location: index.php');
    exit;
} else {
    $_SESSION['mensagem'] = 'ERROR!';
    header('Location: login.php');
    exit;
}
