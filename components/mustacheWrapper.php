<?php

namespace app\components;

use Yii;
use app\models\User;
use app\models\helpers\TopNavbarHelper;
use Mustache;
use yii\base\Object;
use yii\helpers\Html;

/**
 * 
 */
class MustacheWrapper extends Object
{
	
	public $pageTitle;
	public $breadcrumbs=array();
	public $topSideMenu;
	public $layoutTemplate='main';
    
    public function __construct($config = [])
    {
        // ... initialization before configuration is applied

        parent::__construct($config);
    }

	
	public function layoutTemplatePath() { return Yii::$app->basePath.'/templates/';}
	public function layoutLangTemplatePath() { return Yii::$app->basePath.'/templates/lang/'.Yii::$app->language.'/';}
	
	private function templateLoaders($dir)
	{
	  $app = Yii::$app;
	  $loaders = array();
	  $lang_dir = $app->basePath.'/templates/lang/'.Yii::$app->language.'/'.$dir;
	  if (is_dir($lang_dir)) 
		$loaders[] = new \Mustache_Loader_FilesystemLoader($lang_dir);
	  $loaders[] = new \Mustache_Loader_FilesystemLoader($app->basePath.'/templates/'.$dir); 	
	  return $loaders;
	}
	
	//attentions! do not use partials! use hier accessible templates (thanx sir/mam)
	public function render($view, $searchPath, $rgs=null)
	{
		$config = array(
			'cache'=> Yii::$app->basePath.'/runtime/Mustache/cash',

		);
		
		$app = Yii::$app;
		$rgs = array_merge($rgs, array( 
            'csrfmetatags'=>Html::csrfMetaTags(),
            'csrf_token'=>Yii::$app->request->getCsrfToken(),
            'csrf_param'=>Yii::$app->request->csrfParam,
			'baseUrl'=> $app->urlManager->getBaseUrl(),
			'staticUrl'=> $app->urlManager->getBaseUrl(),
			'vendorUrl'=> '/assets/bower_components',
			'appName' => $app->name,
			'username'=>User::isLoggedIn()?$app->user->identity->username:Yii::t('app','guest'), 
			'authenticated'=>User::isLoggedIn(),
			'pageTitle'=>$this->pageTitle,
			'rights'=>'Права &copy; '.date('Y').' Артюх Сергей. Все права защищены.',
		));	
		
		if(isset($this->layoutTemplate))
		{
			$config['loader'] = new \Mustache_Loader_CascadingLoader($this->templateLoaders('layouts'));

			$rgs['content']=$this->renderPartial($view,$searchPath,$rgs);
			
			$view = $this->layoutTemplate;
			$rgs['topNavbar']=$this->renderPartial('topNavbar', 'layouts',
				array_merge($rgs, array(
					'labels'=>(new \app\models\helpers\TopNavbarHelper)->attributeLabels(),
					/*
					'langs'=>array(
						'current'=>$app->locale->getLanguage($app->language),
						'en_us'=>$app->locale->getLanguage('en_us'),
						'ru'=>$app->locale->getLanguage('ru'),
					)
					*/
					'langs'=>array(
						'current'=>Yii::t('app/lang',$app->language),
						'en_us'=>Yii::t('app/lang','en_us'),
						'ru_ru'=>Yii::t('app/lang','ru_ru'),
					)
				)
			));
			$rgs['userMenu']=$this->renderPartial('userMenu', 'layouts',
				array_merge($rgs, array('labels'=>(new \app\models\helpers\UserMenuHelper)->attributeLabels())
			));
			$rgs['cartMenu']=$this->renderPartial('cartMenu', 'layouts',
				array_merge($rgs, array('model'=>$app->session['cart'],'labels'=>(new \app\models\helpers\CartMenuHelper)->attributeLabels())
			));
			
			if($this->topSideMenu)
				$rgs['topsideMenu']=$this->renderPartial('topsideMenu', $this->topSideMenu['searchPath'],
					array_merge($rgs, $this->topSideMenu['args']));
				
		}else{
			$config['loader'] = new Mustache_Loader_FilesystemLoader($this->layoutTemplatePath().$searchPath);	
		}
		$m = new \Mustache_Engine($config);
		echo $m->render($view,$rgs);
	}
	
	public function renderPartial($view, $searchPath, $rgs=null)
	{
		$app = Yii::$app;
		$config = array(
			'cache'=> $app->basePath.'/runtime/Mustache/cash',
			'loader' => new \Mustache_Loader_CascadingLoader($this->templateLoaders($searchPath)),
		);	
		
		

		$m = new \Mustache_Engine($config);
		return $m->render($view,$rgs);
	}
}
