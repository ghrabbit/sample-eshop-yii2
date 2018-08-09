<?php

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

$app = new yii\web\Application($config);

if (isset($app->session['ulang'])) {
	$app->language = $app->session['ulang'];
}

//translate all
//$app->messages->forceTranslation = true;
//$app->user->guestName = isset($app->params['guestName'])?$app->params['guestName']: Yii::t('app','guest');


$app->run();
