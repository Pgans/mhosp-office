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

class Operations15Controller extends Controller
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
			if (\Yii::$app->request->isPost) {

			   // $cid = \Yii::$app->request->post('cid');
			   // Yii::$app->session['cid'] = $cid;

				$log = new LogThaimed();
				$log->username = \Yii::$app->user->identity->username;
				$log->patient_cid = $date1;
				$log->datetime = date('Y-m-d H:i:s');
				$log->ip = \Yii::$app->request->getUserIP();

				if ($log->save()) {
					//MyHelper::setAlert('success','......');
				}
			}
	###############################################################################		
			
    $sql1 = "
   SELECT 
    CONCAT(
        CASE MONTH(a.REG_DATETIME) 
            WHEN 1 THEN 'มกราคม' WHEN 2 THEN 'กุมภาพันธ์' WHEN 3 THEN 'มีนาคม' 
            WHEN 4 THEN 'เมษายน' WHEN 5 THEN 'พฤษภาคม' WHEN 6 THEN 'มิถุนายน' 
            WHEN 7 THEN 'กรกฎาคม' WHEN 8 THEN 'สิงหาคม' WHEN 9 THEN 'กันยายน' 
            WHEN 10 THEN 'ตุลาคม' WHEN 11 THEN 'พฤศจิกายน' WHEN 12 THEN 'ธันวาคม' 
        END, ' ', YEAR(a.REG_DATETIME) + 543
    ) AS yearmonth,
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) = 'บริบาล' THEN '3' END) AS 'บริบาล', 
    COUNT(CASE WHEN LEFT(NICKNAME,6) = 'การนวด' THEN '4' END) AS 'นวด',
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม',
	COUNT(CASE WHEN c.CODE = '873-78-35' THEN 1 END) AS 'พอกหัวเข่า',
	COUNT(CASE WHEN c.CODE = '9991801' THEN 2 END) AS 'Acupuncture',
    COUNT(CODE) AS Total,
	COUNT(DISTINCT a.hn) AS kon
FROM opd_visits a
INNER JOIN opd_operations b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.cgd_id IN (15)
WHERE a.REG_DATETIME BETWEEN '2025-10-01 00:01' AND '2026-12-31 23:59'
AND a.is_cancel = 0
GROUP BY MONTH(a.REG_DATETIME), YEAR(a.REG_DATETIME)
ORDER BY YEAR(a.REG_DATETIME), MONTH(a.REG_DATETIME);

";

$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => !empty($dep) ? $dep : 'ALL',
];

try {
    $data1 = Yii::$app->db14->createCommand($sql1, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

$dataProvider = new ArrayDataProvider([
    'allModels' => $data1,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
##########################  แยกรายวัน ################################################################################################
$sql01 = "
   SELECT 
    CONCAT(
        DAY(a.REG_DATETIME), ' ',
        CASE MONTH(a.REG_DATETIME) 
            WHEN 1 THEN 'มกราคม' WHEN 2 THEN 'กุมภาพันธ์' WHEN 3 THEN 'มีนาคม' 
            WHEN 4 THEN 'เมษายน' WHEN 5 THEN 'พฤษภาคม' WHEN 6 THEN 'มิถุนายน' 
            WHEN 7 THEN 'กรกฎาคม' WHEN 8 THEN 'สิงหาคม' WHEN 9 THEN 'กันยายน' 
            WHEN 10 THEN 'ตุลาคม' WHEN 11 THEN 'พฤศจิกายน' WHEN 12 THEN 'ธันวาคม' 
        END, ' ', YEAR(a.REG_DATETIME) + 543
    ) AS visit_date,
    
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) = 'บริบาล' THEN '3' END) AS 'บริบาล', 
    COUNT(CASE WHEN LEFT(NICKNAME,6) = 'การนวด' THEN '4' END) AS 'นวด',
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม',

    COUNT(CASE WHEN c.CODE = '873-78-35' THEN 1 END) AS 'พอกหัวเข่า',
    COUNT(CASE WHEN c.CODE = '9991801' THEN 2 END) AS 'Acupuncture',
	COUNT(cv.visit_id) AS closed,
    COUNT(CODE) AS Total,
    COUNT(DISTINCT a.hn) AS kon

FROM opd_visits a
INNER JOIN opd_operations b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.cgd_id IN (15)
LEFT JOIN close_visits cv ON a.visit_id = cv.visit_id
WHERE a.REG_DATETIME BETWEEN :start_date AND :end_date
AND a.is_cancel = 0

GROUP BY DATE(a.REG_DATETIME)  -- เปลี่ยนจาก MONTH -> DATE
ORDER BY a.REG_DATETIME;  -- เรียงตามวัน
";

$params = [
    ':start_date' => $startDate,  // ตรวจสอบว่า $startDate ถูกกำหนด
    ':end_date' => $endDate,      // ตรวจสอบว่า $endDate ถูกกำหนด
];

try {
    $data01 = Yii::$app->db14->createCommand($sql01, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// สร้าง ArrayDataProvider เพื่อใช้กับ GridView หรือ DataView
$daysProvider = new ArrayDataProvider([
    'allModels' => $data01,
    'pagination' => [
        'pageSize' => 10, // จำนวนแถวต่อหน้า
    ],
]);






#$######################################### รายงานตามVisit ##############################################################################
##########################################################################

$sql2 = "SELECT DISTINCT 
    a.REG_DATETIME as REGDATE, 
    d.INSCL, 
    d.INSCL_NAME, 
    a.HN, 
    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
    CASE
        WHEN p.SEX = 1 THEN 'ชาย'
        WHEN p.SEX = 2 THEN 'หญิง'
    END AS `เพศ`,
    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
    i.ICD10_TM AS diag,
    ic.CODE, 
    ic.NICKNAME, 
    b.STAFF_ID,
    p_staff.fname AS STAFF_fname,  -- แสดงชื่อ STAFF_ID
    b.SURGEON_ID, 
    p1.fname AS SURGEON_fname,  -- แสดงชื่อ SURGEON_ID
    u.unit_id, 
    u.unit_name,
	ic.CGD_ID,
    IFNULL(ak.claimcode, '') AS claimcode,
	IFNULL(cv.claimcode, '') AS closed,
	CASE 
WHEN a.INSCL in (03,04) AND uc.HOSPMAIN ='10953' THEN CONCAT(d.INSCL_NAME,' --ในเขต') 
WHEN a.INSCL in (03,04) AND uc.HOSPMAIN !='10953' THEN CONCAT(d.INSCL_NAME,' --นอกเขต') 
ELSE d.INSCL_NAME 
END as inscl 
FROM opd_visits a
INNER JOIN cid_hn c ON a.HN = c.HN
INNER JOIN population p ON c.CID = p.CID
LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
LEFT JOIN main_inscls d ON a.inscl = d.inscl
LEFT JOIN service_units u ON u.unit_id = a.unit_reg
LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

-- แสดงชื่อของ STAFF_ID จาก population
LEFT JOIN staff s ON s.staff_id = b.staff_id
LEFT JOIN population p_staff ON s.cid = p_staff.cid  -- นำ CID ของ staff ไปหาชื่อจริง

-- แสดงชื่อของ SURGEON_ID จาก population
LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
LEFT JOIN population p1 ON s1.cid = p1.cid 
LEFT JOIN close_visits cv ON cv.visit_id = a.visit_id 
LEFT JOIN uc_inscl uc ON uc.CID=p.CID AND (uc.date_abort = date(a.REG_DATETIME) or day(uc.date_abort)=0 and trim(uc.hospmain) <> '' ) 
    WHERE  a.reg_datetime BETWEEN :start_date AND :end_date
	AND a.is_cancel = 0
	#a.REG_DATETIME BETWEEN  '2025-02-10 00:01' AND '2025-02-10 23:59'
    #AND a.visit_id NOT in (SELECT VISIT_ID FROM mobile_visits)
  #AND a.reg_datetime BETWEEN :start_date AND :end_date";

// ตรวจสอบแผนก (unit_reg)
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL'); // ค่าเริ่มต้น 'ALL'

if ($unit_reg !== 'ALL') {
    $sql2 .= " AND o.unit_reg = :unit_reg";
}

// ตรวจสอบ ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
   // $sql2 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY
$sql2 .= " GROUP BY o.VISIT_ID
		ORDER BY CODE
";

try {
    // กำหนดพารามิเตอร์
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    if ($unit_reg !== 'ALL') {
        $params[':unit_reg'] = $unit_reg;
    }

    if (!empty($icdcode1) && !empty($icdcode2)) {
        $params[':icd_code1'] = $icdcode1;
        $params[':icd_code2'] = $icdcode2;
    }

    // Debug ตรวจสอบพารามิเตอร์
    Yii::info($params, 'debug');

    // ดึงข้อมูล
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
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE o.reg_datetime BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR o.unit_reg = :unit_reg)
      AND ic.ICD10_TM NOT LIKE 'Z%'
";

// ตรวจสอบเงื่อนไขการกรอง ICD Code
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
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
];

// ถ้า unit_reg ไม่ใช่ 'ALL' ให้กำหนดค่า ถ้าเป็น 'ALL' ให้ใช้ `NULL`
$params[':unit_reg'] = ($unit_reg !== 'ALL') ? $unit_reg : null;

// ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
if (!empty($icdcode1) && !empty($icdcode2)) {
    $params[':icd_code1'] = $icdcode1;
    $params[':icd_code2'] = $icdcode2;
}

// Debug parameters
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
->bindValue(':unit_id', $unit_reg)
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
SELECT 
    d.INSCL,
    d.INSCL_NAME,
    COUNT(a.VISIT_ID) AS Visit,
    COUNT(DISTINCT a.HN) AS amount
FROM 
    opd_visits a
INNER JOIN 
    cid_hn c ON a.HN = c.HN
INNER JOIN 
    population p ON c.CID = p.CID
LEFT JOIN 
    opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
INNER JOIN 
    icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
INNER JOIN 
    main_inscls d ON a.inscl = d.inscl
LEFT JOIN 
    service_units u ON u.unit_id = a.unit_reg
WHERE  a.is_cancel = 0
      AND a.reg_datetime BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
GROUP BY 
    d.INSCL, d.INSCL_NAME
ORDER BY 
    d.INSCL DESC;

    ";
/*
      $sql5 = "
	 
    SELECT 
        m.INSCL_NAME AS 'inscl', 
        COUNT(a.VISIT_ID) AS 'Visit',
        COUNT(DISTINCT a.HN) AS 'amount'
    FROM opd_visits a
    INNER JOIN cid_hn b ON a.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID 
    LEFT JOIN refers r ON a.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2 
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN main_inscls m ON a.inscl = m.inscl
    LEFT JOIN mobile_visits mv ON a.VISIT_ID = mv.VISIT_ID AND mv.IS_CANCEL = 0
    WHERE a.IS_CANCEL = 0
      AND a.reg_datetime BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
      AND mv.VISIT_ID IS NULL
";
*/
// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
   // $sql5 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY และ ORDER BY
$sql5 .= "
    GROUP BY m.INSCL_NAME
    ORDER BY amount DESC
    LIMIT 10
";

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null, // ใช้ $unit_reg ให้ถูกต้อง
];

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
##################  ผู้ทำหัตการ #################################################################################
$sql6 = "SELECT 
    k.surgeon_id, 
    k.SURGEON_fname, 
    COUNT(k.visit_id) AS amount
FROM 
(
    SELECT  
        a.REG_DATETIME as REGDATE, 
        d.INSCL, 
        d.INSCL_NAME, 
        a.HN, 
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
        i.ICD10_TM AS diag,
        ic.CODE, 
        ic.NICKNAME, 
        b.STAFF_ID,
        CONCAT(TRIM(p_staff.fname), ' ', p_staff.lname) AS STAFF_fname,
        b.SURGEON_ID, 
        CONCAT(TRIM(p1.fname), ' ', p1.lname) AS SURGEON_fname,
        u.unit_id, 
        u.unit_name,
        IFNULL(ak.claimcode, '') AS claimcode,
        a.visit_id  
    FROM opd_visits a
    INNER JOIN cid_hn c ON a.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
    LEFT JOIN main_inscls d ON a.inscl = d.inscl
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

    -- แสดงชื่อของ STAFF_ID จาก population
    LEFT JOIN staff s ON s.staff_id = b.staff_id
    LEFT JOIN population p_staff ON s.cid = p_staff.cid  

    -- แสดงชื่อของ SURGEON_ID จาก population
    LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
    LEFT JOIN population p1 ON s1.cid = p1.cid  

    WHERE  
        a.REG_DATETIME BETWEEN :start_date AND :end_date
        AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
		AND a.is_cancel = 0
		#GROUP BY a.visit_id
) AS k
GROUP BY k.surgeon_id, k.SURGEON_fname
ORDER BY amount DESC;";  // เรียงจากจำนวนหัตถการมากไปน้อย

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null
];

// Debugging ข้อมูลพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data6 = Yii::$app->db14->createCommand($sql6, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$surgeonProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data6,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
##################  ผู้สั่งทำหัตการ #################################################################################
$sql9 = "SELECT 
    k.STAFF_ID, 
    k.STAFF_fname, 
    COUNT(k.visit_id) AS amount
FROM 
(
    SELECT  
        a.REG_DATETIME as REGDATE, 
        d.INSCL, 
        d.INSCL_NAME, 
        a.HN, 
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
        i.ICD10_TM AS diag,
        ic.CODE, 
        ic.NICKNAME, 
        b.STAFF_ID,
        CONCAT(TRIM(p_staff.fname), ' ', p_staff.lname) AS STAFF_fname,
        b.SURGEON_ID, 
        CONCAT(TRIM(p1.fname), ' ', p1.lname) AS SURGEON_fname,
        u.unit_id, 
        u.unit_name,
        IFNULL(ak.claimcode, '') AS claimcode,
        a.visit_id  
    FROM opd_visits a
    INNER JOIN cid_hn c ON a.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
    LEFT JOIN main_inscls d ON a.inscl = d.inscl
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

    -- แสดงชื่อของ STAFF_ID จาก population
    LEFT JOIN staff s ON s.staff_id = b.staff_id
    LEFT JOIN population p_staff ON s.cid = p_staff.cid  

    -- แสดงชื่อของ SURGEON_ID จาก population
    LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
    LEFT JOIN population p1 ON s1.cid = p1.cid  

    WHERE  
        a.REG_DATETIME BETWEEN :start_date AND :end_date
        AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
		AND a.is_cancel = 0
		#GROUP BY a.visit_id
) AS k
GROUP BY k.staff_id, k.STAFF_fname
ORDER BY amount DESC;";  // เรียงจากจำนวนหัตถการมากไปน้อย

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null
];

// Debugging ข้อมูลพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data9 = Yii::$app->db14->createCommand($sql9, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$staffProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data9,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
##################  แผนก #################################################################################
$sql7 = "SELECT 
    k.unit_id, 
    k.unit_name, 
    COUNT( k.visit_id) AS amount
FROM 
(
    SELECT  
        a.REG_DATETIME as REGDATE, 
        d.INSCL, 
        d.INSCL_NAME, 
        a.HN, 
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
        i.ICD10_TM AS diag,
        ic.CODE, 
        ic.NICKNAME, 
        b.STAFF_ID,
        CONCAT(TRIM(p_staff.fname), ' ', p_staff.lname) AS STAFF_fname,
        b.SURGEON_ID, 
        CONCAT(TRIM(p1.fname), ' ', p1.lname) AS SURGEON_fname,
        u.unit_id, 
        u.unit_name,
        IFNULL(ak.claimcode, '') AS claimcode,
        a.visit_id  
    FROM opd_visits a
    INNER JOIN cid_hn c ON a.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
    LEFT JOIN main_inscls d ON a.inscl = d.inscl
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

    -- แสดงชื่อของ STAFF_ID จาก population
    LEFT JOIN staff s ON s.staff_id = b.staff_id
    LEFT JOIN population p_staff ON s.cid = p_staff.cid  

    -- แสดงชื่อของ SURGEON_ID จาก population
    LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
    LEFT JOIN population p1 ON s1.cid = p1.cid  

    WHERE  
        a.REG_DATETIME BETWEEN :start_date AND :end_date
        AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
		#GROUP BY a.visit_id
) AS k
GROUP BY k.unit_id
ORDER BY amount DESC;";  // เรียงจากจำนวนหัตถการมากไปน้อย

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null
];

// Debugging ข้อมูลพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data7 = Yii::$app->db14->createCommand($sql7, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$depProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data7,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
##################  รหัสโรค #################################################################################
$sql8 = "SELECT 
    k.diag,
    k.icd_name, 
    k.icd_thai, 
    COUNT(k.visit_id) AS amount
FROM 
(
    SELECT  
        a.REG_DATETIME as REGDATE, 
        d.INSCL, 
        d.INSCL_NAME, 
        a.HN, 
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
        i.ICD10_TM AS diag,
		i.icd_name,
		i.icd_thai,
        ic.CODE, 
        ic.NICKNAME, 
        b.STAFF_ID,
        CONCAT(TRIM(p_staff.fname), ' ', p_staff.lname) AS STAFF_fname,
        b.SURGEON_ID, 
        CONCAT(TRIM(p1.fname), ' ', p1.lname) AS SURGEON_fname,
        u.unit_id, 
        u.unit_name,
        IFNULL(ak.claimcode, '') AS claimcode,
        a.visit_id  
    FROM opd_visits a
    INNER JOIN cid_hn c ON a.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
    LEFT JOIN main_inscls d ON a.inscl = d.inscl
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

    -- แสดงชื่อของ STAFF_ID จาก population
    LEFT JOIN staff s ON s.staff_id = b.staff_id
    LEFT JOIN population p_staff ON s.cid = p_staff.cid  

    -- แสดงชื่อของ SURGEON_ID จาก population
    LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
    LEFT JOIN population p1 ON s1.cid = p1.cid  

    WHERE  
        a.REG_DATETIME BETWEEN :start_date AND :end_date
        AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
) AS k
GROUP BY k.diag
ORDER BY amount DESC LIMIT 6;";  // เรียงจากจำนวนหัตถการมากไปน้อย

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null
];

// Debugging ข้อมูลพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data8 = Yii::$app->db14->createCommand($sql8, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$diagProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data8,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
##################  หัตการทั้งหมด #################################################################################
$sql10 = "SELECT 
    k.code, 
    k.NICKNAME, 
    COUNT(k.visit_id) AS amount
FROM 
(
    SELECT  
        a.REG_DATETIME as REGDATE, 
        d.INSCL, 
        d.INSCL_NAME, 
        a.HN, 
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        CASE
            WHEN p.SEX = 1 THEN 'ชาย'
            WHEN p.SEX = 2 THEN 'หญิง'
        END AS `เพศ`,
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
        i.ICD10_TM AS diag,
        ic.CODE, 
        ic.NICKNAME, 
        b.STAFF_ID,
        CONCAT(TRIM(p_staff.fname), ' ', p_staff.lname) AS STAFF_fname,
        b.SURGEON_ID, 
        CONCAT(TRIM(p1.fname), ' ', p1.lname) AS SURGEON_fname,
        u.unit_id, 
        u.unit_name,
        IFNULL(ak.claimcode, '') AS claimcode,
        a.visit_id  
    FROM opd_visits a
    INNER JOIN cid_hn c ON a.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID
    LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9  AND ic.CGD_ID = 15
    LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
    LEFT JOIN main_inscls d ON a.inscl = d.inscl
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)

    -- แสดงชื่อของ STAFF_ID จาก population
    LEFT JOIN staff s ON s.staff_id = b.staff_id
    LEFT JOIN population p_staff ON s.cid = p_staff.cid  

    -- แสดงชื่อของ SURGEON_ID จาก population
    LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
    LEFT JOIN population p1 ON s1.cid = p1.cid  

    WHERE  
        a.REG_DATETIME BETWEEN :start_date AND :end_date
        AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
		AND a.is_cancel = 0
		
) AS k
GROUP BY k.code
ORDER BY amount DESC;";  // เรียงจากจำนวนหัตถการมากไปน้อย

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL');

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null
];

// Debugging ข้อมูลพารามิเตอร์
Yii::info($params, 'debug');

try {
    // Execute the query
    $data10 = Yii::$app->db14->createCommand($sql10, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$operProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data10,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
// ส่งค่าข้อมูลไปยัง View
return $this->render('index', [
    'dataProvider' => $dataProvider,
    'visitProvider' => $visitProvider,
    'groupProvider' => $groupProvider,
    'monthProvider' => $monthProvider,
    'insclProvider' => $insclProvider,
    'surgeonProvider' => $surgeonProvider,
	'depProvider' => $depProvider,
	'daysProvider' => $daysProvider,
	'diagProvider' => $diagProvider,
	'operProvider' => $operProvider,
	'staffProvider' => $staffProvider,
    'startDate' => $startDate,
    'endDate' => $endDate,
    'departmentName' => $departmentName,
    'sql2' => $sql2,
]);
}
}
