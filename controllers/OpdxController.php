<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\models\LogDatacenter;
class OpdxController extends Controller
{
	public function actionIndex()
{
    $data = Yii::$app->request->get();
    $startDate = isset($data['start_date']) ? date('Y-m-d 00:01', strtotime($data['start_date'])) : '';
    $endDate = isset($data['end_date']) ? date('Y-m-d 23:59', strtotime($data['end_date'])) : '';
    $dep = Yii::$app->request->get('unit_reg', '');
    $icdcode1 = Yii::$app->request->get('icd_code1', '');
    $icdcode2 = Yii::$app->request->get('icd_code2', '');
    $inscl = Yii::$app->request->get('inscl', 'ALL'); // รับค่า inscl
	$age_start = Yii::$app->request->get('age_start', ''); // ✅ รับค่า age_start
    $age_end = Yii::$app->request->get('age_end', ''); // ✅ รับค่า age_end
	$mobile = Yii::$app->request->get('mobile', 'ALL');
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
				$log->dep = 'opd';

				if ($log->save()) {
					//MyHelper::setAlert('success','......');
				}
			}
	###############################################################################	
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
        COUNT(DISTINCT o.VISIT_ID) AS 'visit',
        COUNT(DISTINCT o.HN) AS 'kon',
        COUNT(r.hosp_id) AS 'refers',
        COUNT(DISTINCT i.adm_id) AS 'admit'
    FROM opd_visits o
	INNER JOIN cid_hn b ON o.HN = b.HN
	INNER JOIN population p ON b.CID = p.CID
	LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
	INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 
	LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
	LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
	LEFT JOIN service_units u ON u.unit_id = o.unit_reg
	LEFT JOIN main_inscls m ON o.inscl = m.inscl
    WHERE o.IS_CANCEL = 0
      AND o.REG_DATETIME BETWEEN :start_date AND :end_date
	  AND o.VISIT_ID NOT in (SELECT ipd_reg.VISIT_ID FROM ipd_reg WHERE ipd_reg.IS_CANCEL = 0)
      #AND o.VISIT_ID NOT IN (SELECT mobile_visits.VISIT_ID FROM mobile_visits WHERE mobile_visits.IS_CANCEL = 0)
      AND (:unit_reg = 'ALL' OR o.unit_reg = :unit_reg)
      AND (:inscl = 'ALL' OR p.INSCL = :inscl)
";

// ✅ **เพิ่มเงื่อนไขสำหรับช่วงอายุ**
if (!empty($age_start) && !empty($age_end)) {
     $sql1 .= " AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN :age_start AND :age_end";
}
if ($diag === 'SPECIFIC') {
    $sql1 .= " AND od.DXT_ID = '1' ";   // โรคหลัก
} elseif ($diag === 'ALL') {
    // ทั้งหมด = ไม่กรอง
}
if ($mobile === 'SPECIFIC') {
    // ในหน่วยบริการ
    $sql1 .= " AND o.VISIT_ID NOT IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'MOBILE') {
    // นอกหน่วยบริการ
    $sql1 .= " AND o.VISIT_ID IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'ALL') {
    // ทั้งหมด = ไม่ต้องเพิ่มเงื่อนไขอะไร
}

// ✅ **เพิ่มเงื่อนไขสำหรับรหัสโรค**
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql1 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

$sql1 .= "
    GROUP BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
    ORDER BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
";

try {
    // ✅ **กำหนดค่าพารามิเตอร์**
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
        ':unit_reg' => !empty($dep) ? $dep : 'ALL',
        ':inscl' => !empty($inscl) ? $inscl : 'ALL',
    ];

    // ✅ **เพิ่มพารามิเตอร์ช่วงอายุ**
    if (!empty($age_start) && !empty($age_end)) {
        $params[':age_start'] = $age_start;
        $params[':age_end'] = $age_end;
    }

    // ✅ **เพิ่มพารามิเตอร์รหัสโรค**
    if (!empty($icdcode1) && !empty($icdcode2)) {
        $params[':icd_code1'] = $icdcode1;
        $params[':icd_code2'] = $icdcode2;
    }

    // ✅ **Query ข้อมูล**
    $data1 = Yii::$app->db14->createCommand($sql1, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// ✅ **ใช้ DataProvider แสดงผล**
$dataProvider = new ArrayDataProvider([
    'allModels' => $data1,
    'pagination' => [
        'pageSize' => 10,
    ],
]);

    



#$######################################### รายงานตามVisit ##############################################################################
##########################################################################

$sql2 = "
SELECT 
    o.visit_id, o.hn,
    o.REG_DATETIME AS regdate,
    IFNULL(r.rf_dt, '') AS referdate,
IFNULL(i.adm_dt, '') AS admitdate,
   IFNULL(r.hosp_id, '') AS refer,
IFNULL(i.adm_id, '') AS an,
    o.unit_reg,
    u.unit_name,
	m.inscl_name,
	ak.claimcode,
    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
    CASE
        WHEN p.SEX = 1 THEN 'ชาย'
        WHEN p.SEX = 2 THEN 'หญิง'
    END AS `เพศ`,
    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) AS `age`,
    GROUP_CONCAT(DISTINCT ic.ICD10_TM ORDER BY ic.ICD10_TM ASC SEPARATOR ', ') AS diag
FROM opd_visits o
INNER JOIN cid_hn b ON o.HN = b.HN
INNER JOIN population p ON b.CID = p.CID
LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 
LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
LEFT JOIN service_units u ON u.unit_id = o.unit_reg
LEFT JOIN main_inscls m ON o.inscl = m.inscl
LEFT JOIN authen_kiosk ak ON ak.visit_id = o.visit_id AND ak.cid = p.cid
WHERE o.IS_CANCEL = 0
  AND o.VISIT_ID NOT in (SELECT ipd_reg.VISIT_ID FROM ipd_reg WHERE ipd_reg.IS_CANCEL = 0)
  AND o.reg_datetime BETWEEN :start_date AND :end_date
";

// ตรวจสอบแผนก (unit_reg)
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL'); // ค่าเริ่มต้น 'ALL'
if ($unit_reg !== 'ALL') {
    $sql2 .= " AND o.unit_reg = :unit_reg";
}
if ($diag === 'SPECIFIC') {
    $sql2 .= " AND od.DXT_ID = '1' ";   // โรคหลัก
} elseif ($diag === 'ALL') {
    // ทั้งหมด = ไม่กรอง
}

// ✅ **เพิ่มเงื่อนไขสำหรับช่วงอายุ**
if (!empty($age_start) && !empty($age_end)) {
     $sql2 .= " AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN :age_start AND :age_end";
}

if ($mobile === 'SPECIFIC') {
    // ในหน่วยบริการ
    $sql2 .= " AND o.VISIT_ID NOT IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'MOBILE') {
    // นอกหน่วยบริการ
    $sql2 .= " AND o.VISIT_ID IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'ALL') {
    // ทั้งหมด = ไม่ต้องเพิ่มเงื่อนไขอะไร
}

// ตรวจสอบช่วงอายุ (age_start และ age_end)
if (!empty($age_start) && !empty($age_end)) {
    $sql2 .= " AND TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) BETWEEN :age_start AND :age_end";
}
// ตรวจสอบสิทธิ์ (inscl)
$inscl = Yii::$app->request->get('inscl', 'ALL'); // ค่าเริ่มต้น 'ALL'
if ($inscl !== 'ALL') {
    $sql2 .= " AND o.inscl = :inscl";  // เพิ่มเงื่อนไขกรองสิทธิ์
}

// ตรวจสอบ ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
   $sql2 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY
$sql2 .= " GROUP BY o.VISIT_ID";

try {
    // กำหนดพารามิเตอร์
    $params = [
        ':start_date' => $startDate,
        ':end_date' => $endDate,
    ];

    // กำหนดค่าพารามิเตอร์สำหรับ unit_reg ถ้าไม่ใช่ 'ALL'
    if ($unit_reg !== 'ALL') {
        $params[':unit_reg'] = $unit_reg;
    }

    // กำหนดค่าพารามิเตอร์สำหรับ inscl ถ้าไม่ใช่ 'ALL'
    if ($inscl !== 'ALL') {
        $params[':inscl'] = $inscl;
    }

    // กำหนดค่าพารามิเตอร์สำหรับ ICD Code ถ้ามีการกรอก
    if (!empty($icdcode1) && !empty($icdcode2)) {
        $params[':icd_code1'] = $icdcode1;
        $params[':icd_code2'] = $icdcode2;
    }
	// ✅ **เพิ่มพารามิเตอร์ช่วงอายุ**
    if (!empty($age_start) && !empty($age_end)) {
        $params[':age_start'] = $age_start;
        $params[':age_end'] = $age_end;
    }
    // Debug ตรวจสอบพารามิเตอร์
    Yii::info($params, 'debug');

    // ดึงข้อมูล
    $data2 = Yii::$app->db14->createCommand($sql2, $params)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// GridView data provider
$visitProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $data2,
    'pagination' => [
        'pageSize' => 100,
    ],
]);



##################  10 อันดับโรค #################################################################################
// ดึงค่าจากฟอร์ม (GET parameters)
$request = Yii::$app->request;

$startDate = $request->get('start_date', date('Y-m-01'));
$endDate   = $request->get('end_date', date('Y-m-t'));
$unit_reg  = $request->get('unit_reg', 'ALL');
$icdcode1  = $request->get('icd_code1', null);
$icdcode2  = $request->get('icd_code2', null);
$diag      = $request->get('diag', 'ALL');
$age_start = $request->get('age_start', null);
$age_end   = $request->get('age_end', null);
$mobile    = $request->get('mobile', 'ALL');

$sql4 = "
    SELECT 
        ic.ICD10_TM AS `โรค`,
        ic.NICKNAME AS `รายละเอียดโรค`,
        COUNT(o.visit_id) AS `จำนวนครั้ง`
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
    INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = o.unit_reg
    WHERE o.reg_datetime BETWEEN :start_date AND :end_date
      AND o.VISIT_ID NOT in (
            SELECT ipd_reg.VISIT_ID 
            FROM ipd_reg 
            WHERE ipd_reg.IS_CANCEL = 0
      )
      AND (:unit_reg IS NULL OR o.unit_reg = :unit_reg)
      AND ic.ICD10_TM NOT LIKE 'Z%'
";

// ตรวจสอบเงื่อนไขการกรอง ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql4 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เงื่อนไขโรคหลัก/ทั้งหมด
if ($diag === 'SPECIFIC') {
    $sql4 .= " AND od.DXT_ID = '1' ";   // โรคหลัก
} elseif ($diag === 'ALL') {
    // ทั้งหมด = ไม่กรอง
}

// ✅ เพิ่มเงื่อนไขสำหรับช่วงอายุ
if (!empty($age_start) && !empty($age_end)) {
    $sql4 .= " AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN :age_start AND :age_end";
}

// เงื่อนไข Mobile
if ($mobile === 'SPECIFIC') {
    $sql4 .= " AND o.VISIT_ID NOT IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'MOBILE') {
    $sql4 .= " AND o.VISIT_ID IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'ALL') {
    // ทั้งหมด = ไม่ต้องเพิ่มเงื่อนไขอะไร
}

// เพิ่ม GROUP BY และ ORDER BY
$sql4 .= "
    GROUP BY ic.ICD10_TM, ic.NICKNAME
    ORDER BY COUNT(o.visit_id) DESC
    LIMIT 10
";

// ✅ กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate . ' 00:01',
    ':end_date'   => $endDate . ' 23:59',
    ':unit_reg'   => ($unit_reg !== 'ALL') ? $unit_reg : null,
];

// ถ้ามี icd_code1 และ icd_code2 ให้เพิ่มพารามิเตอร์
if (!empty($icdcode1) && !empty($icdcode2)) {
    $params[':icd_code1'] = $icdcode1;
    $params[':icd_code2'] = $icdcode2;
}

// ถ้ามีอายุ ให้เพิ่มพารามิเตอร์ด้วย
if (!empty($age_start) && !empty($age_end)) {
    $params[':age_start'] = $age_start;
    $params[':age_end']   = $age_end;
}

// Debug parameters (optional)
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
->bindValue(':unit_id', ($unit_reg !== 'ALL') ? $unit_reg : null)
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
        m.INSCL_NAME AS 'inscl_name', 
        COUNT(DISTINCT a.VISIT_ID) AS 'Visit',
        COUNT(DISTINCT a.HN) AS 'amount'
    FROM opd_visits a
    INNER JOIN cid_hn b ON a.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID 
    LEFT JOIN refers r ON a.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2 
    INNER JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 #AND DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10 
    LEFT JOIN ipd_reg i ON i.visit_id = a.visit_id AND i.IS_CANCEL = 0
    LEFT JOIN service_units u ON u.unit_id = a.unit_reg
    LEFT JOIN main_inscls m ON a.inscl = m.inscl   
    LEFT JOIN mobile_visits mv ON a.VISIT_ID = mv.VISIT_ID AND mv.IS_CANCEL = 0
    WHERE a.IS_CANCEL = 0
	  AND a.VISIT_ID NOT in (SELECT ipd_reg.VISIT_ID FROM ipd_reg WHERE ipd_reg.IS_CANCEL = 0)
      AND a.reg_datetime BETWEEN :start_date AND :end_date
      AND (:unit_reg IS NULL OR a.unit_reg = :unit_reg)
";

// เงื่อนไขกรองสิทธิ์การรักษา
if ($inscl !== 'ALL') {
    $sql5 .= " AND a.INSCL = :inscl";
}

if ($diag === 'SPECIFIC') {
    $sql5 .= " AND od.DXT_ID = '1' ";   // โรคหลัก
} elseif ($diag === 'ALL') {
    // ทั้งหมด = ไม่กรอง
}

 
// ตรวจสอบช่วงอายุ (age_start และ age_end)
if (!empty($age_start) && !empty($age_end)) {
    $sql5 .= " AND TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) BETWEEN :age_start AND :age_end";
}


if ($mobile === 'SPECIFIC') {
    // ในหน่วยบริการ
    $sql5 .= " AND a.VISIT_ID NOT IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'MOBILE') {
    // นอกหน่วยบริการ
    $sql5 .= " AND a.VISIT_ID IN (
        SELECT mobile_visits.VISIT_ID 
        FROM mobile_visits 
        WHERE mobile_visits.IS_CANCEL = 0
    )";
} elseif ($mobile === 'ALL') {
    // ทั้งหมด = ไม่ต้องเพิ่มเงื่อนไขอะไร
}
// ตรวจสอบการกรอกข้อมูล ICD Code
if (!empty($icdcode1) && !empty($icdcode2)) {
    $sql5 .= " AND ic.ICD10_TM BETWEEN :icd_code1 AND :icd_code2";
}

// เพิ่ม GROUP BY และ ORDER BY
$sql5 .= "
    GROUP BY a.inscl
    ORDER BY amount DESC
    LIMIT 10
";

// ดึงค่าจากฟอร์ม
$unit_reg = Yii::$app->request->get('unit_reg', 'ALL'); // ค่าเริ่มต้นคือ 'ALL'
$inscl = Yii::$app->request->get('inscl', 'ALL');  // ค่าเริ่มต้นคือ 'ALL'

// กำหนดพารามิเตอร์ SQL
$params = [
    ':start_date' => $startDate,
    ':end_date' => $endDate,
    ':unit_reg' => ($unit_reg !== 'ALL') ? $unit_reg : null, // ใช้ $unit_reg ให้ถูกต้อง
];

// ถ้ามีการเลือก inscl ให้เพิ่มพารามิเตอร์ inscl
if ($inscl !== 'ALL') {
    $params[':inscl'] = $inscl; // ถ้า inscl ไม่เป็น 'ALL' จะเพิ่มพารามิเตอร์นี้
}
// ✅ **เพิ่มพารามิเตอร์ช่วงอายุ**
    if (!empty($age_start) && !empty($age_end)) {
        $params[':age_start'] = $age_start;
        $params[':age_end'] = $age_end;
    }
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
			FROM log_datacenter  v  where v.dep = 'opd'
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
		    'age_start' => $age_start, 
            'age_end' => $age_end, 
			'sql2' => $sql2,
			'amount' => $amount,
        ]);
    }
	
}
