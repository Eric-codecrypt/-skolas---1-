<?php

require_once __DIR__ . '/../Model/ProductModel.php';

class ProductController
{
    private $products;

    public function __construct()
    {
        $this->products = new ProductModel();
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

    private function sanitizeProductInput($src)
    {
        return [
            'name' => isset($src['name']) ? trim($src['name']) : '',
            'sku' => isset($src['sku']) ? trim($src['sku']) : '',
            'category' => isset($src['category']) ? trim($src['category']) : '',
            'material' => isset($src['material']) ? trim($src['material']) : '',
            'size' => isset($src['size']) ? trim($src['size']) : '',
            'weight_grams' => isset($src['weight_grams']) ? (int)$src['weight_grams'] : 0,
            'min_stock' => isset($src['min_stock']) ? (int)$src['min_stock'] : 0,
            'current_stock' => isset($src['current_stock']) ? (int)$src['current_stock'] : 0,
        ];
    }

    private function validateProduct($data, &$error)
    {
        if (strlen($data['name']) < 2) { $error = 'Nome do produto é obrigatório (mín. 2).'; return false; }
        if ($data['sku'] === '') { $error = 'SKU é obrigatório.'; return false; }
        if ($data['weight_grams'] < 0) { $error = 'Peso não pode ser negativo.'; return false; }
        if ($data['min_stock'] < 0) { $error = 'Estoque mínimo não pode ser negativo.'; return false; }
        if ($data['current_stock'] < 0) { $error = 'Estoque atual não pode ser negativo.'; return false; }
        return true;
    }

    public function index()
    {
        $this->requireAuth();
        $this->ensureCsrfToken();
        $term = isset($_GET['q']) ? trim($_GET['q']) : '';
        $items = $term !== '' ? $this->products->search($term) : $this->products->all();
        include __DIR__ . '/../View/product_index.php';
    }

    public function create()
    {
        $this->requireAuth();
        $this->ensureCsrfToken();
        $product = [
            'id' => null, 'name' => '', 'sku' => '', 'category' => '', 'material' => '', 'size' => '',
            'weight_grams' => 0, 'min_stock' => 0, 'current_stock' => 0
        ];
        $mode = 'create';
        include __DIR__ . '/../View/product_form.php';
    }

    public function store()
    {
        $this->requireAuth();
        $this->checkCsrf();
        $data = $this->sanitizeProductInput($_POST);
        $error = '';
        if (!$this->validateProduct($data, $error)) {
            $_SESSION['flash'] = $error;
            header('Location: index.php?r=productsCreate');
            return;
        }
        try {
            $this->products->create($data);
            $_SESSION['flash'] = 'Produto cadastrado com sucesso.';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Erro ao salvar: ' . $e->getMessage();
        }
        header('Location: index.php?r=products');
    }

    public function edit()
    {
        $this->requireAuth();
        $this->ensureCsrfToken();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = $this->products->find($id);
        if (!$product) { $_SESSION['flash'] = 'Produto não encontrado.'; header('Location: index.php?r=products'); return; }
        $mode = 'edit';
        include __DIR__ . '/../View/product_form.php';
    }

    public function update()
    {
        $this->requireAuth();
        $this->checkCsrf();
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $data = $this->sanitizeProductInput($_POST);
        $error = '';
        if (!$this->validateProduct($data, $error)) {
            $_SESSION['flash'] = $error;
            header('Location: index.php?r=productsEdit&id=' . $id);
            return;
        }
        try {
            $ok = $this->products->update($id, $data);
            $_SESSION['flash'] = $ok ? 'Produto atualizado.' : 'Nada a atualizar.';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Erro ao atualizar: ' . $e->getMessage();
        }
        header('Location: index.php?r=products');
    }

    public function delete()
    {
        $this->requireAuth();
        $this->checkCsrf();
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        try {
            $this->products->delete($id);
            $_SESSION['flash'] = 'Produto excluído.';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Erro ao excluir: ' . $e->getMessage();
        }
        header('Location: index.php?r=products');
    }
}
