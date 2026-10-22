<?php
    session_start();
    require __DIR__ . '/conexao.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha =
        '$senha'";
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) == 1) {
            $usuario = mysqli_fetch_assoc($resultado);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: produtos/listar.php");
            exit;
        } else {
            $mensagem = "E-mail ou senha inválidos.";
        }
    }
?>

<?php require __DIR__ . '/cabecalho.php'; ?>

<main>
    <h2 class="centro">Login</h2>
    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>
    <form action="login.php" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email"><br>
        <label>Senha:</label>
        <input type="password" name="senha"><br>
        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>