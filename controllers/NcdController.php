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

class NcdController extends \yii\web\Controller
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
            'only' => ['asthma', 'copd', 'update', 'view', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'asthma', 'copd', 'create', 'update'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 22]; // ตัวอย่าง user_id ที่ได้รับอนุญาต
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 22]; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
    ];
}
	public function actionAsthma()
    {
        $data = Yii::$app->request->post();
		$date1 = isset($data['date1']) && $data['date1'] !== '' 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01', strtotime('-5 days'));
		$date2 = isset($data['date2']) && $data['date2'] !== '' 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT 
			o.hn, i.ADM_ID as an, 
			CONCAT(    CASE
						WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
							#WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
							#WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
							ELSE 'นาง' END ,'',TRIM(p.fname),'  ',p.lname) as fullname,
					TIMESTAMPDIFF(year,p.BIRTHDATE,o1.REG_DATETIME) as age,
			case 
				when i.WARD_NO = 38 THEN 'ward2'
				when i.WARD_NO = 39 THEN 'ward1'
				when i.WARD_NO = 22 THEN 'LR'
			  when i.WARD_NO = 50 THEN 'Homeward'
			ELSE 'ward4'
			end as 'Ward'
			,i.ADM_DT,GROUP_CONCAT(icd.ICD10_TM)as diag,
			case 
				when r.RF_TYPE = ''  THEN ''
				WHEN r.RF_TYPE = '1' THEN 'รับ refer'
				WHEN r.RF_TYPE = '2' THEN 'ส่งต่อ refer'
			ELSE ' '
			END as 'status',
			ds.dsc_status_name,
			dt.dsc_name
			FROM ipd_reg i LEFT JOIN opd_visits o on i.VISIT_ID=o.VISIT_ID AND i.IS_CANCEL =0
			LEFT JOIN opd_visits o1 ON o1.visit_id = i.visit_id AND o1.is_cancel = 0
			INNER JOIN cid_hn c ON o1.hn = c.hn
			INNER JOIN population p ON p.cid = c.cid
			LEFT JOIN opd_diagnosis od ON od.VISIT_ID=i.VISIT_ID AND i.IS_CANCEL = 0
			LEFT JOIN icd10new icd ON icd.ICD10=od.ICD10
			LEFT JOIN refers r ON i.VISIT_ID=r.VISIT_ID AND r.IS_CANCEL = 0
			LEFT JOIN dsc_status ds ON ds.dsc_status = i.dsc_status
			LEFT JOIN dsc_type dt ON dt.dsc_type = i.dsc_type
			WHERE i.ADM_DT BETWEEN '$date1' AND '$date2'
			AND icd.ICD10_TM BETWEEN 'J45' AND 'J46'  ### Asthma
			GROUP BY i.ADM_ID
			ORDER BY i.ADM_DT
				";
       $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('asthma', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
	public function actionCopd()
    {
        $data = Yii::$app->request->post();
		$date1 = isset($data['date1']) && $data['date1'] !== '' 
		? date('Y-m-d 00:01', strtotime($data['date1'])) 
		: date('Y-m-d 00:01', strtotime('-5 days'));
		$date2 = isset($data['date2']) && $data['date2'] !== '' 
		? date('Y-m-d 23:59', strtotime($data['date2'])) 
		: date('Y-m-d 23:59');
		
        $sql = "SELECT 
			o.hn, i.ADM_ID as an, 
			CONCAT(    CASE
						WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
							#WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
							#WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
							WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
							ELSE 'นาง' END ,'',TRIM(p.fname),'  ',p.lname) as fullname,
					TIMESTAMPDIFF(year,p.BIRTHDATE,o1.REG_DATETIME) as age,
			case 
				when i.WARD_NO = 38 THEN 'ward2'
				when i.WARD_NO = 39 THEN 'ward1'
				when i.WARD_NO = 22 THEN 'LR'
			  when i.WARD_NO = 50 THEN 'Homeward'
			ELSE 'ward4'
			end as 'Ward'
			,i.ADM_DT,GROUP_CONCAT(icd.ICD10_TM)as diag,
			case 
				when r.RF_TYPE = ''  THEN ''
				WHEN r.RF_TYPE = '1' THEN 'รับ refer'
				WHEN r.RF_TYPE = '2' THEN 'ส่งต่อ refer'
			ELSE ' '
			END as 'status',
			ds.dsc_status_name,
			dt.dsc_name
			FROM ipd_reg i LEFT JOIN opd_visits o on i.VISIT_ID=o.VISIT_ID AND i.IS_CANCEL =0
			LEFT JOIN opd_visits o1 ON o1.visit_id = i.visit_id AND o1.is_cancel = 0
			INNER JOIN cid_hn c ON o1.hn = c.hn
			INNER JOIN population p ON p.cid = c.cid
			LEFT JOIN opd_diagnosis od ON od.VISIT_ID=i.VISIT_ID AND i.IS_CANCEL = 0
			LEFT JOIN icd10new icd ON icd.ICD10=od.ICD10
			LEFT JOIN refers r ON i.VISIT_ID=r.VISIT_ID AND r.IS_CANCEL = 0
			LEFT JOIN dsc_status ds ON ds.dsc_status = i.dsc_status
			LEFT JOIN dsc_type dt ON dt.dsc_type = i.dsc_type
			WHERE i.ADM_DT BETWEEN '$date1' AND '$date2'
			AND icd.ICD10_TM BETWEEN 'J441' AND 'J449'  ### COPD
			GROUP BY i.ADM_ID
			ORDER BY i.ADM_DT
				";
       $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('copd', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
    public function actionReadmit()
    {
		$data = Yii::$app->request->post();
		$date1 = isset($data['date1']) && $data['date1'] !== '' 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01', strtotime('-30 days'));
		$date2 = isset($data['date2']) && $data['date2'] !== '' 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT 
			c.HN as hn, c.cid,
CONCAT(    CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END ,'',TRIM(p.fname),'  ',p.lname) as fullname,
        TIMESTAMPDIFF(year,p.BIRTHDATE,o1.REG_DATETIME) as age,
      ip1.visit_id as vn1, date(ip1.adm_dt) as adm1, time(ip1.adm_dt) as time1 , c.hn , ip1.adm_id as an1,i1.icd10_tm as icd1, count(c.hn),
			 ip2.visit_id as vn2,ip2.adm_id as an2,date(ip2.adm_dt) as adm2, time(ip2.adm_dt) as time2,i2.icd10_tm as icd2,
			#((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24)) )
			#(date(o2.REG_DATETIME)- (date(o1.REG_DATETIME))) 
			TIMESTAMPDIFF(DAY, ip1.adm_dt, ip2.adm_dt) AS revisit_days,
			MOD(TIMESTAMPDIFF(HOUR, ip1.adm_dt, ip2.adm_dt), 24) AS revisit_hours
			FROM  opd_visits o1
			INNER JOIN cid_hn c ON o1.hn = c.hn
			INNER JOIN population p ON p.cid = c.cid
			INNER JOIN ipd_reg ip1 ON o1.visit_id = ip1.visit_id and ip1.is_cancel = 0
			LEFT OUTER JOIN opd_diagnosis dx1 ON ip1.visit_id = dx1.visit_id AND dx1.dxt_id = 1 AND dx1.is_cancel = 0 
			LEFT OUTER JOIN icd10new i1 ON dx1.icd10 = i1.icd10
			INNER JOIN opd_visits o2 ON o2.hn = c.hn AND o2.is_cancel = 0  
			LEFT OUTER JOIN ipd_reg ip2 ON o2.visit_id = ip2.visit_id AND ip2.is_cancel = 0 
			LEFT OUTER JOIN opd_diagnosis dx2 ON ip2.visit_id = dx2.visit_id AND dx2.dxt_id = 1 AND dx2.is_cancel = 0 AND dx2.icd10 is not null
			LEFT OUTER JOIN icd10new i2 ON dx2.icd10 = i2.icd10 AND i1.icd10_tm = i2.icd10_tm
			WHERE ip1.adm_dt BETWEEN '$date1' AND '$date2'
			#AND i1.icd10_tm  in ('J45','J46')
			#AND ((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24))) <=28
			#AND (date(o2.REG_DATETIME)- (date(o1.REG_DATETIME))) <=28
			AND timestampdiff(day,ip1.adm_dt,ip2.adm_dt) <=28
			AND ip2.visit_id > ip1.visit_id  
			AND i1.icd10_tm = i2.icd10_tm
			AND ip1.is_cancel = 0
			GROUP BY c.hn
			HAVING count(c.hn)>1   ";
       $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('readmit', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
   public function actionIndex2()
    {
         $data = Yii::$app->request->post();
		 $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
        $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';
		#$date1 = $date01 . ' 00:01'; 
		#$date2 = $date02 . ' 23:59'; 
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
			#ROUND((o.WEIGHT / ((o.height / 100) * (o.height / 100))), 2) as BMI,
			x.film_used,
			IFNULL(ll.lab_result, '') AS lab_result,
			h.hosp_name
			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
		    AND o.unit_reg in ('02','11','40','22','38','39')
			AND o.visit_id in (SELECT  visit_id FROM xray_requests )
            AND TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) >= '65'
			GROUP BY o.visit_id ";
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
       return $this->render('index2', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
    public function actionVip()
    {
         $data = Yii::$app->request->post();
		 $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
        $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';
        // SQL สำหรับรายวัน
    $sqlDaily = "SELECT DISTINCT
        DATE_FORMAT(o.REG_DATETIME,'%d-%m-%Y %H:%i:%s') AS regdate,
        o.visit_id as visit_id,
        o.HN as hn,
        p.cid,
        concat(trim(p.fname),' ',p.lname) as 'fullname',
        TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) as age,
        u.unit_name,
        i.icd10_tm as Diag,
        x.film_used,
        IFNULL(ll.lab_result, '') AS lab_result,
        h.hosp_name
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
        INNER JOIN population p ON p.CID=c.CID
        LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
        LEFT JOIN icd10new i on i.icd10= dx.icd10
        LEFT JOIN service_units u ON u.unit_id = o.unit_reg
        LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id in ('069')
        LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
        LEFT JOIN hospitals h ON h.hosp_id = p.town_id
        WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND o.unit_reg IN ('74')
        GROUP BY o.visit_id";

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
 #WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
 WHERE o.REG_DATETIME BETWEEN '2024-01-01' AND '2024-12-31'
AND o.unit_reg IN ('74')
GROUP BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME)
ORDER BY YEAR(o.REG_DATETIME), MONTH(o.REG_DATETIME); 
";

$yearlyMonthlyData = Yii::$app->db70->createCommand($sqlYearlyMonthly, [
    ':date1' => $date1,
    ':date2' => $date2,
])->queryAll();

$yearlyMonthlyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $yearlyMonthlyData,
    'pagination' => false,
]);

$dailyData = Yii::$app->db70->createCommand($sqlDaily)->queryAll();
$monthlyData = Yii::$app->db70->createCommand($sqlMonthly)->queryAll();

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
}
