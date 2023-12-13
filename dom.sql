-- Eu Queria Ter O Dom
-- (c) 2021 Na Beatriz Vicente

-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2014 at 05:16 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP DATABASE IF EXISTS dom;

CREATE DATABASE dom;

USE dom;

CREATE TABLE tipo_produtos (
    ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Designacao VARCHAR(225) NOT NULL
);

CREATE TABLE produtos(
    ID 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Tipo_ID 		INT,
	Cor             Varchar (100),
	Designacao      Varchar (100),
	Descricao 		Varchar (1000),
	Preco 			DECIMAL(50,2) DEFAULT 0.0,
	EmStock			INT DEFAULT 1,
	EmDestaque		BOOL Default False,
	Img				Varchar (100),
	
	FOREIGN KEY (`Tipo_ID`)
       REFERENCES `tipo_produtos` (`ID`)
);

CREATE TABLE tipos_utilizador(
    ID          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Designacao  VARCHAR(50)
);

CREATE TABLE utilizadores (
	ID  			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Tipo_ID			INT,
	Nome 			Varchar(255),
	Apelido         Varchar(255),
	Email 			Varchar(255) NOT NULL,
	`Password` 		Varchar(255) NOT NULL,
	Telemovel       INT,
	NIF             INT,
	Morada			Varchar(255),
	CodPostal		Varchar(255),
	Localidade		Varchar(255),
	Porta           Varchar(255),
	EstadoConta     INT DEFAULT 0,
	token 			Varchar(50),
	
	FOREIGN KEY (Tipo_ID) REFERENCES tipos_utilizador(ID)
);

Create Table carrinho(
	ID 					INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Utilizadores_ID 	INT,
	`Data` 				DATE,
	PrecoTotal      	Decimal(50, 2) DEFAULT 0.00,
	Estado				INT NOT NULL,

	FOREIGN KEY (`Utilizadores_ID`)
		REFERENCES `utilizadores` (`ID`)
);

CREATE TABLE itenscarrinho(
	ID					INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	PrecoTotalProdutos 	DECIMAL(50, 2) DEFAULT 0.00,
	Produto_ID			INT,
	Quantidade			INT, 
	Carrinho_ID			INT,

	FOREIGN KEY (`Produto_ID`)
       REFERENCES `produtos` (`ID`),
	FOREIGN KEY (`Carrinho_ID`)
       REFERENCES `carrinho` (`ID`)
);

INSERT INTO tipo_produtos (Designacao) VALUES 
('Pulseira'),
('Brinco'),
('Colar'),
('Porta-chave'),
('T-shirt'),
('Camisola'),
('Roupa de Bebé'),
('Calças');

INSERT INTO produtos (Tipo_ID, Cor, 
	Designacao, Descricao, Preco, EmDestaque, Img) VALUES
(2, 'Branco','Brinco de missangas', 
	'Brinco de missangas com formato de Berimbau', 
	5, True, '1.jpg'),
(3, 'Castanho','Colar castanho', 
	'Colar com misangas castanhas, com um berimbau com castanho', 
	5, True, '2.jpg'),
(3, 'Castanho claro','Colar castanho claro', 
	'Colar com missangas castanhas claras, com um berimbau com castanho claro', 
	5, True, '3.jpg'), 
(1, 'Azul Escuro', 'Pulseira azul escura',
	'Pulseira azul escura com o símbolo de São Bento, padroeiro da capoeira',
	5, False, '4.jpg'), 
(1, 'Preto','Pulseiras preta e castanha',
	'Pulseira azul escura com o símbolo de São Bento, padroeiro da capoeira',
	5, False,'5.1.jpg'),
(4, 'Castanho','Porta-chaves',
	'Porta-chaves com formato de berimbau',
	5, False, '6.1.jpg'),
(2, 'Branco','Brinco de missangas', 
	'Brinco com formato de berimbau com missangas', 
	5, False, '7.jpg'),
(2, 'Branco','Brinco de missangas', 
	'Brinco com formato de berimbau com missangas', 
	5, False, '8.jpg'),
(3, 'Preto','Colar num saco', 
	'Colar colocado num saco com o nome da marca', 
	5, False, '9.jpg'),
(1, 'Preto','Pulseiras preta e castanha',
	'Pulseira azul escura com o símbolo de São Bento, padroeiro da capoeira',
	5, False,'10.1.jpg'),
(2, 'MultiColor','Brinco de missangas',
	'Brinco com formato de berimbau com missangas', 
	5, False,'11.jpg'),
(1, 'Preto e Branco','Pulseira de missangas',
	'Pulseira de missangas',
	5, False,'12.1.jpg'),
(1, 'Preto e Branco','Pulseira de missangas para casal',
	'Par de pulseiras para casais de missangas',
	5, False,'13.1.jpg'),
(1, 'Rosa','Pulseira de seda',
	'Pulseira de seda para com o símbolo do padroeiro da capoeira, São Bento',
	5, False,'14.jpg'),
(4, 'Preto e branco','Porta-chaves',
	'Porta-chaves',
	5, False,'15.jpg'),
(5, 'Preto','T-shirt com o logotipo',
	'T-shirt com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'16.jpg'),
(3, 'Branco e Preto','Colar preto', 
	'Colar simples de cabo preto', 
	5, True, '19.jpg'),
(1, 'Castanho Claro', 'Pulseira castanha clara',
	'Pulseira castanha clara com o símbolo de São Bento, padroeiro da capoeira',
	5, False, '20.jpg'),
(5, 'Branco','T-shirt com o logotipo',
	'T-shirt de alças com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'21.jpg'),
(6, 'Branca','Camisola com capuz',
	'Camisola com capuz e com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'22.jpg'),
(5, 'Branco','T-shirt com o logotipo',
	'T-shirt de alças com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'23.jpg'),
(6, 'Branca','Camisola com capuz',
	'Camisola com capuz e com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'24.jpg'),
(7, 'Branca','Body para bébé',
	'Body branco para bebé com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'25.jpg'),
(5, 'Preto','T-shirt com o logotipo',
	'T-shirt de alças com o logotipo da marca Eu Queria Ter O Dom',
	15, False,'26.jpg');

INSERT INTO tipos_utilizador (ID, Designacao) VALUES
(0, 'Admin'),
(1, 'Cliente');

INSERT INTO utilizadores (Tipo_ID, Nome, Apelido, Email, `Password`, Telemovel, NIF, 
	Morada, CodPostal, Localidade, Porta) VALUES
(1, 'Ana Beatriz', 'Vicente', 'vicente.anab@gmail.com', /*Pass: 123456*/ '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', '932698462', '123456789',
	'Quinta de Coenços, Cabouco, Ceira, Coimbra', '3030-850', 'Coimbra', ''),
(0, 'Administrador(a)', '', 'admin@euqueriaterodom.com', /*Pass: Admin2021*/'*34A58E44DC0FFF41D7D75E395E53B2187A4E521A', 0, 0,
	'', '', '', '');