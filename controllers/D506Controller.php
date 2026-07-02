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

   // กำหนดช่วงเวลา
$startDatex = '2024-10-01 00:01';
$endDatex = '2026-12-31 23:59';

// คำสั่งดึงข้อมูลรายเดือน
$sqlMonth = "
SELECT 
    diag,
    name_eng,
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-10' THEN 1 ELSE 0 END) AS '2024-10',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-11' THEN 1 ELSE 0 END) AS '2024-11',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-12' THEN 1 ELSE 0 END) AS '2024-12',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-01' THEN 1 ELSE 0 END) AS '2025-01',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-02' THEN 1 ELSE 0 END) AS '2025-02',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-03' THEN 1 ELSE 0 END) AS '2025-03',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-04' THEN 1 ELSE 0 END) AS '2025-04',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-05' THEN 1 ELSE 0 END) AS '2025-05',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-06' THEN 1 ELSE 0 END) AS '2025-06',
    SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-07' THEN 1 ELSE 0 END) AS '2025-07',
	SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-08' THEN 1 ELSE 0 END) AS '2025-08',
	SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-09' THEN 1 ELSE 0 END) AS '2025-09',
    COUNT(*) AS total_case
FROM d506_new
WHERE regdate BETWEEN :start AND :end
GROUP BY name_eng
ORDER BY total_case DESC
";

// ดึงข้อมูลล่าสุดที่บันทึกไว้
$lastUpdatedAt = Yii::$app->db4
    ->createCommand("SELECT MAX(regdate) FROM d506_new WHERE regdate BETWEEN :start AND :end")
    ->bindValues([':start' => $startDatex, ':end' => $endDatex])
    ->queryScalar();

// ดึงข้อมูลรายเดือน
$monthData = Yii::$app->db4->createCommand($sqlMonth)
    ->bindValues([':start' => $startDatex, ':end' => $endDatex])
    ->queryAll();

    return $this->render('index', [
        'dayData' => $dayData,
        'monthData' => $monthData,
		'lastUpdatedAt' => $lastUpdatedAt,
		'amount' => $amount,
		'startDate' => $startDate,
        'endDate' => $endDate,
		'startDatex' => $startDatex,
        'endDatex' => $endDatex,
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

    $sql = "REPLACE INTO d506_new
SELECT 
    b.reg_datetime as regdate,
    b.visit_id,
    b.hn,
    p.cid,
    CONCAT(
      CASE 
        WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) < 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'สามเณร'
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) >= 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'พระภิกษุ'
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) < 15 AND p.sex = '1' THEN 'เด็กชาย'
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '1' THEN 'นาย'
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) < 15 AND p.sex = '2' THEN 'เด็กหญิง'
        WHEN TIMESTAMPDIFF(year, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '2' AND p.MARRIAGE = '1' THEN 'นางสาว'
        ELSE 'นาง'
      END, TRIM(p.FNAME), '  ', TRIM(p.LNAME)
    ) AS fullname,
    TIMESTAMPDIFF(year, p.BIRTHDATE, b.REG_DATETIME) AS age,
    icd.ICD10_TM AS diag,
    icd.icd10_id,
    c506.code506,
    c506.code_min,
    c506.code_max,
    icd.icd_thai,
    g.name_eng,
    g.name_thai,
    d.dxt_id,
    d.dxg_id,
    GROUP_CONCAT(DISTINCT 
      CASE
        WHEN lr.LAB_RESULT LIKE '%RT-PCR%' THEN 'RT-PCR'
        WHEN lr.LAB_RESULT LIKE '%Ag=Negative%' THEN 'Negative'
        WHEN lr.LAB_RESULT LIKE '%Ag=Positive%' THEN 'Positive'
        ELSE LEFT(lr.LAB_RESULT, 20) 
      END 
    ) AS lab,
    GROUP_CONCAT(DISTINCT lr.lab_id) AS lab_id,
    IF(ir.adm_id IS NULL, '', ir.adm_id) AS An,
    IF(ir.ward_no IS NULL, '', ir.ward_no) AS ward,
    b.inscl AS inscl,
    LEFT(e.unit_name,10) AS unit_name
FROM 
    opd_visits b 
    INNER JOIN cid_hn c ON b.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_diagnosis d ON d.visit_id = b.visit_id AND d.is_cancel = 0 
    LEFT JOIN icd10new icd ON icd.icd10 = d.icd10
    LEFT JOIN ipd_reg ir ON ir.VISIT_ID = b.visit_id AND ir.IS_CANCEL = 0
    INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
    LEFT JOIN refers r ON b.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = '0'          
    LEFT JOIN lab_requests lr ON lr.visit_id = b.visit_id AND lr.is_cancel = 0 AND lr.LAB_RESULT LIKE '%positive%'
    LEFT JOIN lab_lists l ON l.lab_id = lr.lab_id  
    LEFT JOIN code506 c506 ON icd.ICD10_TM BETWEEN c506.code_min AND c506.code_max
    INNER JOIN group_d506 g ON g.code506 = c506.code506
WHERE 
    b.IS_CANCEL = 0
    AND b.visit_id NOT IN (SELECT mv.visit_id FROM mobile_visits mv)
    AND b.REG_DATETIME BETWEEN '2025-07-01 00:00' AND '2025-12-31 23:59'
    AND b.unit_reg <> '42'
    AND (
        SELECT code506.code506 
        FROM code506 
        WHERE icd.ICD10_TM BETWEEN code506.code_min AND code506.code_max 
        LIMIT 1
    ) IS NOT NULL
GROUP BY 
    b.VISIT_ID;

    
    ";

    Yii::$app->db4->createCommand($sql)
        ->bindValue(':startDate', $startDate)
        ->bindValue(':endDate', $endDate)
        ->execute();

    Yii::$app->session->setFlash('success', "✅ อัปเดตข้อมูลเดือน $monthNow เรียบร้อยแล้ว");
    return $this->redirect(['index']); // หรือ return 'ok'; ถ้าเป็น API
}

}
   