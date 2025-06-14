CREATE DATABASE peti;

USE peti;

CREATE TABLE organizacao ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    missao TEXT, 
    visao TEXT 
);

CREATE TABLE projetos ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nome VARCHAR(100), 
    responsavel VARCHAR(100), 
    custo DECIMAL(10,2), 
    prazo DATE
);

CREATE TABLE objetivos ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT,
    titulo VARCHAR(100),
    tipo ENUM('Organizacional', 'TI'), 
    descricao TEXT,
    FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE CASCADE
);

-- Inserir dados de exemplo
INSERT INTO projetos (nome, responsavel, custo, prazo) VALUES ('teste', 'teste', 2222.00, '1981-05-08');
INSERT INTO objetivos (projeto_id, titulo, tipo, descricao) VALUES (1, 'fsdfsd', 'TI', 'dasdasd');