<?php
// M-Pesa API Configuration
define('CONSUMER_KEY', 'your_consumer_key');
define('CONSUMER_SECRET', 'your_consumer_secret');
define('BUSINESS_SHORT_CODE', 'your_business_short_code');
define('PASSKEY', 'your_passkey');
define('TRANSACTION_TYPE', 'CustomerPayBillOnline');
define('CALLBACK_URL', 'https://yourdomain.com/callback.php'); // Update with your domain
define('ACCOUNT_REFERENCE', 'TestPayment');
define('TRANSACTION_DESC', 'Payment for service');

// Database configuration (optional for storing transactions)
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'mpesa_payments');

// Generate access token
function generateAccessToken() {
    $credentials = CONSUMER_KEY . ':' . CONSUMER_SECRET;
    $encodedCredentials = base64_encode($credentials);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $encodedCredentials));
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response);
    return $data->access_token;
}
?>