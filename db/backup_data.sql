-- BACKUP AUTOMÁTICO - DEVPLAY
<<<<<<< HEAD
-- Gerado em: 2026-04-16 03:20:47
=======
-- Gerado em: 2026-04-17 00:39:20
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb

CREATE DATABASE IF NOT EXISTS devplay_db;
USE devplay_db;

DROP TABLE IF EXISTS jogos;

CREATE TABLE IF NOT EXISTS jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    imagem TEXT NOT NULL,
    tipo ENUM('internal', 'external') NOT NULL DEFAULT 'internal',
    url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO jogos (id, titulo, descricao, imagem, tipo, url, created_at) VALUES 
(1, 'Scratch', 'Aprenda lógica de programação com blocos visuais', 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 300 200\'><rect width=\'300\' height=\'200\' fill=\'%236366f1\'/><text x=\'150\' y=\'100\' text-anchor=\'middle\' fill=\'white\' font-size=\'20\' font-family=\'Arial\'>Scratch</text></svg>', 'external', 'https://scratch.mit.edu', '2026-03-05 22:25:31'),
(2, 'Quiz HTML', 'Teste seus conhecimentos sobre HTML básico', 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 300 200\'><rect width=\'300\' height=\'200\' fill=\'%238b5cf6\'/><text x=\'150\' y=\'100\' text-anchor=\'middle\' fill=\'white\' font-size=\'16\' font-family=\'Arial\'>Quiz HTML</text></svg>', 'internal', 'games/quiz-html/index.html', '2026-03-05 22:25:31'),
(3, 'Codecademy', 'Cursos interativos de programação', 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 300 200\'><rect width=\'300\' height=\'200\' fill=\'%2306b6d4\'/><text x=\'150\' y=\'100\' text-anchor=\'middle\' fill=\'white\' font-size=\'14\' font-family=\'Arial\'>Codecademy</text></svg>', 'external', 'https://www.codecademy.com', '2026-03-05 22:25:31'),
(5, 'codedex', 'test', 'https://firebasestorage.googleapis.com/v0/b/codedex-io.appspot.com/o/assets%2Flandingpage%2Fv1video.mp4?alt=media&token=c13c0b56-148e-4b89-85ec-00d40b8539dc', 'external', 'https://www.codedex.io/', '2026-03-05 23:36:10'),
<<<<<<< HEAD
(6, 'CodinGame', 'CodinGame', 'https://static.codingame.com/start/static/race-desktop-187dfae7746432be665518586f006ba6.mp4', 'external', 'https://www.codingame.com/start/', '2026-04-15 22:09:38');
=======
(6, 'CodinGame', 'CodinGame', 'https://static.codingame.com/start/static/race-desktop-187dfae7746432be665518586f006ba6.mp4', 'external', 'https://www.codingame.com/start/', '2026-04-15 22:09:38'),
(7, 'CodeCombat', 'codecombat', 'https://www.youtube.com/watch?v=dh05qw-AN-U', 'internal', 'https://codecombat.com/', '2026-04-16 19:39:19');
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb

DROP TABLE IF EXISTS clientes;

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO clientes (id, nome, email, senha, data_cadastro) VALUES 
(1, 'Kurt', 'erickcostamunhoz@gmail.com', '$2y$10$WSG6lbTZxhen0u6g7STFMeyCHnptyigCPJpg407eKN9RyIeXPrD4q', '2026-04-15 20:00:59');

