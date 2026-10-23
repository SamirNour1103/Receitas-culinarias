<?php
require __DIR__ . '/verifica_login.php';      // só quem está logado entra
require __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../funcoes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $ingredientes = trim($_POST['ingredientes'] ?? '');
    $modo_preparo = trim($_POST['modo_preparo'] ?? '');
    $quantidade = trim($_POST['quantidade'] ?? '');

    if ($nome == "" || $ingredientes == "" || $modo_preparo == "" || $quantidade == "") {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } else {
        $imagem = salvar_imagem($_FILES['imagem'] ?? null, $erro_imagem);

        if ($erro_imagem) {
            $mensagem = $erro_imagem;
        } else {
            $stmt = mysqli_prepare(
                $conexao,
                "INSERT INTO receitas (nome, ingredientes, modo_preparo, quantidade, imagem) VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "sssis", $nome, $ingredientes, $modo_preparo, $quantidade, $imagem);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['mensagem'] = "Receita cadastrada com sucesso!";
                header('Location: receitas.php');
                exit;
            } else {
                apagar_imagem($imagem);   // não deixa arquivo órfão
                $mensagem = "Erro ao cadastrar receita: " . mysqli_error($conexao);
            }
        }
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <p><a class="voltar" href="receitas.php">&larr; Todas as receitas</a></p>
    <h2>Cadastrar Receita</h2>

    <?php if (isset($mensagem)) { ?>
        <p class="alerta erro"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <!-- enctype multipart é obrigatório para enviar arquivos -->
    <form action="cadastrar.php" method="POST" enctype="multipart/form-data">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">

        <label>Ingredientes:</label>
        <input type="text" name="ingredientes" value="<?php echo htmlspecialchars($_POST['ingredientes'] ?? ''); ?>">

        <label>Modo de preparo:</label>
        <textarea name="modo_preparo" rows="6"><?php echo htmlspecialchars($_POST['modo_preparo'] ?? ''); ?></textarea>

        <label>Porções:</label>
        <input type="number" name="quantidade" min="1"
            value="<?php echo htmlspecialchars($_POST['quantidade'] ?? ''); ?>">

        <label>Foto da receita (opcional):</label>
        <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
        <small class="dica">JPG, PNG ou WEBP, até 2 MB.</small>

        <button type="submit">Salvar receita</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>