CREATE DATABASE IF NOT EXISTS projeto_aws;

USE projeto_aws;

CREATE TABLE IF NOT EXISTS usuarios (
    matricula INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    arquivo TEXT NOT NULL,
    data_insercao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);