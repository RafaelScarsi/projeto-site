<?php
// =============================================
// CATEGORIAS.PHP - Luk Ideal
// =============================================
$tituloPagina = 'Categorias';
$breadcrumb = [
    ['label' => 'Categorias', 'url' => '', 'ativo' => true]
];
require_once '../includes/header.php';

// Busca categorias do banco de dados
$categorias = getCategorias($conn);

// Para cada categoria, busca o total de produtos (WHILE + consulta)
$catComProdutos = [];
foreach ($categorias as $cat) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM produtos WHERE categoria_id = ? AND ativo = 1");
    $stmt->bind_param('i', $cat['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cat['total_produtos'] = $res['total'];
    $stmt->close();
    $catComProdutos[] = $cat;
}

// Array com ícones e cores para as categorias (Tech Forge - Array estruturado)
$configCategorias = [
    'Feminino'   => ['icone' => 'bi-gender-female', 'cor_bg' => '#ffe0f0', 'cor_text' => '#c2185b', 'destaque' => 'Moda Feminina Moderna'],
    'Masculino'  => ['icone' => 'bi-gender-male',   'cor_bg' => '#e3f2fd', 'cor_text' => '#1565c0', 'destaque' => 'Estilo Masculino'],
    'Infantil'   => ['icone' => 'bi-balloon-heart',  'cor_bg' => '#e8f5e9', 'cor_text' => '#2e7d32', 'destaque' => 'Crianças Estilosas'],
    'Acessórios' => ['icone' => 'bi-bag',            'cor_bg' => '#fff3e0', 'cor_text' => '#e65100', 'destaque' => 'Complete o Look'],
    'Promoções'  => ['icone' => 'bi-percent',        'cor_bg' => '#fce4ec', 'cor_text' => '#b71c1c', 'destaque' => 'Preços Imperdíveis'],
];
?>

<!-- Cabeçalho -->
<section class="page-header py-4 bg-dark text-white">
    <div class="container">
        <h1 class="fw-bold mb-1"><i class="bi bi-tags me-2 text-warning"></i>Categorias</h1>
        <p class="mb-0 opacity-75"><?= count($catComProdutos) ?> categorias disponíveis</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <!-- Cards das Categorias (Bootstrap: Cards + Grid) -->
        <div class="row g-4 mb-5">
            <?php foreach ($catComProdutos as $cat):
                $cfg = $configCategorias[$cat['nome']] ?? [
                    'icone'    => 'bi-tag',
                    'cor_bg'   => '#f5f5f5',
                    'cor_text' => '#333',
                    'destaque' => 'Confira os produtos',
                ];
            ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm categoria-card-page overflow-hidden">
                    <!-- Header colorido -->
                    <div class="p-4 text-center" style="background-color: <?= $cfg['cor_bg'] ?>">
                        <i class="bi <?= $cfg['icone'] ?> display-4" style="color: <?= $cfg['cor_text'] ?>"></i>
                    </div>
                    <div class="card-body text-center">
                        <h4 class="fw-bold"><?= htmlspecialchars($cat['nome']) ?></h4>
                        <p class="text-muted small mb-2"><?= $cfg['destaque'] ?></p>
                        <p class="text-muted mb-3">
                            <?= htmlspecialchars(truncarTexto($cat['descricao'], 90)) ?>
                        </p>
                        <!-- IF: Exibe quantidade de produtos -->
                        <?php if ($cat['total_produtos'] > 0): ?>
                            <span class="badge bg-success mb-3">
                                <?= $cat['total_produtos'] ?> produto(s) disponível(is)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary mb-3">
                                Sem produtos no momento
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-4">
                        <a href="produtos.php?categoria=<?= $cat['id'] ?>"
                           class="btn fw-bold px-4"
                           style="background-color: <?= $cfg['cor_text'] ?>; color: white;">
                            <i class="bi bi-arrow-right-circle me-2"></i>Ver Produtos
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Accordion de FAQ sobre categorias (Bootstrap: Accordion Component) -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="fw-bold text-center mb-4">Dúvidas Frequentes</h3>
                <div class="accordion shadow-sm" id="accordionFAQ">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="bi bi-question-circle-fill text-warning me-2"></i>
                                Vocês aceitam troca ou devolução?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Sim! Aceitamos trocas e devoluções em até <strong>30 dias</strong> após a compra,
                                desde que a peça esteja em perfeito estado, com etiquetas e nota fiscal.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="bi bi-question-circle-fill text-warning me-2"></i>
                                Como funcionam os tamanhos?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Trabalhamos com os tamanhos PP, P, M, G, GG e XGG para adultos.
                                Para infantil, os tamanhos variam por idade (2 a 14 anos).
                                Consulte nossa tabela de medidas em cada produto.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="bi bi-question-circle-fill text-warning me-2"></i>
                                O frete é grátis?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Sim! Para compras acima de <strong>R$ 299,00</strong> o frete é gratuito para
                                todo o Brasil. Abaixo desse valor, o frete é calculado de acordo com o CEP de entrega.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq4">
                                <i class="bi bi-question-circle-fill text-warning me-2"></i>
                                Quais formas de pagamento são aceitas?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Aceitamos <strong>PIX</strong>, <strong>boleto bancário</strong> e
                                <strong>cartões de crédito</strong> (Visa, Mastercard, Elo e Amex)
                                em até 6x sem juros.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
