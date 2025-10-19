<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio Revvo Cursos</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    
    <header class="header">
        <div class="header-content container">
            <a href="/" class="logo">LEO</a> <nav class="main-nav">
                </nav>

            <div class="user-profile">
                <form action="#" method="get" class="search-form">
                    <input type="text" placeholder="Pesquisar cursos..." class="search-input">
                    <button type="submit" class="search-button">Q</button>
                </form>
                <span>Olá, John Doe</span>
                </div>
        </div>
    </header>

    <main>
        <?php require $viewFile; ?>
    </main>
    
    <footer class="footer">
        <div class="footer-content container">
            <div class="footer-logo">
                <a href="/" class="logo">LEO</a>
                <p>Maecenas facilisis mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
            </div>
            
            <div class="footer-section">
                <h4># CONTATO</h4>
                <p>(21) 98765-5432</p>
                <p>contato@leolearning.com</p>
            </div>

            <div class="footer-section">
                <h4># REDES SOCIAIS</h4>
                <div class="social-icons">
                    <a href="#">T</a> <a href="#">Y</a> <a href="#">P</a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>Copyright 2017 - All rights reserved.</p>
        </div>
    </footer>

</body>
</html>