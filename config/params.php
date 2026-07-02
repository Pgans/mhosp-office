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
        'clientId'     => '019c3252-f579-7a77-9ebc-9a690427c45f',  // ← จาก log
        'clientSecret' => '19fab859be30f541319ae5a4f2de03cbb6c7e692',              // ← ใส่ secret จริง
        'redirectUri'  => 'http://192.168.200.9/mhosp-office/web/index.php?r=health-id/callback',
    ],

     // ===== Step 2: Provider ID API =====
    'providerId' => [
        //'baseUrl'   => 'https://uat-provider.id.th',
        'baseUrl'   => 'https://provider.id.th',
        'clientId'  => 'cadec73e-f853-4b9d-a3aa-3e05434d8fa1',
        'secretKey' => 'LoPtrAMGhye5dI61VylaxyjsT5Ac0Pd1',
    ],

    // ===== Step 3: กำหนด hospcode ของโรงพยาบาลนี้ =====
    'allowedHospcode' => '10953',   // ✅ เพิ่มบรรทัดนี้
];
