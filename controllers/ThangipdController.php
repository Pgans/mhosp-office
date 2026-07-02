<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class ThangipdController extends Controller
{
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
                'only'=> ['index','admit','create','update','view','delete'],
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
                        'actions'=>['admit','create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['admit','index','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','admit','create','update','view'],
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
	
	public function actionIndex()
    {
    $startDate = Yii::$app->request->get('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = Yii::$app->request->get('end_date', date('Y-m-d'));

        // คิวรี่ SQL
        $sql = "
           SELECT 
         (YEAR(:end_date) + 543) AS `ปีงบ`,
        COUNT(DISTINCT k.hn) AS `จำนวนคน`, 
        COUNT(k.VISIT_ID) AS `จำนวนครั้ง`, 
        k.`NATN_NAME` AS `สัญชาติ`,
        SUM(k.total) AS `ค่ารักษารวม`,
        SUM(k.paid) AS `เรียกเก็บ`,
        SUM(k.no_claim) AS `เรียกเก็บไม่ได้`
    FROM (
        SELECT 
            i.adm_dt, 
            a.hn, 
            i.ward_no, 
            i.adm_id AS `an`, 
            i.VISIT_ID,
            CASE 
                WHEN c.SEX = 1 THEN 'ชาย' 
                WHEN c.SEX = 2 THEN 'หญิง' 
            END AS `เพศ`,
            TIMESTAMPDIFF(YEAR, c.BIRTHDATE, a.REG_DATETIME) AS `age`,
            f.NATN_NAME,
            (COALESCE(cos.cg01, 0) + COALESCE(cos.cg02, 0) + COALESCE(cos.cg03, 0) +
             COALESCE(cos.cg04, 0) + COALESCE(cos.cg05, 0) + COALESCE(cos.cg06, 0) +
             COALESCE(cos.cg07, 0) + COALESCE(cos.cg08, 0) + COALESCE(cos.cg09, 0) +
             COALESCE(cos.cg10, 0) + COALESCE(cos.cg11, 0) + COALESCE(cos.cg12, 0) +
             COALESCE(cos.cg13, 0) + COALESCE(cos.cg14, 0) + COALESCE(cos.cg15, 0) +
             COALESCE(cos.cg16, 0) + COALESCE(cos.cg17, 0) + COALESCE(cos.cg18, 0) +
             COALESCE(cos.cg19, 0)) AS total,
            COALESCE(r.PAID, 0) AS paid,
            (COALESCE(cos.cg01, 0) + COALESCE(cos.cg02, 0) + COALESCE(cos.cg03, 0) +
             COALESCE(cos.cg04, 0) + COALESCE(cos.cg05, 0) + COALESCE(cos.cg06, 0) +
             COALESCE(cos.cg07, 0) + COALESCE(cos.cg08, 0) + COALESCE(cos.cg09, 0) +
             COALESCE(cos.cg10, 0) + COALESCE(cos.cg11, 0) + COALESCE(cos.cg12, 0) +
             COALESCE(cos.cg13, 0) + COALESCE(cos.cg14, 0) + COALESCE(cos.cg15, 0) +
             COALESCE(cos.cg16, 0) + COALESCE(cos.cg17, 0) + COALESCE(cos.cg18, 0) +
             COALESCE(cos.cg19, 0)) - COALESCE(r.PAID, 0) AS no_claim
        FROM opd_visits a 
        INNER JOIN ipd_reg i ON i.visit_id = a.visit_id AND i.is_cancel = 0
        INNER JOIN cid_hn b ON a.HN = b.HN
        INNER JOIN population c ON b.CID = c.CID
        LEFT JOIN nations f ON c.NATN_ID = f.NATN_ID
        LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
        LEFT JOIN receipts r ON r.visit_id = a.visit_id AND r.is_cancel = 0
        WHERE i.adm_dt BETWEEN :start_date AND :end_date
		AND a.INSCL IN (05, 16)
        AND a.IS_CANCEL = 0
        AND f.NATN_ID IN ('56', '57', '48', '46')
        GROUP BY i.adm_id
    ) k
    GROUP BY k.`NATN_NAME`;
";

    

        // ดึงข้อมูลจากฐานข้อมูล
        $data = Yii::$app->db14->createCommand($sql, [
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        ])->queryAll();

        // จัดรูปแบบข้อมูลสำหรับ GridView
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
		
		 $sql2 = "SELECT 
            i.adm_dt, 
            a.hn, 
            i.ward_no, 
            i.adm_id AS `an`, 
            i.VISIT_ID,
			u.unit_name,
            CASE 
                WHEN c.SEX = 1 THEN 'ชาย' 
                WHEN c.SEX = 2 THEN 'หญิง' 
            END AS `เพศ`,
            TIMESTAMPDIFF(YEAR, c.BIRTHDATE, a.REG_DATETIME) AS `age`,
            f.NATN_NAME,
            (COALESCE(cos.cg01, 0) + COALESCE(cos.cg02, 0) + COALESCE(cos.cg03, 0) +
             COALESCE(cos.cg04, 0) + COALESCE(cos.cg05, 0) + COALESCE(cos.cg06, 0) +
             COALESCE(cos.cg07, 0) + COALESCE(cos.cg08, 0) + COALESCE(cos.cg09, 0) +
             COALESCE(cos.cg10, 0) + COALESCE(cos.cg11, 0) + COALESCE(cos.cg12, 0) +
             COALESCE(cos.cg13, 0) + COALESCE(cos.cg14, 0) + COALESCE(cos.cg15, 0) +
             COALESCE(cos.cg16, 0) + COALESCE(cos.cg17, 0) + COALESCE(cos.cg18, 0) +
             COALESCE(cos.cg19, 0)) AS total,
            COALESCE(r.PAID, 0) AS paid,
            (COALESCE(cos.cg01, 0) + COALESCE(cos.cg02, 0) + COALESCE(cos.cg03, 0) +
             COALESCE(cos.cg04, 0) + COALESCE(cos.cg05, 0) + COALESCE(cos.cg06, 0) +
             COALESCE(cos.cg07, 0) + COALESCE(cos.cg08, 0) + COALESCE(cos.cg09, 0) +
             COALESCE(cos.cg10, 0) + COALESCE(cos.cg11, 0) + COALESCE(cos.cg12, 0) +
             COALESCE(cos.cg13, 0) + COALESCE(cos.cg14, 0) + COALESCE(cos.cg15, 0) +
             COALESCE(cos.cg16, 0) + COALESCE(cos.cg17, 0) + COALESCE(cos.cg18, 0) +
             COALESCE(cos.cg19, 0)) - COALESCE(r.PAID, 0) AS no_claim
        FROM opd_visits a 
        INNER JOIN ipd_reg i ON i.visit_id = a.visit_id AND i.is_cancel = 0
        INNER JOIN cid_hn b ON a.HN = b.HN
        INNER JOIN population c ON b.CID = c.CID
        LEFT JOIN nations f ON c.NATN_ID = f.NATN_ID
        LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
        LEFT JOIN receipts r ON r.visit_id = a.visit_id AND r.is_cancel = 0
		LEFT JOIN service_units u ON u.unit_id = i.ward_no
        WHERE i.adm_dt BETWEEN :start_date AND :end_date
		AND a.INSCL IN (05, 16)
        AND a.IS_CANCEL = 0
        AND f.NATN_ID IN ('56', '57', '48', '46')
        GROUP BY i.adm_id
		";
        // ดึงข้อมูลจากฐานข้อมูล
        $data1 = Yii::$app->db14->createCommand($sql2, [
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        ])->queryAll();

        // จัดรูปแบบข้อมูลสำหรับ GridView
        $hnProvider = new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        // ส่งค่าข้อมูลไปยัง View
        return $this->render('index', [
            'dataProvider' => $dataProvider,
			'hnProvider' => $hnProvider,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
	
}
