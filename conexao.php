<?php
// conexao.php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "sistema_culinaria"; // mesmo nome do banco criado pelo sistema_culinaria.sql

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar ao banco: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");