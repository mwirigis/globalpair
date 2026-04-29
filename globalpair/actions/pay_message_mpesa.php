<?php
/**
 * GlobePair - Handle M-Pesa Message Payment
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pay_message_mpesa') {
    requireLogin();
    
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
    $to_user_id = intval($_POST['to_user_id'] ?? 0);
    
    if (strlen($phone) === 12 && substr($phone, 0, 3) === '254' && $to_user_id) {
        $reference = 'MSG_' . $current_user->id . '_' . time();
        
        $result = initiateMpesaSTKPush($phone, MESSAGE_FEE_KES, $reference);
        
        if ($result['success']) {
            $payment = R::dispense('messagepayment');
            $payment->user_id = $current_user->id;
            $payment->phone = $phone;
            $payment->amount = MESSAGE_FEE_KES;
            $payment->currency = 'KES';
            $payment->status = 'pending';
            $payment->mpesa_receipt = null;
            $payment->checkout_request_id = $result['checkout_request_id'];
            $payment->merchant_request_id = $result['merchant_request_id'];
            $payment->reference = $reference;
            $payment->to_user_id = $to_user_id;
            $payment->payment_method = 'mpesa';
            $payment->expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $payment->created_at = date('Y-m-d H:i:s');
            R::store($payment);
            
            $_SESSION['success'] = 'M-Pesa prompt sent! Enter your PIN to complete KES ' . MESSAGE_FEE_KES . ' payment.';
            header('Location: ?action=payment_status&type=message&id=' . $payment->id);
            exit;
        } else {
            $_SESSION['error'] = 'M-Pesa error: ' . $result['error'];
        }
    } else {
        $_SESSION['error'] = 'Invalid phone number format';
    }
    header('Location: ?action=payment_required&user_id=' . $to_user_id);
    exit;
}
?>