<?php
// =============================================
// PRODUTO.PHP - Detalhe do Produto - Luk Ideal
// Exibe produto individual, tags (N:N), frete, etc.
// =============================================
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Validação antes de acessar o banco
if ($id <= 0) {
    header('Location: produtos.php');
    exit;
}

require_once '../config/database.php';
require_once '../includes/functions.php';

$produto = getProdutoPorId($conn, $id);

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

$tituloPagina = htmlspecialchars($produto['nome']);
$breadcrumb = [
    ['label' => 'Produtos', 'url' => 'produtos.php', 'ativo' => false],
    ['label' => $produto['nome'], 'url' => '', 'ativo' => true],
];

require_once '../includes/header.php';

// Cálculo de frete (função com parâmetros e retorno - Tech Forge)
$freteInfo = calcularFrete((float)$produto['preco'], 'SP');

// Produtos relacionados (mesma categoria)
$produtosRelacionados = getProdutos($conn, (int)$produto['categoria_id']);
// Remove o produto atual da lista usando FOREACH + IF
$relacionados = [];
foreach ($produtosRelacionados as $rel) {
    if ($rel['id'] != $produto['id']) {
        $relacionados[] = $rel;
    }
}
$relacionados = array_slice($relacionados, 0, 4);

// Percentual de desconto
$desconto = 0;
if (!empty($produto['preco_promocional'])) {
    $desconto = calcularDesconto($produto['preco'], $produto['preco_promocional']);
}
?>

<!-- ==================== DETALHE DO PRODUTO ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-start">

            <!-- Imagem do Produto -->
            <div class="col-12 col-md-5">
                <div class="produto-detalhe-img d-flex align-items-center justify-content-center rounded-3 shadow">
                    <i class="bi bi-bag-heart display-1 text-muted opacity-40"></i>
                </div>

                <!-- Thumbnails (Bootstrap: Button Group) -->
                <div class="d-flex gap-2 mt-3 justify-content-center">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="thumb-mini rounded border-2 border d-flex align-items-center justify-content-center">
                        <i class="bi bi-image text-muted small"></i>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Informações do Produto -->
            <div class="col-12 col-md-7">
                <!-- Categoria Badge -->
                <span class="badge bg-warning text-dark mb-2 px-3 py-2">
                    <i class="bi bi-tag me-1"></i><?= htmlspecialchars($produto['categoria_nome']) ?>
                </span>

                <h1 class="fw-bold mb-2"><?= htmlspecialchars($produto['nome']) ?></h1>

                <!-- Tags (relação N:N) -->
                <?php if (!empty($produto['tags'])): ?>
                <div class="mb-3 d-flex flex-wrap gap-1">
                    <?php foreach ($produto['tags'] as $tag): ?>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-hash me-1"></i><?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Preço -->
                <div class="preco-box mb-4 p-3 bg-light rounded-3">
                    <?php if (!empty($produto['preco_promocional'])): ?>
                        <del class="text-muted fs-5"><?= formatarMoeda($produto['preco']) ?></del>
                        <div class="d-flex align-items-center gap-3">
                            <span class="display-6 fw-bold text-danger"><?= formatarMoeda($produto['preco_promocional']) ?></span>
                            <span class="badge bg-danger fs-6">-<?= $desconto ?>% OFF</span>
                        </div>
                        <small class="text-success">
                            <i class="bi bi-piggy-bank me-1"></i>
                            Economia de <?= formatarMoeda($produto['preco'] - $produto['preco_promocional']) ?>!
                        </small>
                    <?php else: ?>
                        <span class="display-6 fw-bold text-dark"><?= formatarMoeda($produto['preco']) ?></span>
                    <?php endif; ?>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-credit-card me-1"></i>
                            ou em até <strong>6x sem juros</strong> no cartão
                        </small>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Descrição do Produto</h6>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
                </div>

                <!-- Tamanhos (Array de tamanhos - Tech Forge) -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Tamanho</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php foreach ($TAMANHOS_DISPONIVEIS as $tam): ?>
                            <button class="btn btn-outline-dark btn-sm tamanho-btn" data-tam="<?= $tam ?>">
                                <?= $tam ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Estoque -->
                <div class="mb-4">
                    <?php if ($produto['estoque'] > 10): ?>
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Em estoque (<?= $produto['estoque'] ?> unidades)
                        </span>
                    <?php elseif ($produto['estoque'] > 0): ?>
                        <span class="badge bg-warning text-dark px-3 py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>Últimas <?= $produto['estoque'] ?> unidades!
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i>Produto esgotado
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Frete (usando resultado da função calcularFrete) -->
                <div class="frete-box mb-4 p-3 border rounded-3 bg-success-subtle">
                    <h6 class="fw-bold mb-1"><i class="bi bi-truck me-2 text-success"></i>Frete para SP:</h6>
                    <?php if ($freteInfo['valor'] == 0): ?>
                        <span class="text-success fw-bold fs-5">✅ <?= $freteInfo['mensagem'] ?></span>
                    <?php else: ?>
                        <span class="fw-bold"><?= formatarMoeda($freteInfo['valor']) ?></span>
                        <small class="text-muted ms-2"><?= $freteInfo['mensagem'] ?></small>
                    <?php endif; ?>
                    <br><small class="text-muted">Compras acima de R$ 299 têm frete grátis!</small>
                </div>

                <!-- Botões de Ação -->
                <?php if ($produto['estoque'] > 0): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <button class="btn btn-dark btn-lg px-5 fw-bold" onclick="adicionarCarrinho(<?= $produto['id'] ?>)">
                            <i class="bi bi-bag-plus me-2"></i>Adicionar ao Carrinho
                        </button>
                        <button class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg px-5" disabled>
                        <i class="bi bi-x-circle me-2"></i>Produto Esgotado
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PRODUTOS RELACIONADOS ==================== -->
<?php if (!empty($relacionados)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4">Produtos <span class="text-warning">Relacionados</span></h3>
        <div class="row g-4">
            <?php foreach ($relacionados as $rel): ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card produto-card h-100 border-0 shadow-sm">
                    <div class="produto-img-wrapper">
                        <div class="produto-placeholder-img d-flex align-items-center justify-content-center">
                            <i class="bi bi-bag-heart fs-1 text-muted opacity-40"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold"><?= htmlspecialchars($rel['nome']) ?></h6>
                        <?php if (!empty($rel['preco_promocional'])): ?>
                            <span class="text-danger fw-bold"><?= formatarMoeda($rel['preco_promocional']) ?></span>
                        <?php else: ?>
                            <span class="fw-bold"><?= formatarMoeda($rel['preco']) ?></span>
                        <?php endif; ?>
                        <div class="mt-2">
                            <a href="produto.php?id=<?= $rel['id'] ?>" class="btn btn-dark btn-sm w-100">
                                Ver Produto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
