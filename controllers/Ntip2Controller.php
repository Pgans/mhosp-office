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

class Ntip2Controller extends \yii\web\Controller
{
	
	/*
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
            'only' => ['index', 'index2', 'update', 'view', 'create', 'delete','vip','vvip'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'index', 'index2', 'create', 'update','vip','vvip'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 158,29,32]; // ตัวอย่าง user_id ที่ได้รับอนุญาต
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 158,29,32]; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
    ];
}
	*/
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
	#######################################################################################
	 public function actionRider()
    {
           $date1x = Yii::$app->request->get('date1', date('Y-m-d'));
		   $date2x = Yii::$app->request->get('date2', date('Y-m-d'));

		   $date1 = date('Y-m-d 00:01', strtotime($date1x));
		   $date2 = date('Y-m-d 23:59', strtotime($date2x));
        // SQL สำหรับรายวัน
    $sqlRider = "SELECT 
    @n := @n + 1 AS 'No',
    data.*
FROM 
(
    SELECT 
        DATE_FORMAT(o.reg_datetime, '%Y-%m-%d %H:%i') AS 'regdate',
        o.visit_id,
        o.hn,
        CONCAT(
           CASE 
              WHEN p.PRENAME != '' THEN TRIM(p.PRENAME)
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'สามเณร'
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'พระภิกษุ'
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15 AND p.sex = '1' THEN 'เด็กชาย'
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '1' THEN 'นาย'
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15 AND p.sex = '2' THEN 'เด็กหญิง'
              WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '2' AND p.MARRIAGE = '1' THEN 'นางสาว'
              ELSE 'นาง'
           END,
           TRIM(p.FNAME), ' ', TRIM(p.LNAME)
        ) AS 'fullname',
        TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) AS 'age',
        p.cid,
        CONCAT(
            CASE 
                WHEN icd1.icd10_tm = 'I10' THEN 'HT'
                WHEN icd1.icd10_tm = 'E119' THEN 'DM Type II'
                ELSE icd1.nickname
            END, ' (', icd1.icd10_tm, ')'
        ) AS Diagx,
        IFNULL(GROUP_CONCAT(DISTINCT jj.icd10_tm ORDER BY jj.icd10_tm SEPARATOR ', '), '') AS 'comore',
        o.BP_SYST AS SBP,
        o.BP_DIAS AS DBP,
        IFNULL(
            NULLIF(
                SUBSTRING(
                    SUBSTRING_INDEX(lr.lab_result, '=', -1),
                    1,
                    LOCATE(' ', SUBSTRING_INDEX(lr.lab_result, '=', -1)) - 1
                ), 
                ''
            ), 
            ''
        ) AS FBS,
        LEFT(GROUP_CONCAT(DISTINCT TRIM(icd.ICD10_TM)), 30) AS Diag,
        LEFT(e.unit_name, 10) AS 'unit_name',
        f.INSCL_NAME AS 'inscl',
        '50.00' AS amount,
        g.hospmain, g.hospsub,
        log.messagecode,
        IFNULL(cl.claimcode, '') AS endpoint,
        IFNULL(ak.claimcode, '') AS claimcode,
        GROUP_CONCAT(
            '  ', TRIM(dr.drug_name), '(',
            CASE 
                WHEN TRIM(dr.STRENGTH)='' THEN ''
                WHEN TRIM(dr.STRENGTH)='0.00' THEN ''
                ELSE REPLACE(dr.STRENGTH,'.00','')
            END,
            su.strength_name, ') ',
            CONCAT(REPLACE(pr.RX_DOSE,'.00',''),' ', TRIM(uu.UUNIT_NAME),' ',TRIM(r.ROUTE_NAME),' ',TRIM(ff.FRQ_NAME)),
            ' #', pr.Rx_amount, TRIM(uu.UUNIT_NAME)
            SEPARATOR ', '
        ) AS 'HomeMed'
    FROM opd_visits o
    INNER JOIN cid_hn c ON o.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID AND LEFT(p.cid, 5) <> '00000'
    INNER JOIN opd_diagnosis d ON d.visit_id = o.visit_id AND d.is_cancel = 0
    LEFT JOIN icd10new icd ON icd.icd10 = d.icd10 AND icd.icd10 <> ''
    LEFT JOIN opd_diagnosis d1 ON d1.visit_id = o.visit_id AND d1.is_cancel = 0 AND d1.dxt_id = 1
    LEFT JOIN icd10new icd1 ON icd1.icd10 = d1.icd10 AND icd1.icd10 <> ''
    LEFT JOIN opd_diagnosis ii ON o.VISIT_ID = ii.VISIT_ID AND ii.IS_CANCEL = 0 AND ii.DXT_ID != '1'
    LEFT JOIN icd10new jj ON ii.ICD10 = jj.ICD10
    LEFT JOIN ipd_reg ir ON ir.VISIT_ID = o.visit_id AND ir.IS_CANCEL = 0
    LEFT JOIN lab_requests lr ON lr.visit_id = o.visit_id AND lr.is_cancel = 0 
    LEFT JOIN lab_lists l ON l.lab_id = lr.lab_id 
    LEFT JOIN service_units e ON o.UNIT_REG = e.unit_id
    LEFT JOIN main_inscls f ON o.INSCL = f.INSCL
    LEFT JOIN uc_inscl g ON c.CID = g.CID AND (g.date_abort > DATE(o.REG_DATETIME) OR DAY(g.DATE_ABORT) = 0) AND TRIM(g.hospmain) <> ''
    LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND ak.visit_id = o.visit_id
    INNER JOIN opd_operations op ON o.VISIT_ID = op.VISIT_ID AND op.IS_CANCEL = 0 AND op.icd9 = '0000016301'
    LEFT JOIN log_fdh_opd_ck log ON log.visit_id = o.visit_id
    LEFT JOIN close_visits cl ON cl.visit_id = o.visit_id
    LEFT JOIN prescriptions pr ON pr.visit_id = o.visit_id AND pr.is_cancel = 0
    LEFT JOIN drugs dr ON dr.drug_id = pr.drug_id
    LEFT JOIN usage_units uu ON uu.UUNIT_ID = dr.UUNIT_ID
    LEFT JOIN routes r ON r.ROUTE_ID = pr.ROUTE_ID
    LEFT JOIN frequency ff ON ff.FRQ_ID = pr.FRQ_ID
    LEFT JOIN strength_units su ON su.STRENGTH_UNIT = dr.strength_unit
    WHERE o.IS_CANCEL = 0
      AND o.REG_DATETIME BETWEEN '$date1' AND '$date2'
      AND o.INSCL IN ('03','04','33','00','23')
      AND o.visit_id NOT IN (SELECT ipd_reg.visit_id FROM ipd_reg WHERE ipd_reg.is_cancel = 0)
    GROUP BY o.visit_id
) AS data,
(SELECT @n := 0) AS init
ORDER BY No DESC;
  ";


$dailyData = Yii::$app->db2->createCommand($sqlRider)->queryAll();

$dailyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $dailyData,
    'pagination' => false,
]);

$monthlyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $monthlyData,
    'pagination' => false,
]);

return $this->render('rider', [
    'dataProvider' => $dailyDataProvider,
    'monthlyDataProvider' => $monthlyDataProvider,
    'yearlyMonthlyDataProvider' => $yearlyMonthlyDataProvider,
    'date1' => $date1,
    'date2' => $date2,
]);
	}
###################################################################################################	
	 public function actionVip()
    {
         $date1x = Yii::$app->request->get('date1', date('Y-m-d'));
		   $date2x = Yii::$app->request->get('date2', date('Y-m-d'));

		   $date1 = date('Y-m-d 00:01', strtotime($date1x));
		   $date2 = date('Y-m-d 23:59', strtotime($date2x));
        // SQL สำหรับรายวัน
    $sqlDaily = "SELECT a.HN,CONCAT(IFNULL(ip.ADM_ID, ''), '/', IFNULL(ss.UNIT_NAME, '')) AS `AN`,
	a.REG_DATETIME as regdate, CONCAT(LEFT(p.cid, LENGTH(p.cid)-2), 'xx') AS cid,
	concat(trim(p.fname),' ',p.lname) as 'fullname',  TIMESTAMPDIFF(year,p.birthdate, a.reg_datetime ) as age, s.unit_name,
f.INSCL_NAME as 'inscl',
TRIM(IFNULL(j.ICD10_TM, '')) AS P_dx,
trim(a.claim_code) as 'appove code EDC',
(
    IFNULL(k.cg01,0) + IFNULL(k.cg01_1,0) + IFNULL(k.cg01_2,0) +
    IFNULL(k.cg02,0) + IFNULL(k.cg03,0) + IFNULL(k.cg04,0) +
    IFNULL(k.cg05,0) + IFNULL(k.cg06,0) + IFNULL(k.cg07,0) +
    IFNULL(k.cg08,0) + IFNULL(k.cg09,0) + IFNULL(k.cg10,0) +
    IFNULL(k.cg11,0) + IFNULL(k.cg12,0) + IFNULL(k.cg13,0) +
    IFNULL(k.cg14,0) + IFNULL(k.cg15,0) + IFNULL(k.cg16,0) +
    IFNULL(k.cg17,0) + IFNULL(k.cg18,0) + IFNULL(k.cg19,0)
) AS `แจ้งหนี้`,

SUM(IFNULL(r.PAID, 0)) AS `ยอดออกใบเสร็จ`,
IFNULL(k.ret_statement, '') AS `ยอดเคลมชดเชย`,
s.UNIT_NAME as 'แผนกลงทะเบียน',
case  
 when ip.ADM_ID !='' then 'Case Admit IPD'
 else 'OPD case'
END as 'บริการนอก/ใน รพ.'

#, g.HOSPSUB,g.UC_REGISTER,g.UC_EXPIRE, h.HOSP_ID as 'รพ.ปกส.',h.SSS_DATE,h.EXP_DATE
FROM opd_visits a LEFT JOIN cid_hn b on a.HN = b.HN
LEFT JOIN population p on b.CID = p.CID
LEFT JOIN main_inscls f ON a.INSCL=f.INSCL
LEFT JOIN uc_inscl g ON p.CID= g.CID AND (g.date_abort > date(a.REG_DATETIME) OR DAY(g.DATE_ABORT)=0)  and trim(g.hospmain) <>''
LEFT JOIN hosp_sss h ON p.CID=h.CID AND (h.date_abort > date(a.REG_DATETIME) OR DAY(h.DATE_ABORT) = 0 )and trim(h.HOSP_ID) <>'' 
LEFT JOIN opd_diagnosis i ON a.VISIT_ID=i.VISIT_ID AND i.IS_CANCEL = 0 AND i.DXT_ID = '1'
LEFT JOIN icd10new j ON i.ICD10=j.ICD10
LEFT JOIN cost_visits k on k.visit_id=a.VISIT_ID
LEFT JOIN receipts r on a.VISIT_ID=r.VISIT_ID AND r.IS_CANCEL !=1
LEFT JOIN authen_kiosk ak ON p.cid=ak.cid AND DATE(ak.d_update)=date(a.REG_DATETIME)
LEFT JOIN service_units s on s.UNIT_ID=a.UNIT_REG
LEFT JOIN ipd_reg ip on ip.VISIT_ID=a.VISIT_ID AND ip.IS_CANCEL = 0
LEFT JOIN service_units ss on ss.UNIT_ID=ip.WARD_NO
LEFT JOIN mobile_visits m ON m.visit_id=a.visit_id
LEFT JOIN log_closevisits lc on a.VISIT_ID=lc.visit_id
WHERE a.IS_CANCEL = 0 
AND a.REG_DATETIME BETWEEN '$date1' AND '$date2'
AND a.UNIT_REG in (40,74)
GROUP BY a.VISIT_ID,r.VISIT_ID
ORDER BY a.INSCL";

    // SQL สำหรับรายเดือน
    $sqlMonthly = "SELECT
        DATE_FORMAT(o.REG_DATETIME,'%Y-%m') AS month,
        COUNT(o.visit_id) AS total_visits,
        COUNT(DISTINCT p.cid) AS total_patients,
        SUM(IF(x.film_used IS NOT NULL, 1, 0)) AS total_xray,
        SUM(IF(ll.lab_result != '', 1, 0)) AS total_lab
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
        INNER JOIN population p ON p.CID=c.CID
        LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('069')
        LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
        WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND o.unit_reg IN ('74')
        GROUP BY DATE_FORMAT(o.REG_DATETIME,'%Y-%m')";
		
	$sqlYearlyMonthly = "SELECT 
    YEAR(o.REG_DATETIME) AS year,
    MONTH(o.REG_DATETIME) AS month,
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
        ELSE 'ธันวาคม'
    END AS month_name, 
    COUNT(o.visit_id) AS total_visits,
    COUNT(DISTINCT p.cid) AS total_patients,
    SUM(IF(x.film_used IS NOT NULL, 1, 0)) AS total_xray,
    SUM(IF(ll.lab_result != '', 1, 0)) AS total_lab
FROM opd_visits o
INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL = 0
INNER JOIN population p ON p.CID = c.CID
LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id IN ('069')
LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
 WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
# WHERE o.REG_DATETIME BETWEEN '2024-01-01' AND '2024-12-31'
AND o.unit_reg IN ('40')
GROUP BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
ORDER BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME); 
";

$yearlyMonthlyData = Yii::$app->db2->createCommand($sqlYearlyMonthly, [
    ':date1' => $date1,
    ':date2' => $date2,
])->queryAll();

$yearlyMonthlyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $yearlyMonthlyData,
    'pagination' => false,
]);

$dailyData = Yii::$app->db2->createCommand($sqlDaily)->queryAll();
$monthlyData = Yii::$app->db2->createCommand($sqlMonthly)->queryAll();

$dailyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $dailyData,
    'pagination' => false,
]);

$monthlyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $monthlyData,
    'pagination' => false,
]);

return $this->render('vip', [
    'dataProvider' => $dailyDataProvider,
    'monthlyDataProvider' => $monthlyDataProvider,
    'yearlyMonthlyDataProvider' => $yearlyMonthlyDataProvider,
    'date1' => $date1,
    'date2' => $date2,
]);
	}
	#######################################################################################
	 public function actionVvip()
    {
           $date1x = Yii::$app->request->get('date1', date('Y-m-d'));
		   $date2x = Yii::$app->request->get('date2', date('Y-m-d'));

		   $date1 = date('Y-m-d 00:01', strtotime($date1x));
		   $date2 = date('Y-m-d 23:59', strtotime($date2x));
        // SQL สำหรับรายวัน
    $sqlVvip = "SELECT a.HN,CONCAT(IFNULL(ip.ADM_ID, ''), '/', IFNULL(ss.UNIT_NAME, '')) AS `AN`,
	a.REG_DATETIME as regdate, CONCAT(LEFT(p.cid, LENGTH(p.cid)-2), 'xx') AS cid,
	concat(trim(p.fname),' ',p.lname) as 'fullname',  TIMESTAMPDIFF(year,p.birthdate, a.reg_datetime ) as age, s.unit_name,
f.INSCL_NAME as 'inscl',
TRIM(IFNULL(j.ICD10_TM, '')) AS P_dx,
trim(a.claim_code) as 'appove code EDC',
(
    IFNULL(k.cg01,0) + IFNULL(k.cg01_1,0) + IFNULL(k.cg01_2,0) +
    IFNULL(k.cg02,0) + IFNULL(k.cg03,0) + IFNULL(k.cg04,0) +
    IFNULL(k.cg05,0) + IFNULL(k.cg06,0) + IFNULL(k.cg07,0) +
    IFNULL(k.cg08,0) + IFNULL(k.cg09,0) + IFNULL(k.cg10,0) +
    IFNULL(k.cg11,0) + IFNULL(k.cg12,0) + IFNULL(k.cg13,0) +
    IFNULL(k.cg14,0) + IFNULL(k.cg15,0) + IFNULL(k.cg16,0) +
    IFNULL(k.cg17,0) + IFNULL(k.cg18,0) + IFNULL(k.cg19,0)
) AS `แจ้งหนี้`,

SUM(IFNULL(r.PAID, 0)) AS `ยอดออกใบเสร็จ`,
IFNULL(k.ret_statement, '') AS `ยอดเคลมชดเชย`,
s.UNIT_NAME as 'แผนกลงทะเบียน',
case  
 when ip.ADM_ID !='' then 'Case Admit IPD'
 else 'OPD case'
END as 'บริการนอก/ใน รพ.'

#, g.HOSPSUB,g.UC_REGISTER,g.UC_EXPIRE, h.HOSP_ID as 'รพ.ปกส.',h.SSS_DATE,h.EXP_DATE
FROM opd_visits a LEFT JOIN cid_hn b on a.HN = b.HN
LEFT JOIN population p on b.CID = p.CID
LEFT JOIN main_inscls f ON a.INSCL=f.INSCL
LEFT JOIN uc_inscl g ON p.CID= g.CID AND (g.date_abort > date(a.REG_DATETIME) OR DAY(g.DATE_ABORT)=0)  and trim(g.hospmain) <>''
LEFT JOIN hosp_sss h ON p.CID=h.CID AND (h.date_abort > date(a.REG_DATETIME) OR DAY(h.DATE_ABORT) = 0 )and trim(h.HOSP_ID) <>'' 
LEFT JOIN opd_diagnosis i ON a.VISIT_ID=i.VISIT_ID AND i.IS_CANCEL = 0 AND i.DXT_ID = '1'
LEFT JOIN icd10new j ON i.ICD10=j.ICD10
LEFT JOIN cost_visits k on k.visit_id=a.VISIT_ID
LEFT JOIN receipts r on a.VISIT_ID=r.VISIT_ID AND r.IS_CANCEL !=1
LEFT JOIN authen_kiosk ak ON p.cid=ak.cid AND DATE(ak.d_update)=date(a.REG_DATETIME)
LEFT JOIN service_units s on s.UNIT_ID=a.UNIT_REG
LEFT JOIN ipd_reg ip on ip.VISIT_ID=a.VISIT_ID AND ip.IS_CANCEL = 0
LEFT JOIN service_units ss on ss.UNIT_ID=ip.WARD_NO
LEFT JOIN mobile_visits m ON m.visit_id=a.visit_id
LEFT JOIN log_closevisits lc on a.VISIT_ID=lc.visit_id
WHERE a.IS_CANCEL = 0 
AND a.REG_DATETIME BETWEEN '$date1' AND '$date2'
AND a.UNIT_REG in (74)
GROUP BY a.VISIT_ID,r.VISIT_ID
ORDER BY a.INSCL";


$dailyData = Yii::$app->db2->createCommand($sqlVvip)->queryAll();

$dailyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $dailyData,
    'pagination' => false,
]);

$monthlyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $monthlyData,
    'pagination' => false,
]);

return $this->render('vvip', [
    'dataProvider' => $dailyDataProvider,
    'monthlyDataProvider' => $monthlyDataProvider,
    'yearlyMonthlyDataProvider' => $yearlyMonthlyDataProvider,
    'date1' => $date1,
    'date2' => $date2,
]);
	}
	
}
