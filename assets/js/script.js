document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const phoneNumber = document.getElementById('phone').value;
    const payButton = document.getElementById('payButton');
    const responseMessage = document.getElementById('responseMessage');
    
    // Validate phone number format
    if (!/^254[17]\d{8}$/.test(phoneNumber)) {
        responseMessage.textContent = 'Please enter a valid Kenyan phone number starting with 254 (e.g., 254712345678)';
        responseMessage.className = 'error';
        responseMessage.style.display = 'block';
        return;
    }
    
    payButton.disabled = true;
    payButton.textContent = 'Processing...';
    responseMessage.style.display = 'none';
    
    // Send request to server
    fetch('process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ phone: phoneNumber }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            responseMessage.textContent = 'Payment request sent successfully. Please check your phone to complete the transaction.';
            responseMessage.className = 'success';
        } else {
            responseMessage.textContent = data.message || 'An error occurred. Please try again.';
            responseMessage.className = 'error';
        }
        responseMessage.style.display = 'block';
    })
    .catch(error => {
        responseMessage.textContent = 'Network error. Please check your connection and try again.';
        responseMessage.className = 'error';
        responseMessage.style.display = 'block';
    })
    .finally(() => {
        payButton.disabled = false;
        payButton.textContent = 'Pay 1 KSH';
    });
});