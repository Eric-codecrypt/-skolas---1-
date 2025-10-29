<?php

require_once __DIR__ . '/../Model/UserModel.php';

class UserController
{
    private $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    private function ensureCsrfToken()
    {
        if (empty($_SESSION['csrf'])) {
            // Gera token compatível com PHP < 7 usando OpenSSL
            $bytes = function_exists('random_bytes') ? random_bytes(16) : openssl_random_pseudo_bytes(16);
            $_SESSION['csrf'] = bin2hex($bytes);
        }
        return $_SESSION['csrf'];
    }

    private function checkCsrf()
    {
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            http_response_code(400);
            echo 'CSRF inválido';
            exit;
        }
    }

    private function requireAuth()
    {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?r=login');
            exit;
        }
    }

    public function showLogin()
    {
        $this->ensureCsrfToken();
        include __DIR__ . '/../View/login.php';
    }

    public function doLogin()
    {
        $this->checkCsrf();
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($email === '' || $password === '') {
            $_SESSION['flash'] = 'Informe e-mail e senha.';
            header('Location: index.php?r=login');
            return;
        }

        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash'] = 'Credenciais inválidas.';
            header('Location: index.php?r=login');
            return;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header('Location: index.php?r=dashboard');
    }

    public function showRegister()
    {
        $this->ensureCsrfToken();
        include __DIR__ . '/../View/register.php';
    }

    public function doRegister()
    {
        $this->checkCsrf();
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['flash'] = 'Dados inválidos. Nome mínimo 2, e-mail válido, senha mínima 6.';
            header('Location: index.php?r=register');
            return;
        }

        if ($this->users->findByEmail($email)) {
            $_SESSION['flash'] = 'E-mail já cadastrado.';
            header('Location: index.php?r=register');
            return;
        }

        $this->users->create($name, $email, $password);
        $_SESSION['flash'] = 'Usuário cadastrado. Faça login.';
        header('Location: index.php?r=login');
    }

    public function dashboard()
    {
        $this->requireAuth();
        include __DIR__ . '/../View/dashboard.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?r=login');
    }
}
//updated log