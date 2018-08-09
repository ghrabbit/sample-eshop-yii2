<?php
/* @var $this View */
use app\components\mustacheWrapper;

	$title = $name;//Yii::t('app','Error');
    $m = new mustacheWrapper; 
	$m->pageTitle=Yii::$app->name.' - '. $title;
	
	$data = array(
		'code'=>$exception->getCode(),
		'message'=>$exception->getMessage(),
		'errorComment'=>Yii::t('app','What is the matter'),
		'labels'=> array(
			'errorPrompt'=>$message,//Yii::t('app','There is unexpected thing!'),
			'code'=>Yii::t('app','Code')
		),
	);
	$m->render('pages/error', 'site', $data);
?>
