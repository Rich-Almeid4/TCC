<?php
session_start();
require 'conecta.php';

$nome  = filter_input(INPUT_POST, 'nome');
$senha = filter_input(INPUT_POST, 'senha');

// Busca usuário pelo nome e senha
$stmt = $conn->prepare("SELECT * FROM usuario WHERE nome = ? AND senha = ?");
$stmt->bind_param("ss", $nome, $senha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Salvar dados na sessão
    $_SESSION['id']   = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo']; // "admin" ou "comum"

    $_SESSION['mensagem'] = 'Logado com sucesso!';

    // Redirecionar conforme tipo
    if ($usuario['tipo'] === "admin") {
        header('Location: admin.php'); // página de admin
    } else {
        header('Location: index.php'); // página de usuário comum
    }
    exit;
} else {
    $_SESSION['mensagem'] = 'Usuário ou senha inválidos!';
    header('Location: login.php');
    exit;
}
