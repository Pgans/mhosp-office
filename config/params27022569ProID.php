<?php

return [
    'adminEmail' => 'admin@example.com',
    
    // Telegram Configuration
    'telegram' => [
        'token' => '7559782200:AAHvRkNmDm5-bGe3NKUGIsvjzEecJQDKuQA',
        'chatId' => '-4721636170'
    ],
	 // ===== Step 1: Health ID (moph.id.th) OAuth2 =====
    'healthId' => [
        //'baseUrl'      => 'https://uat-moph.id.th',
         'baseUrl'   => 'https://moph.id.th',             // PRD
        'clientId'     => '019c325b-e31c-74d0-9459-ca0c716f6b83',  // ← จาก log
        'clientSecret' => '28b92de34fb1cc62eaa36bc0e70e92e3c7fff704',              // ← ใส่ secret จริง
        'redirectUri'  => 'http://192.168.200.9/mhosp-office/web/index.php?r=health-id/callback',
    ],

    // ===== Step 2: Provider ID API =====
    'providerId' => [
        //'baseUrl'   => 'https://uat-provider.id.th',
         'baseUrl' => 'https://provider.id.th',            // PRD
        'clientId'  => '4a57c011-a45d-4f16-a5e9-b24365705837',
        'secretKey' => 'lspNjIrFki3aY5tI8eCVVXhE8xON7oMj',
    ],
];