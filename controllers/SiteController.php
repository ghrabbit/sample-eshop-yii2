<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ContactForm;


class SiteController extends ExtController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

  
    public function actionIndex($pageNo = 1, $pageSize = 6)
    {
        
        return $this->render('index',array(
			'title'=>Yii::t('app','Closeout'),
			'pageNo'=> $pageNo,
			'pageSize' => $pageSize,
		));
    }

    public function actionContact()
    {
        Yii::warning('id->contact');
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['postmaster'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionLang($id)
	{
		//Yii::trace('id->'.$id);
		Yii::warning('id->'.$id);
		$app = Yii::$app;
		$app->language = $id;
		$app->session['ulang']=$id;
		//$this->redirect($app->request->urlReferrer);
		return $this->goHome();
	}
}
