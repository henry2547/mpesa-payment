# M-Pesa Payment Integration Web Application


A complete solution for integrating M-Pesa payments into web applications using PHP and JavaScript. This project enables users to make payments by entering their phone number with country code.

## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Deployment](#deployment)
- [API Reference](#api-reference)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## Features

- M-Pesa STK Push integration
- Phone number validation with country code
- Paybill and Till number support
- Transaction logging and tracking
- Sandbox and production environment support
- Responsive payment form
- Comprehensive callback handling
- MySQL database integration

## Requirements

Before installation, ensure your system has:

- Web server (Apache/Nginx)
- PHP 7.4+
- MySQL 5.7+ or MariaDB 10.2+
- Composer (for dependency management)
- SSL certificate (for production)
- M-Pesa API credentials from Safaricom Developer Portal

## Installation

Follow these steps to set up the project:

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/mpesa-payment-integration.git
   cd mpesa-payment-integration
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up the database:
   ```bash
   mysql -u yourusername -p yourdatabase < database/schema.sql
   ```

4. Set file permissions:
   ```bash
   chmod -R 755 storage/logs/
   chmod 644 config.php
   ```

## Configuration

1. Copy the sample configuration file:
   ```bash
   cp config.sample.php config.php
   ```

2. Edit `config.php` with your credentials:
   ```php
   // M-Pesa API Credentials
   define('CONSUMER_KEY', 'your_consumer_key_here');
   define('CONSUMER_SECRET', 'your_consumer_secret_here');
   
   // Business Details
   define('BUSINESS_SHORT_CODE', '123456'); // Your Paybill/Till number
   define('PASSKEY', 'your_passkey_here');
   define('ACCOUNT_NUMBER', 'YOUR_ACCOUNT_REF');
   
   // Database Configuration
   define('DB_HOST', 'localhost');
   define('DB_USER', 'db_username');
   define('DB_PASS', 'db_password');
   define('DB_NAME', 'mpesa_payments');
   ```

3. Configure your web server:

   **Apache (.htaccess)**
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php [QSA,L]
   ```

   **Nginx**
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

## Usage

### Running the Application

1. Start your web server:
   ```bash
   php -S localhost:8000
   ```

2. Access the payment form at:
   ```
   http://localhost:8000
   ```

### Payment Flow

1. User enters phone number (format: 2547XXXXXXXX)
2. System validates the number
3. STK Push request initiated
4. User receives payment prompt on phone
5. Transaction processed and recorded

## Testing

### Sandbox Testing

Use these test credentials in `config.php`:

```php
// Sandbox Credentials
define('BUSINESS_SHORT_CODE', '174379');
define('PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');
```

Test phone numbers:
- 254708374149
- 254712345678
- 254722123456

Test amount: 1 KSH

## Deployment

### Production Checklist

1. Update to live API endpoints:
   ```php
   define('AUTH_URL', 'https://api.safaricom.co.ke/oauth/v1/generate');
   define('STK_PUSH_URL', 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
   ```

2. Configure HTTPS:
   ```nginx
   server {
       listen 443 ssl;
       server_name yourdomain.com;
       ssl_certificate /etc/ssl/yourdomain.crt;
       ssl_certificate_key /etc/ssl/yourdomain.key;
       root /var/www/mpesa-payment;
       index index.php;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
       }
   }
   ```

3. Set up cron job for reconciliation:
   ```bash
   * * * * * php /var/www/mpesa-payment/cron/reconcile.php >> /var/log/mpesa_reconciliation.log
   ```

## API Reference

### Endpoints

- `POST /process.php` - Initiate STK Push
  ```json
  {
    "phone": "254712345678"
  }
  ```

- `POST /callback.php` - M-Pesa Callback URL

### Response Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 400 | Bad Request |
| 401 | Unauthorized |
| 500 | Server Error |

## Troubleshooting

### Common Issues

1. **Callback Not Working**
   - Verify HTTPS is properly configured
   - Check server error logs
   - Test callback URL with Postman

2. **Database Connection Issues**
   ```bash
   mysql -u yourusername -p
   > USE mpesa_payments;
   > SHOW TABLES;
   ```

3. **STK Push Failures**
   - Verify phone number format
   - Check account balance
   - Validate API credentials

## Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some feature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding style
- Include PHPDoc comments
- Write unit tests for new features

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) [year] [fullname]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
