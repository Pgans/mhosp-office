<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LogDatacenter;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class IpdController extends Controller
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
  $data = Yii::$app->request->get();

$startDate = isset($data['start_date']) ? date('Y-m-d 00:01', strtotime($data['start_date'])) : '';
$endDate = isset($data['end_date']) ? date('Y-m-d 23:59', strtotime($data['end_date'])) : '';
	
    $dep = Yii::$app->request->get('unit_reg', ''); // Default to '11' if not selected
    $icdcode1 = Yii::$app->request->get('icd_code1', '');
    $icdcode2 = Yii::$app->request->get('icd_code2', '');
	#################### LOG #################################################
	        $connection = Yii::$app->db;
			if (\Yii::$app->request->isGet) {

			   // $cid = \Yii::$app->request->post('cid');
			   // Yii::$app->session['cid'] = $cid;

				$log = new LogDatacenter();
				$log->username = \Yii::$app->user->identity->username;
				$log->patient_cid = $startDate;
				$log->datetime = date('Y-m-d H:i:s');
				$log->ip = \Yii::$app->request->getUserIP();
				$log->dep = 'ipd';

				if ($log->save()) {
					//MyHelper::setAlert('success','......');
				}
			}
	###############################################################################	
	
	#############  รวมการนับจำนวนครั้ง จำนวนคน ##################################################
$sql1 = "
    SELECT 
        CASE MONTH(i.dsc_dt)
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
        YEAR(i.dsc_dt) + 543 AS ปี,
        COUNT(DISTINCT i.VISIT_ID) AS 'visit',
        COUNT(DISTINCT o.HN) AS 'kon',
        COUNT(r.hosp_id) AS 'refers',
        COUNT(DISTINCT i.adm_id) AS 'admit',
        SUM(CASE WHEN i.ward_no = '38' THEN 1 ELSE 0 END) AS 'ward1',
        SUM(CASE WHEN i.ward_no = '39' THEN 1 ELSE 0 END) AS 'ward2',
        SUM(CASE WHEN i.ward_no = '22' THEN 1 ELSE 0 END) AS 'lr',
        SUM(CASE WHEN i.ward_no = '50' THEN 1 ELSE 0 END) AS 'HomeWard',
        SUM(CASE WHEN i.ward_no = '55' THEN 1 ELSE 0 END) AS 'ward4',
		SUM(CASE WHEN i.ward_no = '61' THEN 1 ELSE 0 END) AS 'ward5'
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID 
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND o.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2 
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10 
    INNER JOIN ipd_reg i ON i.visit_id = o.visit_id 
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE o.IS_CANCEL = 0
      AND i.IS_CANCEL = 0
      AND i.dsc_dt BETWEEN :start_date AND :end_date
      AND (:ward_no IS NULL OR i.ward_no = COALESCE(:ward_no, i.ward_no))
";

// เพิ่มเงื่อนไขถ้ามี icd_code1 และ icd_code2
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql1 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

$sql1 .= "
    GROUP BY YEAR(i.dsc_dt), MONTH(i.dsc_dt)
    ORDER BY YEAR(i.dsc_dt), MONTH(i.dsc_dt)
";

// รับค่าจากฟอร์ม
$ward_no = Yii::$app->request->get('ward_no', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'

try {
    // กำหนดค่าพารามิเตอร์ให้ถูกต้อง
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    // ถ้า ward_no ไม่ใช่ 'ALL' ให้ใส่ค่าในพารามิเตอร์
    if ($ward_no !== 'ALL') {
        $params[':ward_no'] = $ward_no;
    } else {
        $params[':ward_no'] = null;
    }

    // ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
    if (!empty($icdcode1) && !empty($icdcode2)) {
        $params[':icd_code1'] = $icdcode1;
        $params[':icd_code2'] = $icdcode2;
    }

    // ตรวจสอบค่าพารามิเตอร์
    Yii::info($params, 'debug');

    // ดึงข้อมูลจากฐานข้อมูล
    $data1 = Yii::$app->db14->createCommand($sql1, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// สร้าง GridView data provider
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data1,
    'pagination' => [
        'pageSize' => 10,
    ],
]);



#$######################################### รายงานตามVisit ##############################################################################
##########################################################################

$sql2 = "SELECT
    o.visit_id, o.hn,
    o.REG_DATETIME AS regdate,
    r.rf_dt AS referdate,
    i.adm_dt AS admitdate,
    r.hosp_id AS refer,
    i.adm_id AS an,
    i.ward_no,
    u.unit_name,
    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
    CASE
        WHEN p.SEX = 1 THEN 'ชาย'
        WHEN p.SEX = 2 THEN 'หญิง'
    END AS `เพศ`,
    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) AS `age`,
    GROUP_CONCAT(DISTINCT ic.ICD10_TM ORDER BY ic.ICD10_TM ASC) AS diag
FROM opd_visits o
INNER JOIN cid_hn b ON o.HN = b.HN
INNER JOIN population p ON b.CID = p.CID
LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND DXT_ID = 1
LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
INNER JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
LEFT JOIN service_units u ON u.unit_id = i.WARD_NO
WHERE o.IS_CANCEL = 0
  AND i.dsc_dt BETWEEN :start_date AND :end_date
  AND (:ward_no IS NULL OR i.ward_no = :ward_no)";

// ตรวจสอบเงื่อนไข ICD Code และเพิ่มใน SQL
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql2 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY หลังจากการกรองข้อมูล
$sql2 .= " GROUP BY o.VISIT_ID ORDER BY i.dsc_dt DESC";

try {
    // ดึงค่าจากฟอร์ม
    $ward_no = Yii::$app->request->get('ward_no', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'

    // กำหนดพารามิเตอร์ SQL
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    // ถ้า ward_no ไม่ใช่ 'ALL' ให้กำหนดค่า ถ้าเป็น 'ALL' ให้ปล่อยเป็น NULL
    if ($ward_no !== 'ALL') {
        $params[':ward_no'] = $ward_no;
    } else {
        $params[':ward_no'] = null;
    }

    // ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
    if (!empty($icdcode1) && !empty($icdcode2)) {
        $params[':icd_code1'] = $icdcode1;
        $params[':icd_code2'] = $icdcode2;
    }

    // ตรวจสอบค่าพารามิเตอร์
    Yii::info($params, 'debug');

    // ดึงข้อมูลจากฐานข้อมูล
    $data2 = Yii::$app->db14->createCommand($sql2, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$visitProvider = new ArrayDataProvider([
    'allModels' => $data2,
    'pagination' => [
        'pageSize' => 100,
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
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    INNER JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE i.dsc_dt BETWEEN :start_date AND :end_date
    AND (COALESCE(:ward_no, '') = '' OR i.ward_no = :ward_no)
    AND LEFT(ic.ICD10_TM, 1) <> 'Z'
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

// ดึงค่าจากฟอร์ม
$ward_no = Yii::$app->request->get('ward_no', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
];

// ถ้า ward_no ไม่ใช่ 'ALL' ให้กำหนดค่า ถ้าเป็น 'ALL' ให้ปล่อยเป็น NULL
$params[':ward_no'] = ($ward_no !== 'ALL') ? $ward_no : null;

// ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
if (!empty($icdcode1) && !empty($icdcode2)) {
    $params[':icd_code1'] = $icdcode1;
    $params[':icd_code2'] = $icdcode2;
}

// ตรวจสอบค่าพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data4 = Yii::$app->db14->createCommand($sql4, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// ดึงข้อมูลชื่อแผนกจากฐานข้อมูล
$departmentName = Yii::$app->db14->createCommand("
    SELECT unit_name 
    FROM service_units 
    WHERE unit_id = :unit_id
")
->bindValue(':unit_id', $dep)
->queryScalar();

// หากไม่พบแผนก ให้ตั้งค่าเป็น "ทั้งหมด"
$departmentName = $departmentName ?: 'ทั้งหมด';

// GridView data provider
$groupProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data4,
    'pagination' => [
        'pageSize' => 10,
    ],
]);


#########################
##################  แยกตามสิทธิ์การรักษา  #################################################################################

      $sql5 = "
    SELECT m.INSCL_NAME as 'inscl', 
           COUNT(a.VISIT_ID) as 'Visit',
           COUNT(DISTINCT a.HN) as 'amount'
    FROM opd_visits a
    INNER JOIN cid_hn b ON a.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID 
    LEFT JOIN refers r ON a.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2 
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10 
    INNER JOIN ipd_reg i ON i.visit_id = a.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN main_inscls m ON a.inscl = m.inscl
    WHERE a.IS_CANCEL = 0
      AND i.dsc_dt BETWEEN :start_date AND :end_date
      AND (:ward_no IS NULL OR i.ward_no = :ward_no)
      AND a.VISIT_ID NOT IN (SELECT mobile_visits.VISIT_ID FROM mobile_visits WHERE mobile_visits.IS_CANCEL = 0)
";

// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql5 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY และ ORDER BY
$sql5 .= "
    GROUP BY m.INSCL_NAME
    ORDER BY amount DESC
    LIMIT 10
";

// ดึงค่าจากฟอร์ม
$ward_no = Yii::$app->request->get('ward_no', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
];

// ถ้า ward_no ไม่ใช่ 'ALL' ให้กำหนดค่า ถ้าเป็น 'ALL' ให้ปล่อยเป็น NULL
$params[':ward_no'] = ($ward_no !== 'ALL') ? $ward_no : null;

// ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
if (!empty($icdcode1) && !empty($icdcode2)) {
    $params[':icd_code1'] = $icdcode1;
    $params[':icd_code2'] = $icdcode2;
}

try {
    // ดึงข้อมูลจากฐานข้อมูล
    $data5 = Yii::$app->db14->createCommand($sql5, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$insclProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data5,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
 $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_datacenter  v  where v.dep = 'ipd'
			";
        
         $data = \yii::$app->db->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }

	// ส่งค่าข้อมูลไปยัง View
        return $this->render('index', [
            'dataProvider' => $dataProvider,
			'visitProvider' => $visitProvider,
			'groupProvider' => $groupProvider,
			'monthProvider' => $monthProvider,
			'insclProvider' => $insclProvider,
            'startDate' => $startDate,
            'endDate' => $endDate,
			'departmentName' => $departmentName,
			'sql2' => $sql2,
			'amount'=>$amount, 
        ]);
    }
	
}
