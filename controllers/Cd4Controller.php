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

class Cd4Controller extends \yii\web\Controller
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

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT DISTINCT
			DATE_FORMAT(o.REG_DATETIME,'%d-%m-%Y %H:%i:%s') AS regdate,
			o.visit_id as visit_id,
			o.REG_DATETIME as regdate,
			o.HN as hn,
			p.cid,
			concat(trim(p.fname),' ',p.lname) as 'fullname',
			TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) as age,
			u.unit_name,
			i.icd10_tm as Diag,
			MAX(CASE WHEN ll.lab_id = '161' THEN ll.lab_result ELSE '' END) AS ViralLoad,
    MAX(CASE WHEN ll.lab_id = '045' THEN ll.lab_result ELSE '' END) AS CD4,
    MAX(CASE WHEN ll.lab_id = '011' THEN ll.lab_result ELSE '' END) AS Creatinine,
	MAX(CASE WHEN ll.lab_id in ('086','088' )THEN ll.lab_result ELSE '' END) AS AFB
			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			INNER  JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('161', '045','011','086','088')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
			AND o.unit_reg = '20'
			#AND o.VISIT_ID in ('0003066629','0003066630','0003066387','0003088918')
			GROUP BY o.visit_id  ";
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
