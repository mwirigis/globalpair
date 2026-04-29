<?php
/**
 * GlobePair - Handle Login
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $user = R::findOne('user', 'email = ?', [$email]);
    
    if ($user && password_verify($password, $user->password)) {
        if ($user->status !== 'active') {
            $_SESSION['error'] = 'Your account has been deactivated. Please contact support.';
        } else {
            $_SESSION['user_id'] = $user->id;
            $user->last_login = date('Y-m-d H:i:s');
            R::store($user);
            $_SESSION['success'] = 'Welcome back, ' . $user->first_name . '!';
            header('Location: ?action=dashboard');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Invalid email or password';
    }
}
?>