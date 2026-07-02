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

class NtipController extends \yii\web\Controller
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
            'only' => ['index', 'index2', 'update', 'view', 'create', 'vip','exporttb','exportm30','exportcopd','exporthiv','exportdm','exportntip'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'index2', 'update', 'view', 'create', 'vip','exporttb','exportm30','exportcopd','exporthiv','exportdm','exportntip'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 158, 32, 190]; // ตัวอย่าง user_id ที่ได้รับอนุญาต
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 158, 32,190]; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
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
	############ ส่งออกไฟล์ ออกหน่วยคัดกรองวัณโรค xrayด้วย  ##########################################
	public function actionExporttb()
    {
        $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');

		
        $sql = "SELECT DISTINCT
      'ผู้ป่วยโรคไตเรื้อรัง**' as RISK_TYPE,
CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) AS AGE_Y,
            TIMESTAMPDIFF(MONTH, p.birthdate, o.reg_datetime) % 12 AS AGE_M,
			p.CID,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		 LEFT(p.home_adr,7) as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN 'ไทย'
			 WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
			END as PEOPLE_TYPE,
			CASE
			WHEN n.NATN_ID = '44' THEN '6=จีน'
			WHEN n.NATN_ID = '45' THEN '7=อินเดีย'
			WHEN n.NATN_ID = '46' THEN '4=เวียดนาม'
			WHEN n.NATN_ID = '48' THEN '1=พม่า'
			WHEN n.NATN_ID = '50' THEN '5=มาเลเซีย'
			WHEN n.NATN_ID = '52' THEN '8=ปากีสถาน'
			WHEN n.NATN_ID = '56' THEN '3=ลาว'
			WHEN n.NATN_ID = '57' THEN '2=กัมพูชา'
			WHEN n.NATN_ID = '98' THEN '9=อื่นๆ'
			WHEN n.NATN_ID = '99' THEN '0=ไทย'
			END AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			 'ไม่สงสัยวัณโรค (คะแนน <3)'  AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN 'สิทธิว่าง'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN 'ต่างด้าว'
			WHEN e.inscl IN ('03','04' )THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN 'ข้าราชการ/รัฐวิสาหกิจ'
			WHEN e.inscl IN ('06' )THEN 'จ่ายเอง'
			END AS INSCL_ID,
			'N189' as ICD10,
			'' as TB_CID_INDEX,
			''AS HbA1C,
		#	MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1cx,
         '' AS IMMUNNO_DISEASE,
			'' AS B24

			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  #AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  #AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME  BETWEEN '$date1' AND '$date2'
			AND i.icd10_tm IN ('Z111')
			AND o.visit_id in (SELECT  visit_id FROM xray_requests )
            AND o.VISIT_ID in (SELECT visit_id FROM mobile_visits)
			GROUP BY o.visit_id  ";
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
       return $this->render('exporttb', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
	############ ส่งออกไฟล์ นิรนาม ##########################################
	public function actionExporthiv()
    {
        $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');

		
        $sql = "SELECT DISTINCT
      'ผู้ป่วย B24**' as RISK_TYPE,
		CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			p.CID,
			TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) AS AGE_Y,
            TIMESTAMPDIFF(MONTH, p.birthdate, o.reg_datetime) % 12 AS AGE_M,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		 LEFT(p.home_adr,7) as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN 'ไทย'
			 WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
			END as PEOPLE_TYPE,
			n.NATN_ID AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			 'ไม่สงสัยวัณโรค (คะแนน <3)'  AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN 'สิทธิว่าง'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN 'ต่างด้าว'
			WHEN e.inscl IN ('03','04' )THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN 'ข้าราชการ/รัฐวิสาหกิจ'
			WHEN e.inscl IN ('06' )THEN 'จ่ายเอง'
			END AS INSCL_ID,
			i.ICD10_TM as ICD10,
			MAX(
				  CASE 
					WHEN ll.lab_id = '123' 
					THEN 
					  TRIM(
						SUBSTRING_INDEX(
						  SUBSTRING_INDEX(ll.lab_result, '=', -1), ' ', 1
						)
					  )
					ELSE '' 
				  END
				) AS HbA1C,
			#MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1C,
		#	MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1cx,
      '' AS IMMUNNO_DISEASE,
							MAX(
				  CASE
					WHEN ll.lab_id = '045' AND LOCATE('CD4=', ll.lab_result) > 0 THEN
					  ROUND(
						CAST(
						  TRIM(
							SUBSTRING(
							  ll.lab_result,
							  LOCATE('CD4=', ll.lab_result) + 4,
							  LOCATE(' ', ll.lab_result, LOCATE('CD4=', ll.lab_result) + 4) - (LOCATE('CD4=', ll.lab_result) + 4)
							)
						  ) AS DECIMAL(10,4)
						), 1
					  )
					ELSE NULL
				  END
				) AS B24

			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  #AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('045')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
			AND o.unit_reg in ('20')
		    #AND o.unit_reg in ('02','11','40','22','38','39')
			AND o.visit_id in (SELECT  visit_id FROM xray_requests )
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
       return $this->render('exporthiv', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
	############ ส่งออกไฟล์ โรคปอดอุดกั้นเรื้อรัง COPD  ##########################################
	public function actionExportcopd()
    {
        $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');

		
        $sql = "SELECT DISTINCT
      'โรคปอดอุดกั้นเรื้อรัง (COPD)**' as RISK_TYPE,
		CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			p.CID,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
	 TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) AS AGE_Y,
        TIMESTAMPDIFF(MONTH, p.birthdate, o.reg_datetime) % 12 AS AGE_M,
		 LEFT(p.home_adr,7) as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN 'ไทย'
			 WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
			END as PEOPLE_TYPE,
			n.NATN_ID AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			 'ไม่สงสัยวัณโรค (คะแนน <3)'  AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN 'สิทธิว่าง'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN 'ต่างด้าว'
			WHEN e.inscl IN ('03','04' )THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN 'ข้าราชการ/รัฐวิสาหกิจ'
			WHEN e.inscl IN ('06' )THEN 'จ่ายเอง'
			END AS INSCL_ID,
			i.ICD10_TM as ICD10,
			MAX(
				  CASE 
					WHEN ll.lab_id = '123' 
					THEN 
					  TRIM(
						SUBSTRING_INDEX(
						  SUBSTRING_INDEX(ll.lab_result, '=', -1), ' ', 1
						)
					  )
					ELSE '' 
				  END
				) AS HbA1C,
			#MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1C,
		#	MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1cx,
      '' AS IMMUNNO_DISEASE,
			'' AS B24
			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  #AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  #AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
			AND o.unit_reg in ('12')
			AND TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) >= '65'
			AND i.ICD10_TM BETWEEN 'J440'  AND 'J449'
		    #AND o.unit_reg in ('02','11','40','22','38','39')
			AND o.visit_id in (SELECT  visit_id FROM xray_requests )
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
       return $this->render('exportcopd', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
	############ ส่งออกไฟล์ บุคลาการสาธารณสุข  ##########################################
	public function actionExportm30()
{
    $data = Yii::$app->request->post();

    $date1 = isset($data['date1']) && !empty($data['date1'])
        ? date('Y-m-d 00:01', strtotime($data['date1']))
        : date('Y-m-d 00:01');

    $date2 = isset($data['date2']) && !empty($data['date2'])
        ? date('Y-m-d 23:59', strtotime($data['date2']))
        : date('Y-m-d 23:59');

    $sql = "SELECT DISTINCT
        'บุคลากรสาธารณสุขดูแลผู้ป่วย**' as RISK_TYPE,
        CASE
            WHEN p.PRENAME NOT IN ('') THEN TRIM(p.PRENAME)
            WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15  AND p.sex = '1' THEN 'ด.ช.'
            WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '1' THEN 'นาย'
            WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15  AND p.sex = '2' THEN 'ด.ญ.'
            WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '2' AND p.MARRIAGE = '1' THEN 'น.ส.'
            ELSE 'นาง'
        END AS TITLE_ID,
        TRIM(p.fname)  AS FNAME,
        TRIM(p.lname)  AS LNAME,
		TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) AS AGE_Y,
        TIMESTAMPDIFF(MONTH, p.birthdate, o.reg_datetime) % 12 AS AGE_M,
        p.CID,
        CASE
            WHEN p.sex = 1 THEN 'M'
            WHEN p.sex = 2 THEN 'F'
        END AS GENDER,
        DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
        LEFT(p.home_adr, 7)  AS ADDR,
        RIGHT(p.TOWN_ID, 2)  AS MU,
        t3.TOWN_NAME AS PROVINCE_ID,
        t2.TOWN_NAME AS AMPHUR_ID,
        TRIM(t1.TOWN_NAME) AS TAMBOL_ID,
        CASE
            WHEN p.NATN_ID = 99  THEN 'ไทย'
            WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
        END AS PEOPLE_TYPE,
        n.NATN_ID AS RACE_ID,
        TRIM(DATE_FORMAT(o.REG_DATETIME, '%Y%m%d')) AS CONTACT_DATE,
        'ไม่สงสัยวัณโรค (คะแนน <3)' AS SYMPTOM_SCREEN,
        TRIM(DATE_FORMAT(x.XREQ_DATETIME, '%Y%m%d')) AS CXR_DATE,
        'Normal'    AS CXR_RESULT,
        'No Cavity' AS CXR_ABNORMAL_RESULT,
        'Normal'    AS DX,
        o.HN,
        '10953' AS HMAIN_ID,
        CASE
            WHEN e.inscl = '00'                               THEN 'สิทธิว่าง'
            WHEN e.inscl IN ('05','16','28','52','53')        THEN 'ต่างด้าว'
            WHEN e.inscl IN ('03','04')                       THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
            WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
            WHEN e.inscl IN ('01','25','11','12','14','35','36') THEN 'ข้าราชการ/รัฐวิสาหกิจ'
            WHEN e.inscl = '06'                               THEN 'จ่ายเอง'
            ELSE 'ไม่ระบุสิทธิ'
        END AS INSCL_ID,
        i.ICD10_TM AS ICD10,
        MAX(
            CASE
                WHEN ll.lab_id = '123'
                THEN TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(ll.lab_result, '=', -1), ' ', 1))
                ELSE NULL
            END
        ) AS HbA1C,
        NULL AS IMMUNNO_DISEASE,
        NULL AS B24
    FROM opd_visits o
    INNER JOIN cid_hn      c  ON c.HN       = o.HN          AND o.IS_CANCEL = 0
    INNER JOIN population  p  ON p.CID      = c.CID
    LEFT  JOIN opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
    LEFT  JOIN icd10new    i  ON i.icd10    = dx.icd10
    LEFT  JOIN service_units u ON u.unit_id = o.unit_reg
    LEFT  JOIN lab_requests ll ON ll.visit_id = o.visit_id  AND ll.is_cancel = 0
                               AND ll.lab_id IN ('123','011','086','087')
    LEFT  JOIN xray_requests x ON x.visit_id  = o.visit_id
    LEFT  JOIN nations     n  ON n.NATN_ID  = p.NATN_ID
    LEFT  JOIN towns       t  ON t.town_id  = p.town_id
    LEFT  JOIN hospitals   h  ON h.hosp_id  = t.hospsub
    LEFT  JOIN main_inscls e  ON e.INSCL    = o.INSCL
    LEFT  JOIN towns       t1 ON t1.town_id = CONCAT(LEFT(p.town_id,6),'00')
    LEFT  JOIN towns       t2 ON t2.town_id = CONCAT(LEFT(p.town_id,4),'0000')
    LEFT  JOIN towns       t3 ON t3.town_id = CONCAT(LEFT(p.town_id,2),'000000')
    WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
      AND o.unit_reg = '74'
      AND o.visit_id IN (SELECT visit_id FROM xray_requests)
    GROUP BY o.visit_id";

    // ✅ query เพียงครั้งเดียว (ของเดิม query 2 ครั้ง)
    try {
        $rawData = \Yii::$app->db7->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        // ✅ แก้ db2 → db (typo ในโค้ดเดิม)
        Yii::error('SQL Error: ' . $e->getMessage(), 'exportm30');
        throw new \yii\web\ServerErrorHttpException('เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage());
    }

    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels'  => $rawData,
        'pagination' => false,
    ]);

    return $this->render('exportm30', [
        'dataProvider' => $dataProvider,
        'sql'          => $sql,
        'date1'        => $date1,
        'date2'        => $date2,
    ]);
}
	############ ส่งออกไฟล์ โรคเบหวาน นำเข้าโปรแกรม Ntip  อายุ >= 65 ปี ##########################################
	public function actionExportdm()
    {
         $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT DISTINCT
      'โรคเบาหวาน**' as RISK_TYPE,
CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			p.CID,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		 LEFT(p.home_adr,7) as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN 'ไทย'
			 WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
			END as PEOPLE_TYPE,
			n.NATN_ID AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			 'ไม่สงสัยวัณโรค (คะแนน <3)'  AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN 'สิทธิว่าง'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN 'ต่างด้าว'
			WHEN e.inscl IN ('03','04' )THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN 'ข้าราชการ/รัฐวิสาหกิจ'
			WHEN e.inscl IN ('06' )THEN 'จ่ายเอง'
			END AS INSCL_ID,
			i.ICD10_TM as ICD10,
			MAX(
				  CASE 
					WHEN ll.lab_id = '123' 
					THEN 
					  TRIM(
						SUBSTRING_INDEX(
						  SUBSTRING_INDEX(ll.lab_result, '=', -1), ' ', 1
						)
					  )
					ELSE '' 
				  END
				) AS HbA1C,
			#MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1C,
		#	MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1cx,
      '' AS IMMUNNO_DISEASE,
			'' AS B24
			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  #AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
			#AND o.unit_reg in ('12','13','14','15','16','19','20','34')
			AND i.ICD10_TM BETWEEN 'E10'  AND 'E14'
			AND TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) >= '65'
		    #AND o.unit_reg in ('02','11','40','22','38','39')
			AND o.visit_id in (SELECT  visit_id FROM xray_requests )
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
       return $this->render('exportdm', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
   ######################################################################################################################
   ############ ส่งออกไฟล์ โรคเบหวาน นำเข้าโปรแกรม Ntip  อายุน้อยกว่า 65 ปี ##########################################
	public function actionExportdm2()
    {
         $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT DISTINCT
      'โรคเบาหวาน**' as RISK_TYPE,
CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			TRIM(p.fname) as FNAME,
			TRIM(p.lname) as LNAME,
			p.CID,
			CASE 
				WHEN p.sex = 1 THEN 'M'
				WHEN p.sex = 2 THEN 'F'
			END AS GENDER,
      DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		  LEFT(p.home_adr,7) as ADDR,
      RIGHT(p.TOWN_ID,2) AS MU,
		  t3.TOWN_NAME AS PROVINCE_ID,
		  t2.TOWN_NAME AS AMPHUR_ID,
		  TRIM(t1.TOWN_NAME) AS TAMBOL_ID,
      CASE
        WHEN p.NATN_ID = 99  THEN 'ไทย'
        WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
      END as PEOPLE_TYPE,
			n.NATN_ID AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d') AS CONTACT_DATE,
			'ไม่สงสัยวัณโรค (คะแนน <3)' AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
				WHEN e.inscl = '00'                              THEN 'สิทธิว่าง'
				WHEN e.inscl IN ('05','16','28','52','53')       THEN 'ต่างด้าว'
				WHEN e.inscl IN ('03','04')                      THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
				WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
				WHEN e.inscl IN ('01','25','11','12','14','35','36') THEN 'ข้าราชการ/รัฐวิสาหกิจ'
				WHEN e.inscl IN ('06')                           THEN 'จ่ายเอง'
			END AS INSCL_ID,
			i.ICD10_TM as ICD10,
			MAX(
				CASE 
					WHEN ll.lab_id = '123' 
					THEN 
						TRIM(
							SUBSTRING_INDEX(
								SUBSTRING_INDEX(ll.lab_result, '=', -1), ' ', 1
							)
						)
					ELSE '' 
				END
			) AS HbA1C,
			'' AS IMMUNNO_DISEASE,
			'' AS B24

FROM opd_visits o 
INNER JOIN cid_hn c        ON o.HN = c.HN AND o.IS_CANCEL = 0
INNER JOIN population p    ON p.CID = c.CID
LEFT JOIN  opd_diagnosis dx ON dx.visit_id = o.visit_id AND dx.is_cancel = 0
LEFT JOIN  icd10new i       ON i.icd10 = dx.icd10
LEFT JOIN  service_units u  ON u.unit_id = o.unit_reg
LEFT JOIN  lab_requests ll  ON ll.visit_id = o.visit_id 
                            AND ll.is_cancel = 0 
                            AND ll.lab_id IN ('123','011','086','087')
LEFT JOIN  xray_requests x  ON x.visit_id = o.visit_id
LEFT JOIN  nations n        ON n.NATN_ID = p.NATN_ID
LEFT JOIN  towns t          ON p.town_id = t.town_id
LEFT JOIN  hospitals h      ON h.hosp_id = t.hospsub
LEFT JOIN  main_inscls e    ON e.INSCL = o.INSCL
LEFT JOIN  towns t1         ON CONCAT(LEFT(p.town_id,6),'00')     = t1.town_id 
LEFT JOIN  towns t2         ON CONCAT(LEFT(p.town_id,4),'0000')   = t2.town_id
LEFT JOIN  towns t3         ON CONCAT(LEFT(p.town_id,2),'000000') = t3.town_id

WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
  #AND o.unit_reg IN ('12','13','14','15','16','19','20','34')
  AND i.ICD10_TM BETWEEN 'E10' AND 'E14'
  AND o.visit_id IN (SELECT visit_id FROM xray_requests)
  AND TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) <= '65'
  #AND o.VISIT_ID in (SELECT visit_id FROM mobile_visits)
GROUP BY o.visit_id

HAVING HbA1C != ''
   AND CAST(HbA1C AS DECIMAL(10,2)) >= 7 ";
       $rawData = \yii::$app->db4->createCommand($sql)->queryAll();

       try {
           $rawData = \Yii::$app->db4->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('exportdm2', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
   ######################################################################################################################
	public function actionExportntip()
    {
          $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
		
        $sql = "SELECT DISTINCT
      'ผู้ป่วยโรคไตเรื้อรัง**' as RISK_TYPE,
CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			TIMESTAMPDIFF(YEAR, p.birthdate, o.reg_datetime) AS AGE_Y,
            TIMESTAMPDIFF(MONTH, p.birthdate, o.reg_datetime) % 12 AS AGE_M,
			p.CID,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		 LEFT(p.home_adr,7) as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN 'ไทย'
			 WHEN p.NATN_ID <> 99 THEN 'ไม่ใช่คนไทย'
			END as PEOPLE_TYPE,
			CASE
			WHEN n.NATN_ID = '44' THEN '6=จีน'
			WHEN n.NATN_ID = '45' THEN '7=อินเดีย'
			WHEN n.NATN_ID = '46' THEN '4=เวียดนาม'
			WHEN n.NATN_ID = '48' THEN '1=พม่า'
			WHEN n.NATN_ID = '50' THEN '5=มาเลเซีย'
			WHEN n.NATN_ID = '52' THEN '8=ปากีสถาน'
			WHEN n.NATN_ID = '56' THEN '3=ลาว'
			WHEN n.NATN_ID = '57' THEN '2=กัมพูชา'
			WHEN n.NATN_ID = '98' THEN '9=อื่นๆ'
			WHEN n.NATN_ID = '99' THEN '0=ไทย'
			END AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			 'ไม่สงสัยวัณโรค (คะแนน <3)'  AS SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,			
			'Normal' AS CXR_RESULT,
			'No Cavity' AS CXR_ABNORMAL_RESULT,
			'Normal' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN 'สิทธิว่าง'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN 'ต่างด้าว'
			WHEN e.inscl IN ('03','04' )THEN 'สิทธิหลักประกันสุขภาพถ้วนหน้า'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN 'สิทธิประกันสังคม'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN 'ข้าราชการ/รัฐวิสาหกิจ'
			WHEN e.inscl IN ('06' )THEN 'จ่ายเอง'
			END AS INSCL_ID,
			'N189' as ICD10,
			'' as TB_CID_INDEX,
			''AS HbA1C,
		#	MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1cx,
      '' AS IMMUNNO_DISEASE,
			'' AS B24

			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  #AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
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
       return $this->render('exportntip', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
    public function actionIndex()
{
    $data = Yii::$app->request->post();
    $date1 = isset($data['date1']) && !empty($data['date1']) 
        ? date('Y-m-d 00:01', strtotime($data['date1'])) 
        : date('Y-m-d 00:01');
    $date2 = isset($data['date2']) && !empty($data['date2']) 
        ? date('Y-m-d 23:59', strtotime($data['date2'])) 
        : date('Y-m-d 23:59');
    
    $sql = "SELECT DISTINCT
        DATE_FORMAT(o.REG_DATETIME,'%d-%m-%Y %H:%i:%s') AS regdate,
        o.visit_id as visit_id,
        o.REG_DATETIME as regdate,
        o.HN as hn,
        p.cid,
        concat(trim(p.fname),' ',p.lname) as 'fullname',
        TIMESTAMPDIFF(year,p.birthdate, o.reg_datetime) as age,
        u.unit_name,
        i.icd10_tm as Diag,
        ROUND((o.WEIGHT / ((o.height / 100) * (o.height / 100))), 2) as BMI,
        MAX(CASE WHEN ll.lab_id = '123' THEN ll.lab_result ELSE '' END) AS HbA1c,
        MAX(CASE WHEN ll.lab_id in ('086','088') THEN ll.lab_result ELSE '' END) AS AFB,
        h.hosp_name,
        trim(t.TOWN_NAME) as 'บ้าน',
        trim(t1.TOWN_NAME) as 'ตำบล'
        FROM opd_visits o 
        INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
        INNER JOIN population p ON p.CID=c.CID
        LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
        LEFT JOIN icd10new i on i.icd10= dx.icd10
        LEFT JOIN service_units u ON u.unit_id = o.unit_reg
        LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0 AND ll.lab_id in ('123','086','088')
        LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
        LEFT JOIN towns t on p.town_id = t.town_id
        LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
        LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
        LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
        LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
        WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND o.unit_reg in ('12','13','14','15','16','19','20','34')
        AND o.visit_id in (SELECT visit_id FROM xray_requests)
        GROUP BY o.visit_id";

    // ✅ แก้ไข: ลบ query ซ้ำออก และแก้ไข exception class
    try {
        $rawData = \Yii::$app->db7->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ServerErrorHttpException('SQL error: ' . $e->getMessage());
    }

    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => FALSE,
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
        'sql'   => $sql,
        'date1' => $date1,
        'date2' => $date2,
    ]);
}
   public function actionIndex2()
    {
         $data = Yii::$app->request->post();

// ถ้าไม่มีการส่งค่ามา (isset เป็น false) ให้ใช้ค่าวันที่ปัจจุบันแทน
$date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : date('Y-m-d 00:01');
$date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : date('Y-m-d 23:59');
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
    public function actionExport()
    {
         $data = Yii::$app->request->post();
		 $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
        $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';
		#$date1 = $date01 . ' 00:01'; 
		#$date2 = $date02 . ' 23:59'; 
        $sql = "SELECT DISTINCT
      '' as RISK_TYPE,
CASE
            WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
                #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
                ELSE 'นาง' END as TITLE_ID,
			trim(p.fname) as FNAME,
			trim(p.lname) as LNAME,
			p.CID,
			CASE 
			WHEN p.sex= 1 THEN 'M'
			WHEN p.sex= 2 THEN 'F'
			END AS GENDER,
     DATE_FORMAT(p.birthdate, '%Y%m%d') AS BORN,
		 p.home_adr as ADDR,
     right(p.TOWN_ID,2) AS MU,
		 t3.TOWN_NAME AS PROVINCE_ID,
		 t2.TOWN_NAME AS AMPHUR_ID,
	   trim(t1.TOWN_NAME) AS TAMBOL_ID,
     CASE
      WHEN p.NATN_ID = 99 THEN '01'
			 WHEN p.NATN_ID <> 99 THEN '02'
			END as PEOPLE_TYPE,
			n.NATN_ID AS RACE_ID,
      DATE_FORMAT(o.REG_DATETIME,'%Y%m%d ') AS CONTACT_DATE,
			'' as SYMPTOM_SCREEN,
			DATE_FORMAT(x.XREQ_DATETIME,'%Y%m%d ') AS CXR_DATE,
			CASE
			WHEN x.XRPT_RESULT = 'Normal'  THEN 'Normal'
			WHEN x.XRPT_RESULT <> 'Normal'  THEN 'Abnormal'
      END AS CXR_RESULT,
			x.XRPT_RESULT AS CXR_ABNORMAL_RESULT,
			'' AS DX,
			o.HN,
			'10953' as HMAIN_ID,
			CASE
			WHEN e.inscl = '00' THEN '5'
			WHEN e.inscl IN ('05','16','28','52','53' )THEN '6'
			WHEN e.inscl IN ('03','04' )THEN '56'
			WHEN e.inscl IN ('08','09','30','31','63','64','60') THEN '1'
			WHEN e.inscl IN ('01','25','11','12','14','35','36' )THEN '3'
			WHEN e.inscl IN ('06' )THEN '66'
			END AS INSCL_ID,
			GROUP_CONCAT(DISTINCT i.ICD10_TM) as ICD10,
			''AS HbA1C,
            '' AS IMMUNNO_DISEASE,
			'' AS B24

			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10  AND left(i.icd10_tm,1) NOT IN ('Z','R','M')
			LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			LEFT JOIN lab_requests ll ON ll.visit_id = o.visit_id AND ll.is_cancel = 0  AND ll.lab_id in ('123', '011','086','087')
			LEFT JOIN xray_requests x ON x.visit_id = o.visit_id
			LEFT JOIN nations n ON n.NATN_ID = p.NATN_ID
			LEFT JOIN towns t on p.town_id = t.town_id
			LEFT JOIN hospitals h ON h.hosp_id = t.hospsub
			LEFT JOIN main_inscls e on e.INSCL = o.INSCL
			LEFT JOIN towns t1 on CONCAT(LEFT(p.town_id,6),'00')=t1.town_id 
			LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000')= t2.town_id
			LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000')=t3.town_id
			WHERE o.REG_DATETIME BETWEEN '2025-05-01 00:01' AND '2025-05-05 23:59'
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
       return $this->render('export', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
    public function actionVip()
    {
          $data = Yii::$app->request->post();

		$date1 = isset($data['date1']) && !empty($data['date1']) 
			? date('Y-m-d 00:01', strtotime($data['date1'])) 
			: date('Y-m-d 00:01');

		$date2 = isset($data['date2']) && !empty($data['date2']) 
			? date('Y-m-d 23:59', strtotime($data['date2'])) 
			: date('Y-m-d 23:59');
        // SQL สำหรับรายวัน
    $sqlDaily = "SELECT DISTINCT
        DATE_FORMAT(o.REG_DATETIME,'%d-%m-%Y %H:%i:%s') AS regdate,
        o.visit_id as visit_id,
        o.HN as hn,
        p.cid,
        concat(trim(p.fname),' ',p.lname) as 'fullname',
        TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) as age,
		o.unit_reg,
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
 WHERE o.REG_DATETIME BETWEEN '$date1' AND '$date2'
# WHERE o.REG_DATETIME BETWEEN '2024-01-01' AND '2024-12-31'
AND o.unit_reg IN ('74')
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
	
}
