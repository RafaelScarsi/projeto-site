<?php
// Template Footer - Luk Ideal
?>
</main>
<!-- ==================== MAIN CONTENT END ==================== -->

<!-- ==================== FOOTER (Bootstrap: Grid + Cards) ==================== -->
<footer class="footer bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row g-4">

            <!-- Coluna 1: Sobre a loja -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bag-heart-fill text-warning fs-3"></i>
                    <h5 class="mb-0 fw-bold">Luk <span class="text-warning">Ideal</span></h5>
                </div>
                <p class="text-light opacity-75 small">
                    <?= htmlspecialchars($CONFIG_LOJA['slogan']) ?>. Moda com qualidade, estilo e o melhor preço para você e sua família.
                </p>
                <!-- Badges de pagamento (Bootstrap: Badge Component) -->
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-secondary"><i class="bi bi-credit-card me-1"></i>Cartão</span>
                    <span class="badge bg-secondary"><i class="bi bi-qr-code me-1"></i>PIX</span>
                    <span class="badge bg-secondary"><i class="bi bi-cash me-1"></i>Boleto</span>
                </div>
            </div>

            <!-- Coluna 2: Links rápidos -->
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-bold text-warning mb-3 text-uppercase">Links Rápidos</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="../index.php" class="text-light text-decoration-none opacity-75 hover-link">
                        <i class="bi bi-chevron-right me-1"></i>Início</a></li>
                    <li class="mb-2"><a href="produtos.php" class="text-light text-decoration-none opacity-75 hover-link">
                        <i class="bi bi-chevron-right me-1"></i>Produtos</a></li>
                    <li class="mb-2"><a href="categorias.php" class="text-light text-decoration-none opacity-75 hover-link">
                        <i class="bi bi-chevron-right me-1"></i>Categorias</a></li>
                    <li class="mb-2"><a href="contato.php" class="text-light text-decoration-none opacity-75 hover-link">
                        <i class="bi bi-chevron-right me-1"></i>Contato</a></li>
                </ul>
            </div>

            <!-- Coluna 3: Categorias -->
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-bold text-warning mb-3 text-uppercase">Categorias</h6>
                <ul class="list-unstyled">
                    <?php foreach ($categorias as $cat): ?>
                    <li class="mb-2">
                        <a href="produtos.php?categoria=<?= $cat['id'] ?>"
                           class="text-light text-decoration-none opacity-75 hover-link">
                            <i class="bi bi-tag me-1"></i><?= htmlspecialchars($cat['nome']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Coluna 4: Contato -->
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-bold text-warning mb-3 text-uppercase">Contato</h6>
                <ul class="list-unstyled text-light opacity-75 small">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt-fill me-2 text-warning"></i>
                        <?= htmlspecialchars($CONFIG_LOJA['endereco']) ?><br>
                        <span class="ms-4"><?= htmlspecialchars($CONFIG_LOJA['cidade']) ?></span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone-fill me-2 text-warning"></i>
                        <?= htmlspecialchars($CONFIG_LOJA['telefone']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope-fill me-2 text-warning"></i>
                        <?= htmlspecialchars($CONFIG_LOJA['email']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock-fill me-2 text-warning"></i>
                        <?= htmlspecialchars($CONFIG_LOJA['horario']) ?>
                    </li>
                </ul>
                <!-- Redes Sociais -->
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-warning fs-5 hover-social" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-warning fs-5 hover-social" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-warning fs-5 hover-social" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container -->

    <!-- Rodapé inferior -->
    <div class="footer-bottom border-top border-secondary py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-light opacity-50">
                        &copy; <?= date('Y') ?> <?= htmlspecialchars($CONFIG_LOJA['nome']) ?> — Todos os direitos reservados.
                    </small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-light opacity-50">
                        Desenvolvido com <i class="bi bi-heart-fill text-danger"></i> para a faculdade
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS personalizado -->
<script src="<?= str_repeat('../', substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '/') - 1) ?>assets/js/script.js"></script>

</body>
</html>
