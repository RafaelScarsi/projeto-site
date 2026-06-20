<?php
// =============================================
// PRODUTOS.PHP - Listagem de Produtos - Luk Ideal
// Utiliza: FOREACH, IF, Bootstrap, DB, Arrays, Funções
// =============================================
$tituloPagina = 'Produtos';

$categoriaId = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$busca       = isset($_GET['busca'])    ? trim($_GET['busca'])    : '';
$precoMin    = isset($_GET['preco_min']) && is_numeric($_GET['preco_min']) ? (float)$_GET['preco_min'] : 0;
$precoMax    = isset($_GET['preco_max']) && is_numeric($_GET['preco_max']) ? (float)$_GET['preco_max'] : 0;

$breadcrumb = [
    ['label' => 'Produtos', 'url' => '', 'ativo' => true]
];

require_once '../includes/header.php';

// Busca produtos do banco (usa as funções de functions.php)
$produtos   = getProdutos($conn, $categoriaId, $busca);
$categorias = getCategorias($conn);

// Aplica filtro de preço usando a função do Tech Forge
if ($precoMin > 0 || $precoMax > 0) {
    $produtos = filtrarProdutosPorPreco($produtos, $precoMin, $precoMax);
}

// Se há busca, aplica também a pesquisa no array local (Tech Forge)
if (!empty($busca) && !empty($produtos)) {
    // Reaplica pesquisa no array já filtrado
    $produtos = pesquisarProdutosNoArray($produtos, $busca);
    // Remove duplicatas (a função getProdutos já filtrou no BD, mas garantimos consistência)
    $ids_vistos = [];
    $produtos_unicos = [];
    foreach ($produtos as $p) {
        if (!in_array($p['id'], $ids_vistos)) {
            $ids_vistos[]       = $p['id'];
            $produtos_unicos[] = $p;
        }
    }
    $produtos = $produtos_unicos;
}

// Nome da categoria atual para exibição
$nomeCategoria = '';
if ($categoriaId > 0) {
    foreach ($categorias as $c) {
        if ($c['id'] == $categoriaId) {
            $nomeCategoria = $c['nome'];
            break;
        }
    }
}
?>

<!-- ==================== CABEÇALHO DA PÁGINA ==================== -->
<section class="page-header py-4 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-1">
                    <i class="bi bi-grid me-2 text-warning"></i>
                    <?php if ($nomeCategoria): ?>
                        <?= htmlspecialchars($nomeCategoria) ?>
                    <?php elseif ($busca): ?>
                        Resultados para: <em>"<?= htmlspecialchars($busca) ?>"</em>
                    <?php else: ?>
                        Todos os Produtos
                    <?php endif; ?>
                </h1>
                <p class="mb-0 opacity-75">
                    <?= count($produtos) ?> produto(s) encontrado(s)
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="produtos.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-clockwise me-1"></i>Limpar Filtros
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CONTEÚDO PRINCIPAL ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- ====== SIDEBAR DE FILTROS ====== -->
            <div class="col-12 col-lg-3">
                <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-funnel me-2"></i>Filtros
                    </div>
                    <div class="card-body">
                        <form method="GET" action="produtos.php">

                            <!-- Campo de busca -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-search me-1"></i>Buscar
                                </label>
                                <input type="text" class="form-control"
                                       name="busca"
                                       value="<?= htmlspecialchars($busca) ?>"
                                       placeholder="Nome do produto...">
                            </div>

                            <!-- Categorias (Bootstrap: List Group Component) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tags me-1"></i>Categoria
                                </label>
                                <div class="list-group list-group-flush">
                                    <label class="list-group-item list-group-item-action d-flex gap-2 align-items-center cursor-pointer">
                                        <input type="radio" name="categoria" value="0"
                                               <?= $categoriaId == 0 ? 'checked' : '' ?>>
                                        <span>Todas as Categorias</span>
                                    </label>
                                    <?php foreach ($categorias as $cat): ?>
                                    <label class="list-group-item list-group-item-action d-flex gap-2 align-items-center cursor-pointer">
                                        <input type="radio" name="categoria" value="<?= $cat['id'] ?>"
                                               <?= $categoriaId == $cat['id'] ? 'checked' : '' ?>>
                                        <span><?= htmlspecialchars($cat['nome']) ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Faixa de preço -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-currency-dollar me-1"></i>Faixa de Preço
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm"
                                               name="preco_min" placeholder="Mín R$"
                                               value="<?= $precoMin > 0 ? $precoMin : '' ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm"
                                               name="preco_max" placeholder="Máx R$"
                                               value="<?= $precoMax > 0 ? $precoMax : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 fw-bold">
                                <i class="bi bi-funnel me-2"></i>Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ====== GRID DE PRODUTOS ====== -->
            <div class="col-12 col-lg-9">

                <?php if (empty($produtos)): ?>
                    <!-- Nenhum produto encontrado -->
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                        <h4 class="text-muted">Nenhum produto encontrado</h4>
                        <p class="text-muted">Tente outros termos ou limpe os filtros.</p>
                        <a href="produtos.php" class="btn btn-warning">
                            <i class="bi bi-arrow-clockwise me-2"></i>Ver todos os produtos
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <!-- FOREACH: Itera sobre os produtos do banco de dados -->
                        <?php foreach ($produtos as $produto): ?>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="card produto-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <!-- Imagem placeholder do produto -->
                                    <div class="produto-img-wrapper">
                                        <div class="produto-placeholder-img d-flex align-items-center justify-content-center">
                                            <i class="bi bi-bag-heart fs-1 text-muted opacity-40"></i>
                                        </div>
                                    </div>

                                    <!-- IF: Badge de promoção -->
                                    <?php if (!empty($produto['preco_promocional'])): ?>
                                        <?php $desc = calcularDesconto($produto['preco'], $produto['preco_promocional']); ?>
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                            -<?= $desc ?>% OFF
                                        </span>
                                    <?php endif; ?>

                                    <!-- IF: Badge de estoque baixo -->
                                    <?php if ($produto['estoque'] > 0 && $produto['estoque'] <= 5): ?>
                                        <span class="badge bg-warning text-dark position-absolute bottom-0 start-0 m-2">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Últimas unidades!
                                        </span>
                                    <?php elseif ($produto['estoque'] == 0): ?>
                                        <span class="badge bg-secondary position-absolute bottom-0 start-0 m-2">
                                            Esgotado
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted mb-1">
                                        <i class="bi bi-tag me-1"></i><?= htmlspecialchars($produto['categoria_nome']) ?>
                                    </small>
                                    <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($produto['nome']) ?></h6>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= htmlspecialchars(truncarTexto($produto['descricao'], 70)) ?>
                                    </p>

                                    <div class="mt-auto">
                                        <!-- IF/ELSE: Exibe preço promocional ou normal -->
                                        <?php if (!empty($produto['preco_promocional'])): ?>
                                            <del class="text-muted small"><?= formatarMoeda($produto['preco']) ?></del>
                                            <div class="fs-5 fw-bold text-danger"><?= formatarMoeda($produto['preco_promocional']) ?></div>
                                        <?php else: ?>
                                            <div class="fs-5 fw-bold text-dark"><?= formatarMoeda($produto['preco']) ?></div>
                                        <?php endif; ?>

                                        <div class="d-flex gap-2 mt-2">
                                            <!-- IF: Botão desativado se estoque zero -->
                                            <?php if ($produto['estoque'] > 0): ?>
                                                <a href="produto.php?id=<?= $produto['id'] ?>"
                                                   class="btn btn-dark flex-grow-1 btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver Detalhes
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary flex-grow-1 btn-sm" disabled>
                                                    <i class="bi bi-x-circle me-1"></i>Esgotado
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div><!-- /.col produtos -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>

<?php require_once '../includes/footer.php'; ?>
