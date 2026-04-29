<?php
/**
 * GlobePair - Handle Profile Update
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    requireLogin();
    
    $current_user->first_name = sanitize($_POST['first_name'] ?? '');
    $current_user->last_name = sanitize($_POST['last_name'] ?? '');
    $current_user->city = sanitize($_POST['city'] ?? '');
    $current_user->bio = sanitize($_POST['bio'] ?? '');
    $current_user->birth_date = $_POST['birth_date'] ?? '';
    $current_user->gender = sanitize($_POST['gender'] ?? '');
    $current_user->updated_at = date('Y-m-d H:i:s');
    
    R::store($current_user);
    $_SESSION['success'] = 'Profile updated successfully!';
    header('Location: ?action=dashboard');
    exit;
}
?>