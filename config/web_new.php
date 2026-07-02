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
            'confirmWithin'=> 21600,
            //'enableConfirmation' => false,
            'cost' => 12,
            'admins' => ['pgans@admin']  //ผู้ดูแลระบบใหญ่ กำหนดสิทธิ์ได้ทั้งหมด
        ], 
        'gridview' =>  [
             'class' => '\kartik\grid\Module'
        ],
        // 'admin' => [
        //     'class' => 'mdm\admin\Module',
        //     'layout' => 'left-menu'
        //],
        'rbac' => 'dektrium\rbac\RbacWebModule',
		 'lotto' => [
             'class' => 'app\module\lotto\Module',
            ],
         'personal' => [
             'class' => 'app\module\personal\Module',
             ],
        'huay' => [
            'class' => 'app\module\huay\Module',
        ],
        ],
         //------- สิทธิ์ ของการใช้ URL
      'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //module, controller, action ที่อนุญาตให้ทำงานโดยไม่ต้องผ่านการตรวจสอบสิทธิ์
           // 'gii/*',
           // 'cctvs/*',
            'debug/*',
            'site/*',
           // 'user/security/login',
            'user/security/logout',
            //public
            'personal/person',
            'dashboard/index',
            'booking/view',
            'booking/calendar',
            'pgans@admin/*',
            'pgans/*',
            //'user/*',
            /*
             * ทำให้ใช้งานได้ทั้งหมดก่อน for dev เพิ่ม '*'
             */
            //'*',
            'some-controller/some-action',
        ]
    ],
    //-------
    'components' => [
        'thaiYearFormatter' => [
            'class' => 'app\components\ThaiYearFormatter'
        ],
        'authManager' => [
            //'class' => 'yii\rbac\DbManager',
            'class' => 'dektrium\rbac\components\DbManager'
        ],
		
        'view' => [
            'theme' => [
                'pathMap' => [
                    // '@app/views' => '@agency/views', // uncomment active agency theme
                    '@app/views' => '@app/themes/adminlte' // uncomment active adminlte theme
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456789',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            //'identityClass' => 'app\models\User',
         'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        'db1' => require(__DIR__ . '/db1.php'),
		'db2' => require(__DIR__ . '/db2.php'),
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
