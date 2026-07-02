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



class ReferopdController extends Controller
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
                'only'=> ['index','index3','create','update','view','a15er'],
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
                        'actions'=>['create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['index','index3','create','update','view'],
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
	public function actionIndex3()
    {	 
	     $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_thaimed v 
			";
        
         $data = \yii::$app->db->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
        //return $this->render('index');
		return $this->render('index3',[
              'dataProvider' => $dataProvider,
             // 'sql'=>$sql,
			 // 'date1'=>$date1,
			  //'date2'=>$date2,
			 // 'amount'=>$amount, 
          ]);
    }
	
	public function actionIndex()
    {
  $startDate = Yii::$app->request->get('start_date', date('Y-m-d 00:01', strtotime('-1 month')));
  $endDate = Yii::$app->request->get('end_date', date('Y-m-d 23:59', strtotime('today')));
	
    $dep = Yii::$app->request->get('unit_reg', ''); // Default to '11' if not selected
    $icdcode1 = Yii::$app->request->get('icd_code1', '');
    $icdcode2 = Yii::$app->request->get('icd_code2', '');

    $sql1 = "
    SELECT 
			CASE MONTH(o.REG_DATETIME)
				WHEN 1 THEN 'มกราคม'
				WHEN 2 THEN 'กุมภาพันธ์'
				WHEN 3 THEN 'มีนาคม'
				WHEN 4 THEN 'เมษายน'
				WHEN 5 THEN 'พฤษภาคม'
				WHEN 6 THEN 'มิถุนายน'
				WHEN 7 THEN 'กรกฎาคม'
				WHEN 8 THEN 'สิงหาคม'
				WHEN 9 THEN 'กันยายน'
				WHEN 10 THEN 'ตุลาคม'
				WHEN 11 THEN 'พฤศจิกายน'
				WHEN 12 THEN 'ธันวาคม'
			END AS ชื่อเดือน,
			YEAR(o.REG_DATETIME) + 543 AS ปี,
			COUNT(CASE WHEN r.transport <> '1' THEN o.VISIT_ID END) AS 'จำนวนครั้งโดยรถโรงพยาบาล',
			COUNT(CASE WHEN r.transport = '1' THEN o.VISIT_ID END) AS 'ไปเอง',
			COUNT(DISTINCT r.visit_id) AS 'จำนวนทั้งหมด',  -- จำนวนทั้งหมด
			COUNT(DISTINCT r.visit_id) AS refer_count,  -- จำนวน refer
			COUNT(DISTINCT i.adm_id) AS admit_count,   -- จำนวน admit
			COUNT(DISTINCT o.hn) AS person_count,      -- จำนวนคน
			COUNT(o.visit_id) AS visit_count ,          -- จำนวนครั้ง
			COUNT(CASE WHEN TIMESTAMPDIFF(HOUR, o.REG_DATETIME, r.rf_dt) < 2 THEN 1 END) AS less_than_2_hours,  -- จำนวนกรณีที่ใช้เวลาน้อยกว่า 2 ชั่วโมง
			COUNT(CASE WHEN TIMESTAMPDIFF(HOUR, o.REG_DATETIME, r.rf_dt) >= 2 THEN 1 END) AS more_than_2_hours
		FROM opd_visits o
		INNER JOIN cid_hn b ON o.HN = b.HN
		INNER JOIN population p ON b.CID = p.CID 
		LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND o.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2 
		LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 
		LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10 
		LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
		LEFT JOIN service_units u ON u.unit_id = o.unit_reg
		WHERE r.RF_DT BETWEEN :start_date AND :end_date
        AND (:unit_reg = 'ALL' OR o.unit_reg = :unit_reg)
		#GROUP BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
		#ORDER BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME);

";

// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql1 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// สร้างคำสั่ง SQL
try {
    // กรองค่าพารามิเตอร์ที่ไม่จำเป็นออกจาก array ก่อนการส่งให้ SQL
    $params = array_filter([
        ':start_date' => $startDate,
        ':end_date' => $endDate,
        ':unit_reg' => !empty($dep) ? $dep : 'ALL',  // ส่ง 'ALL' หาก :unit_reg ว่าง
        ':icd_code1' => !empty($icdcode1) ? $icdcode1 : null,
        ':icd_code2' => !empty($icdcode2) ? $icdcode2 : null,
    ]);

    // Execute the query
    $data1 = Yii::$app->db14->createCommand($sql1, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data1,
    'pagination' => [
        'pageSize' => 10,
    ],
]);


$sql2 = "
    SELECT
        o.visit_id, o.hn,
        o.REG_DATETIME AS regdate,
        r.rf_dt AS referdate,
        i.adm_dt AS admitdate,
        r.hosp_id AS refer,
        i.adm_id AS an,
        o.unit_reg,
        u.unit_name,
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) AS `age`,
        ABS(TIMESTAMPDIFF(HOUR, o.REG_DATETIME, r.RF_DT)) AS times,
        ic.ICD10_TM AS PostRefer,
        GROUP_CONCAT(ic.ICD10_TM) AS diag
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND o.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE r.RF_DT BETWEEN :start_date AND :end_date
    AND (:unit_reg = 'ALL' OR o.unit_reg = :unit_reg)
";

// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql2 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY หลังจากการกรอง ICD Code
$sql2 .= " GROUP BY o.VISIT_ID";

// สร้างคำสั่ง SQL
try {
    // กรองค่าพารามิเตอร์ที่ไม่จำเป็นออกจาก array ก่อนการส่งให้ SQL
    $params = array_filter([
        ':start_date' => $startDate,
        ':end_date' => $endDate,
        ':unit_reg' => !empty($dep) ? $dep : 'ALL',  // ส่ง 'ALL' หาก :unit_reg ว่าง
        ':icd_code1' => !empty($icdcode1) ? $icdcode1 : null,
        ':icd_code2' => !empty($icdcode2) ? $icdcode2 : null,
    ]);

    // Execute the query
    $data2 = Yii::$app->db14->createCommand($sql2, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$hnProvider = new ArrayDataProvider([
    'allModels' => $data2,
    'pagination' => [
        'pageSize' => 100,
    ],
]);



// Final query for monthly data
$sql3 = "
   
    SELECT
        CASE MONTH(o.REG_DATETIME)
            WHEN 1 THEN 'มกราคม'
            WHEN 2 THEN 'กุมภาพันธ์'
            WHEN 3 THEN 'มีนาคม'
            WHEN 4 THEN 'เมษายน'
            WHEN 5 THEN 'พฤษภาคม'
            WHEN 6 THEN 'มิถุนายน'
            WHEN 7 THEN 'กรกฎาคม'
            WHEN 8 THEN 'สิงหาคม'
            WHEN 9 THEN 'กันยายน'
            WHEN 10 THEN 'ตุลาคม'
            WHEN 11 THEN 'พฤศจิกายน'
            WHEN 12 THEN 'ธันวาคม'
        END AS ชื่อเดือน,
        YEAR(o.REG_DATETIME) + 543 AS ปี,
        COUNT(CASE WHEN r.transport <> '1' THEN o.VISIT_ID END) AS 'จำนวนครั้งโดยรถโรงพยาบาล',
        COUNT(CASE WHEN r.transport = '1' THEN o.VISIT_ID END) AS 'ไปเอง',
        COUNT(DISTINCT r.visit_id) AS refer_count,
        COUNT(DISTINCT i.adm_id) AS admit_count,
        COUNT(DISTINCT o.hn) AS person_count,
        COUNT(o.visit_id) AS visit_count,
        COUNT(CASE WHEN TIMESTAMPDIFF(HOUR, o.REG_DATETIME, r.rf_dt) < 2 THEN 1 END) AS less_than_2_hours,
        COUNT(CASE WHEN TIMESTAMPDIFF(HOUR, o.REG_DATETIME, r.rf_dt) >= 2 THEN 1 END) AS more_than_2_hours
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND o.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE r.RF_DT BETWEEN '2024-12-16 00:01' AND '2025-01-16 23:59'
    AND o.unit_reg = :unit_reg
    GROUP BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
    ORDER BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
";

try {
    // ตรวจสอบพารามิเตอร์ :unit_reg
    $params = array_filter([
        ':unit_reg' => !empty($dep) ? $dep : 'ALL',
    ]);

    $rawData = Yii::$app->db14->createCommand($sql3, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// Creating data provider for pagination and data display
$monthProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pageSize' => 200, // Limit the number of items per page
    ],
]);
##################  10 อันดับโรค #################################################################################
$sql4 = "
    SELECT 
        ic.ICD10_TM AS `โรค`,
        ic.NICKNAME AS `รายละเอียดโรค`,
        COUNT(o.visit_id) AS `จำนวนครั้ง`
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND o.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE r.RF_DT BETWEEN :start_date AND :end_date
    AND (:unit_reg = 'ALL' OR o.unit_reg = :unit_reg)
";

// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql4 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY และ ORDER BY
$sql4 .= "
    GROUP BY ic.ICD10_TM, ic.NICKNAME
    ORDER BY COUNT(o.visit_id) DESC
    LIMIT 10
";

try {
    // กรองค่าพารามิเตอร์ที่ไม่จำเป็นออกจาก array ก่อนการส่งให้ SQL
    $params = array_filter([
        ':start_date' => $startDate,
        ':end_date' => $endDate,
        ':unit_reg' => !empty($dep) ? $dep : 'ALL',  // ส่ง 'ALL' หาก :unit_reg ว่าง
        ':icd_code1' => !empty($icdcode1) ? $icdcode1 : null,
        ':icd_code2' => !empty($icdcode2) ? $icdcode2 : null,
    ]);

    // Execute the query
    $data4 = Yii::$app->db14->createCommand($sql4, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// ตัวอย่างการดึงข้อมูลชื่อแผนกจากฐานข้อมูล
$departmentName = Yii::$app->db14->createCommand("
    SELECT unit_name 
    FROM service_units 
    WHERE unit_id = :unit_id
")
->bindValue(':unit_id', $dep)
->queryScalar();

// หากไม่พบแผนก ให้ตั้งค่าเป็น "ทุกแผนก"
$departmentName = $departmentName ?: 'ทั้งหมด';
 
// GridView data provider
$groupProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data4,
    'pagination' => [
        'pageSize' => 10,
    ],
]);

	// ส่งค่าข้อมูลไปยัง View
        return $this->render('index', [
            'dataProvider' => $dataProvider,
			'hnProvider' => $hnProvider,
			'groupProvider' => $groupProvider,
			'monthProvider' => $monthProvider,
            'startDate' => $startDate,
            'endDate' => $endDate,
			'departmentName' => $departmentName,
			'sql2' => $sql2,
        ]);
    }
	
}
