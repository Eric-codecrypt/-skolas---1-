<?php
session_start();

require_once __DIR__ . '/Controller/UserController.php';
require_once __DIR__ . '\\Controller\\ProductController.php';
require_once __DIR__ . '\\Controller\\InventoryController.php';

// Roteamento simples por query string
$route = isset($_GET['r']) ? $_GET['r'] : 'login';

$userController = new UserController();
$productController = new ProductController();
$inventoryController = new InventoryController();

switch ($route) {
    // Autenticação
    case 'login':
        $userController->showLogin();
        break;
    case 'doLogin':
        $userController->doLogin();
        break;
    case 'register':
        $userController->showRegister();
        break;
    case 'doRegister':
        $userController->doRegister();
        break;
    case 'dashboard':
        $userController->dashboard();
        break;
    case 'logout':
        $userController->logout();
        break;

    // Produtos
    case 'products':
        $productController->index();
        break;
    case 'productsCreate':
        $productController->create();
        break;
    case 'productsStore':
        $productController->store();
        break;
    case 'productsEdit':
        $productController->edit();
        break;
    case 'productsUpdate':
        $productController->update();
        break;
    case 'productsDelete':
        $productController->delete();
        break;

    // Estoque
    case 'inventory':
        $inventoryController->index();
        break;
    case 'inventoryMove':
        $inventoryController->move();
        break;

    default:
        $userController->showLogin();
        break;
}
