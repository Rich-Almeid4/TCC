CREATE DATABASE arthropoda;
USE arthropoda;

CREATE TABLE usuario(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    imagem VARCHAR(255),
    tipo ENUM('comum','admin') DEFAULT 'comum',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE especie(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_comum VARCHAR(100) NOT NULL,
    nome_cientifico VARCHAR(150) NOT NULL,
    familia VARCHAR(100),
    ordem VARCHAR(100),
    descricao TEXT,
    habitat VARCHAR(200),
    distribuicao_geografica VARCHAR(200),
    alimentacao VARCHAR(200),
    envergadura_alas VARCHAR(50),
    ciclo_vida VARCHAR(150),
    comportamento TEXT,
    status_conservacao VARCHAR(100),
    imagem VARCHAR(255)
);

CREATE TABLE galeria_imagens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_especie INT NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    descricao VARCHAR(255),
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_especie) REFERENCES especie(id)
);


CREATE TABLE artigo(
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(100),
    data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE favorito(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_especie INT,
    id_artigo INT,
    data_salvo DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_especie) REFERENCES especie(id),
    FOREIGN KEY (id_artigo) REFERENCES artigo(id)
);

CREATE TABLE mensagem(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    texto TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

CREATE TABLE historico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    acao ENUM('login', 'logout', 'visualizou_especie', 'leu_artigo', 'favoritou_especie', 'favoritou_artigo', 'enviou_mensagem') NOT NULL,
    id_especie INT,
    id_artigo INT,
    id_mensagem INT,
    data_atividade DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_especie) REFERENCES especie(id),
    FOREIGN KEY (id_artigo) REFERENCES artigo(id),
    FOREIGN KEY (id_mensagem) REFERENCES mensagem(id)
);