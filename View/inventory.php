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
    <title>Gestão de Estoque</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family: Arial, sans-serif; margin:24px;}
        .grid{display:grid; grid-template-columns: 1fr; gap:16px;}
        .card{border:1px solid #ddd; border-radius:8px; padding:16px;}
        label{display:block; margin:8px 0 4px;}
        select,input,textarea{width:100%; padding:8px; box-sizing:border-box;}
        .row{display:grid; grid-template-columns:1fr 1fr; gap:12px;}
        table{border-collapse: collapse; width:100%;}
        th,td{border:1px solid #ddd; padding:8px;}
        th{background:#f6f6f6}
        .alert{color:#a00;}
        .btn{padding:8px 12px; border:1px solid #ccc; background:#fafafa; border-radius:4px; text-decoration:none;}
    </style>
</head>
<body>
    <h1>Gestão de Estoque</h1>
    <?php if ($flash): ?>
        <p class="alert"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <div class="grid">
        <div class="card">
            <h2>Nova Movimentação</h2>
            <form method="post" action="index.php?r=inventoryMove">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
                <label for="product_id">Produto</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?php echo (int)$p['id']; ?>">
                            <?php echo htmlspecialchars($p['name'] . ' (' . $p['sku'] . ') - Estoque: ' . $p['current_stock'] . ' | Mín: ' . $p['min_stock'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="row">
                    <div>
                        <label for="type">Tipo</label>
                        <select id="type" name="type" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity">Quantidade</label>
                        <input id="quantity" name="quantity" type="number" min="1" step="1" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="movement_date">Data da Movimentação</label>
                        <input id="movement_date" name="movement_date" type="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="note">Observação</label>
                        <input id="note" name="note" type="text" maxlength="255">
                    </div>
                </div>
                <div style="margin-top:12px; display:flex; gap:8px;">
                    <button class="btn" type="submit">Salvar Movimentação</button>
                    <a class="btn" href="index.php?r=dashboard">Voltar ao painel</a>
                </div>
            </form>
        </div>
        <div class="card">
            <h2>Produtos (ordenados alfabeticamente)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>SKU</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($products)): foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo (int)$p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($p['sku'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int)$p['current_stock']; ?></td>
                        <td><?php echo (int)$p['min_stock']; ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5">Sem produtos.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card">
            <h2>Últimas Movimentações</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Responsável</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($movements)): foreach ($movements as $m): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($m['movement_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($m['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($m['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int)$m['quantity']; ?></td>
                        <td><?php echo htmlspecialchars($m['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($m['note'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6">Ainda sem movimentações.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
