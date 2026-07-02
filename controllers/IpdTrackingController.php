<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\IpdTrackingSearch;

class IpdTrackingController extends Controller
{
    public function actionIndex()
    {
        $searchModel  = new IpdTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}