<?php

require_once __DIR__ . '/../Config.php';

class InventoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../Config.php';
    }

    public function listProducts()
    {
        $stmt = $this->db->query('SELECT id, name, sku, min_stock, current_stock FROM products');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listMovements($limit = 20)
    {
        $stmt = $this->db->prepare('SELECT m.*, p.name AS product_name, u.name AS user_name
            FROM inventory_movements m
            JOIN products p ON p.id = m.product_id
            JOIN users u ON u.id = m.user_id
            ORDER BY m.created_at DESC
            LIMIT ?');
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function move($productId, $userId, $type, $quantity, $date, $note, &$belowMin)
    {
        $belowMin = false;
        $this->db->beginTransaction();
        try {
            // Carrega produto para verificar estoque
            $stmt = $this->db->prepare('SELECT id, current_stock, min_stock FROM products WHERE id = ? FOR UPDATE');
            $stmt->execute([(int)$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) { throw new Exception('Produto inválido.'); }
            if ($quantity <= 0) { throw new Exception('Quantidade deve ser maior que zero.'); }
            if ($type !== 'entrada' && $type !== 'saida') { throw new Exception('Tipo de movimento inválido.'); }

            $newStock = (int)$product['current_stock'];
            if ($type === 'entrada') {
                $newStock += $quantity;
            } else {
                // saída
                if ($quantity > $newStock) {
                    throw new Exception('Quantidade de saída maior que o estoque atual.');
                }
                $newStock -= $quantity;
            }

            // Atualiza estoque do produto
            $upd = $this->db->prepare('UPDATE products SET current_stock = ? WHERE id = ?');
            $upd->execute([$newStock, (int)$productId]);

            // Registra movimento
            $ins = $this->db->prepare('INSERT INTO inventory_movements (product_id, user_id, type, quantity, movement_date, note) VALUES (?,?,?,?,?,?)');
            $ins->execute([(int)$productId, (int)$userId, $type, (int)$quantity, $date, $note]);

            // Verifica estoque mínimo após saída
            if ($type === 'saida' && $newStock < (int)$product['min_stock']) {
                $belowMin = true;
            }

            $this->db->commit();
            return $newStock;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
