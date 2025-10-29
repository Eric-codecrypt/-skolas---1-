<?php
if (!isset($_SESSION)) { session_start(); }
if (empty($_SESSION['user'])) { header('Location: index.php?r=login'); exit; }
$csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : '';
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Cadastro de Produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family: Arial, sans-serif; margin:24px;}
        table{border-collapse: collapse; width:100%;}
        th, td{border:1px solid #ddd; padding:8px;}
        th{background:#f6f6f6;}
        .toolbar{display:flex; gap:12px; align-items:center; margin-bottom:12px;}
        .alert{color:#a00; margin-bottom:8px;}
        .btn{padding:6px 10px; border:1px solid #ccc; background:#fafafa; border-radius:4px; text-decoration:none;}
        form.inline{display:inline;}
    </style>
</head>
<body>
    <h1>Produtos</h1>
    <?php if ($flash): ?>
        <div class="alert"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="toolbar">
        <form method="get" action="index.php">
            <input type="hidden" name="r" value="products">
            <input type="text" name="q" placeholder="Buscar por nome, SKU ou categoria" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <button type="submit" class="btn">Buscar</button>
        </form>
        <a class="btn" href="index.php?r=productsCreate">Novo Produto</a>
        <a class="btn" href="index.php?r=dashboard">Voltar ao painel</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>SKU</th>
                <th>Categoria</th>
                <th>Material</th>
                <th>Tamanho</th>
                <th>Peso (g)</th>
                <th>Estoque Min</th>
                <th>Estoque Atual</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($items)): foreach ($items as $p): ?>
            <tr>
                <td><?php echo (int)$p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($p['sku'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($p['material'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($p['size'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo (int)$p['weight_grams']; ?></td>
                <td><?php echo (int)$p['min_stock']; ?></td>
                <td><?php echo (int)$p['current_stock']; ?></td>
                <td>
                    <a class="btn" href="index.php?r=productsEdit&id=<?php echo (int)$p['id']; ?>">Editar</a>
                    <form class="inline" method="post" action="index.php?r=productsDelete" onsubmit="return confirm('Deseja excluir este produto?');">
                        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                        <button type="submit" class="btn">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="10">Nenhum produto encontrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

//updated log