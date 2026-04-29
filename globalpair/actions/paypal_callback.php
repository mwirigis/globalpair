<?php
/**
 * GlobePair - PayPal Callback Handler
 */

if ($action === 'paypal_success') {
    requireLogin();
    
    $payment_id = intval($_GET['payment_id'] ?? 0);
    $token = sanitize($_GET['token'] ?? '');
    
    $payment = R::load('messagepayment', $payment_id);
    
    if ($payment->id && $payment->status === 'pending' && $payment->payment_method === 'paypal') {
        $result = capturePayPalOrder($payment->paypal_order_id);
        
        if ($result['success']) {
            $payment->status = 'completed';
            $payment->paypal_transaction_id = $result['transaction_id'];
            $payment->completed_at = date('Y-m-d H:i:s');
            R::store($payment);
            
            $_SESSION['success'] = 'Payment successful! You can now send messages.';
            header('Location: ?action=chat&user_id=' . $payment->to_user_id);
            exit;
        } else {
            $_SESSION['error'] = 'PayPal capture failed: ' . $result['error'];
        }
    }
    header('Location: ?action=dashboard');
    exit;
}

if ($action === 'paypal_cancel') {
    $_SESSION['error'] = 'Payment cancelled';
    header('Location: ?action=dashboard');
    exit;
}
?>