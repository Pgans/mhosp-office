<?php

namespace app\controllers;

use Yii;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\widgets\Pjax;
use app\models\Service;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
class Top10Controller extends \yii\web\Controller
{
    public function actionIndex()
    {
    $connection = Yii::$app->db70;
	 // 10 อันดับโรคผู้ป่วยนอก ปีงบ2566
       $dataopd1066 = $connection->createCommand("
       SELECT a.ICD10_TM, a.ICD_NAME , COUNT(a.ICD10_TM) as AMOUNT
        FROM mb_opd_visits_fiscal a
        WHERE a.REG_DATETIME BETWEEN '2022-10-01 00:01' AND '2023-09-30 23:59'
        AND a.ICD10_TM NOT BETWEEN 'Z00' AND 'Z99' AND a.ICD10_TM <> 'U778'
        GROUP BY a.ICD10_TM ORDER BY AMOUNT DESC  LIMIT 10
        ")->queryAll(); 

        $opd1066dataProvider = new ArrayDataProvider([
            'allModels' => $dataopd1066,
            'sort'=>[
                'attributes'=>['FISCAL','OPD','ER','THAIMED','PHISICAL','VIP']
            ],
        ]);
         // 10 อันดับโรคผู้ป่วยนอก ปีงบ2565
       $dataopd1065 = $connection->createCommand("
       SELECT a.ICD10_TM, a.ICD_NAME , COUNT(a.ICD10_TM) as AMOUNT
        FROM mb_opd_visits_fiscal a
        WHERE a.REG_DATETIME BETWEEN '2021-10-01 00:01' AND '2022-09-30 23:59'
        AND a.ICD10_TM NOT BETWEEN 'Z00' AND 'Z99' AND a.ICD10_TM <> 'U778'
        GROUP BY a.ICD10_TM ORDER BY AMOUNT DESC  LIMIT 10
        ")->queryAll(); 

        $opd1065dataProvider = new ArrayDataProvider([
            'allModels' => $dataopd1065,
            'sort'=>[
                'attributes'=>['FISCAL','OPD','ER','THAIMED','PHISICAL','VIP']
            ],
        ]);
		 // 10 อันดับโรคผู้ป่วยนอก ปีงบ2564
       $dataopd1064 = $connection->createCommand("
       SELECT a.ICD10_TM, a.ICD_NAME , COUNT(a.ICD10_TM) as AMOUNT
        FROM mb_opd_visits_fiscal a
        WHERE a.REG_DATETIME BETWEEN '2020-10-01 00:01' AND '2021-09-30 23:59'
        AND a.ICD10_TM NOT BETWEEN 'Z00' AND 'Z99' AND a.ICD10_TM <> 'U778'
        GROUP BY a.ICD10_TM ORDER BY AMOUNT DESC  LIMIT 10
        ")->queryAll(); 

        $opd1064dataProvider = new ArrayDataProvider([
            'allModels' => $dataopd1064,
            'sort'=>[
                'attributes'=>['FISCAL','OPD','ER','THAIMED','PHISICAL','VIP']
            ],
        ]);
		 return $this->render('index', [
                    'acdataProvider' => $acdataProvider,
                    'opd1066dataProvider' => $opd1066dataProvider,
                    'opd1065dataProvider' => $opd1065dataProvider,
                    'opd1064dataProvider' => $opd1064dataProvider,
                    
        ]);
    }
       
    
    }


