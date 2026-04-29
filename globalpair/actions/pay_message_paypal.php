<?php
/**
 * GlobePair - Handle PayPal Message Payment
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pay_message_paypal') {
    requireLogin();
    
    $to_user_id = intval($_POST['to_user_id'] ?? 0);
    
    if ($to_user_id) {
        $reference = 'MSG_' . $current_user->id . '_' . time();
        
        $result = createPayPalOrder(MESSAGE_FEE_USD, $reference);
        
        if ($result['success']) {
            $payment = R::dispense('messagepayment');
            $payment->user_id = $current_user->id;
            $payment->amount = MESSAGE_FEE_USD;
            $payment->currency = 'USD';
            $payment->status = 'pending';
            $payment->paypal_order_id = $result['order_id'];
            $payment->reference = $reference;
            $payment->to_user_id = $to_user_id;
            $payment->payment_method = 'paypal';
            $payment->expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $payment->created_at = date('Y-m-d H:i:s');
            R::store($payment);
            
            if ($result['approve_url']) {
                header('Location: ' . $result['approve_url']);
                exit;
            }
        } else {
            $_SESSION['error'] = 'PayPal error: ' . $result['error'];
        }
    }
    header('Location: ?action=payment_required&user_id=' . $to_user_id);
    exit;
}
?>