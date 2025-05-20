CREATE DATABASE peti;

USE peti;

CREATE TABLE organizacao ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    missao TEXT, 
    visao TEXT 
);

CREATE TABLE objetivos ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    tipo ENUM('Organizacional', 'TI'), 
    descricao TEXT 
);

CREATE TABLE projetos ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nome VARCHAR(100), 
    responsavel VARCHAR(100), 
    custo DECIMAL(10,2), 
    prazo DATE
);

CREATE TABLE projetos_objetivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT,
    objetivo_id INT,
    FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE CASCADE,
    FOREIGN KEY (objetivo_id) REFERENCES objetivos(id) ON DELETE CASCADE
);