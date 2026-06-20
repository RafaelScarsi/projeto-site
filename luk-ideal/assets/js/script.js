// =============================================
// SCRIPT.JS - Luk Ideal - Loja de Roupas
// =============================================

document.addEventListener('DOMContentLoaded', function () {

    // ---- Botões de tamanho ----
    document.querySelectorAll('.tamanho-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tamanho-btn').forEach(b => b.classList.remove('ativo'));
            this.classList.add('ativo');
        });
    });

    // ---- Animação de entrada nos cards ----
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.produto-card, .categoria-card, .categoria-card-page').forEach(function (el) {
        observer.observe(el);
    });

    // ---- Alerta de carrinho (simulação) ----
    window.adicionarCarrinho = function (id) {
        var toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 m-4 toast show align-items-center text-bg-success border-0';
        toast.setAttribute('role', 'alert');
        toast.innerHTML =
            '<div class="d-flex">' +
            '<div class="toast-body"><i class="bi bi-bag-check me-2"></i>Produto #' + id + ' adicionado ao carrinho!</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>';
        document.body.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 3000);
    };

    // ---- Navbar sombra no scroll ----
    window.addEventListener('scroll', function () {
        var nav = document.getElementById('mainNavbar');
        if (nav) {
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        }
    });

    // ---- Validação do checkbox de termos no formulário ----
    var formContato = document.querySelector('form[action="contato.php"]');
    if (formContato) {
        formContato.addEventListener('submit', function (e) {
            var check = document.getElementById('concordo');
            if (check && !check.checked) {
                e.preventDefault();
                alert('Você precisa concordar com os Termos de Uso para enviar a mensagem.');
            }
        });
    }

    // ---- Botão Voltar ao Topo ----
    var btnTopo = document.createElement('button');
    btnTopo.className = 'btn btn-warning position-fixed d-none';
    btnTopo.style.cssText = 'bottom:30px;right:30px;z-index:9999;border-radius:50%;width:48px;height:48px;box-shadow:0 4px 15px rgba(0,0,0,.2)';
    btnTopo.innerHTML = '<i class="bi bi-arrow-up"></i>';
    btnTopo.title = 'Voltar ao topo';
    document.body.appendChild(btnTopo);

    window.addEventListener('scroll', function () {
        if (window.scrollY > 400) {
            btnTopo.classList.remove('d-none');
        } else {
            btnTopo.classList.add('d-none');
        }
    });
    btnTopo.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

});
