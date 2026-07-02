<?php

namespace app\controllers;

use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Logd506;
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


class D506Controller extends \yii\web\Controller
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
	public function actionIndex()
{
	#############################LOG DRUGSZONE#############################
        $connection = Yii::$app->db_log;
				$log = new Logd506();
		$log->username = Yii::$app->user->identity->username ?? 'guest';
		$log->datetime = date('Y-m-d H:i:s');
		$log->ip = Yii::$app->request->getUserIP();
		$log->patient_cid = Yii::$app->request->post('cid', null);
		$log->save(); // ✅ บันทึกลง db_log
	####### นับจำนวนเข้าใช้งาน ##################################################	
		 $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_d506 v 
			";
        
         $data = \yii::$app->db_log->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
	########################################################################			
    // รายวัน
   $startDate = Yii::$app->request->post('start_date', date('Y-m-d'));
        $endDate = Yii::$app->request->post('end_date', date('Y-m-d'));

        $sqlDay = "SELECT 
            d5.name_thai AS 'โรค',
            COUNT(DISTINCT b.visit_id) AS 'จำนวนเคส'
        FROM opd_visits b
        INNER JOIN cid_hn c ON b.HN = c.HN
        INNER JOIN population p ON c.CID = p.CID
        LEFT JOIN opd_diagnosis d ON d.visit_id = b.visit_id AND d.is_cancel = 0
        LEFT JOIN icd10new icd ON icd.icd10 = d.icd10
        LEFT JOIN code506 c506 ON icd.ICD10_TM BETWEEN c506.code_min AND c506.code_max
        LEFT JOIN group_d506 d5 ON d5.code506 = c506.code506
        WHERE b.IS_CANCEL = 0
            AND b.visit_id NOT IN (SELECT mv.visit_id FROM mobile_visits mv)
            AND b.REG_DATETIME BETWEEN :start_date AND :end_date
            AND d5.name_thai IS NOT NULL
            AND d5.name_thai <> ''
        GROUP BY d5.name_thai
        ORDER BY COUNT(DISTINCT b.visit_id) DESC";

        $dayData = Yii::$app->db2->createCommand($sqlDay)
            ->bindValue(':start_date', $startDate . ' 00:00:00')
            ->bindValue(':end_date', $endDate . ' 23:59:59')
            ->queryAll();
###################################################################################################

    // รายเดือน
    $sqlMonth = "SELECT 
    disease AS โรค,
    SUM(CASE WHEN month = '2024-10' THEN case_count ELSE 0 END) AS '2024-10',
    SUM(CASE WHEN month = '2024-11' THEN case_count ELSE 0 END) AS '2024-11',
    SUM(CASE WHEN month = '2024-12' THEN case_count ELSE 0 END) AS '2024-12',
    SUM(CASE WHEN month = '2025-01' THEN case_count ELSE 0 END) AS '2025-01',
    SUM(CASE WHEN month = '2025-02' THEN case_count ELSE 0 END) AS '2025-02',
    SUM(CASE WHEN month = '2025-03' THEN case_count ELSE 0 END) AS '2025-03',
    SUM(CASE WHEN month = '2025-04' THEN case_count ELSE 0 END) AS '2025-04',
    SUM(CASE WHEN month = '2025-05' THEN case_count ELSE 0 END) AS '2025-05',
    SUM(CASE WHEN month = '2025-06' THEN case_count ELSE 0 END) AS '2025-06',
    SUM(CASE WHEN month = '2025-07' THEN case_count ELSE 0 END) AS '2025-07',
    SUM(CASE WHEN month = '2025-08' THEN case_count ELSE 0 END) AS '2025-08',
    SUM(CASE WHEN month = '2025-09' THEN case_count ELSE 0 END) AS '2025-09',
    SUM(case_count) AS total_case
FROM d506_summary_month
WHERE month BETWEEN '2024-10' AND '2025-09'
GROUP BY disease
ORDER BY total_case DESC;";

$lastUpdatedAt = Yii::$app->db4
    ->createCommand("SELECT MAX(updated_at) FROM d506_summary_month WHERE month = :month")
    ->bindValue(':month', date('Y-m'))
    ->queryScalar();

    $monthData = Yii::$app->db4->createCommand($sqlMonth)->queryAll();

    return $this->render('index', [
        'dayData' => $dayData,
        'monthData' => $monthData,
		'lastUpdatedAt' => $lastUpdatedAt,
		'amount' => $amount,
		'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
}
###############################################################################################
public function actionUpdate()
{
		// หาวันที่เริ่มต้นและสิ้นสุดของเดือนปัจจุบัน
		// หาวันที่เริ่มต้นและสิ้นสุดของเดือนปัจจุบัน พร้อมกำหนดเวลา
	$startDate = date('Y-m-01 00:01:00');  // วันที่ 1 ของเดือนนี้ เวลา 00:01:00 (หลังเที่ยงคืน 1 นาที)
	$endDate = date('Y-m-t 23:59:59');    // วันสุดท้ายของเดือนนี้ เวลา 23:59:59 (ก่อนเที่ยงคืน 1 วินาที)
	$monthNow = date('Y-m');                // รูปแบบเดือนปัจจุบัน เช่น 2025-07

    $sql = "
    INSERT INTO d506_summary_month (disease, code506, month, case_count)
    SELECT 
        d5.name_thai AS disease,
        c506.code506,
        DATE_FORMAT(b.REG_DATETIME, '%Y-%m') AS month,
        COUNT(DISTINCT b.visit_id) AS case_count
    FROM opd_visits b
    LEFT JOIN (
        SELECT d.visit_id, icd.ICD10_TM
        FROM opd_diagnosis d
        LEFT JOIN icd10new icd ON icd.icd10 = d.icd10
        WHERE d.is_cancel = 0
    ) dx ON dx.visit_id = b.visit_id
    LEFT JOIN code506 c506 ON dx.ICD10_TM BETWEEN c506.code_min AND c506.code_max
    LEFT JOIN group_d506 d5 ON d5.code506 = c506.code506
    WHERE b.IS_CANCEL = 0
      AND d5.name_thai IS NOT NULL
      AND b.REG_DATETIME BETWEEN :startDate AND :endDate
      AND NOT EXISTS (
          SELECT 1 FROM mobile_visits mv WHERE mv.visit_id = b.visit_id
      )
    GROUP BY disease, c506.code506, month
    ON DUPLICATE KEY UPDATE
        case_count = VALUES(case_count),
        updated_at = CURRENT_TIMESTAMP
    ";

    Yii::$app->db4->createCommand($sql)
        ->bindValue(':startDate', $startDate)
        ->bindValue(':endDate', $endDate)
        ->execute();

    Yii::$app->session->setFlash('success', "✅ อัปเดตข้อมูลเดือน $monthNow เรียบร้อยแล้ว");
    return $this->redirect(['index']); // หรือ return 'ok'; ถ้าเป็น API
}

}
   