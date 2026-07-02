<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
		 'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'enableConfirmation' => true,
            'confirmWithin'=> 21600,
            //'enableConfirmation' => false,
            'cost' => 12,
            'admins' => ['pgans@admin']  //ผู้ดูแลระบบใหญ่
        ], 
        'gridview' =>  [
             'class' => '\kartik\grid\Module'
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu'
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
		 'lotto' => [
             'class' => 'app\module\lotto\Module',
            ],
        'opdcard' => [
            'class' => 'app\modules\opdcard\Module',
             ],
        'apdcard' => [
             'class' => 'app\modules\apdcard\Module',
             ],  
         'personal' => [
             'class' => 'app\module\personal\Module',
             ],
        'huay' => [
            'class' => 'app\module\huay\Module',
        ],
		'language' => 'th', // ตั้งค่าภาษาเริ่มต้น
        ],
    'components' => [
        'thaiYearFormatter' => [
            'class' => 'app\components\ThaiYearFormatter'
        ],
		'errorHandler' => [
        'errorAction' => 'site/error',
		],
		'request' => [
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ],
        'maxFileSize' => 10 * 1024 * 1024, // เปลี่ยนเป็นขนาดสูงสุดที่คุณต้องการ เช่น 10MB
    ],
        'rentalStatus' => [
            'class' => 'app\components\Rental'

        ],
		'i18n' => [
        'translations' => [
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages', // ไดเร็กทอรีของไฟล์การแปล
                'sourceLanguage' => 'en',
                'fileMap' => [
                    'app' => 'app.php', // แผนที่ไฟล์การแปล
                ],
            ],
        ],
    ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    // '@app/views' => '@agency/views', // uncomment active agency theme
                    '@app/views' => '@app/themes/adminlte' // uncomment active adminlte theme
                ],
            ],
        ],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456789',
        ],
		//'maxFileSize' => 10 * 1024 * 1024, // เปลี่ยนเป็นขนาดสูงสุดที่คุณต้องการ เช่น 10MB
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            //'identityClass' => 'app\models\User',
         'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 600,
        ],
        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
		'db_host' => require(__DIR__ . '/db_host.php'),
        //'db1' => require(__DIR__ . '/db1.php'),
		'db2' => require(__DIR__ . '/db2.php'),
		'db4' => require(__DIR__ . '/db4.php'),
		'db7' => require(__DIR__ . '/db7.php'),
		'db70' => require(__DIR__ . '/db70.php'),
		'db74' => require(__DIR__ . '/db74.php'),
		'db_ehr' => require(__DIR__ . '/db_ehr.php'),
		'db_jhcis' => require(__DIR__ . '/db_jhcis.php'),
		'db22' => require(__DIR__ . '/db22.php'),
		'db143' => require(__DIR__ . '/db143.php'),
		'db_log' => require(__DIR__ . '/db_log.php'),
		'db14' => require(__DIR__ . '/db14.php'),
		
       // 'db' => $db,
       // 'db1'=> $db1,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
		'allowedIPs' => ['127.0.0.1', '::1', '192.168.200.*'],
         //  'password' => '@858480#'
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
return $config;

/*
    if (YII_ENV_DEV) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = 'yii\debug\Module';
    
        $config['bootstrap'][] = 'gii';
        $config['modules']['gii'] = [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],
        ];
    }
return $config;*/
