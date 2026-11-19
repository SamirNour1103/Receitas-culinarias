<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/funcoes.php';
$url = BASE_URL;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receitas Abdallah - Receitas Culinárias</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $url; ?>css/style.css">
  <script src="<?php echo $url; ?>js/js.js"></script>
</head>

<body>

  <header class="header">
    <div class="logo">
      <i class="fa-solid fa-fire-flame-curved"></i>
      Receitas <span>Abdallah</span>
    </div>

    <!-- Botão do Menu Sanduíche (Hambúrguer) para Celular -->
    <div class="menu-toggle" id="mobile-menu">
      <i class="fa-solid fa-bars"></i>
    </div>

    <nav class="nav">
      <a href="<?php echo $url; ?>index.php">Início</a>
      <a href="<?php echo $url; ?>produtos/receitas.php">Receitas</a>
      <a href="<?php echo $url; ?>produtos/avaliacoes.php">Avaliações</a>
      <?php if (isset($_SESSION['usuario_id'])) { ?>
        <a href="<?php echo $url; ?>produtos/cadastrar.php">Nova receita</a>
        <a href="<?php echo $url; ?>logout.php">Sair</a>
      <?php } else { ?>
        <a href="<?php echo $url; ?>login.php">Login</a>
      <?php } ?>
    </nav>
  </header>

  <section class="hero">
    <div class="search-bar">
      <h1>Receitas Culinárias</h1>
    </div>
  </section>

  <!-- Script para ativar o funcionamento do menu sanduíche -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const mobileMenu = document.getElementById('mobile-menu');
      const nav = document.querySelector('.nav');

      if (mobileMenu && nav) {
        mobileMenu.addEventListener('click', () => {
          nav.classList.toggle('active');
          
          // Alterna o ícone entre barras e o 'X' de fechar
          const icon = mobileMenu.querySelector('i');
          if (icon) {
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-xmark');
          }
        });
      }
    });
  </script>
</body>
</html>