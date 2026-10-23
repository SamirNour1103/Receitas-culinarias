<?php
session_start();
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta preparada (evita SQL Injection)
    $stmt = mysqli_prepare($conexao, "SELECT id, nome FROM usuarios WHERE email = ? AND senha = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: produtos/receitas.php");
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
        <p class="alerta erro"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>
    <form action="login.php" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email">
        <label>Senha:</label>
        <input type="password" name="senha">
        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>