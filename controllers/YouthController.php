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

class YouthController extends \yii\web\Controller
{
	
	public function actionYouthService()
    {
        $fiscalYear = Yii::$app->request->get('year', 2569);
        
        // ดึงข้อมูลทั้ง 2 ส่วน
        $data = $this->getYouthServiceReport($fiscalYear);
        $drugData = $this->getYouthDrugServiceData($fiscalYear); // เรียกใช้ฟังก์ชันยาเสพติด
        
        // จัดรูปแบบข้อมูลสำหรับแสดงผล
        $formattedData = $this->formatReportData($data);
        
        return $this->render('youth-service', [
            'data' => $formattedData,
            'drugData' => $drugData, // ส่งข้อมูลยาเสพติดไปด้วย
            'fiscalYear' => $fiscalYear
        ]);
    }
    
    private function formatReportData($rawData)
    {
        $result = [];
        
        foreach ($rawData as $row) {
            $ageGroup = $row['age_group'];
            $gender = $row['gender'] == '1' ? 'male' : 'female';
            $month = $row['visit_month'];
            
            if (!isset($result[$ageGroup])) {
                $result[$ageGroup] = [
                    'male' => array_fill(1, 12, 0),
                    'female' => array_fill(1, 12, 0),
                ];
            }
            
            $result[$ageGroup][$gender][$month] = $row['visit_count'];
        }
        
        return $result;
    }

    ##########################################################################################################
    // Model หรือ Controller
    public function getYouthServiceReport($fiscalYear = 2569, $startDate = null, $endDate = null)
    {
        // คำนวณวันที่เริ่มต้น-สิ้นสุดปีงบประมาณ
        if (!$startDate || !$endDate) {
            $startDate = ($fiscalYear - 1) . '-10-01 00:00:00';
            $endDate = $fiscalYear . '-09-30 23:59:59';
        }
        
        $sql = "
        SELECT 
            -- กลุ่มอายุ
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 10 AND 14 THEN '10-14'
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 15 AND 19 THEN '15-19'
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 20 AND 24 THEN '20-24'
                ELSE 'อื่นๆ'
            END as age_group,
            
            -- เพศ
            p.SEX as gender,
            
            -- โรงพยาบาล/หน่วยงาน
            u.unit_name as department,
            
            -- นับจำนวนผู้ใช้บริการ (Unique visit)
            COUNT(DISTINCT o.visit_id) as visit_count,
            
            -- นับจำนวนคน (Unique HN)
            COUNT(DISTINCT o.HN) as person_count,
            
            -- เดือน (สำหรับแยกรายเดือน)
            MONTH(o.REG_DATETIME) as visit_month,
            YEAR(o.REG_DATETIME) as visit_year
            
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
        INNER JOIN population p ON p.CID = c.CID
        LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
        LEFT JOIN icd10new i ON i.icd10 = dx.icd10
        INNER JOIN prescriptions pr ON pr.visit_id = o.visit_id AND pr.IS_CANCEL = 0
        INNER JOIN drugs d ON pr.drug_id = d.drug_id
        LEFT JOIN ipd_reg l ON o.visit_id = l.visit_id AND l.is_cancel = 0
        LEFT JOIN service_units u ON u.unit_id = o.unit_reg
        INNER JOIN towns t ON t.TOWN_ID = p.TOWN_ID 
        
        WHERE o.REG_DATETIME BETWEEN '2025-10-01 00:00' AND '2026-09-30 23:59'
        AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 10 AND 24
        
        GROUP BY age_group, p.SEX, YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME), o.unit_reg
        
        ORDER BY age_group, visit_year, visit_month, p.SEX
        ";
        
        return Yii::$app->db70->createCommand($sql)
            ->bindValue(':startDate', $startDate)
            ->bindValue(':endDate', $endDate)
            ->queryAll();
    }
    
    ##############################################################################################################
    public function getServiceDetailReport($fiscalYear = 2569)
{
    $startDate = ($fiscalYear - 1) . '-10-01';
    $endDate = $fiscalYear . '-09-30';
    
    $sql = "
    SELECT 
        service_name,
        age_group,
        gender,
        
        -- แยกตามเดือน ต.ค. - ก.ย.
        SUM(CASE WHEN MONTH(reg_date) = 10 THEN 1 ELSE 0 END) as oct,
        SUM(CASE WHEN MONTH(reg_date) = 11 THEN 1 ELSE 0 END) as nov,
        SUM(CASE WHEN MONTH(reg_date) = 12 THEN 1 ELSE 0 END) as dec,
        SUM(CASE WHEN MONTH(reg_date) = 1 THEN 1 ELSE 0 END) as jan,
        SUM(CASE WHEN MONTH(reg_date) = 2 THEN 1 ELSE 0 END) as feb,
        SUM(CASE WHEN MONTH(reg_date) = 3 THEN 1 ELSE 0 END) as mar,
        SUM(CASE WHEN MONTH(reg_date) = 4 THEN 1 ELSE 0 END) as apr,
        SUM(CASE WHEN MONTH(reg_date) = 5 THEN 1 ELSE 0 END) as may,
        SUM(CASE WHEN MONTH(reg_date) = 6 THEN 1 ELSE 0 END) as jun,
        SUM(CASE WHEN MONTH(reg_date) = 7 THEN 1 ELSE 0 END) as jul,
        SUM(CASE WHEN MONTH(reg_date) = 8 THEN 1 ELSE 0 END) as aug,
        SUM(CASE WHEN MONTH(reg_date) = 9 THEN 1 ELSE 0 END) as sep,
        
        COUNT(*) as total
        
    FROM (
        SELECT DISTINCT
            o.visit_id,
            o.REG_DATETIME as reg_date,
            o.HN as hn,
            
            -- กำหนดชื่อบริการตามเงื่อนไข
            '51.งานบูรณาการทั้งหมด (หญิง)' as service_name,
            
            -- กลุ่มอายุ
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 10 AND 14 THEN '10-14'
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 15 AND 19 THEN '15-19'
                WHEN TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 20 AND 24 THEN '20-24'
            END as age_group,
            
            p.SEX as gender
            
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
        INNER JOIN population p ON p.CID = c.CID
        LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
        INNER JOIN prescriptions pr ON pr.visit_id = o.visit_id AND pr.IS_CANCEL = 0
        INNER JOIN drugs d ON pr.drug_id = d.drug_id
        INNER JOIN towns t ON t.TOWN_ID = p.TOWN_ID 
        
        WHERE o.REG_DATETIME BETWEEN '2025-10-01 00:00' AND '2026-09-30 23:59'
        AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN 10 AND 24
        AND p.SEX = '2'  -- หญิง
        
        -- เพิ่มเงื่อนไขตามรายการบริการ เช่น
        -- AND d.drug_id IN (SELECT drug_id FROM drugs WHERE drug_name LIKE '%...%')
        
    ) as sub
    
    GROUP BY service_name, age_group, gender
    ORDER BY service_name, age_group
    ";
    
    return Yii::$app->db70->createCommand($sql)
        ->bindValue(':startDate', $startDate)
        ->bindValue(':endDate', $endDate)
        ->queryAll();
}

    ############################################################################################################################
    // เปลี่ยนจาก function เป็น public function
   public function getYouthDrugServiceData($fiscalYear = 2569, $startDate = null, $endDate = null)
{
    // คำนวณวันที่ถ้าไม่ได้ส่งมา
     $startDate = '2025-10-01 00:00:00';
     $endDate   = '2026-09-30 23:59:59';
    
    // Query สำหรับแต่ละรายการบริการ
    $services = [
        [
            'id' => 38,
            'name' => 'วัยรุ่นใช้สารเสพติด',
            'age_min' => 10,
            'age_max' => 14,
            'age_group' => '10-14 ปี',
            'icd_condition' => "i.ICD10_TM BETWEEN 'F192' AND 'F199'",
            'icd_code' => 'F10-F19 (ทุกรหัส)',
            'description' => 'ใช้วิธีตามประเภทสารที่ใช้'
        ],
        [
            'id' => 39,
            'name' => 'วัยรุ่นใช้สารเสพติด',
            'age_min' => 15,
            'age_max' => 19,
            'age_group' => '15-19 ปี',
            'icd_condition' => "i.ICD10_TM BETWEEN 'F192' AND 'F199'",
            'icd_code' => 'F10-F19 (ทุกรหัส)',
            'description' => 'ใช้วิธีตามประเภทสารที่ใช้'
        ],
        [
            'id' => 40,
            'name' => 'เยาวชนใช้สารเสพติด',
            'age_min' => 20,
            'age_max' => 24,
            'age_group' => '20-24 ปี',
            'icd_condition' => "i.ICD10_TM BETWEEN 'F192' AND 'F199'",
            'icd_code' => 'F10-F19 (ทุกรหัส)',
            'description' => 'ใช้วิธีตามประเภทสารที่ใช้'
        ],
        [
            'id' => 41,
            'name' => 'บริการให้คำปรึกษาเรื่องยาเสพติด',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM = 'Z715'",
            'icd_code' => 'Z71.5',
            'description' => 'Counselling about drug/substance use (ให้คำปรึกษาเรื่องยาเสพติด)'
        ],
        [
            'id' => 42,
            'name' => 'บริการนำปัตรกิจา',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM IN ('F102', 'F112', 'F122', 'F132', 'F142', 'F152', 'F162', 'F182', 'F192')",
            'icd_code' => 'F10.2, F11.2, F12.2, F13.2, F14.2, F15.2, F16.2, F18.2, F19.2',
            'description' => 'วิธีโรคติด (Dependence syndrome) ตามประเภทสาร'
        ],
        [
            'id' => 43,
            'name' => 'บริการติดตามฟื้นฟู',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM = 'Z503'",
            'icd_code' => 'Z50.3',
            'description' => 'Rehabilitation for alcohol/drug addiction (ฟื้นฟูผู้ติดสารเสพติด)'
        ],
        [
            'id' => 44,
            'name' => 'ผู้ใช้กัญชา',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM LIKE 'F12%'",
            'icd_code' => 'F12.x',
            'description' => 'F12.2=ติดกัญชา, F12.3=ถอนพิษ, F12.5=โรคจิต, อื่นๆ'
        ],
        [
            'id' => 45,
            'name' => 'ผู้ใช้ยาบ้า (แอมเฟตามีน)',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM LIKE 'F15%'",
            'icd_code' => 'F15.x',
            'description' => 'F15.2=ติดยาบ้า, F15.3=ถอนพิษ, F15.5=โรคจิต, อื่นๆ'
        ],
        [
            'id' => 46,
            'name' => 'ผู้ใช้กระเทย',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "i.ICD10_TM LIKE 'F18%'",
            'icd_code' => 'F18.x',
            'description' => 'F18.2=ติดกระเทย, F18.3=ถอนพิษ, F18.5=โรคจิต, อื่นๆ'
        ],
        [
            'id' => 47,
            'name' => 'ผู้ใช้สารเสพติดอื่น ๆ',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "(i.ICD10_TM LIKE 'F10%' OR i.ICD10_TM LIKE 'F11%' OR i.ICD10_TM LIKE 'F14%' OR i.ICD10_TM LIKE 'F16%' OR i.ICD10_TM LIKE 'F19%')",
            'icd_code' => 'F10.x (แอลกอฮอล์), F11.x (ฝิ่น), F14.x (โคเคน), F16.x (ยาหลอน), F19.x (หลายชนิด)',
            'description' => 'ใช้วิธีตามประเภทสารที่ระบุ'
        ],
        [
            'id' => 48,
            'name' => 'ส่งต่อผู้บำบัดยาเสพติด',
            'age_min' => 10,
            'age_max' => 24,
            'age_group' => '10-24 ปี',
            'icd_condition' => "((i.ICD10_TM BETWEEN 'F102' AND 'F192') OR i.ICD10_TM = 'Z503')",
            'icd_code' => 'F10.2-F19.2 + Z50.3',
            'description' => 'ใช้วิธีโรคติดทุกชนิด + วิธีฟื้นฟู Z50.3'
        ],
			[
		'id' => 49,
		'name' => 'ส่งต่อทางจังหวัด',
		'age_min' => 10,
		'age_max' => 24,
		'age_group' => '10-24 ปี',
		'icd_condition' => "(i.ICD10_TM LIKE 'Z59%' OR i.ICD10_TM LIKE 'Z60%' OR i.ICD10_TM LIKE 'Z63%')",
		'icd_code' => 'Z59.x, Z60.x, Z63.x',
		'description' => 'วิธีปัญหาสังคม: Z59=ที่อยู่อาศัย, Z60=สิ่งแวดล้อม, Z63=ครอบครัว',
		'need_refer' => true
	],

    ];
    
    $result = [];
    
    foreach ($services as $service) {
		$referJoin = '';

if (!empty($service['need_refer'])) {
    $referJoin = "
        INNER JOIN refers r 
            ON r.visit_id = o.visit_id
           AND r.is_cancel = 0
           AND r.rf_type = 2
    ";
}

        $sql = "
        SELECT 
            '{$service['name']}' as service_name,
            '{$service['age_group']}' as age_group,
            '{$service['icd_code']}' as icd_code,
            '{$service['description']}' as description,
            
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 10 THEN 1 ELSE 0 END) as oct,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 11 THEN 1 ELSE 0 END) as nov,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 12 THEN 1 ELSE 0 END) as `dec`,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 1 THEN 1 ELSE 0 END) as jan,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 2 THEN 1 ELSE 0 END) as feb,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 3 THEN 1 ELSE 0 END) as mar,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 4 THEN 1 ELSE 0 END) as apr,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 5 THEN 1 ELSE 0 END) as may,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 6 THEN 1 ELSE 0 END) as jun,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 7 THEN 1 ELSE 0 END) as jul,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 8 THEN 1 ELSE 0 END) as aug,
            SUM(CASE WHEN MONTH(o.REG_DATETIME) = 9 THEN 1 ELSE 0 END) as sep,
            COUNT(*) as total
            
        FROM (
            SELECT DISTINCT
                o.visit_id,
                o.REG_DATETIME
            FROM opd_visits o 
		INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
		INNER JOIN population p ON p.CID = c.CID
		LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
		LEFT JOIN icd10new i ON i.icd10 = dx.icd10
		{$referJoin}
		LEFT JOIN prescriptions pr ON pr.visit_id = o.visit_id AND pr.IS_CANCEL = 0
		LEFT JOIN drugs d ON pr.drug_id = d.drug_id

            WHERE o.REG_DATETIME BETWEEN :startDate AND :endDate
            AND TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) BETWEEN :minAge AND :maxAge
            AND {$service['icd_condition']}
            GROUP BY o.visit_id
        ) as o
        ";
        
        $data = Yii::$app->db70->createCommand($sql)
            ->bindValue(':startDate', $startDate)
            ->bindValue(':endDate', $endDate)
            ->bindValue(':minAge', $service['age_min'])
            ->bindValue(':maxAge', $service['age_max'])
            ->queryOne();
        
        if ($data && $data['total'] > 0) {
            $result[] = $data;
        } else {
            // เพิ่มแถวว่างถ้าไม่มีข้อมูล
            $result[] = [
                'service_name' => $service['name'],
                'age_group' => $service['age_group'],
                'icd_code' => $service['icd_code'],
                'description' => $service['description'],
                'oct' => 0, 'nov' => 0, 'dec' => 0, 'jan' => 0,
                'feb' => 0, 'mar' => 0, 'apr' => 0, 'may' => 0,
                'jun' => 0, 'jul' => 0, 'aug' => 0, 'sep' => 0,
                'total' => 0
            ];
        }
    }
    
    return $result;
}

}