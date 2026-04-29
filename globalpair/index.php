<?php
/**
 * GlobePair - Main Router/Entry Point
 * Handles all routing and page loading
 */

// Load configuration and functions
require 'config.php';
require 'functions.php';

// Get current user
$current_user = getCurrentUser();
$page_title = SITE_NAME;

// Get action parameter
$action = $_GET['action'] ?? 'home';

// Handle special redirect case
if ($action === 'chat_redirect') {
    $redirect_user_id = intval($_GET['user_id'] ?? 0);
    if (!$current_user) {
        $_SESSION['chat_redirect'] = $redirect_user_id;
        header('Location: ?action=register');
        exit;
    } else {
        header('Location: ?action=chat&user_id=' . $redirect_user_id);
        exit;
    }
}

// Load action handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'actions/login.php';
    include 'actions/register.php';
    include 'actions/update_profile.php';
    include 'actions/send_message.php';
    include 'actions/add_favorite.php';
    include 'actions/upgrade_premium.php';
    include 'actions/pay_message_mpesa.php';
    include 'actions/pay_message_paypal.php';
    include 'actions/admin_actions.php';
}

// Handle special cases
include 'actions/logout.php';
include 'actions/mpesa_callback.php';
include 'actions/paypal_callback.php';

// Route to pages
match ($action) {
    'home' => include 'pages/home.php',
    'login' => include 'pages/login.php',
    'register' => include 'pages/register.php',
    'dashboard' => include 'pages/dashboard.php',
    'discover' => include 'pages/discover.php',
    'view_profile' => include 'pages/view_profile.php',
    'chat' => include 'pages/chat.php',
    'favorites' => include 'pages/favorites.php',
    'edit_profile' => include 'pages/edit_profile.php',
    'premium' => include 'pages/premium.php',
    'payment_required' => include 'pages/payment_required.php',
    'payment_status' => include 'pages/payment_status.php',
    'my_payments' => include 'pages/my_payments.php',
    'admin' => include 'pages/admin.php',
    default => include 'pages/home.php',
};
?>