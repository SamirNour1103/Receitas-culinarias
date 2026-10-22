<?php
$url = '/receitas-culinarias/';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receitas Abdallah - Receitas Culinárias</title>
  <!-- Google Fonts & Font Awesome Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
 
  <!-- Link para o ficheiro CSS separado -->
  <link rel="stylesheet" href="<?php echo $url; ?>css/style.css">
</head>
<body>

  <!-- NAVEGAÇÃO SUPERIOR -->
  <header class="header">
    <div class="logo">
      <i class="fa-solid fa-fire-flame-curved"></i>
      Receitas <span> Abdallah</span>
    </div>
    <nav class="nav">
      <a href="<?php echo $url; ?>produtos/receitas.php" class="active">Receitas</a>
      <a href="<?php echo $url; ?>">####</a>
      <a href="<?php echo $url; ?>">####</a>
      <a href="<?php echo $url; ?>login.php">Login</a>
      <a href="<?php echo $url; ?>logout.php">Sair</a>
    </nav>
  </header>

  <!-- BANNER HERO COM BARRA DE PESQUISA -->
  <section class="hero">
    <div class="search-bar">
      <i class="fa-solid fa-magnifying-glass"></i>
      <h1>Receitas Culinárias</h1>
    </div>
  </section>


</body>
</html>