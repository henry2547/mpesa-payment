<?php
require_once 'config.php';

header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$phone = isset($data['phone']) ? $data['phone'] : '';

// Validate phone number
if (empty($phone) || !preg_match('/^254[17]\d{8}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number format']);
    exit;
}

// Generate timestamp and password
$timestamp = date('YmdHis');
$password = base64_encode(BUSINESS_SHORT_CODE . PASSKEY . $timestamp);

// Prepare STK push request
$stkPushUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$accessToken = generateAccessToken();

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
];

$payload = [
    'BusinessShortCode' => BUSINESS_SHORT_CODE,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => TRANSACTION_TYPE,
    'Amount' => '1',
    'PartyA' => $phone,
    'PartyB' => BUSINESS_SHORT_CODE,
    'PhoneNumber' => $phone,
    'CallBackURL' => CALLBACK_URL,
    'AccountReference' => ACCOUNT_REFERENCE,
    'TransactionDesc' => TRANSACTION_DESC
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $stkPushUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
    echo json_encode(['success' => true, 'message' => 'STK push initiated successfully']);
} else {
    $error = $result['errorMessage'] ?? 'Failed to initiate payment';
    echo json_encode(['success' => false, 'message' => $error]);
}
?>