<?php
session_start();
require 'conecta.php';

// INSERT-usuarios
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

// UPDATE-admin
if (isset($_POST['update'])) {
    $usuario_id = mysqli_real_escape_string($conn, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

    // Inicializa variável de atualização da imagem
    $update_imagem = "";

    // --- Upload da imagem ---
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . strtolower($ext);
        $destino = "img/" . $novo_nome;

        // Move o arquivo e adiciona ao SQL apenas se tudo der certo
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $update_imagem = ", imagem = '$novo_nome'";
        }
    }

    // --- Atualiza os dados ---
    $sql = "UPDATE usuario SET 
                nome = '$nome', 
                senha = '$senha', 
                email = '$email'
                $update_imagem
            WHERE id = '$usuario_id'";

    mysqli_query($conn, $sql);

    // --- Feedback e redirecionamento ---
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
    if (isset($_POST['update-comum'])) {
    // --- ATUALIZAR PERFIL (usuário comum) ---
if (isset($_POST['update-comum'])) {
    $usuario_id = mysqli_real_escape_string($conn, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

    // Inicializa variável de atualização da imagem
    $update_imagem = "";

    // --- Upload da imagem ---
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . strtolower($ext);
        $destino = "img/" . $novo_nome;

        // Move o arquivo e adiciona ao SQL apenas se tudo der certo
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $update_imagem = ", imagem = '$novo_nome'";
        }
    }

    // --- Atualiza os dados ---
    $sql = "UPDATE usuario SET 
                nome = '$nome', 
                senha = '$senha', 
                email = '$email'
                $update_imagem
            WHERE id = '$usuario_id'";

    mysqli_query($conn, $sql);

    // --- Feedback e redirecionamento ---
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
    }
if (isset($_POST['delete'])){
    $usuario_id = mysqli_real_escape_string($conn, $_POST['delete']);

    $sql = "DELETE from usuario WHERE id = '$usuario_id'";
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn)>0) {
        $_SESSION['mensagem'] = 'Deletado com sucesso'; 
        header('Location: admin.php');
        exit;
    }else{
        $_SESSION['mensagem'] = 'Erro ao deletar'; 
        header('Location: admin.php');
        exit;
    }

}


// INSERT-borboletas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- CADASTRAR ESPÉCIE ---
    if (isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
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

        // --- Upload da imagem ---
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

    // --- EDITAR ESPÉCIE ---
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

        // Atualiza a imagem se o usuário enviar uma nova
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

    // --- EXCLUIR ESPÉCIE ---
    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $id = (int) $_POST['id'];

        $sql = "DELETE FROM especie WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Espécie excluída com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao excluir: " . mysqli_error($conn);
        }

        header("Location: especie.php");
        exit;
    }
}
// --- FAVORITAR ESPÉCIE ---
if (isset($_POST['favoritar'])) {
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION['id'])) {
        $_SESSION['mensagem'] = "Você precisa estar logado para favoritar.";
        header("Location: login.php");
        exit;
    }

    $id_usuario = $_SESSION['id'];
    $id_especie = (int) $_POST['id_especie'];

    // Evita duplicar favoritos
    $check = mysqli_query($conn, "SELECT * FROM favorito WHERE id_usuario = '$id_usuario' AND id_especie = '$id_especie'");
    if (mysqli_num_rows($check) == 0) {
        $sql = "INSERT INTO favorito (id_usuario, id_especie) VALUES ('$id_usuario', '$id_especie')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Espécie adicionada aos favoritos!";
        } else {
            $_SESSION['mensagem'] = "Erro ao favoritar: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['mensagem'] = "Essa espécie já está nos seus favoritos!";
    }

    header("Location: especie.php");
    exit;
}
// --- REMOVER FAVORITO ---
if (isset($_POST['remover-favorito'])) {
    session_start();
    $id_favorito = (int) $_POST['id_favorito'];
    $sql = "DELETE FROM favorito WHERE id = '$id_favorito' AND id_usuario = '" . $_SESSION['id'] . "'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Favorito removido com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover favorito: " . mysqli_error($conn);
    }
    header("Location: favoritos.php");
    exit;
}

// --- CADASTRAR ARTIGO ---
if (isset($_POST['upload_artigo'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);

    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

        // Aceita apenas PDF por segurança
        if (strtolower($extensao) !== 'pdf') {
            $_SESSION['mensagem'] = 'Somente arquivos PDF são permitidos!';
            header("Location: upload_artigo.php");
            exit;
        }

        $novo_nome = uniqid() . '.' . $extensao;
        $destino = 'documentos/' . $novo_nome;

        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino)) {
            $sql = "INSERT INTO artigo (titulo, autor, caminho_arquivo) VALUES ('$titulo', '$autor', '$destino')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['mensagem'] = 'Artigo enviado com sucesso!';
            } else {
                $_SESSION['mensagem'] = 'Erro ao salvar no banco de dados: ' . mysqli_error($conn);
            }
        } else {
            $_SESSION['mensagem'] = 'Erro ao mover o arquivo!';
        }
    } else {
        $_SESSION['mensagem'] = 'Nenhum arquivo enviado!';
    }

    header("Location: upload_artigo.php");
    exit;
}
