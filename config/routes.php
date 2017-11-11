<?php

//For URL Manager config component
return [

	'enablePrettyUrl' => true,
	'showScriptName' => false,
	'enableStrictParsing' => false,

	'rules' => array(

			'profile/<id:\w+>/posts' => 'profile/posts',
			'profile/<id:\w+>/faves' => 'profile/faves',
			'profile/<id:\w+>' => 'profile/view',
			'profile/<action:\w+>/<id:\w+>' => 'profile/<action>',

			'<controller:\w+>/<id:\d+>' => '<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
			'<controller:\w+>/<action:\w+>' => '<controller>/<action>',

			'login' => '/user/security/login',

			'register' => '/user/registration/register',

			'upload' => '/post/create',


	),


];
