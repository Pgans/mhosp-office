<?php

namespace app\controllers;

use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Logtodaydep;
use Yii;
use kartik\mpdf\Pdf;
//use mpdf\src\Config\ConfigVariables;
//use mpdf\src\Config\FontVariables;
use mPDF;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadCSV;
use yii\web\UploadedFile;
use app\models\Closevisits;
use yii\web\NotFoundHttpException;
use app\models\CloseVisit; 
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


class TodaysController extends \yii\web\Controller
{
	/*
	public function behaviors(){
    
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=> ['index','admit','create','update','view','a15er'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions' => [ 'view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['a15er','create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['a15er','index','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','index','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['delete'],
                        'allow'=> true,
                        'roles'=>[User::ROLE_ADMIN]
                    ]
                ]
            ]
        ];
    }
	*/
    public function actionIndexxxx()
    {
        // $_token = $model->token;


        return $this->render('indexxxx');
    }
	public function actionDashboard()
{
	#############################LOG DRUGSZONE#############################
        $connection = Yii::$app->db_log;
				$log = new Logtodaydep();
		$log->username = Yii::$app->user->identity->username ?? 'guest';
		$log->datetime = date('Y-m-d H:i:s');
		$log->ip = Yii::$app->request->getUserIP();
		$log->patient_cid = Yii::$app->request->post('cid', null);
		$log->save(); // ✅ บันทึกลง db_log
	####### นับจำนวนเข้าใช้งาน ##################################################	
		 $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_todaydep v 
			";
        
         $data = \yii::$app->db_log->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
	########################################################################	
    $sql = " SELECT  
    DATE(a.REG_DATETIME) AS REGDATE,
    COUNT(DISTINCT CASE WHEN a.UNIT_REG THEN a.visit_id END) AS 'TOTAL',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = '02' THEN a.visit_id END) AS 'OPD',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (11, 53) AND HOUR(a.REG_DATETIME) BETWEEN 0 AND 7 THEN a.visit_id END) AS 'ERดึก',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (11, 53) AND HOUR(a.REG_DATETIME) BETWEEN 8 AND 15 THEN a.visit_id END) AS 'ERเช้า',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (11, 53) AND HOUR(a.REG_DATETIME) BETWEEN 16 AND 23 THEN a.visit_id END) AS 'ERบ่าย',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 22 THEN a.visit_id END) AS 'LR',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 31 THEN a.visit_id END) AS 'PHISICAL',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 26 THEN a.visit_id END) AS 'THAIMED',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (03, 04, 05) THEN a.visit_id END) AS 'DENT',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (12, 14, 15, 16, 34, 51) THEN a.visit_id END) AS 'NCD',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 35 THEN a.visit_id END) AS 'ARI',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 40 THEN a.visit_id END) AS 'VIP',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 27 THEN a.visit_id END) AS 'ANC',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (13, 17, 18, 37, 44, 46, 49) THEN a.visit_id END) AS 'AIDS',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (19) THEN a.visit_id END) AS 'TB',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (20) THEN a.visit_id END) AS 'ARV',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 45 THEN a.visit_id END) AS 'PCU',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 47 THEN a.visit_id END) AS 'ACU',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG IN (36, 43) THEN a.visit_id END) AS 'CAPD',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 42 THEN a.visit_id END) AS 'HD',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 28 THEN a.visit_id END) AS 'elderly',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 63 THEN a.visit_id END) AS 'Telemed',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 65 THEN a.visit_id END) AS 'คลินิกวัยใส',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 68 THEN a.visit_id END) AS 'Teledentistry',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 74 THEN a.visit_id END) AS 'Vipพิเศษ',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 69 THEN a.visit_id END) AS 'ไวรัสตับอักเสบ',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 76 THEN a.visit_id END) AS 'อายุรกรรมทั่วไป',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG = 77 THEN a.visit_id END) AS 'อายุรกรรมโรคไต',
    COUNT(DISTINCT CASE WHEN a.UNIT_REG NOT IN (11, 02, 35, 03, 04, 05, 12, 14, 15, 16, 34, 51, 40, 19, 26, 28, 42, 47, 36, 43, 13, 17, 18, 20, 37, 44, 46, 31, 45, 27, 22, 49, 51, 53, 63, 65, 68, 69, 74, 76, 77) THEN a.visit_id END) AS 'ORTHER'
FROM opd_visits a
INNER JOIN opd_diagnosis od ON od.visit_id = a.visit_id AND od.dxt_id = 1 AND od.is_cancel = 0
WHERE a.REG_DATETIME BETWEEN CURDATE() AND NOW()
  #AND a.VISIT_ID NOT IN (SELECT m.visit_id FROM mobile_visits m WHERE m.is_cancel = 0)
  AND a.IS_CANCEL = 0
  AND od.is_cancel = 0
GROUP BY REGDATE;
	
	"; 
    $data = Yii::$app->db2->createCommand($sql)->queryAll();

    return $this->render('dashboard', [
        'data' => $data,
        'r' => $data[0] ?? [],
		'amount' => $amount,
    ]);
}

	
}
   