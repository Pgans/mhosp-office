<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class DischargeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
