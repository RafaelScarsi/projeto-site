-- =============================================
-- BANCO DE DADOS: Luk Ideal - Loja de Roupas
-- =============================================

CREATE DATABASE IF NOT EXISTS luk_ideal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE luk_ideal;

-- ---------------------------------------------
-- Tabela 1: CATEGORIAS
-- ---------------------------------------------
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Tabela 2: PRODUTOS
-- (FK: categoria_id -> categorias.id)
-- ---------------------------------------------
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    preco_promocional DECIMAL(10,2) DEFAULT NULL,
    estoque INT DEFAULT 0,
    imagem VARCHAR(255),
    destaque TINYINT(1) DEFAULT 0,
    ativo TINYINT(1) DEFAULT 1,
    categoria_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------
-- Tabela 3: CLIENTES
-- ---------------------------------------------
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Tabela 4: PEDIDOS
-- (FK: cliente_id -> clientes.id)
-- ---------------------------------------------
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    status ENUM('pendente','confirmado','enviado','entregue','cancelado') DEFAULT 'pendente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------
-- Tabela 5: TAGS (etiquetas para produtos)
-- ---------------------------------------------
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- ===============================================
-- RELACIONAMENTO N:N (Muitos para Muitos)
-- Tabela 6: PEDIDO_PRODUTOS
-- Um pedido pode ter muitos produtos
-- Um produto pode estar em muitos pedidos
-- ===============================================
CREATE TABLE pedido_produtos (
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (pedido_id, produto_id),
    CONSTRAINT fk_pp_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pp_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ===============================================
-- RELACIONAMENTO N:N (Muitos para Muitos)
-- Tabela 7: PRODUTO_TAGS
-- Um produto pode ter muitas tags
-- Uma tag pode pertencer a muitos produtos
-- ===============================================
CREATE TABLE produto_tags (
    produto_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (produto_id, tag_id),
    CONSTRAINT fk_pt_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pt_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- =============================================
-- DADOS DE EXEMPLO
-- =============================================

-- Categorias
INSERT INTO categorias (nome, descricao) VALUES
('Feminino', 'Roupas femininas modernas e elegantes'),
('Masculino', 'Roupas masculinas com estilo e conforto'),
('Infantil', 'Roupas infantis coloridas e confortáveis'),
('Acessórios', 'Bolsas, cintos, bonés e muito mais'),
('Promoções', 'Peças selecionadas com preços especiais');

-- Tags
INSERT INTO tags (nome) VALUES
('Novo'), ('Promoção'), ('Destaque'), ('Verão'), ('Inverno'), ('Casual'), ('Esportivo'), ('Elegante');

-- Produtos
INSERT INTO produtos (nome, descricao, preco, preco_promocional, estoque, destaque, categoria_id) VALUES
('Vestido Floral Midi', 'Vestido midi com estampa floral, tecido leve e fresco ideal para o verão.', 159.90, 129.90, 20, 1, 1),
('Blusa Cropped Listrada', 'Blusa cropped com listras coloridas, perfeita para looks casuais.', 79.90, NULL, 35, 0, 1),
('Calça Skinny Preta', 'Calça skinny básica preta, combina com tudo e é super versátil.', 129.90, 99.90, 15, 1, 1),
('Camisa Xadrez Masculina', 'Camisa xadrez clássica, 100% algodão, ideal para looks casuais e descontraídos.', 119.90, NULL, 25, 1, 2),
('Camiseta Básica Branca', 'Camiseta básica branca, 100% algodão, corte regular.', 49.90, NULL, 50, 0, 2),
('Bermuda Jeans Masculina', 'Bermuda jeans lavagem clara, confortável e estilosa.', 99.90, 79.90, 18, 0, 2),
('Conjunto Infantil Urso', 'Conjunto camiseta + short infantil com estampa de urso. Sizes 2-8 anos.', 89.90, NULL, 30, 1, 3),
('Vestido Infantil Princesa', 'Vestido infantil com renda, perfeito para festas e ocasiões especiais.', 119.90, 89.90, 12, 0, 3),
('Bolsa Feminina Couro', 'Bolsa feminina em couro sintético, tamanho médio com várias divisórias.', 189.90, NULL, 10, 1, 4),
('Cinto Masculino Couro', 'Cinto masculino em couro legítimo, fivela dourada.', 69.90, 49.90, 22, 0, 4),
('Blusa Feminina Verão', 'Blusa leve com decote v, perfeita para dias quentes.', 69.90, 39.90, 28, 1, 5),
('Camiseta Polo Masculina', 'Polo clássica masculina, disponível em várias cores.', 89.90, 59.90, 20, 0, 5);

-- Produto Tags
INSERT INTO produto_tags (produto_id, tag_id) VALUES
(1, 1), (1, 4), (1, 3),
(2, 6), (2, 4),
(3, 8), (3, 6),
(4, 6), (4, 3),
(5, 6), (5, 1),
(6, 4), (6, 7),
(7, 1), (7, 6),
(8, 8),
(9, 3), (9, 8),
(10, 6),
(11, 2), (11, 4),
(12, 2), (12, 6);

-- Clientes de exemplo
INSERT INTO clientes (nome, email, telefone, endereco) VALUES
('Ana Paula Silva', 'ana.silva@email.com', '(11) 99999-1111', 'Rua das Flores, 123 - São Paulo/SP'),
('João Carlos Souza', 'joao.souza@email.com', '(11) 99999-2222', 'Av. Central, 456 - São Paulo/SP'),
('Maria Fernanda Lima', 'maria.lima@email.com', '(11) 99999-3333', 'Rua dos Ipês, 789 - São Paulo/SP');

-- Pedidos de exemplo
INSERT INTO pedidos (cliente_id, status, total) VALUES
(1, 'entregue', 289.80),
(2, 'confirmado', 119.90),
(3, 'pendente', 189.90);

-- Itens dos pedidos (N:N entre pedidos e produtos)
INSERT INTO pedido_produtos (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1, 1, 1, 129.90),
(1, 3, 1, 99.90),
(1, 10, 1, 49.90),
(2, 4, 1, 119.90),
(3, 9, 1, 189.90);
