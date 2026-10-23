<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome']);
    $ingredientes = trim($_POST['ingredientes']);
    $modo_preparo = trim($_POST['modo_preparo']);
    $quantidade = (int) $_POST['quantidade'];

    $stmt = mysqli_prepare(
        $conexao,
        "UPDATE receitas SET nome = ?, ingredientes = ?, modo_preparo = ?, quantidade = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "sssii", $nome, $ingredientes, $modo_preparo, $quantidade, $id);
    mysqli_stmt_execute($stmt);

    $_SESSION['mensagem'] = "Receita atualizada com sucesso!";
    header('Location: receitas.php');
    exit;
} else {
    $id = (int) ($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conexao, "SELECT * FROM receitas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $receita = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$receita) {
        header('Location: receitas.php');
        exit;
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Atualizar Receita</h2>
    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $receita['id']; ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($receita['nome']); ?>">

        <label>Ingredientes:</label>
        <input type="text" name="ingredientes" value="<?php echo htmlspecialchars($receita['ingredientes']); ?>">

        <label>Modo de preparo:</label>
        <textarea name="modo_preparo" rows="6"><?php echo htmlspecialchars($receita['modo_preparo']); ?></textarea>

        <label>Porções:</label>
        <input type="number" name="quantidade" min="1" value="<?php echo (int) $receita['quantidade']; ?>">

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>