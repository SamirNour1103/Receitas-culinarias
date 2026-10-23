<?php
// receitas.php: mostra a LISTA de receitas e, com ?id=N, a PÁGINA de uma receita
require __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../funcoes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
$logado = isset($_SESSION['usuario_id']);

$id = (int) ($_POST['receita_id'] ?? $_GET['id'] ?? 0);
$detalhe = $id > 0;   // true = página de uma receita | false = lista

if ($detalhe) {
    // ---------- Receita (com média e total de avaliações) ----------
    $stmt = mysqli_prepare(
        $conexao,
        "SELECT r.*, ROUND(AVG(a.score), 1) AS media, COUNT(a.id) AS total
           FROM receitas r
           LEFT JOIN avaliacoes a ON a.receita_id = r.id
          WHERE r.id = ?
          GROUP BY r.id"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // ---------- Salvar avaliação (a receita é a desta própria página) ----------
    if ($r && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!$logado) {
            header('Location: ../login.php');
            exit;
        }

        $usuario_id = (int) $_SESSION['usuario_id'];   // vem da sessão, nunca do formulário
        $score = (int) ($_POST['score'] ?? 0);
        $descricao = trim($_POST['descricao'] ?? '');

        if ($score < 1 || $score > 5) {
            $mensagem = "Escolha uma nota de 1 a 5.";
        } elseif ($descricao == "") {
            $mensagem = "Escreva um comentário sobre a receita.";
        } else {
            $data = date('d/m/Y');
            $stmt = mysqli_prepare(
                $conexao,
                "INSERT INTO avaliacoes (receita_id, usuario_id, data, descricao, score) VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "iissi", $id, $usuario_id, $data, $descricao, $score);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['mensagem'] = "Avaliação enviada com sucesso!";
                header('Location: receitas.php?id=' . $id . '#avaliacoes');
                exit;
            } else {
                $mensagem = "Erro ao salvar a avaliação: " . mysqli_error($conexao);
            }
        }
    }

    // ---------- Avaliações desta receita (JOIN com usuarios para o nome) ----------
    if ($r) {
        $stmt = mysqli_prepare(
            $conexao,
            "SELECT a.id, a.data, a.descricao, a.score, u.nome AS usuario
               FROM avaliacoes a
               INNER JOIN usuarios u ON u.id = a.usuario_id
              WHERE a.receita_id = ?
              ORDER BY a.id DESC"
        );
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $avaliacoes = mysqli_stmt_get_result($stmt);
    }
} else {
    // ---------- Lista de receitas ----------
    $resultado = mysqli_query(
        $conexao,
        "SELECT r.id, r.nome, r.ingredientes, r.quantidade, r.imagem,
                ROUND(AVG(a.score), 1) AS media,
                COUNT(a.id) AS total
           FROM receitas r
           LEFT JOIN avaliacoes a ON a.receita_id = r.id
          GROUP BY r.id
          ORDER BY r.id DESC"
    );
}
?>
<?php require __DIR__ . '/../cabecalho.php'; ?>

<?php if ($detalhe && !$r) { ?>

    <main>
        <h2>Receita não encontrada</h2>
        <p><a class="btn" href="receitas.php">Voltar às receitas</a></p>
    </main>

<?php } elseif ($detalhe) { ?>

    <!-- ====================== PÁGINA DE UMA RECEITA ====================== -->
    <main>
        <p><a class="voltar" href="receitas.php">&larr; Todas as receitas</a></p>

        <?php if (isset($_SESSION['mensagem'])) { ?>
            <p class="alerta sucesso"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
            <?php unset($_SESSION['mensagem']); ?>
        <?php } ?>

        <?php echo tag_imagem($r['imagem'], $r['nome'], 'receita-capa'); ?>

        <h2><?php echo htmlspecialchars($r['nome']); ?></h2>

        <div class="receita-card-meta grande">
            <span><?php echo (int) $r['quantidade']; ?> porções</span>
            <?php if ($r['total'] > 0) { ?>
                <span><span class="estrelas">★</span> <?php echo $r['media']; ?> (<?php echo (int) $r['total']; ?>
                    <?php echo $r['total'] == 1 ? 'avaliação' : 'avaliações'; ?>)</span>
            <?php } else { ?>
                <span class="mudo">Sem avaliações</span>
            <?php } ?>
        </div>

        <h3 class="subtitulo">Ingredientes</h3>
        <p class="texto-receita"><?php echo htmlspecialchars($r['ingredientes']); ?></p>

        <h3 class="subtitulo">Modo de preparo</h3>
        <p class="texto-receita"><?php echo htmlspecialchars($r['modo_preparo']); ?></p>

        <?php if ($logado) { ?>
            <p>
                <a class="btn-sec sem-margem" href="atualizar.php?id=<?php echo $r['id']; ?>">Editar receita</a>
                <a class="btn-sec" href="excluir.php?id=<?php echo $r['id']; ?>">Excluir receita</a>
            </p>
        <?php } ?>

        <h3 class="subtitulo secao" id="avaliar">Avalie esta receita</h3>

        <?php if (isset($mensagem)) { ?>
            <p class="alerta erro"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php } ?>

        <?php if ($logado) { ?>
            <form action="receitas.php?id=<?php echo $r['id']; ?>#avaliar" method="POST" class="form-avaliacao">
                <input type="hidden" name="receita_id" value="<?php echo $r['id']; ?>">

                <label>Sua nota, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>:</label>
                <select name="score">
                    <option value="5">5 - Excelente</option>
                    <option value="4">4 - Muito boa</option>
                    <option value="3">3 - Boa</option>
                    <option value="2">2 - Regular</option>
                    <option value="1">1 - Ruim</option>
                </select>

                <label>Comentário:</label>
                <textarea name="descricao" rows="4"><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>

                <button type="submit">Enviar avaliação</button>
            </form>
        <?php } else { ?>
            <p class="alerta aviso">Para avaliar esta receita você precisa <a href="../login.php">fazer login</a>.</p>
        <?php } ?>

        <h3 class="subtitulo secao" id="avaliacoes">Avaliações desta receita</h3>
        <?php if (mysqli_num_rows($avaliacoes) == 0) { ?>
            <p class="vazio">Ainda sem avaliações. Seja a primeira pessoa a avaliar!</p>
        <?php } else { ?>
            <?php while ($a = mysqli_fetch_assoc($avaliacoes)) {
                render_avaliacao($a, $r['id'], $r['nome']);
            } ?>
        <?php } ?>
    </main>

    <?php render_modal_avaliacao(); ?>

<?php } else { ?>

    <!-- ====================== LISTA DE RECEITAS ====================== -->
    <main>
        <h2>Receitas</h2>

        <?php if (isset($_SESSION['mensagem'])) { ?>
            <p class="alerta sucesso"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
            <?php unset($_SESSION['mensagem']); ?>
        <?php } ?>

        <?php if ($logado) { ?>
            <details class="nova-receita">
                <summary>+ Nova receita</summary>

                <!-- quem salva é o cadastrar.php -->
                <form id="nova" action="cadastrar.php" method="POST" enctype="multipart/form-data">
                    <label>Nome:</label>
                    <input type="text" name="nome">

                    <label>Ingredientes:</label>
                    <input type="text" name="ingredientes">

                    <label>Modo de preparo:</label>
                    <textarea name="modo_preparo" rows="6"></textarea>

                    <label>Porções:</label>
                    <input type="number" name="quantidade" min="1">

                    <label>Foto da receita (opcional):</label>
                    <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
                    <small class="dica">JPG, PNG ou WEBP, até 2 MB.</small>

                    <button type="submit">Salvar receita</button>
                </form>
            </details>
        <?php } else { ?>
            <p class="alerta aviso">Quer compartilhar uma receita? <a href="../login.php">Faça login</a>.</p>
        <?php } ?>

        <?php if (mysqli_num_rows($resultado) == 0) { ?>
            <p>Nenhuma receita cadastrada ainda.</p>
        <?php } else { ?>
            <div class="receitas-lista">
                <?php while ($r = mysqli_fetch_assoc($resultado)) { ?>
                    <article class="receita-card">
                        <?php echo tag_imagem($r['imagem'], $r['nome'], 'receita-card-img'); ?>
                        <div class="receita-card-corpo">
                            <h3><a class="link-cartao"
                                    href="receitas.php?id=<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['nome']); ?></a>
                            </h3>
                            <p class="resumo"><?php echo htmlspecialchars($r['ingredientes']); ?></p>
                            <div class="receita-card-meta">
                                <span><?php echo (int) $r['quantidade']; ?> porções</span>
                                <?php if ($r['total'] > 0) { ?>
                                    <span><span class="estrelas">★</span> <?php echo $r['media']; ?>
                                        (<?php echo (int) $r['total']; ?>)</span>
                                <?php } else { ?>
                                    <span class="mudo">Sem avaliações</span>
                                <?php } ?>
                            </div>
                            <div class="acoes-card">
                                <a href="receitas.php?id=<?php echo $r['id']; ?>#avaliar">Avaliar</a>
                                <?php if ($logado) { ?>
                                    <a href="atualizar.php?id=<?php echo $r['id']; ?>">Editar</a>
                                    <a class="perigo" href="excluir.php?id=<?php echo $r['id']; ?>">Excluir</a>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

<?php } ?>

<?php require __DIR__ . '/../rodape.php'; ?>