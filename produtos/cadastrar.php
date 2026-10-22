<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $preco = trim($_POST['preco']);
    $quantidade = trim($_POST['quantidade']);

    if ($nome == "" || $preco == "" || $quantidade == "") {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
                VALUES ('$nome', '$descricao', '$preco', '$quantidade')";

        if (mysqli_query($conexao, $sql)) {
            $_SESSION['mensagem'] = "Produto cadastrado com sucesso!";
            header('Location: listar.php');
            exit;
        } else {
            $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conexao);
        }
    }
}

?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Cadastrar Produto</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form action="cadastrar.php" method="POST">
        <label>Nome:</label>
        <input type="text" name="nome"><br>

        <label>Descrição:</label>
        <input type="text" name="descricao"><br>

        <label>Preço:</label>
        <input type="text" name="preco"><br>

        <label>Quantidade:</label>
        <input type="text" name="quantidade"><br>

        <button type="submit">Salvar</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>