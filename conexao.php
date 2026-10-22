<?php
    // conexao.php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "banco";
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);

    // if ($conexao) {
    // echo "Conexão realizada com sucesso!";
    // } else {
    // echo "Erro ao conectar: " . mysqli_connect_error();
    // }

?>