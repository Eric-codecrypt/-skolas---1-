<?php

require_once __DIR__ . '/../Model/InventoryModel.php';

class InventoryController
{
    private $inventory;

    public function __construct()
    {
        $this->inventory = new InventoryModel();
    }

    private function requireAuth()
    {
        if (!isset($_SESSION)) { session_start(); }
        if (empty($_SESSION['user'])) {
            header('Location: index.php?r=login');
            exit;
        }
    }

    private function ensureCsrfToken()
    {
        if (!isset($_SESSION)) { session_start(); }
        if (empty($_SESSION['csrf'])) {
            $bytes = function_exists('random_bytes') ? random_bytes(16) : openssl_random_pseudo_bytes(16);
            $_SESSION['csrf'] = bin2hex($bytes);
        }
        return $_SESSION['csrf'];
    }

    private function checkCsrf()
    {
        if (!isset($_SESSION)) { session_start(); }
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            http_response_code(400);
            echo 'CSRF inválido';
            exit;
        }
    }

    private function bubbleSortByName(&$items)
    {
        // Algoritmo de ordenação explícito (Bubble Sort) por nome
        $n = count($items);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                $a = mb_strtolower($items[$j]['name']);
                $b = mb_strtolower($items[$j + 1]['name']);
                if ($a > $b) {
                    $tmp = $items[$j];
                    $items[$j] = $items[$j + 1];
                    $items[$j + 1] = $tmp;
                }
            }
        }
    }

    public function index()
    {
        $this->requireAuth();
        $this->ensureCsrfToken();
        $products = $this->inventory->listProducts();
        $this->bubbleSortByName($products);
        $movements = $this->inventory->listMovements(20);
        include __DIR__ . '/../View/inventory.php';
    }

    public function move()
    {
        $this->requireAuth();
        $this->checkCsrf();
        $userId = $_SESSION['user']['id'];
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $date = isset($_POST['movement_date']) ? $_POST['movement_date'] : date('Y-m-d');
        $note = isset($_POST['note']) ? trim($_POST['note']) : '';

        try {
            $belowMin = false;
            $newStock = $this->inventory->move($productId, $userId, $type, $quantity, $date, $note, $belowMin);
            $_SESSION['flash'] = 'Movimentação registrada. Novo estoque: ' . $newStock . '.';
            if ($belowMin) {
                $_SESSION['flash'] .= ' Alerta: estoque abaixo do mínimo configurado!';
            }
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Erro: ' . $e->getMessage();
        }
        header('Location: index.php?r=inventory');
    }
}
