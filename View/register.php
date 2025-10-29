<?php
if (!isset($_SESSION)) { session_start(); }
$csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Registrar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family: Arial, sans-serif; margin:40px;}
        .card{max-width:480px; margin:auto; padding:24px; border:1px solid #ddd; border-radius:8px;}
        label{display:block; margin:8px 0 4px;}
        input{width:100%; padding:8px; box-sizing:border-box;}
        button{margin-top:12px; padding:10px 14px;}
        .alert{color:#a00; margin-bottom:10px;}
        .muted{color:#555}
        a{color:#06c}
    </style>
</head>
<body>
<div class="card">
    <h1>Cadastro de Usuário</h1>
    <?php if ($flash): ?>
        <div class="alert"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form method="post" action="index.php?r=doRegister">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <label for="name">Nome</label>
        <input id="name" type="text" name="name" required minlength="2">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" required>
        <label for="password">Senha</label>
        <input id="password" type="password" name="password" required minlength="6">
        <button type="submit">Registrar</button>
    </form>
    <p class="muted"><a href="index.php?r=login">Voltar ao login</a></p>
</div>
</body>
</html>

//updated log