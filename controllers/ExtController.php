<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\helpers\TopNavbarHelper;
use Mustache;

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ExtController extends Controller
{
    public function render($view, $params = [])
    {
        //$content = $this->getView()->render($view, $params, $this);
        //return $this->renderContent($content);
        //return $this->renderFile(dirname(__FILE__)."/../views/$this->id/$view.php", $params);
        return $this->renderFile("@app/views/$this->id/$view.php", $params);
    }
}
