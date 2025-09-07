CREATE DATABASE tcc;
USE tcc;

CREATE TABLE usuario(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('comum','admin') DEFAULT 'comum',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE configuracoes(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    leitor_voz BOOLEAN DEFAULT FALSE,
    libras BOOLEAN DEFAULT FALSE,
    idioma VARCHAR(50) DEFAULT 'pt-BR',
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

CREATE TABLE especie(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_comum VARCHAR(100) NOT NULL,
    nome_cientifico VARCHAR(150) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255)
);

CREATE TABLE artigo(
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(100),
    data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE salvos(
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