<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        
        .navbar-custom {
            background-color: #2c3e50;
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ecf0f1 !important;
        }
        
        .btn-entrar {
            background-color: #e74c3c;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-entrar:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .livro-destaque {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .livro-destaque:hover {
            transform: translateY(-5px);
        }
        
        .livro-capa {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .categoria-sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            height: fit-content;
        }
        
        .categoria-item {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            color: #495057;
            text-decoration: none;
            display: block;
        }
        
        .categoria-item:hover {
            color: #007bff;
            text-decoration: none;
        }
        
        .search-box {
            border-radius: 25px;
            border: 1px solid #ddd;
            padding: 8px 15px;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin: 1rem 0;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open me-2"></i>
                Sistema de Livros
            </a>
            
            <div class="navbar-nav ms-auto">
                <a href="{{ route('login') }}" class="btn btn-entrar">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Entrar
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Gerencie sua Biblioteca Digital</h1>
                        <p>Sistema completo para catalogação, organização e controle de livros, autores e assuntos. Simplifique a gestão da sua biblioteca com nossa plataforma intuitiva e moderna.</p>
                        
                        <div class="d-flex gap-3 mb-4">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-rocket me-2"></i>
                                Começar Agora
                            </a>
                            <a href="#recursos" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle me-2"></i>
                                Saiba Mais
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-books feature-icon" style="font-size: 15rem; color: rgba(255,255,255,0.3);"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recursos Section -->
    <section id="recursos" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 mb-3">Recursos Principais</h2>
                    <p class="lead text-muted">Descubra todas as funcionalidades que tornam nosso sistema único</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-book feature-icon"></i>
                        <h4>Cadastro de Livros</h4>
                        <p>Cadastre e organize seus livros com informações detalhadas, incluindo autores, assuntos e imagens de capa.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-users feature-icon"></i>
                        <h4>Gestão de Autores</h4>
                        <p>Mantenha um catálogo completo de autores com suas biografias e obras relacionadas.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-tags feature-icon"></i>
                        <h4>Organização por Assuntos</h4>
                        <p>Classifique seus livros por assuntos e categorias para facilitar a busca e organização.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-chart-bar feature-icon"></i>
                        <h4>Relatórios Detalhados</h4>
                        <p>Gere relatórios completos sobre sua biblioteca, incluindo estatísticas e análises.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-search feature-icon"></i>
                        <h4>Busca Avançada</h4>
                        <p>Encontre rapidamente qualquer livro através de nossa ferramenta de busca inteligente.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-envelope feature-icon"></i>
                        <h4>Notificações por E-mail</h4>
                        <p>Receba relatórios e atualizações importantes diretamente em seu e-mail.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container text-center">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-3">Pronto para começar?</h2>
                    <p class="lead mb-4">Junte-se a milhares de usuários que já organizam suas bibliotecas conosco.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Acessar Sistema
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book-open me-2"></i>Sistema de Livros</h5>
                    <p class="mb-0">Gerenciamento inteligente de bibliotecas digitais.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 Sistema de Livros. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

