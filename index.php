<?php require __DIR__ . '/cabecalho.php'; ?>


<main class="livro-wrapper">
    <div class="livro-capa">
        <!-- Fita marcadora vermelha -->
        <div class="fita-marcadora"></div>

        <div class="livro-paginas">
            <!-- PÁGINA ESQUERDA: Apresentação e Data -->
            <section class="pagina pagina-esquerda">
                <span class="data-manuscrita">Anotado em <?php echo date('d/m/Y'); ?></span>
                
                <h1 class="titulo-livro">Caderno de Receitas</h1>

                <div class="texto-boas-vindas">
                    <?php if (isset($_SESSION['usuario_nome'])) { ?>
                        <p>Olá, Chef <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!</p>
                    <?php } else { ?>
                        <p>Bem-vindo(a) à nossa cozinha!</p>
                    <?php } ?>
                    
                    <p>Este é o seu espaço culinário. Folheie as páginas para consultar pratos cadastrados, registrar novas descobertas gastronômicas ou conferir as avaliações da cozinha.</p>
                </div>

                <div class="selo-cozinha">
                    <span>★ Receitas Abdallah ★</span>
                    <small>Coleção Culinária & Sabores</small>
                </div>

                <span class="num-pagina">— Pág. 01 —</span>
            </section>

            <!-- PÁGINA DIREITA: Índice e Navegação -->
            <section class="pagina pagina-direita">
                <h2 class="subtitulo-indice">Índice Rápido</h2>
                <p class="descricao-indice">
                    Escolha uma seção do livro para abrir:
                </p>

                <div class="lista-acoes">
                    <a class="btn-livro" href="produtos/receitas.php">
                        <span>📖 Explorar Todas as Receitas</span>
                        <span>→</span>
                    </a>

                    <a class="btn-livro-sec" href="produtos/avaliacoes.php">
                        <span>⭐ Ver Avaliações e Notas</span>
                        <span>→</span>
                    </a>
                </div>

                <span class="num-pagina">— Pág. 02 —</span>
            </section>
        </div>
    </div>
</main>

<?php require __DIR__ . '/rodape.php'; ?>