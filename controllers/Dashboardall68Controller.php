<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Logdashboardall;

class Dashboardall68Controller extends Controller
{
    public function actionIndex()
    {
	   #############################LOG DRUGSZONE#############################
        $connection = Yii::$app->db_log;
				$log = new Logdashboardall();
		$log->username = Yii::$app->user->identity->username ?? 'guest';
		$log->datetime = date('Y-m-d H:i:s');
		$log->ip = Yii::$app->request->getUserIP();
		$log->patient_cid = Yii::$app->request->post('cid', null);
		$log->save(); // ✅ บันทึกลง db_log
	############################ ###########################################################################

			 $sqlTelemed = "
        SELECT 
		  fiscal_year,
		  SUM(total_visits) AS total_visits,
		  SUM(opd) AS total_opd,
		  SUM(pcu) AS total_pcu,
		  SUM(tb) AS total_tb
		FROM dashboard_telemed
		GROUP BY fiscal_year
		ORDER BY fiscal_year;

		";
    $telemed = Yii::$app->db2->createCommand($sqlTelemed)->queryAll(); 

  #######################################################################################################
    // ดึงข้อมูล OPD ย้อนหลัง 5 ปี
    $sqlOpd5 = "
        SELECT fiscal_year, SUM(visit) AS total_visit, SUM(person) AS total_person
        FROM dashboard_opd_summary
        WHERE fiscal_year BETWEEN 2564 AND 2568
        GROUP BY fiscal_year
        ORDER BY fiscal_year
    ";
    $opdData5 = Yii::$app->db2->createCommand($sqlOpd5)->queryAll();

    // ดึงข้อมูล IPD ย้อนหลัง 5 ปี
    $sqlIpd5 = "
        SELECT fiscal_year, SUM(visit) AS total_visit, SUM(person) AS total_person
        FROM dashboard_ipd_summary
        WHERE fiscal_year BETWEEN 2564 AND 2568
        GROUP BY fiscal_year
        ORDER BY fiscal_year
    ";
    $ipdData5 = Yii::$app->db2->createCommand($sqlIpd5)->queryAll();
	
   // ดึงข้อมูล dent ย้อนหลัง 5 ปี
    $sqlDent = "
        SELECT fiscal_year, SUM(visit) AS total_visit, SUM(person) AS total_person
        FROM dashboard_dent_summary
        WHERE fiscal_year BETWEEN 2564 AND 2569
        GROUP BY fiscal_year
        ORDER BY fiscal_year
    ";
    $opdDent5 = Yii::$app->db2->createCommand($sqlDent)->queryAll();
    

    #########################################################################################	
        $opdSql = "
            SELECT 
    period_year,
    period_month,
    fiscal_year,
    month_name,
    visit,
    person,
    refers
FROM dashboard_opd_summary
WHERE fiscal_year = 2568  -- 🔁 เปลี่ยนปีงบที่ต้องการ
ORDER BY period_year, period_month;

        ";
#############################################################################################
// ดึงข้อมูล Readmit revisit ย้อนหลัง 3 ปี
    $sqlReadmit = "
       SELECT fiscal_year, sum(readmit) as readmit ,sum(revisit) as revisit, sum(unplanned_refer) as unplan
        FROM report_readmit_summary
        WHERE fiscal_year BETWEEN 2566 AND 2568
        GROUP BY fiscal_year
        ORDER BY fiscal_year
    ";
    $revisitReadmit = Yii::$app->db2->createCommand($sqlReadmit)->queryAll();
################################################################################
$ipdSql = "
        SELECT 
            period_year,
            period_month,
            fiscal_year,
            month_name,
            visit,
            person,
            refers,
            admit,
            ward1,
            ward2,
            lr,
            homeward,
            ward4,
            ward5
        FROM dashboard_ipd_summary
        WHERE fiscal_year = 2568
        ORDER BY period_year, period_month;
    ";

    $ipdData = Yii::$app->db2->createCommand($ipdSql)->queryAll();

    $ipdDataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $ipdData,
        'pagination' => false,
    ]);
	
		##################  IPD 10 อันดับโรค  #################################################################################
    $sql4 = "
    SELECT * 
	FROM dashboard_top10_disease
WHERE type = 'IPD' AND fiscal_year = '2568'
ORDER BY total_visit DESC LIMIT 10;

";

##################  OPD 10 อันดับโรค  #################################################################################
    $sql5 = "
    SELECT 
    icd10_tm,
    nickname,
    total_visit,
    type,
    fiscal_year,
    created_at
FROM dashboard_top10_disease 
WHERE type = 'OPD' AND fiscal_year = '2568'
ORDER BY total_visit DESC
LIMIT 10;

";
##################  OPD-Refer 10 อันดับโรค  #################################################################################
    $sql6 = "SELECT 
        icd10_code AS `โรค`,
        disease_name_th AS `รายละเอียดโรค`,
        total_cases AS `จำนวนครั้ง`
    FROM dashboard_top10_refer_disease
    WHERE refer_type = 'OPD'
      AND fiscal_year = 2568
    ORDER BY total_cases DESC
    LIMIT 10";

			
	##################  IPD-Refer 10 อันดับโรค  #################################################################################
    
    $sql7 = "SELECT 
        icd10_code AS `โรค`,
        disease_name_th AS `รายละเอียดโรค`,
        total_cases AS `จำนวนครั้ง`
    FROM dashboard_top10_refer_disease
    WHERE refer_type = 'IPD'
      AND fiscal_year = 2568
    ORDER BY total_cases DESC
    LIMIT 10";
	
	/*
	
					$opdRawData5 = Yii::$app->db2->createCommand($opdData5)->queryAll();
			$opdData5 = new \yii\data\ArrayDataProvider([
				'allModels' => $opdRawData5,
				'pagination' => false,
			]);
*/
			$opdRawData = Yii::$app->db2->createCommand($opdSql)->queryAll();
			$opdData = new \yii\data\ArrayDataProvider([
				'allModels' => $opdRawData,
				'pagination' => false,
			]);

			$ipdRawData = Yii::$app->db2->createCommand($ipdSql)->queryAll(); // ดึงข้อมูลดิบก่อน
			$ipdData = new \yii\data\ArrayDataProvider([
				'allModels' => $ipdRawData,
				'pagination' => false, // ปิดแบ่งหน้า (GridView จะโชว์ pageSummary เต็ม)
			]);

			$top10IpdData = Yii::$app->db2->createCommand($sql4)->queryAll();
			$top10OpdData = Yii::$app->db2->createCommand($sql5)->queryAll();
			$top10RefOpdData = Yii::$app->db2->createCommand($sql6)->queryAll();
			$top10RefIpdData = Yii::$app->db2->createCommand($sql7)->queryAll();
			
			$db2 = Yii::$app->db2;
			 $opdUpdatedAt = $db2->createCommand("
				SELECT MAX(created_at) FROM dashboard_opd_summary
			")->queryScalar();

			$ipdUpdatedAt = $db2->createCommand("
				SELECT MAX(created_at) FROM dashboard_ipd_summary
			")->queryScalar();
	############### นับการเข้าใช้งาน ###################################
		    $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_dashboardall v 
			";
        
         $data = \yii::$app->db_log->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amountx = $data[$i]['amount'];    
             }
		####### Readmit ##################################################################
		$request = Yii::$app->request;

		// Readmit
		$readmit_date1 = $request->post('readmit_date1', '');
		$readmit_date2 = $request->post('readmit_date2', '');

		// Revisit
		$revisit_date1 = $request->post('revisit_date1', '');
		$revisit_date2 = $request->post('revisit_date2', '');
		
		// Unplan Refer
		$unplan_date1 = $request->post('unplan_date1', '');
		$unplan_date2 = $request->post('unplan_date2', '');

		$sql = "
			SELECT ip1.visit_id as vn1, date(ip1.adm_dt) as adm1, time(ip1.adm_dt) as time1 , c.hn , ip1.adm_id as an1,i1.icd10_tm as icd1, count(c.hn),
			 ip2.visit_id as vn2,ip2.adm_id as an2,date(ip2.adm_dt) as adm2, time(ip2.adm_dt) as time2,i2.icd10_tm as icd2,
			#((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24)) )
			#(date(o2.REG_DATETIME)- (date(o1.REG_DATETIME))) 
			timestampdiff(day,ip1.adm_dt,ip2.adm_dt)
			as revist_time
			FROM  opd_visits o1
			INNER JOIN cid_hn c ON o1.hn = c.hn
			INNER JOIN ipd_reg ip1 ON o1.visit_id = ip1.visit_id and ip1.is_cancel = 0
			LEFT OUTER JOIN opd_diagnosis dx1 ON ip1.visit_id = dx1.visit_id AND dx1.dxt_id = 1 AND dx1.is_cancel = 0 
			LEFT OUTER JOIN icd10new i1 ON dx1.icd10 = i1.icd10
			INNER JOIN opd_visits o2 ON o2.hn = c.hn AND o2.is_cancel = 0  
			LEFT OUTER JOIN ipd_reg ip2 ON o2.visit_id = ip2.visit_id AND ip2.is_cancel = 0 
			LEFT OUTER JOIN opd_diagnosis dx2 ON ip2.visit_id = dx2.visit_id AND dx2.dxt_id = 1 AND dx2.is_cancel = 0 AND dx2.icd10 is not null
			LEFT OUTER JOIN icd10new i2 ON dx2.icd10 = i2.icd10 AND i1.icd10_tm = i2.icd10_tm
			WHERE ip1.adm_dt BETWEEN '$readmit_date1' AND '$readmit_date2'
			#AND ((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24))) <=28
			#AND (date(o2.REG_DATETIME)- (date(o1.REG_DATETIME))) <=28
			AND timestampdiff(day,ip1.adm_dt,ip2.adm_dt) <=28
			AND ip2.visit_id > ip1.visit_id  
			AND i1.icd10_tm = i2.icd10_tm
			AND ip1.is_cancel = 0
			GROUP BY c.hn
			HAVING count(c.hn)>1     
         ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $reamitProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
	 ######### ReVisit ###############################################################
	   	
        $sql = "
			SELECT o1.visit_id as vn1,date(o1.REG_DATETIME) as d1,time(o1.REG_DATETIME) as time_1,i1.icd10_tm as icdname_1,
			 concat(trim(p.fname), '  ' ,p.lname) as ptname ,
			 c.hn,count(c.hn),
			o2.visit_id as vn_2,date(o2.reg_datetime) as d2 ,time(o2.REG_DATETIME) as time_2, i2.icd10_tm as icdname_2, #d2.name as doctor_name2 ,
			(((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24)) + (( time_to_sec(o2.REG_DATETIME))/3600)) - (( time_to_sec(o1.REG_DATETIME))/3600))
			as revist_time
			from opd_visits o1
			LEFT OUTER JOIN  cid_hn c on o1.hn = c.hn AND o1.is_cancel = 0
			LEFT OUTER JOIN population p on p.cid = c.cid
			LEFT OUTER JOIN opd_visits o2 on o2.hn = c.hn AND o2.is_cancel = 0 
			#left outer join vn_stat v2 on v2.vn=o.vn
			LEFT OUTER JOIN opd_diagnosis dx1 on o1.visit_id = dx1.visit_id AND dx1.DXT_ID = 1 AND dx1.is_cancel = 0
			LEFT OUTER JOIN opd_diagnosis dx2 on o2.visit_id = dx2.visit_id AND dx2.DXT_ID = 1 AND dx2.is_cancel = 0
			LEFT OUTER JOIN icd10new i1 on i1.icd10 = dx1.icd10
			LEFT OUTER JOIN icd10new i2 on i2.icd10 = dx2.icd10
			#left outer join icd101 i2 on i2.code=v2.pdx
			#left outer join doctor d1 on d1.code=v.dx_doctor
			#left outer join doctor d2 on d2.code=v2.dx_doctor
			#left outer join patient p on p.hn=o.hn  
			WHERE o1.REG_DATETIME between'$revisit_date1' AND '$revisit_date2'
			AND o2.visit_id > o1.visit_id
			AND i1.icd10_tm = i2.icd10_tm  AND left(i1.icd10_tm,1) not in ('Z','U')
			AND (((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24)) + (( time_to_sec(o2.REG_DATETIME))/3600)) - (( time_to_sec(o1.REG_DATETIME))/3600)) between 0.001 and 48 
			group by c.hn
			having count(c.hn)>1
              ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $revisitProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
	  #### Unplan Refer ##############################################################
	  
	   $sql = "
			SELECT i.VISIT_ID ,op.HN,i.ADM_ID as AN ,op.REG_DATETIME as REGDATE,i.ADM_DT, r.RF_DT ,
			 ((to_days(r.RF_DT)*24)- (to_days(i.ADM_DT)*24))/24 AS DAYS, abs((time_to_sec(r.RF_DT)/3600) - (time_to_sec(i.ADM_DT)/3600)) as Times 
			,i.P_DIAG  as Dxก่อนRefer, ic.ICD10_TM  as PostRefer
			FROM ipd_reg i 
			LEFT  JOIN opd_visits op ON op.visit_id = i.visit_id AND op.is_cancel = 0 
			LEFT  JOIN refers r on i.VISIT_ID = r.VISIT_ID AND i.IS_CANCEL = 0 AND r.IS_CANCEL = 0 AND r.rf_type = 2 
			INNER JOIN opd_diagnosis o ON i.VISIT_ID = o.VISIT_ID AND o.IS_CANCEL = 0 AND o.DXT_ID = 1 
			INNER JOIN icd10new ic ON o.ICD10 = ic.ICD10 
			#INNER JOIN opd_diagnosis o1 ON r.VISIT_ID = o1.VISIT_ID AND o1.IS_CANCEL = 0 AND o1.DXT_ID = 1
			#INNER JOIN icd10new ic1 ON o1.ICD10 = ic1.ICD10 
			WHERE r.RF_DT BETWEEN '$unplan_date1' AND '$unplan_date2' 
			AND ((to_days(r.RF_DT)*24)- (to_days(i.ADM_DT)*24))/24 = '0' AND abs((time_to_sec(r.RF_DT)/3600) - (time_to_sec(i.ADM_DT)/3600)) <= '1.0'
              ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $unplanProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
     ############### X60-X84 ฆ่าตัวตาย ##################################################################	 
	   $rows = Yii::$app->db2->createCommand("
            SELECT
				CASE
					WHEN MONTH(regdate) >= 10
					THEN YEAR(regdate) + 1
					ELSE YEAR(regdate)
				END AS fiscal_year,
				
				COUNT(*) AS total_visits,             -- จำนวนครั้ง (visit_id)
				COUNT(DISTINCT hn) AS total_persons   -- จำนวนคน (hn)
				
			FROM x60_cases
			WHERE diag BETWEEN 'X60' AND 'X84'
			GROUP BY fiscal_year
			ORDER BY fiscal_year;

        ")->queryAll();

        // แยก labels / values สำหรับ Chart.js
		    $years = [];
			$totalVisits = [];
			$totalPersons = [];

			foreach ($rows as $row) {
				$years[] = $row['fiscal_year'];
				$totalVisits[] = (int)$row['total_visits'];
				$totalPersons[] = (int)$row['total_persons'];
			}
		######### กราฟ x60-x84   รายเดือน ##################################################################
		// ✅ ดึงข้อมูลรวมรายเดือนในปีงบประมาณ 2568
        $data = Yii::$app->db2->createCommand("
            SELECT 
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-10' THEN 1 ELSE 0 END) AS `2024-10`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-11' THEN 1 ELSE 0 END) AS `2024-11`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2024-12' THEN 1 ELSE 0 END) AS `2024-12`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-01' THEN 1 ELSE 0 END) AS `2025-01`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-02' THEN 1 ELSE 0 END) AS `2025-02`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-03' THEN 1 ELSE 0 END) AS `2025-03`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-04' THEN 1 ELSE 0 END) AS `2025-04`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-05' THEN 1 ELSE 0 END) AS `2025-05`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-06' THEN 1 ELSE 0 END) AS `2025-06`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-07' THEN 1 ELSE 0 END) AS `2025-07`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-08' THEN 1 ELSE 0 END) AS `2025-08`,
                SUM(CASE WHEN DATE_FORMAT(regdate, '%Y-%m') = '2025-09' THEN 1 ELSE 0 END) AS `2025-09`
            FROM x60_cases
            WHERE diag BETWEEN 'X60' AND 'X84'
        ")->queryOne();

        // ✅ เตรียม labels และ values สำหรับ Chart.js
        $labels = ['ต.ค.', 'พ.ย.', 'ธ.ค.', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.'];

        $values = [
            (int)$data['2024-10'],
            (int)$data['2024-11'],
            (int)$data['2024-12'],
            (int)$data['2025-01'],
            (int)$data['2025-02'],
            (int)$data['2025-03'],
            (int)$data['2025-04'],
            (int)$data['2025-05'],
            (int)$data['2025-06'],
            (int)$data['2025-07'],
            (int)$data['2025-08'],
            (int)$data['2025-09'],
        ];
############## PHR ##############################################################################
$sqlphr = "
    SELECT 
    month,
    total_visits,
    phr_sent,
    percent_phr_sent,
    percent_of_total_phr
FROM dash_phr
ORDER BY month;
";

// รัน SQL และดักจับ error
try {
    $rawData = Yii::$app->db4->createCommand($sqlphr)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// สร้าง DataProvider
$phrProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pageSize' => 200,
    ],
]);
######################## Dental แสดงปีงบ 2568 ##################################################################
$sqldent = "
    SELECT 
    CONCAT(
        CASE period_month
            WHEN 1 THEN 'ม.ค.'
            WHEN 2 THEN 'ก.พ.'
            WHEN 3 THEN 'มี.ค.'
            WHEN 4 THEN 'เม.ย.'
            WHEN 5 THEN 'พ.ค.'
            WHEN 6 THEN 'มิ.ย.'
            WHEN 7 THEN 'ก.ค.'
            WHEN 8 THEN 'ส.ค.'
            WHEN 9 THEN 'ก.ย.'
            WHEN 10 THEN 'ต.ค.'
            WHEN 11 THEN 'พ.ย.'
            WHEN 12 THEN 'ธ.ค.'
        END,
        ' ',
        CASE 
            WHEN period_month >= 10 THEN fiscal_year - 1  -- ต.ค.–ธ.ค. ของปีงบ 2568 → 2567
            ELSE fiscal_year
        END
    ) AS month_year,
    SUM(visit) AS total_visit,
    SUM(person) AS total_person,
    SUM(refers) AS total_refers
FROM dashboard_dent_summary
WHERE fiscal_year = 2568   -- ปีงบ
GROUP BY period_year, period_month
ORDER BY period_year, period_month;

";

// รัน SQL และดักจับ error
try {
    $rawData = Yii::$app->db2->createCommand($sqldent)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
}

// สร้าง DataProvider
$dentProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pageSize' => 200,
    ],
]);

######################## Claim Telemed #######################################################################
$rows = Yii::$app->db4->createCommand("
        SELECT 
            'ต.ค.2567' AS month_name, t10 AS total_sent, r10 AS passed, hosp_claim_10 AS claim, ret_statement_10 AS compensate
        FROM (
            SELECT * FROM dashboard_claim_opd 
            WHERE users = 'telemed' 
            ORDER BY created_at DESC 
            LIMIT 1
        ) AS latest
        UNION ALL
        SELECT 'พ.ย.2567', t11, r11, hosp_claim_11, ret_statement_11 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'ธ.ค.2567', t12, r12, hosp_claim_12, ret_statement_12 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'ม.ค.2568', t1, r1, hosp_claim_1, ret_statement_1 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'ก.พ.2568', t2, r2, hosp_claim_2, ret_statement_2 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'มี.ค.2568', t3, r3, hosp_claim_3, ret_statement_3 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'เม.ย.2568', t4, r4, hosp_claim_4, ret_statement_4 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'พ.ค.2568', t5, r5, hosp_claim_5, ret_statement_5 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'มิ.ย.2568', t6, r6, hosp_claim_6, ret_statement_6 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'ก.ค.2568', t7, r7, hosp_claim_7, ret_statement_7 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
		
        UNION ALL
        SELECT 'ส.ค.2568', t8, r8, hosp_claim_8, ret_statement_8 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
        UNION ALL
        SELECT 'ก.ย.2568', t9, r9, hosp_claim_9, ret_statement_9 FROM (SELECT * FROM dashboard_claim_opd WHERE users = 'telemed' ORDER BY created_at DESC LIMIT 1) AS latest
		
    ")->queryAll();

        ################################################################################################################################
		 $data = Yii::$app->db4->createCommand("
			SELECT 
				RIGHT(serial_no, 2) AS year,
				department,
				COUNT(department) as amount
			FROM death_cert
			WHERE RIGHT(serial_no, 2) IN ('69','68','67','66','65')
			GROUP BY unit_id, RIGHT(serial_no,2)
			ORDER BY RIGHT(serial_no,2) DESC
		")->queryAll();

		$yearsx = []; // ใช้แทน $years
		$departments = [];
		$chartData = [];

		foreach ($data as $row) {
			$year = $row['year'];
			$dept = $row['department'];
			$amount = (int)$row['amount'];

			if (!in_array($year, $yearsx)) {
				$yearsx[] = $year;
			}

			if (!in_array($dept, $departments)) {
				$departments[] = $dept;
			}

			$chartData[$dept][$year] = $amount;
		}
		################################################################################################################################
				// ✅ ดึงเวลาล่าสุดจาก updated_at ใน dash_phr
			$phrUpdatedAt = Yii::$app->db14->createCommand("
				SELECT MAX(updated_at) 
				FROM dash_phr
			")->queryScalar();
			
			################################################################################################################################
				// ✅ ดึงเวลาล่าสุดจาก updated_at ใน dash_telemed
			$teleUpdatedAt = Yii::$app->db4->createCommand("
				SELECT MAX(updated_at) 
				FROM dashboard_claim_opd
			")->queryScalar();
		################################################################################################################################
		################################################################################################################################
				// ✅ ดึงเวลาล่าสุดจาก updated_at ใน dash_telemed
			$dentUpdatedAt = Yii::$app->db2->createCommand("
				SELECT MAX(created_at) 
				FROM dashboard_dent_summary
			")->queryScalar();
		################################################################################################################################
			return $this->render('index', [
				'opdData' => $opdData,
				'ipdData' => $ipdData, // ตอนนี้เป็น DataProvider แล้ว
				'top10IpdData' => $top10IpdData,
				'top10OpdData' => $top10OpdData,
				'top10RefOpdData' => $top10RefOpdData,
				'top10RefIpdData' => $top10RefIpdData,
				'opdData5' => $opdData5,
				'opdDent5' => $opdDent5,
                'ipdData5' => $ipdData5,
				'opdUpdatedAt' => $opdUpdatedAt,
				'ipdUpdatedAt' => $ipdUpdatedAt,
				'phrUpdatedAt' => $phrUpdatedAt,
				'teleUpdatedAt' => $teleUpdatedAt,
				'dentUpdatedAt' => $dentUpdatedAt,
				'amount' => $amount,
				'amountx' => $amountx,
				'reamitProvider' => $reamitProvider,
				'revisitProvider' => $revisitProvider, 
				'unplanProvider' => $unplanProvider,  
				'revisitReadmit' => $revisitReadmit, 
				'years' => $years,
               'totalVisits' => $totalVisits,
			   'totalPersons' => $totalPersons,
			   'labels' => $labels,
               'values' => $values,
			    'rows' => $rows,
				 'yearsx' => $yearsx,
				'departments' => $departments,
				'chartData' => $chartData,
				'rawData' => $data,
				'phrProvider' => $phrProvider,
				'dentProvider' =>$dentProvider,
				]);
			}
				
			
##################################################################################################################
	public function actionUpdate()
    {
        $fiscalYear = 2568;
		$currentMonth = (int)date('n'); // เดือน 1-12 เช่น 7

		$startDate = date('Y-m-01 00:00:00'); // วันที่ 1 ของเดือน
		$endDate = date('Y-m-t 23:59:59');     // วันที่สุดท้ายของเดือน
		
		$startDatex = '2024-10-01 00:01';
		$endDatex = '2025-09-30 23:59';


       // 1. ลบข้อมูลเฉพาะปี 2568 และเดือนปัจจุบัน
		Yii::$app->db2->createCommand("
			DELETE FROM dashboard_ipd_summary
			WHERE fiscal_year = :fiscal AND period_month = :month
		")->bindValues([
			':fiscal' => $fiscalYear,
			':month' => $currentMonth
		])->execute();

		// 2. อัปเดตข้อมูลใหม่เฉพาะเดือนปัจจุบัน
		Yii::$app->db2->createCommand("
			REPLACE INTO dashboard_ipd_summary (
				period_year,
				period_month,
				fiscal_year,
				month_name,
				visit,
				person,
				refers,
				admit,
				ward1,
				ward2,
				lr,
				homeward,
				ward4,
				ward5
			)
			SELECT 
				YEAR(i.dsc_dt) AS period_year,
				MONTH(i.dsc_dt) AS period_month,
				CASE 
					WHEN MONTH(i.dsc_dt) >= 10 THEN YEAR(i.dsc_dt) + 544
					ELSE YEAR(i.dsc_dt) + 543
				END AS fiscal_year,
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
				END AS month_name,
				COUNT(DISTINCT i.visit_id) AS visit,
				COUNT(DISTINCT o.HN) AS person,
				COUNT(r.hosp_id) AS refers,
				COUNT(DISTINCT i.adm_id) AS admit,
				SUM(CASE WHEN i.ward_no = '38' THEN 1 ELSE 0 END) AS ward1,
				SUM(CASE WHEN i.ward_no = '39' THEN 1 ELSE 0 END) AS ward2,
				SUM(CASE WHEN i.ward_no = '22' THEN 1 ELSE 0 END) AS lr,
				SUM(CASE WHEN i.ward_no = '50' THEN 1 ELSE 0 END) AS homeward,
				SUM(CASE WHEN i.ward_no = '55' THEN 1 ELSE 0 END) AS ward4,
				SUM(CASE WHEN i.ward_no = '61' THEN 1 ELSE 0 END) AS ward5
			FROM ipd_reg i
			INNER JOIN opd_visits o ON i.visit_id = o.visit_id
			INNER JOIN cid_hn b ON o.HN = b.HN
			INNER JOIN population p ON b.CID = p.CID
			LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
			LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.DXT_ID = 1 AND od.IS_CANCEL = 0
			LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
			WHERE i.IS_CANCEL = 0
			  AND o.IS_CANCEL = 0
			  AND i.dsc_dt BETWEEN :start AND :end
			GROUP BY period_year, period_month
		")->bindValues([
			':start' => $startDate,
			':end' => $endDate
		])->execute();
###########################################################################################################################
			
			// 1. ลบข้อมูลเก่าเฉพาะเดือนปัจจุบันของปีงบ 2568
			Yii::$app->db2->createCommand("
				DELETE FROM dashboard_opd_summary
				WHERE fiscal_year = :fiscal AND period_month = :month
			")->bindValues([
				':fiscal' => $fiscalYear,
				':month' => $currentMonth
			])->execute();

			// 2. เพิ่มหรือแทนที่ข้อมูลใหม่
			Yii::$app->db2->createCommand("
				REPLACE INTO dashboard_opd_summary (
					period_year,
					period_month,
					fiscal_year,
					month_name,
					visit,
					person,
					refers
				)
				SELECT 
					YEAR(o.REG_DATETIME) AS period_year,
					MONTH(o.REG_DATETIME) AS period_month,
					CASE 
						WHEN MONTH(o.REG_DATETIME) >= 10 THEN YEAR(o.REG_DATETIME) + 544
						ELSE YEAR(o.REG_DATETIME) + 543
					END AS fiscal_year,
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
					END AS month_name,
					COUNT(DISTINCT o.VISIT_ID) AS visit,
					COUNT(DISTINCT o.HN) AS person,
					COUNT(r.hosp_id) AS refers
				FROM opd_visits o
				INNER JOIN cid_hn b ON o.HN = b.HN
				INNER JOIN population p ON b.CID = p.CID
				LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
				INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
				LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
				WHERE o.IS_CANCEL = 0
				  AND i.visit_id IS NULL
				  AND o.REG_DATETIME BETWEEN :start AND :end
				  #AND MONTH(o.REG_DATETIME) = :month
				GROUP BY period_year, period_month
			")->bindValues([
				':start' => $startDate,
				':end' => $endDate,
				':month' => $currentMonth
			])->execute();

########################################################################################################################################
        // 3. Top 10 Disease
       // ลบเฉพาะข้อมูล OPD ของปี 2568
			Yii::$app->db2->createCommand("
				DELETE FROM dashboard_top10_disease
				WHERE type = 'OPD' AND fiscal_year = 2568
			")->execute();
			
			// แทรกข้อมูลใหม่ 10 อันดับ
			Yii::$app->db2->createCommand("
				REPLACE INTO dashboard_top10_disease 
					(type, fiscal_year, icd10_tm, nickname, total_visit, created_at)
				SELECT 
					'OPD' AS type,
					2568 AS fiscal_year,
					ic.ICD10_TM,
					ic.NICKNAME,
					COUNT(o.visit_id) AS total_visit,
					CURDATE() AS created_at
				FROM opd_visits o
				INNER JOIN cid_hn b ON o.HN = b.HN
				INNER JOIN population p ON b.CID = p.CID
				LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID AND r.IS_CANCEL = 0 AND r.rf_type = 2
				INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
				LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
				LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
				WHERE o.REG_DATETIME BETWEEN :start AND :end
				  AND i.visit_id IS NULL
				  AND ic.ICD10_TM NOT LIKE 'Z%'
				GROUP BY ic.ICD10_TM, ic.NICKNAME
				ORDER BY total_visit DESC
				LIMIT 10
			", [
				':start' => $startDatex,
				':end' => $endDatex
			])->execute();
		##################################################################################	
		  // 4. Top 10 Disease
		// ลบเฉพาะข้อมูล IPD ของปี 2568
		Yii::$app->db2->createCommand("
			DELETE FROM dashboard_top10_disease
			WHERE type = 'IPD' AND fiscal_year = 2568
		")->execute();

		// แทรกข้อมูลใหม่ 10 อันดับ IPD ปี 2568
Yii::$app->db2->createCommand("
    REPLACE INTO dashboard_top10_disease (
        type, fiscal_year, icd10_tm, nickname, total_visit, created_at
    )
    SELECT 
        'IPD' AS type,
        2568 AS fiscal_year,
        ic.ICD10_TM,
        ic.NICKNAME,
        COUNT(o.visit_id) AS total_visit,
        CURDATE() AS created_at
    FROM opd_visits o
    INNER JOIN cid_hn b ON o.HN = b.HN
    INNER JOIN population p ON b.CID = p.CID
    LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID 
        AND r.IS_CANCEL = 0 AND r.rf_type = 2
    LEFT JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID 
        AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
    LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
    INNER JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
    WHERE i.dsc_dt BETWEEN :start AND :end
      AND ic.ICD10_TM NOT LIKE 'Z%'
    GROUP BY ic.ICD10_TM, ic.NICKNAME
    ORDER BY total_visit DESC
    LIMIT 10
", [
    ':start' => $startDatex,
    ':end' => $endDatex
])->execute();
############# Alert #################################################################################3
			####	echo "✅ Dashboard data updated: {$endDate}\n";
			Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเรียบร้อยแล้ว');

    // redirect กลับไปที่หน้า index
    return $this->redirect(['dashboardall/index']);
	}
	

	###################################################################################################
	public function actionUpdatex()
	{
	$fiscalYear = 2568;
		$currentMonth = (int)date('n'); // เดือน 1-12 เช่น 7

		$startDate = date('Y-m-01 00:00:00'); // วันที่ 1 ของเดือน
		$endDate = date('Y-m-t 23:59:59');     // วันที่สุดท้ายของเดือน
		
		$startDatex = '2024-10-01 00:01';
		$endDatex = '2025-12-31 23:59';

##########################################################################################
// 5. Top 10 Disease  OPD REFER
		
		// ลบข้อมูลเดิมของปีนี้
		Yii::$app->db2->createCommand("
			DELETE FROM dashboard_top10_refer_disease
			WHERE refer_type = 'OPD' AND fiscal_year = :fy
		")->bindValue(':fy', $fiscalYear)->execute();


				// เพิ่มใหม่ (TOP 10 โรค refer OPD)
		Yii::$app->db2->createCommand("
			INSERT INTO dashboard_top10_refer_disease (refer_type, fiscal_year, icd10_code, disease_name_th, total_cases)
			SELECT 
				'OPD' AS refer_type,
				:fy AS fiscal_year,
				ic.ICD10_TM AS icd10_code,
				ic.NICKNAME AS disease_name_th,
				COUNT(o.visit_id) AS total_cases
			FROM opd_visits o
			INNER JOIN cid_hn b ON o.HN = b.HN
			INNER JOIN population p ON b.CID = p.CID
			INNER JOIN refers r ON o.VISIT_ID = r.VISIT_ID 
				AND r.IS_CANCEL = 0 AND r.rf_type = 2
			INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID 
				AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
			LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
			LEFT JOIN ipd_reg i2 ON i2.visit_id = o.visit_id AND i2.IS_CANCEL = 0
			WHERE 
				o.reg_datetime BETWEEN '2024-10-01 00:00:00' AND '2025-09-30 23:59:59'
				AND i2.visit_id IS NULL
				AND ic.ICD10_TM NOT LIKE 'Z%'
			GROUP BY ic.ICD10_TM, ic.NICKNAME
			ORDER BY total_cases DESC
			LIMIT 10
		")->bindValue(':fy', $fiscalYear)->execute();
		############################ X60-X84 update ###############################################
			Yii::$app->db2->createCommand("
        REPLACE INTO x60_cases (
            visit_id, regdate, hn, fullname, age, sex, diag, icd_name,
            tel, baan, tambon, amphoe, changwat, created_at, updated_at
        )
        SELECT DISTINCT
            o.visit_id,
            o.REG_DATETIME,
            o.HN,
            CONCAT(TRIM(p.fname), ' ', p.lname),
            TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime),
            p.sex,
            i.icd10_tm,
            i.icd_name,
            p.TELEPHONE,
            t.TOWN_NAME,
            tt.TOWN_NAME,
            ttt.TOWN_NAME,
            tttt.TOWN_NAME,
            NOW(),
            NOW()
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
        INNER JOIN population p ON p.CID = c.CID
        LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
        LEFT JOIN icd10new i ON i.icd10 = dx.icd10
        INNER JOIN prescriptions pr ON pr.visit_id = o.visit_id AND pr.IS_CANCEL = 0
        INNER JOIN drugs d ON pr.drug_id = d.drug_id
        LEFT JOIN ipd_reg l ON o.visit_id = l.visit_id AND l.is_cancel = 0
        INNER JOIN towns t ON t.TOWN_ID = p.TOWN_ID 
        INNER JOIN towns tt ON CONCAT(LEFT(p.TOWN_ID, 6), '00') = tt.TOWN_ID 
        INNER JOIN towns ttt ON CONCAT(LEFT(p.TOWN_ID, 4), '0000') = ttt.TOWN_ID 
        INNER JOIN towns tttt ON CONCAT(LEFT(p.TOWN_ID, 2), '000000') = tttt.TOWN_ID 
        WHERE 
            o.REG_DATETIME BETWEEN '2025-07-01 00:00:00' AND '2025-12-31 23:59:59'
            AND i.icd10_tm BETWEEN 'X60' AND 'X84'
        GROUP BY o.visit_id
    ")->execute();

###################################################################################################
// 6. Top 10 Disease  iPD REFER
		
			Yii::$app->db2->createCommand("
			DELETE FROM dashboard_top10_refer_disease
			WHERE refer_type = 'IPD' AND fiscal_year = :fy
		")->bindValue(':fy', $fiscalYear)->execute();

		Yii::$app->db2->createCommand("
			INSERT INTO dashboard_top10_refer_disease (refer_type, fiscal_year, icd10_code, disease_name_th, total_cases)
			SELECT 
				'IPD' AS refer_type,
				:fy AS fiscal_year,
				ic.ICD10_TM AS icd10_code,
				ic.NICKNAME AS disease_name_th,
				COUNT(o.visit_id) AS total_cases
			FROM ipd_reg i
			INNER JOIN opd_visits o ON i.visit_id = o.visit_id
			INNER JOIN cid_hn b ON o.HN = b.HN
			INNER JOIN population p ON b.CID = p.CID
			INNER JOIN refers r ON o.VISIT_ID = r.VISIT_ID 
				AND r.IS_CANCEL = 0 AND r.rf_type = 2
			INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID 
				AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
			LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
			WHERE 
				i.IS_CANCEL = 0
				AND o.IS_CANCEL = 0
				AND i.dsc_dt BETWEEN '2024-10-01 00:00:00' AND '2025-09-30 23:59:59'
				AND ic.ICD10_TM NOT LIKE 'Z%'
			GROUP BY ic.ICD10_TM, ic.NICKNAME
			ORDER BY total_cases DESC
			LIMIT 10
		")->bindValue(':fy', $fiscalYear)->execute();


############# Alert #################################################################################3
			####	echo "✅ Dashboard data updated: {$endDate}\n";
			Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเรียบร้อยแล้ว');

    // redirect กลับไปที่หน้า index
    return $this->redirect(['dashboardall/index']);
	}
	###################################################################################################
	public function actionUpdateclaim()
	{
	$fiscalYear = 2568;
		$currentMonth = (int)date('n'); // เดือน 1-12 เช่น 7

		$startDate = date('Y-m-01 00:00:00'); // วันที่ 1 ของเดือน
		$endDate = date('Y-m-t 23:59:59');     // วันที่สุดท้ายของเดือน
		
		$startDatex = '2024-10-01 00:01';
		$endDatex = '2025-12-31 23:59';


		
			Yii::$app->db2->createCommand("
			DELETE FROM dashboard_top10_refer_disease
			WHERE refer_type = 'IPD' AND fiscal_year = :fy
		")->bindValue(':fy', $fiscalYear)->execute();

		Yii::$app->db2->createCommand("
			INSERT INTO dashboard_top10_refer_disease (refer_type, fiscal_year, icd10_code, disease_name_th, total_cases)
			SELECT 
				'IPD' AS refer_type,
				:fy AS fiscal_year,
				ic.ICD10_TM AS icd10_code,
				ic.NICKNAME AS disease_name_th,
				COUNT(o.visit_id) AS total_cases
			FROM ipd_reg i
			INNER JOIN opd_visits o ON i.visit_id = o.visit_id
			INNER JOIN cid_hn b ON o.HN = b.HN
			INNER JOIN population p ON b.CID = p.CID
			INNER JOIN refers r ON o.VISIT_ID = r.VISIT_ID 
				AND r.IS_CANCEL = 0 AND r.rf_type = 2
			INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID 
				AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
			LEFT JOIN icd10new ic ON od.ICD10 = ic.ICD10
			WHERE 
				i.IS_CANCEL = 0
				AND o.IS_CANCEL = 0
				AND i.dsc_dt BETWEEN '2024-10-01 00:00:00' AND '2025-09-30 23:59:59'
				AND ic.ICD10_TM NOT LIKE 'Z%'
			GROUP BY ic.ICD10_TM, ic.NICKNAME
			ORDER BY total_cases DESC
			LIMIT 10
		")->bindValue(':fy', $fiscalYear)->execute();


############# Alert #################################################################################3
			####	echo "✅ Dashboard data updated: {$endDate}\n";
			Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเรียบร้อยแล้ว');

    // redirect กลับไปที่หน้า index
    return $this->redirect(['dashboardall/index']);
	}
	###################################################################################################
	public function actionUpdatephr()
{
    // กำหนดช่วงวันที่ (ปีงบประมาณ 2568)
    $startDate = '2025-01-01 00:00';
    $endDate   = '2025-12-30 23:59';

    // คำสั่ง REPLACE INTO เพื่ออัปเดตตาราง dash_phr
    $sql = "
        REPLACE INTO dash_phr (month, total_visits, phr_sent, percent_phr_sent, percent_of_total_phr)
        SELECT 
            DATE_FORMAT(o.reg_datetime, '%Y-%m') AS month,
            COUNT(DISTINCT o.visit_id) AS total_visits,
            COUNT(DISTINCT l.visit_id) AS phr_sent,
            ROUND(COUNT(DISTINCT l.visit_id) / COUNT(DISTINCT o.visit_id) * 100, 2) AS percent_phr_sent,
            ROUND(COUNT(DISTINCT l.visit_id) / (
                SELECT COUNT(DISTINCT l2.visit_id)
                FROM log_all.log_phr l2
                LEFT JOIN opd_visits o2 ON o2.visit_id = l2.visit_id
                LEFT JOIN opd_diagnosis dx2 ON dx2.visit_id = o2.visit_id AND dx2.is_cancel = 0
                LEFT JOIN icd10new i2 ON i2.icd10 = dx2.icd10
                WHERE o2.reg_datetime BETWEEN :startDate AND :endDate
                  AND o2.is_cancel = 0
                  AND i2.icd10_tm <> ''
            ) * 100, 2) AS percent_of_total_phr
        FROM opd_visits o
        LEFT JOIN log_all.log_phr l ON l.visit_id = o.visit_id
        LEFT JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
        LEFT JOIN icd10new i ON i.icd10 = dx.icd10
        WHERE o.reg_datetime BETWEEN :startDate AND :endDate
          AND o.is_cancel = 0
          AND i.icd10_tm <> ''
        GROUP BY month
        ORDER BY month
    ";

    // รันคำสั่ง SQL
    Yii::$app->db4->createCommand($sql)
        ->bindValue(':startDate', $startDate)
        ->bindValue(':endDate', $endDate)
        ->execute();

    // ตั้งค่า Flash message
    Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเรียบร้อยแล้ว');

    // redirect กลับไปที่หน้า dashboard
    return $this->redirect(['dashboardall/index']);
}
##################################################################################
public function actionUpdatetelemed()
{
    $months = [
        10 => ['t' => 't10','r' => 'r10','hosp' => 'hosp_claim_10','ret' => 'ret_statement_10'],
        11 => ['t' => 't11','r' => 'r11','hosp' => 'hosp_claim_11','ret' => 'ret_statement_11'],
        12 => ['t' => 't12','r' => 'r12','hosp' => 'hosp_claim_12','ret' => 'ret_statement_12'],
        1  => ['t' => 't1','r' => 'r1','hosp' => 'hosp_claim_1','ret' => 'ret_statement_1'],
        2  => ['t' => 't2','r' => 'r2','hosp' => 'hosp_claim_2','ret' => 'ret_statement_2'],
        3  => ['t' => 't3','r' => 'r3','hosp' => 'hosp_claim_3','ret' => 'ret_statement_3'],
        4  => ['t' => 't4','r' => 'r4','hosp' => 'hosp_claim_4','ret' => 'ret_statement_4'],
        5  => ['t' => 't5','r' => 'r5','hosp' => 'hosp_claim_5','ret' => 'ret_statement_5'],
        6  => ['t' => 't6','r' => 'r6','hosp' => 'hosp_claim_6','ret' => 'ret_statement_6'],
        7  => ['t' => 't7','r' => 'r7','hosp' => 'hosp_claim_7','ret' => 'ret_statement_7'],
        8  => ['t' => 't8','r' => 'r8','hosp' => 'hosp_claim_8','ret' => 'ret_statement_8'],
        9  => ['t' => 't9','r' => 'r9','hosp' => 'hosp_claim_9','ret' => 'ret_statement_9'],
    ];

    // --- หาวันเดือนปีปัจจุบัน ---
    $currentMonth = (int)date('n'); // 1-12
    $currentYear  = (int)date('Y');

    // ตรวจสอบว่ามีคีย์ใน $months
    if (!isset($months[$currentMonth])) {
        Yii::$app->session->setFlash('error', '❌ ไม่มีข้อมูลเดือนปัจจุบันในระบบ');
        return $this->redirect(['dashboardall/index']);
    }

    $fields = $months[$currentMonth];

    // --- คำนวณปีปฏิทินที่ใช้ (Dynamic) ---
    // ต.ค.–ธ.ค. ใช้ปีปัจจุบัน, ม.ค.–ก.ย. ใช้ปีปัจจุบันเช่นกัน
    // (ฟังก์ชันนี้ไม่ล็อคปีงบ ทำงานได้ทุกปีอัตโนมัติ)
    $year = $currentYear;

    $startDate = "$year-" . str_pad($currentMonth,2,'0',STR_PAD_LEFT) . "-01 00:01";
    $endDate   = date("Y-m-t 23:59:59", strtotime($startDate)); // วันสุดท้ายของเดือน

    $sql = "
        UPDATE dashboard_claim_opd d
        JOIN (
            SELECT
                SUM(1) AS total_sent,
                SUM(CASE WHEN r.messages NOT IN ('rejected','') THEN 1 ELSE 0 END) AS passed,
                SUM(c.hosp_claim) AS hosp_claim,
                SUM(c.ret_statement) AS ret_statement
            FROM opd_visits o
            LEFT JOIN log_fdh_opd_ck r ON o.visit_id = r.visit_id
            LEFT JOIN mbase_data1.cost_visits c ON c.visit_id = o.visit_id
            WHERE o.IS_CANCEL = 0
              AND o.UNIT_REG IN ('63','70','75')
              AND o.REG_DATETIME BETWEEN :startDate AND :endDate
        ) AS stats
        SET
            d.{$fields['t']}    = stats.total_sent,
            d.{$fields['r']}    = stats.passed,
            d.{$fields['hosp']} = stats.hosp_claim,
            d.{$fields['ret']}  = stats.ret_statement,
            d.updated_at        = NOW()
        WHERE d.users = 'telemed';
    ";

    Yii::$app->db4->createCommand($sql)
        ->bindValue(':startDate', $startDate)
        ->bindValue(':endDate', $endDate)
        ->execute();

    Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเดือนปัจจุบันเรียบร้อยแล้ว');
    return $this->redirect(['dashboardall/index']);
}
#############################################################################################
################################## DENTAL UPDATE ################################################
public function actionUpdatedent()
{
    // ✅ หาวันที่ปัจจุบัน
    $currentDate = new \DateTime('now', new \DateTimeZone('Asia/Bangkok'));
    $currentYear = (int)$currentDate->format('Y');
    $currentMonth = (int)$currentDate->format('n'); // 1–12

    // ✅ คำนวณปีงบประมาณ (เริ่ม ต.ค.)
    $fiscalYear = ($currentMonth >= 10) ? $currentYear + 544 : $currentYear + 543;

    // ✅ ช่วงวันที่ของเดือนปัจจุบัน
    $startDate = $currentDate->format('Y-m-01 00:00:00');
    $endDate   = $currentDate->format('Y-m-t 23:59:59');

    // 1. ลบข้อมูลเก่าเฉพาะเดือนปัจจุบันของปีงบ
    Yii::$app->db2->createCommand("
        DELETE FROM dashboard_dent_summary
        WHERE fiscal_year = :fiscal AND period_month = :month
    ")->bindValues([
        ':fiscal' => $fiscalYear,
        ':month' => $currentMonth
    ])->execute();

    // 2. เพิ่ม/แทนที่ข้อมูลใหม่
    Yii::$app->db2->createCommand("
        REPLACE INTO dashboard_dent_summary (
            period_year,
            period_month,
            fiscal_year,
            month_name,
            visit,
            person,
            refers
        )
        SELECT 
            YEAR(o.REG_DATETIME) AS period_year,
            MONTH(o.REG_DATETIME) AS period_month,
            CASE 
                WHEN MONTH(o.REG_DATETIME) >= 10 THEN YEAR(o.REG_DATETIME) + 544
                ELSE YEAR(o.REG_DATETIME) + 543
            END AS fiscal_year,
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
            END AS month_name,
            COUNT(DISTINCT o.VISIT_ID) AS visit,
            COUNT(DISTINCT o.HN) AS person,
            COUNT(r.hosp_id) AS refers
        FROM opd_visits o
        INNER JOIN cid_hn b ON o.HN = b.HN
        INNER JOIN population p ON b.CID = p.CID
        LEFT JOIN refers r ON o.VISIT_ID = r.VISIT_ID 
            AND r.IS_CANCEL = 0 
            AND r.rf_type = 2
        INNER JOIN opd_diagnosis od ON o.VISIT_ID = od.VISIT_ID 
            AND od.IS_CANCEL = 0 
            AND od.DXT_ID = 1
        LEFT JOIN ipd_reg i ON i.visit_id = o.visit_id AND i.IS_CANCEL = 0
        WHERE o.IS_CANCEL = 0
          AND i.visit_id IS NULL
          AND o.REG_DATETIME BETWEEN :start AND :end
          AND o.UNIT_REG IN ('03','04','05')   -- ✅ รหัสคลินิกทันตกรรม
        GROUP BY period_year, period_month
    ")->bindValues([
        ':start' => $startDate,
        ':end' => $endDate
    ])->execute();

    // ✅ Flash message
    Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลเดือนปัจจุบันเรียบร้อยแล้ว');
    return $this->redirect(['dashboardall/index']);
}



			
}
