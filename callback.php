<?php
require_once 'config.php';

// Log the callback data (for debugging)
file_put_contents('callback_log.txt', file_get_contents('php://input') . "\n\n", FILE_APPEND);

// Get the callback data
$callbackData = json_decode(file_get_contents('php://input'), true);

if (isset($callbackData['Body']['stkCallback'])) {
    $resultCode = $callbackData['Body']['stkCallback']['ResultCode'];
    $resultDesc = $callbackData['Body']['stkCallback']['ResultDesc'];
    $checkoutRequestID = $callbackData['Body']['stkCallback']['CheckoutRequestID'];
    
    if ($resultCode == 0) {
        // Transaction was successful
        $merchantRequestID = $callbackData['Body']['stkCallback']['MerchantRequestID'];
        $checkoutRequestID = $callbackData['Body']['stkCallback']['CheckoutRequestID'];
        $amount = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
        $mpesaReceiptNumber = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
        $transactionDate = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];
        $phoneNumber = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
        
        // Here you can save the transaction to your database
        // Example:
        /*
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = $db->prepare("INSERT INTO transactions 
                             (merchant_request_id, checkout_request_id, amount, mpesa_receipt, transaction_date, phone_number, status)
                             VALUES (?, ?, ?, ?, ?, ?, 'completed')");
        $stmt->bind_param("ssdsss", $merchantRequestID, $checkoutRequestID, $amount, $mpesaReceiptNumber, $transactionDate, $phoneNumber);
        $stmt->execute();
        $stmt->close();
        $db->close();
        */
        
        // Respond to M-Pesa that callback was received
        header('Content-Type: application/json');
        echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Callback processed successfully']);
    } else {
        // Transaction failed
        // You can log this or update your database
        header('Content-Type: application/json');
        echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Failed to process transaction']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Invalid callback data']);
}
?>