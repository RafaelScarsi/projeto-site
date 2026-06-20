<?php
// Template Header - Luk Ideal
// Inclui Bootstrap 5, navegação e barra de pesquisa
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$categorias = getCategorias($conn);
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloPagina) ? htmlspecialchars($tituloPagina) . ' | ' : '' ?>Luk Ideal - Moda que combina com você</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="<?= str_repeat('../', substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '/') - 1) ?>assets/css/style.css">
</head>
<body>

<!-- ==================== BARRA SUPERIOR (Bootstrap: Badge + utilitários) ==================== -->
<div class="top-bar bg-dark text-white py-1">
    <div class="container d-flex justify-content-between align-items-center">
        <small><i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($CONFIG_LOJA['telefone']) ?></small>
        <small class="d-none d-md-block">
            <i class="bi bi-clock me-1"></i><?= htmlspecialchars($CONFIG_LOJA['horario']) ?>
        </small>
        <small>
            <i class="bi bi-instagram me-1"></i><?= htmlspecialchars($CONFIG_LOJA['redes']['instagram']) ?>
        </small>
    </div>
</div>

<!-- ==================== NAVBAR PRINCIPAL (Bootstrap: Navbar Component) ==================== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= estarNaRaiz() ? 'index.php' : '../index.php' ?>">
            <div class="logo-icon">
                <i class="bi bi-bag-heart-fill fs-4"></i>
            </div>
            <div>
                <span class="fw-bold fs-4">Luk <span class="text-warning">Ideal</span></span>
                <br><small class="text-light opacity-75" style="font-size:0.65rem; line-height:1"><?= htmlspecialchars($CONFIG_LOJA['slogan']) ?></small>
            </div>
        </a>

        <!-- Toggler Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $paginaAtual === 'index.php' ? 'active' : '' ?>"
                       href="<?= estarNaRaiz() ? 'index.php' : '../index.php' ?>">
                        <i class="bi bi-house-door me-1"></i>Início
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $paginaAtual === 'produtos.php' ? 'active' : '' ?>"
                       href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-grid me-1"></i>Produtos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= estarNaRaiz() ? 'pages/produtos.php' : 'produtos.php' ?>">
                            <i class="bi bi-collection me-2"></i>Todos os Produtos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php foreach ($categorias as $cat): ?>
                        <li>
                            <a class="dropdown-item" href="<?= estarNaRaiz() ? 'pages/produtos.php' : 'produtos.php' ?>?categoria=<?= $cat['id'] ?>">
                                <i class="bi bi-tag me-2"></i><?= htmlspecialchars($cat['nome']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $paginaAtual === 'categorias.php' ? 'active' : '' ?>"
                       href="<?= estarNaRaiz() ? 'pages/categorias.php' : 'categorias.php' ?>">
                        <i class="bi bi-tags me-1"></i>Categorias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $paginaAtual === 'contato.php' ? 'active' : '' ?>"
                       href="<?= estarNaRaiz() ? 'pages/contato.php' : 'contato.php' ?>">
                        <i class="bi bi-envelope me-1"></i>Contato
                    </a>
                </li>
            </ul>

            <!-- Barra de Pesquisa -->
            <form class="d-flex gap-2" action="<?= estarNaRaiz() ? 'pages/produtos.php' : 'produtos.php' ?>" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="busca"
                           placeholder="Buscar produtos..."
                           value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                    <button class="btn btn-warning" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- ==================== BREADCRUMB (Bootstrap: Breadcrumb Component) ==================== -->
<?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
<div class="breadcrumb-area py-2 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= estarNaRaiz() ? 'index.php' : '../index.php' ?>">
                        <i class="bi bi-house-door"></i> Início
                    </a>
                </li>
                <?php foreach ($breadcrumb as $item): ?>
                    <?php if ($item['ativo']): ?>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($item['label']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item">
                            <a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['label']) ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
    </div>
</div>
<?php endif; ?>

<!-- ==================== MAIN CONTENT START ==================== -->
<main>

<?php
function estarNaRaiz() {
    return basename($_SERVER['SCRIPT_FILENAME']) === 'index.php' &&
           dirname($_SERVER['SCRIPT_FILENAME']) === $_SERVER['DOCUMENT_ROOT'];
}
?>
