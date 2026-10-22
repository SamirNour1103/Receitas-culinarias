<?php 
    $url = '/projeto-php/';
?> 

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto php</title>
    <link rel="stylesheet" href="<?php echo $url; ?>css/style.css">
</head>

<body>
    <header>
        <h1>Sistema de Produtos</h1>
        <nav>
            <a href="<?php echo $url; ?>index.php">Início</a>
            <a href="<?php echo $url; ?>produtos/listar.php">Produtos</a>
            <a href="<?php echo $url; ?>login.php">Login</a>
            <a href="<?php echo $url; ?>logout.php">Logout</a>
        </nav>
    </header>