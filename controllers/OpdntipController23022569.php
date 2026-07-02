<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

class OpdntipController extends Controller
{
	 public function actionIndex()
{
  $data = Yii::$app->request->get();

$startDate = isset($data['start_date']) ? date('Y-m-d 00:01', strtotime($data['start_date'])) : '';
$endDate = isset($data['end_date']) ? date('Y-m-d 23:59', strtotime($data['end_date'])) : '';
	
    $dep = Yii::$app->request->get('unit_reg', ''); // Default to '11' if not selected
    $icdcode1 = Yii::$app->request->get('icd_code1', '');
    $icdcode2 = Yii::$app->request->get('icd_code2', '');

    $sql1 = "
   SELECT 
    YEAR(o.REG_DATETIME) + 543 AS ปี,
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
    COUNT(o.VISIT_ID) AS Visit,
    COUNT(DISTINCT o.HN) AS amount
FROM opd_visits o
INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
INNER JOIN population p ON p.CID = c.CID
LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
LEFT JOIN icd10new i ON i.icd10 = dx.icd10
LEFT JOIN service_units u ON u.unit_id = o.unit_reg
LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('123', '086', '088')
LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
LEFT JOIN towns t ON p.town_id = t.town_id
LEFT JOIN hospitals h ON h.hosp_id = t.hospsub 
LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id, 6), '00') = t1.town_id
LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(o.REG_DATETIME) = DATE(ak.d_update)
WHERE o.REG_DATETIME BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
  AND o.unit_reg IN ('12', '15', '34')
  AND o.visit_id IN (SELECT visit_id FROM xray_requests) 
GROUP BY ปี, ชื่อเดือน
ORDER BY ปี, MONTH(o.REG_DATETIME);

";

$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => !empty($dep) ? $dep : 'ALL',
];

try {
    $data1 = Yii::$app->db70->createCommand($sql1, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

$dataProvider = new ArrayDataProvider([
    'allModels' => $data1,
    'pagination' => [
        'pageSize' => 10,
    ],
]);




#$######################################### รายงานตามVisit ##############################################################################
##########################################################################

$sql2 = "SELECT DISTINCT
    DATE_FORMAT(o.REG_DATETIME, '%d-%m-%Y %H:%i:%s') AS regdate,
    o.visit_id AS visit_id,
    o.REG_DATETIME AS regdate,
    o.HN AS hn,
    p.cid,
	 CASE
        WHEN p.SEX = 1 THEN 'ชาย'
        WHEN p.SEX = 2 THEN 'หญิง'
    END AS `sex`,
    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
    TIMESTAMPDIFF(year, p.birthdate, o.REG_DATETIME) AS age,
    u.unit_name,
    i.icd10_tm AS diag,
    ROUND((o.WEIGHT / ((o.height / 100) * (o.height / 100))), 2) AS BMI,
    MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1c,
    MAX(CASE WHEN ll.lab_id IN ('086', '088') THEN ll.lab_result ELSE '' END) AS AFB,
    h.hosp_name,
    h.hosp_id,
	IFNULL(ak.claimcode, '') AS claimcode,
    TRIM(t.TOWN_NAME) AS 'บ้าน',
    TRIM(t1.TOWN_NAME) AS 'ตำบล',
	TRIM(t2.TOWN_NAME) AS 'อำเภอ',
	TRIM(t3.TOWN_NAME) AS 'จังหวัด'
FROM opd_visits o
INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
INNER JOIN population p ON p.CID = c.CID
LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
LEFT JOIN icd10new i ON i.icd10 = dx.icd10
LEFT JOIN service_units u ON u.unit_id = o.unit_reg
LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('123', '086', '088')
LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
LEFT JOIN towns t ON p.town_id = t.town_id
LEFT JOIN hospitals h ON h.hosp_id = t.hospsub 
LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id, 6), '00') = t1.town_id
LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id, 4), '0000') = t2.town_id
LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id, 2), '000000') = t3.town_id
LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(o.REG_DATETIME) = DATE(ak.d_update)
WHERE o.reg_datetime BETWEEN :start_date AND :end_date
#o.REG_DATETIME BETWEEN '2025-01-01 00:01' AND NOW()
  AND o.unit_reg IN ('12', '15', '34')
  AND o.visit_id IN (SELECT visit_id FROM xray_requests) 
  GROUP BY o.hn
 ;
";

// ตรวจสอบแผนก (unit_reg)
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL'); // ค่าเริ่มต้น 'ALL'

if ($unit_reg !== 'ALL') {
    //$sql2 .= " AND o.unit_reg = :unit_reg";
}

// ตรวจสอบ ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
   // $sql2 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY
$sql2 .= " ORDER BY h.hosp_id ";

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
    $data2 = Yii::$app->db70->createCommand($sql2, $params)->queryAll();
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
    $data4 = Yii::$app->db70->createCommand($sql4, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// ดึงข้อมูลชื่อแผนกจากฐานข้อมูล
$departmentName = Yii::$app->db70->createCommand("
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
    icd9cm ic ON b.icd9 = ic.icd9 AND ic.code = 9007810 AND ic.CGD_ID = 15
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
    $data5 = Yii::$app->db70->createCommand($sql5, $params)->queryAll();
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
##################  ผู้ทำหัตการนวด #################################################################################
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
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9 AND ic.code = 9007810 AND ic.CGD_ID = 15
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
		GROUP BY a.visit_id
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
    $data6 = Yii::$app->db70->createCommand($sql6, $params)->queryAll();
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
##################  แยกตาม รพสต #################################################################################
$sql9 = "SELECT 
    k.hosp_id, 
    k.hosp_name, 
    COUNT(k.visit_id) AS amount
FROM (
    SELECT 
        o.visit_id AS visit_id,
        o.REG_DATETIME AS regdate,
        o.HN AS hn,
        p.cid,
        IFNULL(ak.claimcode, '') AS claimcode,
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        TIMESTAMPDIFF(year, p.birthdate, o.REG_DATETIME) AS age,
        u.unit_name,
        u.unit_id,
        i.icd10_tm AS Diag,
        ROUND((o.WEIGHT / ((o.height / 100) * (o.height / 100))), 2) AS BMI,
        MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1c,
        MAX(CASE WHEN ll.lab_id IN ('086', '088') THEN ll.lab_result ELSE '' END) AS AFB,
        h.hosp_name,
        h.hosp_id,
        TRIM(t.TOWN_NAME) AS 'บ้าน',
        TRIM(t1.TOWN_NAME) AS 'ตำบล'
    FROM opd_visits o
    INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
    INNER JOIN population p ON p.CID = c.CID
    LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
    LEFT JOIN icd10new i ON i.icd10 = dx.icd10
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('123', '086', '088')
    LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
    LEFT JOIN towns t ON p.town_id = t.town_id
    LEFT JOIN hospitals h ON h.hosp_id = t.hospsub 
    LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id, 6), '00') = t1.town_id
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(o.REG_DATETIME) = DATE(ak.d_update)
    WHERE o.REG_DATETIME BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR o.unit_reg = :unit_reg)
      AND o.unit_reg IN ('12', '15', '34')
      AND o.visit_id IN (SELECT visit_id FROM xray_requests) 
    GROUP BY o.hn
) AS k
GROUP BY k.hosp_id 
ORDER BY k.hosp_id";

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
    $data9 = Yii::$app->db70->createCommand($sql9, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\BadRequestHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$staffProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data9,
    'pagination' => [
        'pageSize' => 30,
    ],
]);
##################  แผนก #################################################################################
$sql7 = "SELECT 
    k.unit_id, 
    k.unit_name, 
    COUNT(k.visit_id) AS amount
FROM (
    SELECT 
        o.visit_id AS visit_id,
        o.REG_DATETIME AS regdate,
        o.HN AS hn,
        p.cid,
        IFNULL(ak.claimcode, '') AS claimcode,
        CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
        TIMESTAMPDIFF(year, p.birthdate, o.REG_DATETIME) AS age,
        u.unit_name,
        u.unit_id,
        i.icd10_tm AS Diag,
        ROUND((o.WEIGHT / ((o.height / 100) * (o.height / 100))), 2) AS BMI,
        MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1c,
        MAX(CASE WHEN ll.lab_id IN ('086', '088') THEN ll.lab_result ELSE '' END) AS AFB,
        h.hosp_name,
        h.hosp_id,
        TRIM(t.TOWN_NAME) AS 'บ้าน',
        TRIM(t1.TOWN_NAME) AS 'ตำบล'
    FROM opd_visits o
    INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
    INNER JOIN population p ON p.CID = c.CID
    LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
    LEFT JOIN icd10new i ON i.icd10 = dx.icd10
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('123', '086', '088')
    LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
    LEFT JOIN towns t ON p.town_id = t.town_id
    LEFT JOIN hospitals h ON h.hosp_id = t.hospsub 
    LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id, 6), '00') = t1.town_id
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(o.REG_DATETIME) = DATE(ak.d_update)
    WHERE o.REG_DATETIME BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR o.unit_reg = :unit_reg)
      AND o.unit_reg IN ('12', '15', '34')
      AND o.visit_id IN (SELECT visit_id FROM xray_requests) 
    GROUP BY o.hn
) AS k
GROUP BY k.unit_id 
ORDER BY k.unit_id";


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
    $data7 = Yii::$app->db70->createCommand($sql7, $params)->queryAll();
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
    INNER JOIN icd9cm ic ON b.icd9 = ic.icd9 AND ic.code = 9007810 AND ic.CGD_ID = 15
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
    $data8 = Yii::$app->db70->createCommand($sql8, $params)->queryAll();
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
// ส่งค่าข้อมูลไปยัง View
return $this->render('index', [
    'dataProvider' => $dataProvider,
    'visitProvider' => $visitProvider,
    'groupProvider' => $groupProvider,
    'monthProvider' => $monthProvider,
    'insclProvider' => $insclProvider,
    'surgeonProvider' => $surgeonProvider,
	'depProvider' => $depProvider,
	'diagProvider' => $diagProvider,
	'staffProvider' => $staffProvider,
    'startDate' => $startDate,
    'endDate' => $endDate,
    'departmentName' => $departmentName,
    'sql2' => $sql2,
]);
}
}
