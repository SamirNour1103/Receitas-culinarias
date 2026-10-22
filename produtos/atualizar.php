<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    $sql = "UPDATE produtos SET
            nome = '$nome',
            descricao = '$descricao',
            preco = '$preco',
            quantidade = '$quantidade'
            WHERE id = '$id'";

    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
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
    <h2>Atualizar Produto</h2>
    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $produto['nome']; ?>"><br>

        <label>Descrição:</label>
        <input type="text" name="descricao" value="<?php echo $produto['descricao']; ?>"><br>

        <label>Preço:</label>
        <input type="text" name="preco" value="<?php echo $produto['preco']; ?>"><br>

        <label>Quantidade:</label>
        <input type="text" name="quantidade" value="<?php echo $produto['quantidade']; ?>"><br>

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>