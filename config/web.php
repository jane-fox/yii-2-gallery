<?php

$params = require(__DIR__ . '/params.php');

$config = [

    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'layout' => false,
	  
	'modules' => [
		'user' => [
			'class' => 'dektrium\user\Module',

			'admins' => ['vixe', 'hexxy'],

			'enableConfirmation' =>false,

			'modelMap' => [
				'User' => 'app\models\User',
			],
			'admins' => ['hexxy','vixe'],
			'controllerMap' => [
				'security' => 'app\controllers\SecurityController',
				'registration' => 'app\controllers\RegistrationController'
			]
		],
		'comments' => [
			'class' => 'rmrevin\yii\module\Comments\Module',
			'userIdentityClass' => 'app\models\User',
			'useRbac' => true,
		]
	],

    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
	
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'GLBC_ZYCDKsA3yEdr18zARrxwjWyo01M',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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

		//Over-rides for user templates
		'view' => [
			'theme' => [
				'pathMap' => [
					'@dektrium/user/views' => '@app/views/account'
				],
			],
			 'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    // set cachePath to false in order to disable template caching
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
					'options' => YII_DEBUG ? [
						'debug' => true,
						'auto_reload' => true,
					] : [],
					'extensions' => YII_DEBUG ? [
						'\Twig_Extension_Debug',
					] : [],
					'globals' => ['html' => '\yii\helpers\Html'],

                    // ... see ViewRenderer for more options
                ],
            ]
		],


        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => require(__DIR__ . '/routes.php'),
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
