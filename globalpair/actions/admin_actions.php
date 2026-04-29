<?php
/**
 * GlobePair - Admin Actions
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    requireAdmin();
    
    $user_id = intval($_POST['user_id'] ?? 0);
    $new_status = sanitize($_POST['new_status'] ?? 'active');
    
    if ($user_id) {
        $user = R::load('user', $user_id);
        if ($user->id && !$user->is_admin) {
            $user->status = $new_status;
            $user->updated_at = date('Y-m-d H:i:s');
            R::store($user);
            $_SESSION['success'] = 'User status updated to ' . $new_status;
        }
    }
    header('Location: ?action=admin');
    exit;
}
?>