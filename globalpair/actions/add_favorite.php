<?php
/**
 * GlobePair - Handle Adding Favorite
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_favorite') {
    requireLogin();
    
    $favorite_user_id = intval($_POST['favorite_user_id'] ?? 0);
    
    if ($favorite_user_id && $favorite_user_id !== $current_user->id) {
        $existing = R::findOne('favorite', 'user_id = ? AND favorite_user_id = ?', [$current_user->id, $favorite_user_id]);
        
        if (!$existing) {
            $favorite = R::dispense('favorite');
            $favorite->user_id = $current_user->id;
            $favorite->favorite_user_id = $favorite_user_id;
            $favorite->created_at = date('Y-m-d H:i:s');
            R::store($favorite);
            $_SESSION['success'] = 'Added to favorites!';
        }
    }
    
    $redirect = $_POST['redirect'] ?? 'view_profile';
    if ($redirect === 'home') {
        header('Location: ?action=home#members');
    } else {
        header('Location: ?action=view_profile&id=' . $favorite_user_id);
    }
    exit;
}
?>