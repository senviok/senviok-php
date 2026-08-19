# Senviok PHP SDK

The official PHP SDK for the [Senviok](https://senviok.live) notification and messaging infrastructure API.

## Installation

You can install the SDK via [Composer](https://getcomposer.org/):

```bash
composer require senviok/senviok-php
```

Make sure you have GuzeHttp installed (Composer will automatically attempt to install Guzzle 7 when installing this package).

---

## Quick Start

### 1. Initialize Client
```php
require_once 'vendor/autoload.php';

use Senviok\Senviok;

// Initialize client with your API key
$senviok = new Senviok('svk_live_your_api_key_here');
```

### 2. Send an Email
```php
try {
    $response = $senviok->emails->send([
        'from' => 'Sender Name <onboarding@senviok.live>',
        'to' => 'user@example.com',
        'subject' => 'Welcome to Senviok!',
        'html' => '<h1>Hello World</h1><p>This is a transactional email.</p>'
    ]);

    echo "Email Sent! ID: " . $response['id'] . "\n";
} catch (\Senviok\Exceptions\ApiException $e) {
    echo "Error (" . $e->getStatusCode() . "): " . $e->getMessage() . "\n";
}
```

---

## API Documentation

### Emails & Inbox
```php
// Send email with attachments
$senviok->emails->send([
    'from' => 'verify@yourdomain.com',
    'to' => 'recipient@example.com',
    'subject' => 'Monthly Report',
    'html' => '<p>Attached is your invoice.</p>',
    'attachments' => [
        [
            'filename' => 'invoice.pdf',
            'contentType' => 'application/pdf',
            'content' => base64_encode(file_get_contents('invoice.pdf'))
        ]
    ]
]);

// List incoming emails in your inbox
$inbox = $senviok->emails->listInbox([
    'limit' => 20,
    'offset' => 0
]);

// Get incoming email details
$email = $senviok->emails->getInbox('inbound_msg_id');

// Reply to an incoming thread
$senviok->emails->replyInbox('inbound_msg_id', [
    'textBody' => 'Hi, thanks for reaching out. We received your request.'
]);
```

### SMS
```php
$senviok->sms->send([
    'from' => 'SVKALERT',
    'to' => '+2348012345678',
    'sms' => 'Your OTP code is 981249. Expires in 5 minutes.'
]);
```

### WhatsApp
```php
$senviok->whatsapp->send([
    'from' => '+2348012345678', // Sender number
    'to' => '+2348087654321',   // Recipient
    'text' => 'Your order #28103 has been shipped!'
]);
```

### OTP Verification
```php
// Send OTP
$otp = $senviok->otp->send([
    'to' => '+2348012345678',
    'from' => 'SVKALERT',
    'channel' => 'SMS',
    'expiryMinutes' => 5
]);

// Resend OTP (keeps the same code if still valid to reduce spam/costs)
$resend = $senviok->otp->resend([
    'to' => '+2348012345678',
    'from' => 'SVKALERT',
    'channel' => 'SMS'
]);

// Verify OTP
$verification = $senviok->otp->verify([
    'to' => '+2348012345678',
    'code' => '123456'
]);
```

### Templates CRUD
```php
// Create
$template = $senviok->templates->create([
    'name' => 'Verify Account',
    'subject' => 'Verification Code: {{code}}',
    'htmlContent' => '<p>Use code <b>{{code}}</b> to complete signup.</p>'
]);

// Update
$senviok->templates->update('tmpl_id', [
    'name' => 'Updated Name'
]);

// Delete
$senviok->templates->delete('tmpl_id');
```

### Custom Domains
```php
// Register domain
$domain = $senviok->domains->create(['name' => 'mydomain.com']);

// Get DKIM keys
$dkim = $senviok->domains->getDkim('dom_id');

// Verify DNS records
$result = $senviok->domains->verify('dom_id');
```

---

## Webhook Signature Verification

To verify that webhook requests coming into your application are sent by Senviok, use the signature verification utility:

```php
$rawBody = file_get_contents('php://input');
$signature = $_SERVER['HTTP_SVK_SIGNATURE'] ?? '';
$secret = 'whsec_your_webhook_secret_here';

$isValid = $senviok->webhooks->verifySignature($rawBody, $signature, $secret);

if ($isValid) {
    // Process event safely
} else {
    // Reject request
    http_response_code(401);
}
```

## License
MIT License.
