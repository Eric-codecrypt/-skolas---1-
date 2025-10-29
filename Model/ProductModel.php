<?php

require_once __DIR__ . '/../Config.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../Config.php';
    }

    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($term)
    {
        $term = '%' . $term . '%';
        $stmt = $this->db->prepare('SELECT * FROM products WHERE name LIKE ? OR sku LIKE ? OR category LIKE ? ORDER BY id DESC');
        $stmt->execute([$term, $term, $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO products (name, sku, category, material, size, weight_grams, min_stock, current_stock) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $data['name'], $data['sku'], $data['category'], $data['material'], $data['size'],
            (int)$data['weight_grams'], (int)$data['min_stock'], (int)$data['current_stock']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE products SET name=?, sku=?, category=?, material=?, size=?, weight_grams=?, min_stock=?, current_stock=? WHERE id=?');
        return $stmt->execute([
            $data['name'], $data['sku'], $data['category'], $data['material'], $data['size'],
            (int)$data['weight_grams'], (int)$data['min_stock'], (int)$data['current_stock'], (int)$id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id=? LIMIT 1');
        return $stmt->execute([(int)$id]);
    }
}

//updated log