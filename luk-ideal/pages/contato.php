<?php
// =============================================
// CONTATO.PHP - Luk Ideal
// =============================================
$tituloPagina = 'Contato';
$breadcrumb = [
    ['label' => 'Contato', 'url' => '', 'ativo' => true]
];

require_once '../includes/header.php';

// Processamento do formulário de contato
$mensagemEnviada = false;
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome    = trim($_POST['nome']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem= trim($_POST['mensagem']?? '');

    // Validações usando funções (Tech Forge - Parâmetros e Retorno)
    if (empty($nome)) {
        $erros[] = 'Nome é obrigatório.';
    } elseif (mb_strlen($nome) < 3) {
        $erros[] = 'Nome deve ter pelo menos 3 caracteres.';
    }

    $validacaoEmail = validarEmail($email);
    if (!$validacaoEmail['valido']) {
        $erros[] = $validacaoEmail['mensagem'];
    }

    if (empty($assunto)) {
        $erros[] = 'Assunto é obrigatório.';
    }

    if (empty($mensagem) || mb_strlen($mensagem) < 10) {
        $erros[] = 'Mensagem deve ter pelo menos 10 caracteres.';
    }

    // IF: Só salva no banco se não houver erros
    if (empty($erros)) {
        $stmt = $conn->prepare("
            INSERT INTO clientes (nome, email, telefone)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE nome = VALUES(nome)
        ");
        $tel = trim($_POST['telefone'] ?? '');
        $stmt->bind_param('sss', $nome, $email, $tel);
        $stmt->execute();
        $stmt->close();
        $mensagemEnviada = true;
    }
}

// Dados de contato em array (Tech Forge - Array estruturado)
$infoContato = [
    ['icone' => 'bi-geo-alt-fill',   'cor' => 'text-danger',  'titulo' => 'Endereço',
     'valor' => $CONFIG_LOJA['endereco'] . ', ' . $CONFIG_LOJA['cidade']],
    ['icone' => 'bi-telephone-fill',  'cor' => 'text-success', 'titulo' => 'Telefone',
     'valor' => $CONFIG_LOJA['telefone']],
    ['icone' => 'bi-envelope-fill',   'cor' => 'text-primary', 'titulo' => 'E-mail',
     'valor' => $CONFIG_LOJA['email']],
    ['icone' => 'bi-clock-fill',      'cor' => 'text-warning', 'titulo' => 'Horário',
     'valor' => $CONFIG_LOJA['horario']],
];
?>

<!-- Cabeçalho -->
<section class="page-header py-4 bg-dark text-white">
    <div class="container">
        <h1 class="fw-bold mb-1"><i class="bi bi-envelope me-2 text-warning"></i>Entre em Contato</h1>
        <p class="mb-0 opacity-75">Estamos aqui para ajudar você!</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">

            <!-- ====== INFORMAÇÕES DE CONTATO ====== -->
            <div class="col-12 col-lg-4">
                <h4 class="fw-bold mb-4">Informações</h4>

                <!-- FOREACH: Itera sobre o array de informações de contato -->
                <?php foreach ($infoContato as $info): ?>
                <div class="d-flex gap-3 mb-4 align-items-start">
                    <div class="contact-icon-box rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="bi <?= $info['icone'] ?> <?= $info['cor'] ?> fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($info['titulo']) ?></h6>
                        <p class="text-muted mb-0 small"><?= htmlspecialchars($info['valor']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Redes Sociais (Bootstrap: Button/Badge Components) -->
                <h6 class="fw-bold mt-4 mb-3">Redes Sociais</h6>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-danger btn-sm px-3">
                        <i class="bi bi-instagram me-1"></i>Instagram
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm px-3">
                        <i class="bi bi-facebook me-1"></i>Facebook
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm px-3">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>

                <!-- Mapa placeholder -->
                <div class="mt-4 rounded-3 overflow-hidden border shadow-sm map-placeholder d-flex align-items-center justify-content-center">
                    <div class="text-center p-4 text-muted">
                        <i class="bi bi-map fs-1 d-block mb-2"></i>
                        <small>Mapa — Configure seu Google Maps API</small>
                    </div>
                </div>
            </div>

            <!-- ====== FORMULÁRIO DE CONTATO ====== -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-chat-dots me-2"></i>Envie sua Mensagem
                    </div>
                    <div class="card-body p-4">

                        <!-- IF: Exibe mensagem de sucesso -->
                        <?php if ($mensagemEnviada): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Mensagem enviada com sucesso!</strong>
                            Entraremos em contato em breve.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <!-- IF: Exibe erros de validação -->
                        <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Corrija os seguintes erros:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?= htmlspecialchars($erro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="contato.php" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" name="nome"
                                               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                                               placeholder="Seu nome" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" name="email"
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                               placeholder="seu@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Telefone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control" name="telefone"
                                               value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"
                                               placeholder="(11) 99999-0000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Assunto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-chat-square-text"></i></span>
                                        <select class="form-select" name="assunto" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            // Array de assuntos (Tech Forge - Array estruturado)
                                            $assuntos = [
                                                'Dúvida sobre produto',
                                                'Troca ou devolução',
                                                'Informação sobre pedido',
                                                'Frete e entrega',
                                                'Pagamento',
                                                'Parceria ou fornecimento',
                                                'Outros',
                                            ];
                                            foreach ($assuntos as $opt):
                                                $sel = (($_POST['assunto'] ?? '') === $opt) ? 'selected' : '';
                                            ?>
                                                <option value="<?= htmlspecialchars($opt) ?>" <?= $sel ?>>
                                                    <?= htmlspecialchars($opt) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mensagem <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="mensagem" rows="5"
                                              placeholder="Digite sua mensagem aqui..." required><?= htmlspecialchars($_POST['mensagem'] ?? '') ?></textarea>
                                    <div class="form-text">Mínimo 10 caracteres.</div>
                                </div>
                                <div class="col-12">
                                    <!-- Bootstrap: Progress Bar usado como indicador visual -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="concordo" required>
                                        <label class="form-check-label small" for="concordo">
                                            Concordo com os <a href="#">Termos de Uso</a> e
                                            <a href="#">Política de Privacidade</a>.
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-lg px-5 fw-bold">
                                        <i class="bi bi-send me-2"></i>Enviar Mensagem
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
