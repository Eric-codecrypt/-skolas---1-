<?php
if (!isset($_SESSION)) { session_start(); }
if (empty($_SESSION['user'])) { header('Location: index.php?r=login'); exit; }
$csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title><?php echo $mode === 'edit' ? 'Editar Produto' : 'Novo Produto'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family: Arial, sans-serif; margin:24px;}
        .form{max-width:700px}
        label{display:block; margin:8px 0 4px;}
        input{width:100%; padding:8px; box-sizing:border-box;}
        .row{display:grid; grid-template-columns: 1fr 1fr; gap:12px;}
        .actions{margin-top:12px; display:flex; gap:8px;}
        .btn{padding:8px 12px; border:1px solid #ccc; background:#fafafa; border-radius:4px; text-decoration:none;}
    </style>
</head>
<body>
    <h1><?php echo $mode === 'edit' ? 'Editar Produto' : 'Novo Produto'; ?></h1>
    <form class="form" method="post" action="index.php?r=<?php echo $mode === 'edit' ? 'productsUpdate' : 'productsStore'; ?>">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($mode === 'edit'): ?>
            <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
        <?php endif; ?>
        <label for="name">Nome</label>
        <input id="name" name="name" type="text" required minlength="2" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="row">
            <div>
                <label for="sku">SKU</label>
                <input id="sku" name="sku" type="text" required value="<?php echo htmlspecialchars($product['sku'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div>
                <label for="category">Categoria</label>
                <input id="category" name="category" type="text" value="<?php echo htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <div class="row">
            <div>
                <label for="material">Material</label>
                <input id="material" name="material" type="text" value="<?php echo htmlspecialchars($product['material'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div>
                <label for="size">Tamanho</label>
                <input id="size" name="size" type="text" value="<?php echo htmlspecialchars($product['size'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <div class="row">
            <div>
                <label for="weight_grams">Peso (g)</label>
                <input id="weight_grams" name="weight_grams" type="number" min="0" step="1" value="<?php echo (int)$product['weight_grams']; ?>">
            </div>
            <div>
                <label for="min_stock">Estoque Mínimo</label>
                <input id="min_stock" name="min_stock" type="number" min="0" step="1" value="<?php echo (int)$product['min_stock']; ?>">
            </div>
        </div>
        <div class="row">
            <div>
                <label for="current_stock">Estoque Atual</label>
                <input id="current_stock" name="current_stock" type="number" min="0" step="1" value="<?php echo (int)$product['current_stock']; ?>">
            </div>
        </div>
        <div class="actions">
            <button class="btn" type="submit">Salvar</button>
            <a class="btn" href="index.php?r=products">Cancelar</a>
        </div>
    </form>
</body>
</html>

//updated log