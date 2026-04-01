<?php
class AuthController extends Controller {

    public function login() {
        if (isLoggedIn()) {
            redirect('index.php?c=dashboard');
        }

        if ($this->isPost()) {
            csrf_check();
            $email = $this->postParam('email');
            $password = $_POST['password'] ?? '';

            $user = Usuario::authenticate($email, $password);
            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol'],
                ];
                redirect('index.php?c=dashboard');
            } else {
                flash('error', 'Email o contrasena incorrectos');
                redirect('index.php?c=auth&a=login');
            }
        }

        $this->viewRaw('auth/login');
    }

    public function logout() {
        session_destroy();
        redirect('index.php?c=auth&a=login');
    }

    public function password() {
        $this->requireAuth();

        if ($this->isPost()) {
            csrf_check();
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $user = Usuario::findById(currentUser()['id']);

            if (!password_verify($current, $user['password'])) {
                flash('error', 'La contrasena actual no es correcta');
            } elseif (strlen($new) < 6) {
                flash('error', 'La nueva contrasena debe tener al menos 6 caracteres');
            } elseif ($new !== $confirm) {
                flash('error', 'Las contrasenas no coinciden');
            } else {
                Usuario::update($user['id'], ['password' => password_hash($new, PASSWORD_DEFAULT)]);
                flash('success', 'Contrasena actualizada correctamente');
            }
            redirect('index.php?c=auth&a=password');
        }

        $this->view('auth/password', ['pageTitle' => 'Cambiar Contrasena']);
    }
}
