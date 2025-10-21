<header class="header">
  <div class="header-content container">
    <a href="/" class="logo">LEO</a>

    <nav class="main-nav"></nav>

    <div class="user-profile">
      <form action="#" method="get" class="search-form">
        <input type="text" placeholder="Pesquisar cursos..." class="search-input" />
        <button type="submit" class="search-button">Q</button>
      </form>
      <span>Olá, <?= htmlspecialchars($userName ?? 'John Doe') ?></span>
    </div>
  </div>
</header>
