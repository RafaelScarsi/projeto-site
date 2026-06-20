<?php
// =============================================
// INDEX.PHP - Página Inicial - Luk Ideal
// =============================================
$tituloPagina = 'Início';
require_once 'includes/header.php';

// Busca produtos em destaque
$produtosDestaque = getProdutosDestaque($conn, 4);

// Calcula frete de exemplo (demonstração das funções)
$freteExemplo = calcularFrete(150.00, 'SP');
?>

<!-- ==================== CARROSSEL (Bootstrap: Carousel Component) ==================== -->
<div id="carrosselPrincipal" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carrosselPrincipal" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#carrosselPrincipal" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#carrosselPrincipal" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
            <div class="carousel-bg slide-1">
                <div class="carousel-overlay d-flex align-items-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2 fs-6">✨ Nova Coleção 2025</span>
                                <h1 class="display-4 fw-bold mb-3">Moda Feminina <br>com <span class="text-warning">Atitude</span></h1>
                                <p class="lead mb-4 opacity-90">Descubra os lançamentos da nossa coleção verão. Peças exclusivas para você brilhar!</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="pages/produtos.php?categoria=1" class="btn btn-warning btn-lg fw-bold px-4">
                                        <i class="bi bi-bag-heart me-2"></i>Ver Coleção
                                    </a>
                                    <a href="pages/produtos.php" class="btn btn-outline-light btn-lg px-4">
                                        <i class="bi bi-grid me-2"></i>Todos os Produtos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="carousel-bg slide-2">
                <div class="carousel-overlay d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-danger mb-3 px-3 py-2 fs-6">🔥 Promoção Especial</span>
                                <h1 class="display-4 fw-bold mb-3">Até <span class="text-warning">50% OFF</span> <br>em Selecionados</h1>
                                <p class="lead mb-4 opacity-90">Aproveite nossas promoções imperdíveis! Peças de qualidade com preços que cabem no bolso.</p>
                                <a href="pages/produtos.php?categoria=5" class="btn btn-danger btn-lg fw-bold px-4">
                                    <i class="bi bi-tag me-2"></i>Ver Promoções
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="carousel-bg slide-3">
                <div class="carousel-overlay d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-success mb-3 px-3 py-2 fs-6">👔 Moda Masculina</span>
                                <h1 class="display-4 fw-bold mb-3">Estilo <br>para <span class="text-warning">Todos</span></h1>
                                <p class="lead mb-4 opacity-90">Camisas, camisetas, bermudas e muito mais. Conforto e estilo para o dia a dia.</p>
                                <a href="pages/produtos.php?categoria=2" class="btn btn-success btn-lg fw-bold px-4">
                                    <i class="bi bi-person me-2"></i>Moda Masculina
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carrosselPrincipal" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carrosselPrincipal" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- ==================== BENEFÍCIOS (Bootstrap: Cards) ==================== -->
<section class="py-4 bg-warning-subtle">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="benefit-item p-3">
                    <i class="bi bi-truck fs-2 text-warning"></i>
                    <h6 class="fw-bold mt-2 mb-1">Frete Grátis</h6>
                    <small class="text-muted">Acima de R$ 299</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item p-3">
                    <i class="bi bi-shield-check fs-2 text-warning"></i>
                    <h6 class="fw-bold mt-2 mb-1">Compra Segura</h6>
                    <small class="text-muted">Site 100% protegido</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item p-3">
                    <i class="bi bi-arrow-return-left fs-2 text-warning"></i>
                    <h6 class="fw-bold mt-2 mb-1">Troca Fácil</h6>
                    <small class="text-muted">Em até 30 dias</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-item p-3">
                    <i class="bi bi-headset fs-2 text-warning"></i>
                    <h6 class="fw-bold mt-2 mb-1">Suporte 24h</h6>
                    <small class="text-muted">Atendimento rápido</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PRODUTOS EM DESTAQUE ==================== -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Produtos em <span class="text-warning">Destaque</span></h2>
                <p class="text-muted mb-0">As peças mais amadas da nossa coleção</p>
            </div>
            <a href="pages/produtos.php" class="btn btn-outline-dark btn-sm px-4">
                Ver todos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php if (!empty($produtosDestaque)): ?>
                <?php foreach ($produtosDestaque as $produto): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card produto-card h-100 border-0 shadow-sm">
                        <!-- Badge de promoção ou destaque -->
                        <div class="position-relative">
                            <div class="produto-img-wrapper">
                                <div class="produto-placeholder-img d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bag-heart fs-1 text-muted opacity-50"></i>
                                </div>
                            </div>
                            <?php if ($produto['preco_promocional']): ?>
                                <?php $desconto = calcularDesconto($produto['preco'], $produto['preco_promocional']); ?>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-2 py-1">
                                    -<?= $desconto ?>%
                                </span>
                            <?php endif; ?>
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                                <i class="bi bi-star-fill me-1"></i>Destaque
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted mb-1">
                                <i class="bi bi-tag me-1"></i><?= htmlspecialchars($produto['categoria_nome']) ?>
                            </small>
                            <h6 class="card-title fw-bold"><?= htmlspecialchars($produto['nome']) ?></h6>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars(truncarTexto($produto['descricao'], 80)) ?>
                            </p>
                            <div class="mt-auto">
                                <?php if ($produto['preco_promocional']): ?>
                                    <p class="mb-1">
                                        <del class="text-muted small"><?= formatarMoeda($produto['preco']) ?></del>
                                        <span class="fs-5 fw-bold text-danger ms-2"><?= formatarMoeda($produto['preco_promocional']) ?></span>
                                    </p>
                                <?php else: ?>
                                    <p class="fs-5 fw-bold text-dark mb-2"><?= formatarMoeda($produto['preco']) ?></p>
                                <?php endif; ?>
                                <a href="pages/produto.php?id=<?= $produto['id'] ?>" class="btn btn-dark w-100">
                                    <i class="bi bi-eye me-2"></i>Ver Produto
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>Nenhum produto em destaque no momento.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ==================== BANNER CATEGORIAS ==================== -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Explore por <span class="text-warning">Categoria</span></h2>
        <div class="row g-3">
            <?php
            $iconesCat = [
                'Feminino'    => ['icone' => 'bi-gender-female', 'cor' => 'cat-pink'],
                'Masculino'   => ['icone' => 'bi-gender-male',   'cor' => 'cat-blue'],
                'Infantil'    => ['icone' => 'bi-balloon-heart',  'cor' => 'cat-green'],
                'Acessórios'  => ['icone' => 'bi-bag',           'cor' => 'cat-orange'],
                'Promoções'   => ['icone' => 'bi-percent',       'cor' => 'cat-red'],
            ];
            foreach ($categorias as $cat):
                $config = $iconesCat[$cat['nome']] ?? ['icone' => 'bi-tag', 'cor' => 'cat-default'];
            ?>
            <div class="col-6 col-md-4 col-lg-2-4">
                <a href="pages/produtos.php?categoria=<?= $cat['id'] ?>" class="text-decoration-none">
                    <div class="categoria-card text-center p-4 rounded-3 h-100 <?= $config['cor'] ?>">
                        <i class="bi <?= $config['icone'] ?> fs-1 mb-2"></i>
                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($cat['nome']) ?></h6>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==================== BANNER FRETE GRÁTIS ==================== -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <i class="bi bi-truck fs-1 text-warning mb-3 d-block"></i>
        <h2 class="fw-bold mb-2">Frete Grátis nas compras acima de <span class="text-warning">R$ 299,00</span></h2>
        <p class="lead opacity-75 mb-4">Entregamos para todo o Brasil com segurança e rapidez!</p>
        <a href="pages/produtos.php" class="btn btn-warning btn-lg fw-bold px-5">
            <i class="bi bi-bag-heart me-2"></i>Aproveitar Agora
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
