<?php

namespace app\controllers;
use yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\User;

/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
//use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class AfbController extends \yii\web\Controller
{
	public function behaviors() {
    return [
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
            ],
        ],
        'access' => [
            'class' => AccessControl::class,
            'only' => ['index', 'index2', 'update', 'view', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'index', 'index2', 'create', 'update'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 158]; // 158=น้องหญิง
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 158]; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
    ];
}
    public function actionIndex()
    {
         $data = Yii::$app->request->post();
		 $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
         $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';
		
        $sql = "SELECT 
    DATE_FORMAT(ll.LREQ_DT, '%d-%m-%Y %H:%i:%s') AS lab_request_date,
    o.visit_id AS visit_id,
    o.REG_DATETIME AS registration_date,
    o.HN AS hn,
    ipd.adm_id AS an,
    p.cid,
	u.unit_name,
	i.icd10_tm as Diag,
    CONCAT(trim(p.FNAME),'   ',REPLACE(p.lname,left(p.lname,3),'xxx')) as fullname,
    TIMESTAMPDIFF(YEAR, p.birthdate, o.REG_DATETIME) AS age,
    -- Modified CASE statement to check if admission_id is not empty
    CASE 
        WHEN ipd.adm_id IS NOT NULL AND ipd.adm_id != '' THEN COALESCE(u1.unit_name, ipd.WARD_NO)
        ELSE COALESCE(u.unit_name, ipd.WARD_NO)
    END AS registration_unit,
    i.icd10_tm AS diagnosis,
    MAX(CASE WHEN ll.lab_id IN ('064', '065') THEN l.LAB_NAME ELSE '' END) AS AntiHIV_result,
    MAX(CASE WHEN ll.lab_id IN ('086', '088', '093') THEN l.lab_name ELSE '' END) AS AFB_result,
	MAX(CASE WHEN ll.lab_id IN ('305') THEN l.lab_name ELSE '' END) AS AFB_1,
	MAX(CASE WHEN ll.lab_id IN ('306') THEN l.lab_name ELSE '' END) AS AFB_2,
	MAX(CASE WHEN ll.lab_id IN ('332') THEN l.lab_name ELSE '' END) AS TBDNA
FROM 
    opd_visits o
    INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
    INNER JOIN population p ON p.CID = c.CID
    LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
    LEFT JOIN icd10new i ON i.icd10 = dx.icd10
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 
		LEFT JOIN lab_lists l ON l.LAB_ID =  ll.LAB_ID
    LEFT JOIN ipd_reg ipd ON ipd.visit_id = o.visit_id AND ipd.is_cancel = 0
    LEFT JOIN service_units u1 ON u1.unit_id = ipd.WARD_NO
WHERE 
    ll.LREQ_DT BETWEEN  '$date1' AND '$date2'
	#ll.LREQ_DT BETWEEN '2024-04-25 00:01' AND '2024-04-25 23:59'
    AND ll.lab_id IN ('064', '065', '086', '088', '093','305','306','332')
#AND o.hn in ('033537','006713','103378')
GROUP BY 
    o.visit_id
ORDER BY 
    lab_request_date ASC  ";
       $rawData = \yii::$app->db7->createCommand($sql)->queryAll();

       try {
           $rawData = \Yii::$app->db7->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('index', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
  
   
}
