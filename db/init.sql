CREATE DATABASE IF NOT EXISTS devplay_db;
USE devplay_db;

CREATE TABLE IF NOT EXISTS jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    imagem TEXT NOT NULL,
    tipo ENUM('internal', 'external') NOT NULL DEFAULT 'internal',
    url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir dados iniciais baseados no projeto original
INSERT INTO jogos (titulo, descricao, imagem, tipo, url) VALUES 
('Scratch', 'Aprenda lógica de programação com blocos visuais', 'data:image/svg+xml,<svg xmlns=''http://www.w3.org/2000/svg'' viewBox=''0 0 300 200''><rect width=''300'' height=''200'' fill=''%236366f1''/><text x=''150'' y=''100'' text-anchor=''middle'' fill=''white'' font-size=''20'' font-family=''Arial''>Scratch</text></svg>', 'external', 'https://scratch.mit.edu'),
('Quiz HTML', 'Teste seus conhecimentos sobre HTML básico', 'data:image/svg+xml,<svg xmlns=''http://www.w3.org/2000/svg'' viewBox=''0 0 300 200''><rect width=''300'' height=''200'' fill=''%238b5cf6''/><text x=''150'' y=''100'' text-anchor=''middle'' fill=''white'' font-size=''16'' font-family=''Arial''>Quiz HTML</text></svg>', 'internal', 'games/quiz-html/index.html'),
('Codecademy', 'Cursos interativos de programação', 'data:image/svg+xml,<svg xmlns=''http://www.w3.org/2000/svg'' viewBox=''0 0 300 200''><rect width=''300'' height=''200'' fill=''%2306b6d4''/><text x=''150'' y=''100'' text-anchor=''middle'' fill=''white'' font-size=''14'' font-family=''Arial''>Codecademy</text></svg>', 'external', 'https://www.codecademy.com');
