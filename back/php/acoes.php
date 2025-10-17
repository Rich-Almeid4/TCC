<?php
session_start();
require 'conecta.php';

// ============================================
// GERENCIAMENTO DE USUÁRIOS
// ============================================

// INSERT - Cadastrar novo usuário
if (isset($_POST['criar'])) {
    $nome  = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $imag = mysqli_real_escape_string($conn, trim($_POST['imagem']));

    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = 'Preencha todos os campos!';
        header('Location: cadastro.php');
        exit;
    }

    $tipo = isset($_POST['tipo']) && ($_POST['tipo'] === 'admin') ? 'admin' : 'comum';

    $sql = "INSERT INTO usuario (nome, email, senha, tipo, imagem) VALUES ('$nome', '$email', '$senha', '$tipo', '$imag')";
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

// UPDATE - Atualizar perfil (admin)
if (isset($_POST['update'])) {
    $usuario_id = mysqli_real_escape_string($conn, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

    $update_imagem = "";

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . strtolower($ext);
        $destino = "img/" . $novo_nome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $update_imagem = ", imagem = '$novo_nome'";
        }
    }

    $sql = "UPDATE usuario SET 
                nome = '$nome', 
                senha = '$senha', 
                email = '$email'
                $update_imagem
            WHERE id = '$usuario_id'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Perfil atualizado com sucesso!';
        header('Location: admin.php');
        exit;
    } elseif (mysqli_affected_rows($conn) === 0) {
        $_SESSION['mensagem'] = 'Nenhuma alteração realizada.';
        header('Location: admin.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar perfil: ' . mysqli_error($conn);
        header('Location: index.php');
        exit;
    }
}

// UPDATE - Atualizar perfil (usuário comum)
if (isset($_POST['update-comum'])) {
    $usuario_id = mysqli_real_escape_string($conn, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

    $update_imagem = "";

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . strtolower($ext);
        $destino = "img/" . $novo_nome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $update_imagem = ", imagem = '$novo_nome'";
        }
    }

    $sql = "UPDATE usuario SET 
                nome = '$nome', 
                senha = '$senha', 
                email = '$email'
                $update_imagem
            WHERE id = '$usuario_id'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Perfil atualizado com sucesso!';
        header('Location: index.php');
        exit;
    } elseif (mysqli_affected_rows($conn) === 0) {
        $_SESSION['mensagem'] = 'Nenhuma alteração realizada.';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar perfil: ' . mysqli_error($conn);
        header('Location: index.php');
        exit;
    }
}

// DELETE - Deletar usuário
if (isset($_POST['delete'])){
    $usuario_id = mysqli_real_escape_string($conn, $_POST['delete']);

    $sql = "DELETE from usuario WHERE id = '$usuario_id'";
    mysqli_query($conn, $sql);
    
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Deletado com sucesso'; 
        header('Location: admin.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao deletar'; 
        header('Location: admin.php');
        exit;
    }
}

// ============================================
// GERENCIAMENTO DE ESPÉCIES
// ============================================

// INSERT - Cadastrar espécie
if (isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    // Verificar se é admin
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
        $_SESSION['mensagem'] = "Acesso negado!";
        header("Location: login.php");
        exit;
    }
    
    $nome_comum = mysqli_real_escape_string($conn, $_POST['nome_comum']);
    $nome_cientifico = mysqli_real_escape_string($conn, $_POST['nome_cientifico']);
    $familia = mysqli_real_escape_string($conn, $_POST['familia']);
    $ordem = mysqli_real_escape_string($conn, $_POST['ordem']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $habitat = mysqli_real_escape_string($conn, $_POST['habitat']);
    $distribuicao_geografica = mysqli_real_escape_string($conn, $_POST['distribuicao_geografica']);
    $alimentacao = mysqli_real_escape_string($conn, $_POST['alimentacao']);
    $envergadura_alas = mysqli_real_escape_string($conn, $_POST['envergadura_alas']);
    $ciclo_vida = mysqli_real_escape_string($conn, $_POST['ciclo_vida']);
    $comportamento = mysqli_real_escape_string($conn, $_POST['comportamento']);
    $status_conservacao = mysqli_real_escape_string($conn, $_POST['status_conservacao']);

    $imagem = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $destino = "img/" . $novo_nome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $imagem = $novo_nome;
        }
    }

    $sql = "INSERT INTO especie (
                nome_comum, nome_cientifico, familia, ordem, descricao, habitat, 
                distribuicao_geografica, alimentacao, envergadura_alas, ciclo_vida, 
                comportamento, status_conservacao, imagem
            ) VALUES (
                '$nome_comum', '$nome_cientifico', '$familia', '$ordem', '$descricao',
                '$habitat', '$distribuicao_geografica', '$alimentacao', '$envergadura_alas',
                '$ciclo_vida', '$comportamento', '$status_conservacao', '$imagem'
            )";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Espécie cadastrada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar espécie: " . mysqli_error($conn);
    }

    header("Location: admin.php");
    exit;
}

// UPDATE - Editar espécie
if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = (int) $_POST['id'];
    $nome_comum = mysqli_real_escape_string($conn, $_POST['nome_comum']);
    $nome_cientifico = mysqli_real_escape_string($conn, $_POST['nome_cientifico']);
    $familia = mysqli_real_escape_string($conn, $_POST['familia']);
    $ordem = mysqli_real_escape_string($conn, $_POST['ordem']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $habitat = mysqli_real_escape_string($conn, $_POST['habitat']);
    $distribuicao_geografica = mysqli_real_escape_string($conn, $_POST['distribuicao_geografica']);
    $alimentacao = mysqli_real_escape_string($conn, $_POST['alimentacao']);
    $envergadura_alas = mysqli_real_escape_string($conn, $_POST['envergadura_alas']);
    $ciclo_vida = mysqli_real_escape_string($conn, $_POST['ciclo_vida']);
    $comportamento = mysqli_real_escape_string($conn, $_POST['comportamento']);
    $status_conservacao = mysqli_real_escape_string($conn, $_POST['status_conservacao']);

    $update_imagem = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $destino = "img/" . $novo_nome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $update_imagem = ", imagem = '$novo_nome'";
        }
    }

    $sql = "UPDATE especie SET 
                nome_comum = '$nome_comum',
                nome_cientifico = '$nome_cientifico',
                familia = '$familia',
                ordem = '$ordem',
                descricao = '$descricao',
                habitat = '$habitat',
                distribuicao_geografica = '$distribuicao_geografica',
                alimentacao = '$alimentacao',
                envergadura_alas = '$envergadura_alas',
                ciclo_vida = '$ciclo_vida',
                comportamento = '$comportamento',
                status_conservacao = '$status_conservacao'
                $update_imagem
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Espécie atualizada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar: " . mysqli_error($conn);
    }

    header("Location: especie.php");
    exit;
}

// DELETE - Excluir espécie
if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
    $id = (int) $_POST['id'];

    // Remove favoritos relacionados primeiro
    $sql_delete_favoritos = "DELETE FROM favorito WHERE id_especie = $id";
    mysqli_query($conn, $sql_delete_favoritos);

    $sql = "DELETE FROM especie WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Espécie excluída com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir: " . mysqli_error($conn);
    }

    header("Location: edit_especie.php");
    exit;
}

// ============================================
// SISTEMA DE FAVORITOS - ESPÉCIES (AJAX)
// ============================================

// Adicionar favorito (resposta JSON para AJAX)
if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar_favorito') {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        exit;
    }
    
    $usuario_id = $_SESSION['id'];
    $especie_id = mysqli_real_escape_string($conn, $_POST['id_especie']);
    
    // Verificar se já está favoritado
    $sql_check = "SELECT id FROM favorito WHERE id_usuario = '$usuario_id' AND id_especie = '$especie_id'";
    $query_check = mysqli_query($conn, $sql_check);
    
    if (mysqli_num_rows($query_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Já está nos favoritos']);
        exit;
    }
    
    $sql = "INSERT INTO favorito (id_usuario, id_especie) VALUES ('$usuario_id', '$especie_id')";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Adicionado aos favoritos']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar favorito']);
    }
    exit;
}

// Remover favorito (resposta JSON para AJAX)
if (isset($_POST['acao']) && $_POST['acao'] == 'remover_favorito') {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        exit;
    }
    
    $usuario_id = $_SESSION['id'];
    $especie_id = mysqli_real_escape_string($conn, $_POST['id_especie']);
    
    $sql = "DELETE FROM favorito WHERE id_usuario = '$usuario_id' AND id_especie = '$especie_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Removido dos favoritos']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover favorito']);
    }
    exit;
}

// Remover favorito da página de favoritos (POST tradicional)
if (isset($_POST['remover-favorito'])) {
    $favorito_id = mysqli_real_escape_string($conn, $_POST['id_favorito']);
    $usuario_id = $_SESSION['id'];
    
    $sql = "DELETE FROM favorito WHERE id = '$favorito_id' AND id_usuario = '$usuario_id'";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover favorito!";
    }
    
    header("Location: favoritos.php");
    exit;
}

// ============================================
// GERENCIAMENTO DE ARTIGOS
// ============================================

// INSERT - Cadastrar artigo
if (isset($_POST['upload_artigo'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);

    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $extensao_pdf = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));

        if ($extensao_pdf !== 'pdf') {
            $_SESSION['mensagem'] = 'Somente arquivos PDF são permitidos!';
            header("Location: upload_artigo.php");
            exit;
        }

        $novo_nome_pdf = uniqid() . '.' . $extensao_pdf;
        $destino_pdf = 'documentos/' . $novo_nome_pdf;

        if (!is_dir('documentos')) mkdir('documentos', 0777, true);
        if (!is_dir('capas')) mkdir('capas', 0777, true);

        $destino_capa = null;
        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $extensao_img = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $nome_capa = uniqid() . '.' . $extensao_img;
            $destino_capa = 'capas/' . $nome_capa;

            move_uploaded_file($_FILES['capa']['tmp_name'], $destino_capa);
        }

        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino_pdf)) {
            $sql = "INSERT INTO artigo (titulo, autor, caminho_arquivo, capa) 
                    VALUES ('$titulo', '$autor', '$destino_pdf', " . 
                    ($destino_capa ? "'$destino_capa'" : "NULL") . ")";
            
            if (mysqli_query($conn, $sql)) {
                $_SESSION['mensagem'] = 'Artigo enviado com sucesso!';
                header("Location: admin.php");
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao salvar no banco de dados: ' . mysqli_error($conn);
                header("Location: upload_artigo.php");
                exit;
            }
        } else {
            $_SESSION['mensagem'] = 'Erro ao mover o arquivo PDF!';
            header("Location: upload_artigo.php");
            exit;
        }
    } else {
        $_SESSION['mensagem'] = 'Nenhum arquivo PDF enviado!';
        header("Location: upload_artigo.php");
        exit;
    }
}

// DELETE - Excluir artigo
if (isset($_POST['acao']) && $_POST['acao'] === 'excluir_artigo') {
    $id = (int) $_POST['id'];

    // Remove favoritos relacionados primeiro
    $sql_delete_favoritos = "DELETE FROM favorito_artigo WHERE id_artigo = $id";
    mysqli_query($conn, $sql_delete_favoritos);

    $sql = "DELETE FROM artigo WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Artigo excluído com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir artigo: " . mysqli_error($conn);
    }

    header("Location: edit_artigo.php");
    exit;
}

// ============================================
// SISTEMA DE FAVORITOS - ARTIGOS
// ============================================

// Favoritar artigo
if (isset($_POST['favoritar_artigo'])) {
    if (!isset($_SESSION['id'])) {
        $_SESSION['mensagem'] = "Você precisa estar logado para favoritar artigos.";
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION['id'];
    $id_artigo = (int) $_POST['id_artigo'];

    $check = mysqli_query($conn, "SELECT * FROM favorito_artigo WHERE id_usuario = '$id_usuario' AND id_artigo = '$id_artigo'");
    if (mysqli_num_rows($check) == 0) {
        $sql = "INSERT INTO favorito_artigo (id_usuario, id_artigo) VALUES ('$id_usuario', '$id_artigo')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Artigo adicionado aos favoritos!";
        } else {
            $_SESSION['mensagem'] = "Erro ao favoritar artigo: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['mensagem'] = "Esse artigo já está nos seus favoritos!";
    }

    header("Location: artigos.php");
    exit;
}

// Remover favorito de artigo
if (isset($_POST['remover-favorito-artigo'])) {
    $id_favorito = (int) $_POST['id_favorito'];
    $id_usuario = $_SESSION['id'];

    $sql = "DELETE FROM favorito_artigo WHERE id = '$id_favorito' AND id_usuario = '$id_usuario'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Artigo removido dos favoritos!";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover artigo: " . mysqli_error($conn);
    }

    header("Location: favoritos.php");
    exit;
}

// ============================================
// FALLBACK - Ação inválida
// ============================================

$_SESSION['mensagem'] = "Ação inválida!";
header("Location: index.php");
exit;
?>
