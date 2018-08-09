<?php

use yii\data\Pagination;
use yii\widgets\LinkPager;

use app\components\mustacheWrapper;
use app\models\Product;

    $m = new mustacheWrapper; 
	$m->pageTitle=Yii::$app->name.' - '. $title;
		$model = new Product;
		$count=$model->get_on_specials_total_count();
		$pages = new Pagination([
            'totalCount' => $count, 
            'page'=>$pageNo - 1, 
            'pageSize'=>$pageSize, 
            'pageParam' => 'pageNo',
            'pageSizeParam'=>'pageSize',
        ]);

	//the items on given page
	$_items = $model->get_on_specials_page($pageNo, $pageSize); 
	
		
	$labels = $model->attributeLabels();
	$items = array();
	/**/
	foreach($_items as $one)
	{
		$items[] = array('model'=>$one, 'labels'=>$labels);
	}	
		
	$data = array(
		'title' => $title,
		'username' => Yii::$app->user->id,
		'pageItems' => $items,
		'pager' => LinkPager::widget(array('pagination' => $pages,), true),
		'itemsCount' => count($items),
		'pageNo'=> $pageNo,
		'pageSize' => $pageSize,
	);
	$m->render('index', 'site', $data);
?>
