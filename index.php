<?php require __DIR__ . '/cabecalho.php'; ?>
<main>
    <?php if (isset($_SESSION['usuario_nome'])) { ?>
        <p>Bem-vindo(a), <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!</p>
    <?php } else { ?>
        <p>Bem-vindo(a) ao sistema!</p>
    <?php } ?>
    <p>Hoje é <?php echo date('d/m/Y'); ?></p>
    <p>
        <a class="btn" href="produtos/receitas.php">Ver receitas</a>
        <a class="btn-sec" href="produtos/avaliacoes.php">Ver avaliações</a>
    </p>
</main>
<?php require __DIR__ . '/rodape.php'; ?>