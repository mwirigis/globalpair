<?php
/**
 * GlobePair - Helper Functions
 * All reusable functions for the application
 */

// ==================== SANITIZATION ====================
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// ==================== AUTHENTICATION ====================
function getCurrentUser() {
    return isset($_SESSION['user_id']) ? R::load('user', $_SESSION['user_id']) : null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    $user = getCurrentUser();
    return $user && $user->is_admin == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
        header('Location: ?action=login');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['error'] = 'Access denied. Admin only.';
        header('Location: ?action=home');
        exit;
    }
}

// ==================== CSRF PROTECTION ====================
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ==================== FILE UPLOAD ====================
function handlePhotoUpload($file, $user_id = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['error' => 'File size must be less than 5MB'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_TYPES)) {
        return ['error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP'];
    }
    
    $extension = '';
    switch ($mime_type) {
        case 'image/jpeg': $extension = '.jpg'; break;
        case 'image/png': $extension = '.png'; break;
        case 'image/gif': $extension = '.gif'; break;
        case 'image/webp': $extension = '.webp'; break;
    }
    
    $filename = ($user_id ? 'user_' . $user_id : 'temp_' . uniqid()) . '_' . time() . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/' . $filename;
    }
    
    return ['error' => 'Failed to upload file'];
}

// ==================== MESSAGING & PERMISSIONS ====================
function canSendMessage($from_user, $to_user) {
    if ($from_user->user_role === 'premium' && $to_user->user_role === 'premium') {
        return true;
    }
    
    $payment = R::findOne('messagepayment', 'user_id = ? AND status = ? AND expires_at > ?', 
        [$from_user->id, 'completed', date('Y-m-d H:i:s')]);
    
    return (bool)$payment;
}

// ==================== M-PESA FUNCTIONS ====================
function getMpesaAccessToken() {
    $url = MPESA_SANDBOX ? 
        'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials' :
        'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    
    $credentials = base64_encode(MPESA_CONSUMER_KEY . ':' . MPESA_CONSUMER_SECRET);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response);
    return $result->access_token ?? null;
}

function initiateMpesaSTKPush($phone, $amount, $reference) {
    $access_token = getMpesaAccessToken();
    if (!$access_token) {
        return ['success' => false, 'error' => 'Failed to get M-Pesa access token'];
    }
    
    $timestamp = date('YmdHis');
    $password = base64_encode(MPESA_SHORTCODE . MPESA_PASSKEY . $timestamp);
    
    $url = MPESA_SANDBOX ?
        'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest' :
        'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    
    $data = [
        'BusinessShortCode' => MPESA_SHORTCODE,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phone,
        'PartyB' => MPESA_PAYBILL,
        'PhoneNumber' => $phone,
        'CallBackURL' => MPESA_CALLBACK_URL,
        'AccountReference' => $reference,
        'TransactionDesc' => 'GlobePair Payment'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response);
    
    if (isset($result->ResponseCode) && $result->ResponseCode === '0') {
        return [
            'success' => true,
            'checkout_request_id' => $result->CheckoutRequestID,
            'merchant_request_id' => $result->MerchantRequestID
        ];
    }
    
    return ['success' => false, 'error' => $result->errorMessage ?? 'M-Pesa request failed'];
}

// ==================== PAYPAL FUNCTIONS ====================
function getPayPalAccessToken() {
    $url = PAYPAL_MODE === 'sandbox' ?
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' :
        'https://api-m.paypal.com/v1/oauth2/token';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response);
    return $result->access_token ?? null;
}

function createPayPalOrder($amount, $reference) {
    $access_token = getPayPalAccessToken();
    if (!$access_token) {
        return ['success' => false, 'error' => 'Failed to get PayPal access token'];
    }
    
    $url = PAYPAL_MODE === 'sandbox' ?
        'https://api-m.sandbox.paypal.com/v2/checkout/orders' :
        'https://api-m.paypal.com/v2/checkout/orders';
    
    $data = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'reference_id' => $reference,
            'amount' => [
                'currency_code' => PAYPAL_CURRENCY,
                'value' => $amount
            ],
            'description' => 'GlobePair Message Fee'
        ]]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response);
    
    if (isset($result->id)) {
        foreach ($result->links as $link) {
            if ($link->rel === 'approve') {
                return [
                    'success' => true,
                    'order_id' => $result->id,
                    'approve_url' => $link->href
                ];
            }
        }
        return [
            'success' => true,
            'order_id' => $result->id,
            'approve_url' => null
        ];
    }
    
    return ['success' => false, 'error' => $result->message ?? 'PayPal order creation failed'];
}

function capturePayPalOrder($order_id) {
    $access_token = getPayPalAccessToken();
    if (!$access_token) {
        return ['success' => false, 'error' => 'Failed to get PayPal access token'];
    }
    
    $url = (PAYPAL_MODE === 'sandbox' ?
        'https://api-m.sandbox.paypal.com/v2/checkout/orders/' :
        'https://api-m.paypal.com/v2/checkout/orders/') . $order_id . '/capture';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response);
    
    if (isset($result->status) && $result->status === 'COMPLETED') {
        return ['success' => true, 'transaction_id' => $result->purchase_units[0]->payments->captures[0]->id ?? null];
    }
    
    return ['success' => false, 'error' => $result->message ?? 'PayPal capture failed'];
}
?>