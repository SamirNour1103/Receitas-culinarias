<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM produtos WHERE id = '$id'";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = "Produto excluído com sucesso!";
    header('Location: listar.php');
    exit;
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM produtos WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $sql);
    $produto = mysqli_fetch_assoc($resultado);
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Excluir Produto</h2>
    <p>Tem certeza que deseja excluir o produto
       <strong><?php echo $produto['nome']; ?></strong>?</p>

    <form action="excluir.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
        <button type="submit">Sim, excluir</button>
        <a href="listar.php">Cancelar</a>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>