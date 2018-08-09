<?php
use app\components\mustacheWrapper;

    $title = Yii::t('app','About');
    $m = new mustacheWrapper; 
	$m->pageTitle=Yii::$app->name.' - '. $title;

    $m->breadcrumbs=array($title);

    $data = array(
		'pageTitle' => $title,
	);
	$m->render('pages/about', 'site', $data);
?>
