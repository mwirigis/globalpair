<?php
/**
 * GlobePair - M-Pesa Callback Handler (Webhook)
 */

if ($action === 'mpesa_callback') {
    $rawData = file_get_contents('php://input');
    $logFile = __DIR__ . '/../logs/mpesa_callback.log';
    
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Received: " . $rawData . "\n\n", FILE_APPEND);
    
    $data = json_decode($rawData, true);

    if (isset($data['Body']['stkCallback'])) {
        $callback = $data['Body']['stkCallback'];
        $checkoutId = $callback['CheckoutRequestID'] ?? null;
        $resultCode = $callback['ResultCode'] ?? 1;
        
        if ($checkoutId) {
            $payment = R::findOne('payment', 'checkout_request_id = ?', [$checkoutId]);
            
            if (!$payment) {
                $payment = R::findOne('messagepayment', 'checkout_request_id = ?', [$checkoutId]);
            }
            
            if ($payment && $payment->id) {
                if ($resultCode == 0) {
                    $mpesaReceipt = '';
                    if (isset($callback['CallbackMetadata']['Item'])) {
                        foreach ($callback['CallbackMetadata']['Item'] as $item) {
                            if ($item['Name'] == 'MpesaReceiptNumber') {
                                $mpesaReceipt = $item['Value'];
                            }
                        }
                    }
                    
                    $payment->status = 'completed';
                    $payment->mpesa_receipt = $mpesaReceipt;
                    $payment->completed_at = date('Y-m-d H:i:s');
                    
                    $isPremium = (isset($payment->payment_type) && $payment->payment_type == 'premium');
                    
                    R::store($payment);
                    
                    if ($isPremium) {
                        $user = R::load('user', $payment->user_id);
                        if ($user->id) {
                            $user->user_role = 'premium';
                            $user->premium_until = date('Y-m-d', strtotime('+30 days'));
                            R::store($user);
                        }
                    }
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - SUCCESS: Receipt {$mpesaReceipt}\n\n", FILE_APPEND);
                } else {
                    $payment->status = 'failed';
                    R::store($payment);
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - FAILED: Code {$resultCode}\n\n", FILE_APPEND);
                }
            } else {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - WARNING: CheckoutID not found in DB.\n\n", FILE_APPEND);
            }
        }
    } else {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Invalid callback format.\n\n", FILE_APPEND);
    }

    http_response_code(200);
    echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    exit;
}
?>