<?php
// =============================================
// FUNÇÕES AUXILIARES - Luk Ideal
// Tech Forge: Arrays, Funções, Parâmetros, Retorno, Filtro, Validação
// =============================================

// -----------------------------------------------
// TECH FORGE - Armazenamento Estruturado com Arrays
// Dados organizados em arrays (sem variáveis soltas)
// -----------------------------------------------
$CONFIG_LOJA = [
    'nome'        => 'Luk Ideal',
    'slogan'      => 'Estilo que combina com você',
    'telefone'    => '(44) 99859-9363',
    'email'       => 'lukideal@gmail.com',
    'endereco'    => 'Rua da Moda, 100 – Centro',
    'cidade'      => 'Mamborê/PR',
    'horario'     => 'Seg-Sex: 9h-18h | Sáb: 9h-14h',
    'redes'       => [
        'instagram' => '@lukideal',
        'facebook'  => 'facebook.com/lukideal',
        'whatsapp'  => '(44) 99859-9363',
    ]
];

$TAMANHOS_DISPONIVEIS = ['PP', 'P', 'M', 'G', 'GG', 'XGG'];

$STATUS_PEDIDO = [
    'pendente'   => ['label' => 'Pendente',   'cor' => 'warning'],
    'confirmado' => ['label' => 'Confirmado', 'cor' => 'info'],
    'enviado'    => ['label' => 'Enviado',    'cor' => 'primary'],
    'entregue'   => ['label' => 'Entregue',   'cor' => 'success'],
    'cancelado'  => ['label' => 'Cancelado',  'cor' => 'danger'],
];

// -----------------------------------------------
// TECH FORGE - Modularização com Funções de Processamento
// Todas as funções recebem parâmetros e retornam valores
// -----------------------------------------------

/**
 * Formata valor monetário para BRL
 * @param float $valor Valor numérico
 * @return string Valor formatado em R$
 */
function formatarMoeda($valor) {
    if (!is_numeric($valor)) return 'R$ 0,00';
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

/**
 * Calcula o percentual de desconto entre dois preços
 * @param float $precoOriginal Preço original do produto
 * @param float $precoPromocional Preço com desconto
 * @return int Percentual de desconto (0 se inválido)
 */
function calcularDesconto($precoOriginal, $precoPromocional) {
    // Validação de Regras de Negócio com Condicionais (Tech Forge)
    if (!is_numeric($precoOriginal) || !is_numeric($precoPromocional)) return 0;
    if ($precoOriginal <= 0 || $precoPromocional <= 0) return 0;
    if ($precoPromocional >= $precoOriginal) return 0;

    $desconto = (($precoOriginal - $precoPromocional) / $precoOriginal) * 100;
    return (int)round($desconto);
}

/**
 * Calcula o frete estimado baseado no valor do pedido
 * @param float $totalPedido Total do pedido em reais
 * @param string $estado UF do destinatário
 * @return array Array com valor do frete e prazo
 */
function calcularFrete($totalPedido, $estado = 'SP') {
    // Validação: array vazio ou valores negativos (Tech Forge)
    if (!is_numeric($totalPedido) || $totalPedido < 0) {
        return ['valor' => 0, 'prazo' => 0, 'mensagem' => 'Valor inválido'];
    }

    // Frete grátis acima de R$ 299
    if ($totalPedido >= 299.00) {
        return ['valor' => 0.00, 'prazo' => 5, 'mensagem' => 'Frete Grátis!'];
    }

    // Tabela de fretes por estado (Array estruturado)
    $tabelaFrete = [
        'SP' => ['valor' => 15.90, 'prazo' => 3],
        'RJ' => ['valor' => 18.90, 'prazo' => 4],
        'MG' => ['valor' => 17.90, 'prazo' => 4],
        'RS' => ['valor' => 22.90, 'prazo' => 6],
        'SC' => ['valor' => 21.90, 'prazo' => 5],
        'PR' => ['valor' => 20.90, 'prazo' => 5],
    ];

    // Lógica de busca no array (Tech Forge)
    if (isset($tabelaFrete[$estado])) {
        $frete = $tabelaFrete[$estado];
        $frete['mensagem'] = "Entrega em até {$frete['prazo']} dias úteis";
        return $frete;
    }

    return ['valor' => 29.90, 'prazo' => 8, 'mensagem' => 'Entrega em até 8 dias úteis'];
}

/**
 * Aplica desconto percentual a um valor
 * @param float $valor Valor original
 * @param int   $percentual Percentual de desconto (1-100)
 * @return float Valor com desconto aplicado
 */
function aplicarDesconto($valor, $percentual) {
    if (!is_numeric($valor) || $valor <= 0) return 0;
    if (!is_numeric($percentual) || $percentual <= 0 || $percentual > 100) return $valor;
    return round($valor - ($valor * $percentual / 100), 2);
}

/**
 * Filtra array de produtos por faixa de preço (Tech Forge - Lógica de Filtro)
 * @param array  $produtos   Array de produtos
 * @param float  $precoMin   Preço mínimo
 * @param float  $precoMax   Preço máximo (0 = sem limite)
 * @return array Produtos filtrados
 */
function filtrarProdutosPorPreco($produtos, $precoMin = 0, $precoMax = 0) {
    // Validação: array vazio (Tech Forge)
    if (empty($produtos)) return [];
    if (!is_numeric($precoMin) || $precoMin < 0) return $produtos;

    $filtrados = [];
    foreach ($produtos as $produto) {
        $preco = (float)$produto['preco'];
        $dentroDoMinimo = ($preco >= $precoMin);
        $dentroDoMaximo = ($precoMax <= 0 || $preco <= $precoMax);

        if ($dentroDoMinimo && $dentroDoMaximo) {
            $filtrados[] = $produto;
        }
    }
    return $filtrados;
}

/**
 * Pesquisa um produto pelo nome dentro de um array (Tech Forge - Lógica de Pesquisa)
 * @param array  $produtos Array de produtos
 * @param string $busca    Termo de busca
 * @return array Produtos encontrados
 */
function pesquisarProdutosNoArray($produtos, $busca) {
    // Validação: array vazio ou busca inválida (Tech Forge)
    if (empty($produtos) || empty(trim($busca))) return [];

    $busca = strtolower(trim($busca));
    $resultado = [];

    foreach ($produtos as $produto) {
        $nome = strtolower($produto['nome']);
        $descricao = strtolower($produto['descricao'] ?? '');

        if (strpos($nome, $busca) !== false || strpos($descricao, $busca) !== false) {
            $resultado[] = $produto;
        }
    }
    return $resultado;
}

/**
 * Valida e-mail com regras de negócio
 * @param string $email E-mail a validar
 * @return array ['valido' => bool, 'mensagem' => string]
 */
function validarEmail($email) {
    if (empty(trim($email))) {
        return ['valido' => false, 'mensagem' => 'E-mail não pode ser vazio.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valido' => false, 'mensagem' => 'Formato de e-mail inválido.'];
    }
    return ['valido' => true, 'mensagem' => 'E-mail válido.'];
}

/**
 * Busca produtos em destaque no banco de dados
 * @param mysqli $conn  Conexão com o banco
 * @param int    $limite Número máximo de produtos
 * @return array Lista de produtos em destaque
 */
function getProdutosDestaque($conn, $limite = 4) {
    if ($limite <= 0) return [];

    $stmt = $conn->prepare("
        SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        WHERE p.destaque = 1 AND p.ativo = 1
        LIMIT ?
    ");
    $stmt->bind_param('i', $limite);
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
    $stmt->close();
    return $produtos;
}

/**
 * Busca todos os produtos com filtro opcional de categoria
 * @param mysqli $conn        Conexão com o banco
 * @param int    $categoriaId ID da categoria (0 = todas)
 * @param string $busca       Termo de busca
 * @return array Lista de produtos
 */
function getProdutos($conn, $categoriaId = 0, $busca = '') {
    $sql = "SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            WHERE p.ativo = 1";

    $params = [];
    $types  = '';

    if ($categoriaId > 0) {
        $sql   .= " AND p.categoria_id = ?";
        $params[] = $categoriaId;
        $types   .= 'i';
    }

    if (!empty(trim($busca))) {
        $termoBusca = '%' . trim($busca) . '%';
        $sql    .= " AND (p.nome LIKE ? OR p.descricao LIKE ?)";
        $params[] = $termoBusca;
        $params[] = $termoBusca;
        $types   .= 'ss';
    }

    $sql .= " ORDER BY p.destaque DESC, p.criado_em DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
    $stmt->close();
    return $produtos;
}

/**
 * Busca todas as categorias
 * @param mysqli $conn Conexão com o banco
 * @return array Lista de categorias
 */
function getCategorias($conn) {
    $result = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");
    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    return $categorias;
}

/**
 * Busca produto por ID com suas tags
 * @param mysqli $conn Conexão com o banco
 * @param int    $id   ID do produto
 * @return array|null Produto ou null se não encontrado
 */
function getProdutoPorId($conn, $id) {
    if (!is_numeric($id) || $id <= 0) return null;

    $stmt = $conn->prepare("
        SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        INNER JOIN categorias c ON p.categoria_id = c.id
        WHERE p.id = ? AND p.ativo = 1
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result  = $stmt->get_result();
    $produto = $result->fetch_assoc();
    $stmt->close();

    if (!$produto) return null;

    // Busca tags do produto (relação N:N)
    $stmtTags = $conn->prepare("
        SELECT t.nome FROM tags t
        INNER JOIN produto_tags pt ON t.id = pt.tag_id
        WHERE pt.produto_id = ?
    ");
    $stmtTags->bind_param('i', $id);
    $stmtTags->execute();
    $resTags = $stmtTags->get_result();

    $produto['tags'] = [];
    while ($tag = $resTags->fetch_assoc()) {
        $produto['tags'][] = $tag['nome'];
    }
    $stmtTags->close();
    return $produto;
}

/**
 * Trunca texto ao tamanho definido
 * @param string $texto  Texto original
 * @param int    $limite Número máximo de caracteres
 * @return string Texto truncado
 */
function truncarTexto($texto, $limite = 100) {
    if (empty($texto) || $limite <= 0) return '';
    if (mb_strlen($texto) <= $limite) return $texto;
    return mb_substr($texto, 0, $limite) . '...';
}

/**
 * Gera URL segura para um slug de produto
 * @param string $texto Texto a converter
 * @return string Slug URL-safe
 */
function gerarSlug($texto) {
    $texto = mb_strtolower($texto, 'UTF-8');
    $de = ['á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç','ñ',' '];
    $para = ['a','a','a','a','e','e','i','o','o','o','u','u','c','n','-'];
    $texto = str_replace($de, $para, $texto);
    return preg_replace('/[^a-z0-9\-]/', '', $texto);
}
