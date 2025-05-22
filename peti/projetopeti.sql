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
    tipo ENUM('Organizacional','TI'), 
    descricao TEXT 
);

CREATE TABLE projetos ( 
	id INT AUTO_INCREMENT PRIMARY KEY, 
    nome VARCHAR(100), 
    responsavel VARCHAR(100), 
    custo DECIMAL(10,2), 
    prazo DATE
);