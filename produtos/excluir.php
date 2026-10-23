<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];

    $stmt = mysqli_prepare($conexao, "DELETE FROM receitas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $_SESSION['mensagem'] = "Receita excluída com sucesso!";
    header('Location: receitas.php');
    exit;
} else {
    $id = (int) ($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conexao, "SELECT id, nome FROM receitas WHERE id = ?");
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
    <h2>Excluir Receita</h2>
    <p>Tem certeza que deseja excluir a receita
        <strong><?php echo htmlspecialchars($receita['nome']); ?></strong>?
    </p>

    <form action="excluir.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $receita['id']; ?>">
        <button type="submit" class="perigo">Sim, excluir</button>
        <a class="btn-sec" href="receitas.php">Cancelar</a>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>