<?php
if (!isset($_SESSION)) { session_start(); }
if (empty($_SESSION['user'])) { header('Location: index.php?r=login'); exit; }
$user = $_SESSION['user'];
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family: Arial, sans-serif; margin:24px;}
        nav a{margin-right:12px;}
        .alert{color:#a00;}
    </style>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <?php if ($flash): ?>
        <p class="alert"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <nav>
        <a href="index.php?r=products">Cadastro de Produto</a>
        <a href="index.php?r=inventory">Gestão de Estoque</a>
        <a href="index.php?r=logout">Sair</a>
    </nav>
    <p>Esta é a interface principal do sistema. Use o menu acima para navegar.</p>
</body>
</html>
