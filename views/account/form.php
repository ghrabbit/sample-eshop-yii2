<?php
/**
 * */
use app\components\mustacheWrapper;
use yii\captcha\Captcha;
use app\components\utils;

    $m = new mustacheWrapper; 
	$m->pageTitle=Yii::$app->name.' - '. $title;

    //$m->breadcrumbs=array($title);

	$errorSummary = (isset($model->errors) && count($model->errors))? 
		utils::renderErrors($model->errors):false;
	
	$captcha = isset($withCaptcha)?
		Captcha::widget([
          'id'=>'inputVerifyCode',
          'name' => 'verifyCode',
          'template' => '{image}<span><button href="'.Yii::$app->urlManager->getBaseUrl().'/site/captcha?refresh=1" type="button" class="btn btn-default" name="refreshCaptcha" data-toggle="tooltip" data-placement="bottom" title="refresh captcha Code"><span class="glyphicon glyphicon-refresh"></span></button></span>{input}',
        ]):false;		

	$options = array(
		'model' => $model, 
		'values' => $model->attributes,
		'formName' => $template,
		'errorSummary' =>$errorSummary,
		'captcha' => $captcha,
		'labels' => $model->attributeLabels(),
		'pageTitle' => $title,
	);

	$m->render($template, 'account', $options);
?>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
jQuery(document).on('click', 'button[name=refreshCaptcha]', function(){
	jQuery.ajax({
		url: "<?php echo Yii::$app->urlManager->baseUrl?>\/account\/captcha?refresh=1",
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
