<?php
/**
 * GlobePair - Handle Premium Upgrade
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upgrade_premium') {
    requireLogin();
    
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
    
    if (strlen($phone) === 12 && substr($phone, 0, 3) === '254') {
        $reference = 'PREM_' . $current_user->id . '_' . time();
        
        $result = initiateMpesaSTKPush($phone, PREMIUM_PRICE, $reference);
        
        if ($result['success']) {
            $payment = R::dispense('payment');
            $payment->user_id = $current_user->id;
            $payment->phone = $phone;
            $payment->amount = PREMIUM_PRICE;
            $payment->status = 'pending';
            $payment->mpesa_receipt = null;
            $payment->checkout_request_id = $result['checkout_request_id'];
            $payment->merchant_request_id = $result['merchant_request_id'];
            $payment->reference = $reference;
            $payment->payment_type = 'premium';
            $payment->created_at = date('Y-m-d H:i:s');
            R::store($payment);
            
            $_SESSION['success'] = 'M-Pesa prompt sent to your phone. Please enter your PIN to complete payment.';
            header('Location: ?action=payment_status&id=' . $payment->id);
            exit;
        } else {
            $_SESSION['error'] = 'M-Pesa error: ' . $result['error'];
        }
    } else {
        $_SESSION['error'] = 'Invalid phone number format. Use 254712345678';
    }
    header('Location: ?action=premium');
    exit;
}
?>