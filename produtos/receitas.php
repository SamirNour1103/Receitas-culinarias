<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$logado = isset($_SESSION['usuario_id']);

$resultado = mysqli_query(
    $conexao,
    "SELECT r.*, ROUND(AVG(a.score), 1) AS media, COUNT(a.id) AS total
       FROM receitas r
       LEFT JOIN avaliacoes a ON a.receita_id = r.id
      GROUP BY r.id
      ORDER BY r.id ASC"
);

$receitas = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $res_av = mysqli_query($conexao, "SELECT a.*, u.nome AS usuario FROM avaliacoes a INNER JOIN usuarios u ON u.id = a.usuario_id WHERE a.receita_id = {$row['id']} ORDER BY a.id DESC");
    $avs = [];
    while ($av = mysqli_fetch_assoc($res_av)) {
        $avs[] = $av;
    }
    $row['avaliacoes_detalhes'] = $avs;
    $receitas[] = $row;
}
?>
<?php require __DIR__ . '/../cabecalho.php'; ?>
<link rel="stylesheet" href="../css/livro-receitas.css">

<main class="livro-wrapper">
    <?php if (isset($_SESSION['mensagem'])) { ?>
        <p class="alerta sucesso"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php } ?>

    <div class="livro-capa">
        <div class="fita-marcadora"></div>

        <div class="livro-aberto" id="livroContainer">
            
            <!-- PÁGINA 0: ESQUERDA = Capa (Espaço Livre) | DIREITA = Sumário -->
            <div class="pagina-parativa pagina-capa" data-pagina-par="0">
                <!-- Esquerda: Capa / Espaço Livre -->
                <div class="pagina-metade esquerda">
                    <div class="conteudo-pagina">
                        <span class="data-manuscrita">Bem-vindo(a)</span>
                        <h2 class="titulo-livro">O Livro do Chef</h2>
                        <p>Este é o seu caderno culinário pessoal. Aqui você pode registrar e consultar todas as suas receitas favoritas.</p>
                        <div style="text-align: center; margin-top: 40px;">
                            <p style="font-style: italic; color: #8d6e63;">"A culinária é a arte de misturar sabores e criar memórias."</p>
                        </div>
                        <span class="num-pagina">Capa</span>
                    </div>
                </div>

                <!-- Direita: Sumário / Índice -->
                <div class="pagina-metade direita">
                    <div class="conteudo-pagina">
                        <span class="data-manuscrita">Sumário</span>
                        <h2 class="titulo-livro">Índice de Receitas</h2>
                        <div class="texto-boas-vindas">
                            <p>Clique em uma receita:</p>
                            <ul class="lista-indice">
                                <?php foreach ($receitas as $index => $rec) { 
                                    $num_pag = $index + 1;
                                ?>
                                    <li>
                                        <a href="#" onclick="irParaPagina(<?php echo $num_pag; ?>); return false;">
                                            <span><?php echo htmlspecialchars($rec['nome']); ?></span>
                                            <span>Pág <?php echo $num_pag; ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php if ($logado) { ?>
                            <div style="margin-top: 15px;">
                                <a href="cadastrar.php" class="btn-livro" style="display: block; text-align: center; text-decoration: none;">+ Adicionar Nova Receita</a>
                            </div>
                        <?php } ?>
                        <span class="num-pagina">Índice</span>
                    </div>
                </div>
            </div>

            <!-- PÁGINAS DAS RECEITAS (Folheando de 2 em 2) -->
            <?php 
            $total_receitas = count($receitas);
            for ($i = 0; $i < $total_receitas; $i += 2) { 
                $par_num = ($i / 2) + 1;
                $rec_esq = $receitas[$i] ?? null;
                $rec_dir = $receitas[$i + 1] ?? null;
            ?>
                <div class="pagina-parativa receita-par" data-pagina-par="<?php echo $par_num; ?>" style="display: none;">
                    
                    <!-- Lado Esquerdo -->
                    <div class="pagina-metade esquerda">
                        <div class="conteudo-pagina">
                            <?php if ($rec_esq) { ?>
                                <span class="data-manuscrita">Receita #<?php echo $rec_esq['id']; ?></span>
                                <h2 class="titulo-livro"><?php echo htmlspecialchars($rec_esq['nome']); ?></h2>
                                <div class="meta-info-receita" style="font-size: 0.85rem; color: #8d6e63; margin-bottom: 8px;">
                                    <span>🍽️ <?php echo (int) $rec_esq['quantidade']; ?> porções</span> | 
                                    <span>⭐ <?php echo $rec_esq['media'] ?: '0.0'; ?> (<?php echo (int) $rec_esq['total']; ?>)</span>
                                </div>
                                <?php if (!empty($rec_esq['imagem'])) { ?>
                                    <div style="text-align: center; margin: 6px 0;">
                                        <?php echo tag_imagem($rec_esq['imagem'], $rec_esq['nome'], 'receita-foto-livro'); ?>
                                    </div>
                                <?php } ?>
                                <div class="bloco-texto">
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 0.95rem; margin: 8px 0 2px 0; color: #5d4037;">Ingredientes</h3>
                                    <p style="font-size: 0.85rem; line-height: 1.3; margin: 0;"><?php echo nl2br(htmlspecialchars($rec_esq['ingredientes'])); ?></p>
                                </div>
                                <div class="bloco-texto">
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 0.95rem; margin: 8px 0 2px 0; color: #5d4037;">Modo de Preparo</h3>
                                    <p style="font-size: 0.85rem; line-height: 1.3; margin: 0;"><?php echo nl2br(htmlspecialchars($rec_esq['modo_preparo'])); ?></p>
                                </div>
                                <?php if ($logado) { ?>
                                    <div style="margin-top: 10px; display: flex; gap: 6px;">
                                        <a href="atualizar.php?id=<?php echo $rec_esq['id']; ?>" class="btn-acao-livro">Editar</a>
                                        <a href="excluir.php?id=<?php echo $rec_esq['id']; ?>" class="btn-acao-livro perigo" onclick="return confirm('Excluir?')">Excluir</a>
                                    </div>
                                <?php } ?>
                                <span class="num-pagina">Pág. <?php echo $i + 1; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Lado Direito -->
                    <div class="pagina-metade direita">
                        <div class="conteudo-pagina">
                            <?php if ($rec_dir) { ?>
                                <span class="data-manuscrita">Receita #<?php echo $rec_dir['id']; ?></span>
                                <h2 class="titulo-livro"><?php echo htmlspecialchars($rec_dir['nome']); ?></h2>
                                <div class="meta-info-receita" style="font-size: 0.85rem; color: #8d6e63; margin-bottom: 8px;">
                                    <span>🍽️ <?php echo (int) $rec_dir['quantidade']; ?> porções</span> | 
                                    <span>⭐ <?php echo $rec_dir['media'] ?: '0.0'; ?> (<?php echo (int) $rec_dir['total']; ?>)</span>
                                </div>
                                <?php if (!empty($rec_dir['imagem'])) { ?>
                                    <div style="text-align: center; margin: 6px 0;">
                                        <?php echo tag_imagem($rec_dir['imagem'], $rec_dir['nome'], 'receita-foto-livro'); ?>
                                    </div>
                                <?php } ?>
                                <div class="bloco-texto">
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 0.95rem; margin: 8px 0 2px 0; color: #5d4037;">Ingredientes</h3>
                                    <p style="font-size: 0.85rem; line-height: 1.3; margin: 0;"><?php echo nl2br(htmlspecialchars($rec_dir['ingredientes'])); ?></p>
                                </div>
                                <div class="bloco-texto">
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 0.95rem; margin: 8px 0 2px 0; color: #5d4037;">Modo de Preparo</h3>
                                    <p style="font-size: 0.85rem; line-height: 1.3; margin: 0;"><?php echo nl2br(htmlspecialchars($rec_dir['modo_preparo'])); ?></p>
                                </div>
                                <?php if ($logado) { ?>
                                    <div style="margin-top: 10px; display: flex; gap: 6px;">
                                        <a href="atualizar.php?id=<?php echo $rec_dir['id']; ?>" class="btn-acao-livro">Editar</a>
                                        <a href="excluir.php?id=<?php echo $rec_dir['id']; ?>" class="btn-acao-livro perigo" onclick="return confirm('Excluir?')">Excluir</a>
                                    </div>
                                <?php } ?>
                                <span class="num-pagina">Pág. <?php echo $i + 2; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            <?php } ?>

        </div>
    </div>

    <!-- Botões de Navegação -->
    <div class="navegacao-folhas">
        <button class="btn-virar" onclick="mudarPar(-1)">&#10094; Páginas Anteriores</button>
        <button class="btn-virar" onclick="mudarPar(1)">Próximas Páginas &#10095;</button>
    </div>
</main>

<script>
    let parAtual = 0;
    const maxPar = Math.ceil(<?php echo count($receitas); ?> / 2);

    function atualizarLivro() {
        const pares = document.querySelectorAll('.pagina-parativa');
        pares.forEach((par) => {
            const numPar = parseInt(par.getAttribute('data-pagina-par'));
            if (numPar === parAtual) {
                par.style.display = 'flex';
            } else {
                par.style.display = 'none';
            }
        });
    }

    function mudarPar(direcao) {
        parAtual += direcao;
        if (parAtual < 0) parAtual = 0;
        if (parAtual > maxPar) parAtual = maxPar;
        atualizarLivro();
    }

    function irParaPagina(numPagina) {
        parAtual = Math.ceil(numPagina / 2);
        atualizarLivro();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const receitaIdParam = urlParams.get('id');
        
        <?php foreach ($receitas as $index => $r) { ?>
            if (receitaIdParam == "<?php echo $r['id']; ?>") {
                parAtual = Math.ceil((<?php echo $index + 1; ?>) / 2);
            }
        <?php } ?>
        atualizarLivro();
    });
</script>

<?php require __DIR__ . '/../rodape.php'; ?>