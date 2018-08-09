<?php
/* @var $this view */
use app\components\mustacheWrapper;
use yii\captcha\Captcha;
	$title = Yii::t('app','Contact');
    $m = new mustacheWrapper; 
	$m->pageTitle=Yii::$app->name.' - '. $title;
	
    //$captcha = Captcha::widget(['id'=>'captcha','name' => 'captcha'/*'showRefreshButton'=>false*/]);
    $captcha = Captcha::widget([
        'id'=>'inputVerifyCode',
        'name' => 'verifyCode',
        'template' => '{image}<span><button href="'.Yii::$app->urlManager->getBaseUrl().'/site/captcha?refresh=1" type="button" class="btn btn-default" name="refreshCaptcha" data-toggle="tooltip" data-placement="bottom" title="refresh captcha Code"><span class="glyphicon glyphicon-refresh"></span></button></span>{input}',
    ]);    
		
	$data = array(
		'model'=>$model->attributes,
		'pageTitle' => isset($pageTitle)?$pageTitle:$title,
		'labels'=> $model->attributeLabels(),
		'captcha' => $captcha,	
		'errorSummary' => (isset($model->errors) && count($model->errors))? 
				utils::mustacheRender('errorSummary', 'documents', array(
					'errors' => utils::toAttributeArray($model->errors)
				)):false,
		'postmsg'=>isset($postmsg)?$postmsg:false,
	);
	$m->render('pages/contact', 'site', $data);
?>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
jQuery(document).on('click', 'button[name=refreshCaptcha]', function(){
	jQuery.ajax({
		url: "\/site\/captcha?refresh=1",
		dataType: 'json',
		cache: false,
		success: function(data) {
			jQuery('#inputVerifyCode-image').attr('src', data['url']);
			jQuery('body').data('captcha.hash', [data['hash1'], data['hash2']]);
		}
	});
	return false;
});

});
/*]]>*/
</script>
