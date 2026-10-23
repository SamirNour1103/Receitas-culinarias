<?php
require __DIR__ . '/../conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['usuario_id']);

// Receita escolhida: vem do formulário (POST) ou do link ?receita_id= (GET)
$receita_sel = (int) ($_POST['receita_id'] ?? $_GET['receita_id'] ?? 0);

// ---------- Salvar avaliação ----------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$logado) {
        header('Location: ../login.php');
        exit;
    }

    $usuario_id = (int) $_SESSION['usuario_id'];   // vem da sessão, nunca do formulário
    $score = (int) ($_POST['score'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');

    // A receita escolhida realmente existe?
    $stmt = mysqli_prepare($conexao, "SELECT id FROM receitas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $receita_sel);
    mysqli_stmt_execute($stmt);
    $existe = mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 1;

    if (!$existe) {
        $mensagem = "Escolha uma receita para avaliar.";
    } elseif ($score < 1 || $score > 5) {
        $mensagem = "Escolha uma nota de 1 a 5.";
    } elseif ($descricao == "") {
        $mensagem = "Escreva um comentário sobre a receita.";
    } else {
        $data = date('d/m/Y');   // cabe no varchar(10) da coluna `data`

        $stmt = mysqli_prepare(
            $conexao,
            "INSERT INTO avaliacoes (receita_id, usuario_id, data, descricao, score)
             VALUES (?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "iissi", $receita_sel, $usuario_id, $data, $descricao, $score);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensagem'] = "Avaliação enviada com sucesso!";
            header('Location: avaliacoes.php?receita_id=' . $receita_sel);
            exit;
        } else {
            $mensagem = "Erro ao salvar a avaliação: " . mysqli_error($conexao);
        }
    }
}

// ---------- Dados para a tela ----------
// Lista de receitas para o <select>
$receitas = mysqli_query($conexao, "SELECT id, nome FROM receitas ORDER BY nome");

// Todas as receitas com suas avaliações (JOIN entre as 3 tabelas).
// LEFT JOIN: receitas sem avaliação também aparecem.
// A receita escolhida vem primeiro (r.id = ? vale 1 para ela e 0 para as outras).
$stmt = mysqli_prepare(
    $conexao,
    "SELECT r.id AS receita_id, r.nome AS receita,
            a.id, a.data, a.descricao, a.score, u.nome AS usuario
       FROM receitas r
       LEFT JOIN avaliacoes a ON a.receita_id = r.id
       LEFT JOIN usuarios u   ON u.id = a.usuario_id
      ORDER BY (r.id = ?) DESC, r.nome, a.id DESC"
);
mysqli_stmt_bind_param($stmt, "i", $receita_sel);
mysqli_stmt_execute($stmt);
$linhas = mysqli_stmt_get_result($stmt);

// Separa as avaliações por receita
$grupos = [];
while ($l = mysqli_fetch_assoc($linhas)) {
    $rid = $l['receita_id'];
    if (!isset($grupos[$rid])) {
        $grupos[$rid] = ['nome' => $l['receita'], 'avaliacoes' => [], 'soma' => 0];
    }
    if ($l['id'] !== null) {   // null = receita ainda sem avaliação
        $grupos[$rid]['avaliacoes'][] = $l;
        $grupos[$rid]['soma'] += (int) $l['score'];
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Avaliações</h2>

    <?php if (isset($_SESSION['mensagem'])) { ?>
        <p class="alerta sucesso"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php } ?>

    <?php if (isset($mensagem)) { ?>
        <p class="alerta erro"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <?php if ($logado) { ?>
        <form action="avaliacoes.php" method="POST" class="form-avaliacao">
            <h3>Deixe sua avaliação, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></h3>

            <label>Receita:</label>
            <select name="receita_id">
                <option value="0">Selecione uma receita</option>
                <?php while ($r = mysqli_fetch_assoc($receitas)) { ?>
                    <option value="<?php echo $r['id']; ?>" <?php echo ($r['id'] == $receita_sel) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($r['nome']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Nota:</label>
            <select name="score">
                <option value="5">5 - Excelente</option>
                <option value="4">4 - Muito boa</option>
                <option value="3">3 - Boa</option>
                <option value="2">2 - Regular</option>
                <option value="1">1 - Ruim</option>
            </select>

            <label>Comentário:</label>
            <textarea name="descricao" rows="4"></textarea>

            <button type="submit">Enviar avaliação</button>
        </form>
    <?php } else { ?>
        <p class="alerta aviso">Para avaliar uma receita você precisa <a href="../login.php">fazer login</a>.</p>
    <?php } ?>

    <h3 class="subtitulo">Avaliações por receita</h3>

    <?php if (empty($grupos)) { ?>
        <p>Nenhuma receita cadastrada ainda.</p>
    <?php } ?>

    <?php foreach ($grupos as $rid => $g) { ?>
        <?php $total = count($g['avaliacoes']); ?>
        <section class="grupo-receita <?php echo ($rid == $receita_sel) ? 'destaque' : ''; ?>"
            id="receita-<?php echo $rid; ?>">
            <div class="grupo-topo">
                <h4><?php echo htmlspecialchars($g['nome']); ?></h4>
                <?php if ($total > 0) { ?>
                    <span class="media">
                        <span class="estrelas">★</span>
                        <?php echo round($g['soma'] / $total, 1); ?>
                        <small>(<?php echo $total; ?>         <?php echo $total == 1 ? 'avaliação' : 'avaliações'; ?>)</small>
                    </span>
                <?php } ?>
            </div>

            <?php if ($total == 0) { ?>
                <p class="vazio">Ainda sem avaliações. Seja a primeira pessoa a avaliar!</p>
            <?php } else { ?>
                <?php foreach ($g['avaliacoes'] as $a) { ?>
                    <article class="avaliacao">
                        <div class="avaliacao-topo">
                            <strong><?php echo htmlspecialchars($a['usuario']); ?></strong>
                            <span class="estrelas" title="Nota <?php echo (int) $a['score']; ?> de 5">
                                <?php echo str_repeat('★', (int) $a['score']) . str_repeat('☆', 5 - (int) $a['score']); ?>
                            </span>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($a['descricao'])); ?></p>
                        <small><?php echo htmlspecialchars($a['data']); ?></small>
                    </article>
                <?php } ?>
            <?php } ?>
        </section>
    <?php } ?>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>