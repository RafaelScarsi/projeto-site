# 🛍️ Luk Ideal — Loja de Roupas

Projeto acadêmico desenvolvido para as disciplinas do curso de Engenharia de Software

---

## 📋 Requisitos do Sistema

- **PHP** 7.4+ (recomendado PHP 8.x)
- **MySQL** 5.7+ / MariaDB 10.x
- **Apache** com `mod_rewrite` habilitado
- **Bootstrap** 5 (carregado via CDN)

---

## 🚀 Como Instalar e Configurar

### 1. Banco de Dados (IP Fixo — máquina separada)

Configure o servidor MySQL com IP fixo (ex: `192.168.1.100`).

```sql
-- Execute o arquivo database.sql no servidor MySQL:
mysql -h 192.168.1.100 -u root -p < database.sql
```

Edite `config/database.php` com as credenciais corretas:
```php
define('DB_HOST', '192.168.1.100');  // IP fixo do BD
define('DB_USER', 'root');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'luk_ideal');
```

---

### 2. Configurar Porta 8080 no Apache

Edite `/etc/apache2/ports.conf`:
```
Listen 8080
```

Edite o VirtualHost (`/etc/apache2/sites-available/lukideal.conf`):
```apache
<VirtualHost *:8080>
    ServerName lukideal.local
    DocumentRoot /var/www/luk-ideal
    <Directory /var/www/luk-ideal>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/lukideal_error.log
    CustomLog ${APACHE_LOG_DIR}/lukideal_access.log combined
</VirtualHost>
```

Habilitar site e reiniciar:
```bash
sudo a2ensite lukideal.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

### 3. Configurar DNS Local (arquivo hosts)

Edite o arquivo `hosts` da **máquina cliente**:

- **Linux/Mac**: `/etc/hosts`
- **Windows**: `C:\Windows\System32\drivers\etc\hosts`

Adicione a linha:
```
192.168.1.200   lukideal.local
```

> Substitua `192.168.1.200` pelo IP da máquina do servidor Apache.

Depois, acesse no navegador:
```
http://lukideal.local:8080
```

---

### 4. Copiar arquivos para o servidor

```bash
sudo cp -r luk-ideal/ /var/www/luk-ideal
sudo chown -R www-data:www-data /var/www/luk-ideal
sudo chmod -R 755 /var/www/luk-ideal
```

---

## 📁 Estrutura de Arquivos

```
luk-ideal/
├── .htaccess               ← Proíbe listagem, segurança, rewrite
├── index.php               ← Página inicial (carrossel, destaques)
├── database.sql            ← Script completo do banco de dados
├── config/
│   └── database.php        ← Configuração da conexão MySQL
├── includes/
│   ├── header.php          ← Template do cabeçalho (Navbar + Breadcrumb)
│   ├── footer.php          ← Template do rodapé
│   └── functions.php       ← Funções PHP (arrays, filtros, cálculos)
├── pages/
│   ├── produtos.php        ← Listagem de produtos com filtros
│   ├── produto.php         ← Detalhe do produto individual
│   ├── categorias.php      ← Página de categorias com FAQ (Accordion)
│   └── contato.php         ← Formulário de contato com validação
└── assets/
    ├── css/style.css       ← CSS personalizado
    └── js/script.js        ← JavaScript (interações, carrinho)
```

---

## ✅ Checklist da Rubrica

### Modelagem e Banco de Dados
- [x] DER com 7 tabelas (ver `database.sql`)
- [x] Mínimo 3 tabelas: `categorias`, `produtos`, `clientes`, `pedidos`, `tags`
- [x] Chaves primárias em todas as tabelas
- [x] Chaves estrangeiras: `produtos.categoria_id`, `pedidos.cliente_id`, etc.
- [x] Relação N:N: `pedido_produtos` e `produto_tags`

### Sistemas Operacionais e Redes
- [x] Listagem de diretórios proibida (`.htaccess`: `Options -Indexes`)
- [x] Aplicação na porta **8080** (ver configuração Apache acima)
- [x] DNS local configurado em `/etc/hosts` (`lukideal.local`)
- [x] Banco de dados com **IP Fixo** (`192.168.1.100`)
- [x] App e BD em **máquinas separadas**

### Desenvolvimento Web Moderna
- [x] Layout agradável e dinâmico com PHP
- [x] Template com PHP (`includes/header.php` + `includes/footer.php`)
- [x] Bootstrap 5 com 3+ componentes: Navbar, Carousel, Cards, Accordion, Badges, Breadcrumb
- [x] Conexão com banco de dados (`config/database.php`)
- [x] Dados recuperados do banco e exibidos na tela
- [x] Comandos PHP: `IF`, `WHILE`, `FOREACH`, `IF/ELSE`

### Tech Forge
- [x] Arrays estruturados: `$CONFIG_LOJA`, `$TAMANHOS_DISPONIVEIS`, `$STATUS_PEDIDO`, etc.
- [x] Funções com lógica: `calcularFrete()`, `aplicarDesconto()`, `calcularDesconto()`
- [x] Parâmetros e retorno em todas as funções
- [x] Lógica de pesquisa/filtro: `pesquisarProdutosNoArray()`, `filtrarProdutosPorPreco()`
- [x] Validação com condicionais: `validarEmail()`, verificações em todas as funções

---

## 📞 Informações da Loja

**Luk Ideal — Estilo que combina com você**
- Tel: (44) 99859-9363
- E-mail: lukideal@gmail.com
- Horário: Seg-Sex: 9h-18h | Sáb: 9h-14h
