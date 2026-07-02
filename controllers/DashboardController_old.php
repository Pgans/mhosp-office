<?php

namespace app\controllers;

class DashboardController extends \yii\web\Controller
{
    //public function actionIndex()
    // {
    //     return $this->render('index');


    public function actionDashboard()
    {

        $sql = "SELECT  date_format(date_add(ak.d_update, INTERVAL 543 YEAR),'%d-%m-%Y') regdate, 
        COUNT(CASE WHEN ak.dep_name = 'ไตเทียม'  THEN '1'  END) AS 'ไตเทียม',
        COUNT(CASE WHEN ak.dep_name = 'กายภาพ'  THEN '2'  END) AS 'กายภาพ',
        COUNT(CASE WHEN ak.dep_name = 'COVID19 OP'  THEN '3'  END) AS 'ARI OP',
        COUNT(CASE WHEN ak.dep_name = 'COVID19 HI' THEN '4'  END) AS 'ARI HI'
        FROM authen_kiosk ak 
        WHERE ak.d_update >= CURDATE()-5
        GROUP BY date(ak.d_update)
        ORDER BY ak.d_update DESC  ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
        try {
            $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);
        ############################ EPIDEM VACCINE ###############################################
		
		$sql = "SELECT date(d_update) as send_date,COUNT(DISTINCT visit_id) amount
		FROM log_epidem 
		WHERE d_update BETWEEN CURDATE()-5 AND now()
		GROUP BY date(d_update)  ORDER BY d_update DESC
		";
		$rawData = \yii::$app->db2->createCommand($sql)->queryAll();
		try {
			$rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
		} catch (\yii\db\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$epidemProvider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawData,
		'pagination' => [
		'pageSize' => 8,
		],
		]);
        ###################### VACCINE ##################################################
        $sql = "SELECT (k.regdate),
        #COUNT(CASE WHEN  k.dose = 1   THEN '1'   END) AS 'dose1' ,
        #COUNT(CASE WHEN  k.dose = 2   THEN '20'   END) AS 'dose2', 
        #COUNT(CASE WHEN  k.dose = 3   THEN '30'   END) AS 'dose3', 
        COUNT(CASE WHEN k.drug_id = '2790' and k.dose = 1  THEN '1'  END) AS 'sinovac1',
        COUNT(CASE WHEN k.drug_id = '2790' and k.dose = 2  THEN '2'  END) AS 'sinovac2',
        COUNT(CASE WHEN k.drug_id = '2790' and k.dose = 3  THEN '3'  END) AS 'sinovac3',
        
        COUNT(CASE WHEN k.drug_id = '2813' and k.dose = 1  THEN '21'  END) AS 'astra1',
        COUNT(CASE WHEN k.drug_id = '2813' and k.dose = 2  THEN '22'  END) AS 'astra2',
        COUNT(CASE WHEN k.drug_id = '2813' and k.dose = 3  THEN '23'  END) AS 'astra3',
        
        COUNT(CASE WHEN k.drug_id = '2244' and k.dose = 1  THEN '31'  END) AS 'sinopharm1',
        COUNT(CASE WHEN k.drug_id = '2244' and k.dose = 2  THEN '32'  END) AS 'sinopharm2',
        COUNT(CASE WHEN k.drug_id = '2244' and k.dose = 3  THEN '33'  END) AS 'sinopharm3',
        
        COUNT(CASE WHEN k.drug_id = '2372' and k.dose = 1  THEN '41'  END) AS 'pfizer1',
        COUNT(CASE WHEN k.drug_id = '2372' and k.dose = 2  THEN '42'  END) AS 'pfizer2',
        COUNT(CASE WHEN k.drug_id = '2372' and k.dose = 3  THEN '43'  END) AS 'pfizer3',

        COUNT(CASE WHEN k.drug_id = '2043' and k.dose = 1  THEN '41'  END) AS 'pfizer21',
        COUNT(CASE WHEN k.drug_id = '2043' and k.dose = 2  THEN '42'  END) AS 'pfizer22',
        COUNT(CASE WHEN k.drug_id = '2043' and k.dose = 3  THEN '43'  END) AS 'pfizer23',

        COUNT(CASE WHEN k.drug_id = '2332' and k.dose = 1  THEN '44'  END) AS 'moderna1',
        COUNT(CASE WHEN k.drug_id = '2332' and k.dose = 2  THEN '45'  END) AS 'moderna2',
        COUNT(CASE WHEN k.drug_id = '2332' and k.dose = 3  THEN '46'  END) AS 'moderna3',
        COUNT(CASE WHEN k.drug_id in ('2790', '2813', '2043', '2244','2332','2372') THEN '' END) as 'total'
        FROM (
        SELECT 
        p.CID,
        date(a.REG_DATETIME) as 'regdate',c.hn,
        CONCAT(    CASE
        WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
            #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW())< '20' AND pv.sex='1' AND pv.MARRIAGE = '4'THEN 'สามเณร'
            #WHEN TIMESTAMPDIFF(year,pv.BIRTHDATE,NOW()) >= '20' AND pv.sex='1' AND pv.MARRIAGE  = '4'THEN 'พระภิกษุ'
            WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'ด.ช.'
            WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
            WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'ด.ญ.'
            WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.'
            ELSE 'นาง' END ,'',TRIM(p.fname),'  ',p.lname) as fullname,
        FLOOR(DATEDIFF(NOW(),p.BIRTHDATE)/365.25) as age,
        a.visit_id,
        CASE
        WHEN z.vaccine_id3 != '' THEN z.vac_datetime3
        WHEN z.vaccine_id3 = '' AND z.vaccine_id2 != '' THEN z.vac_datetime2
        ELSE z.vac_datetime1
        END as 'doseday',
        CASE
        WHEN z.vaccine_id3 != '' THEN '  3'
        WHEN z.vaccine_id3 = '' AND z.vaccine_id2 != '' THEN '2'
        ELSE  ' 1'
        END as dose, d.drug_name,d.drug_id,
        CASE
        WHEN z.vaccine_id3 != '' THEN z.vaccine_id3
        WHEN z.vaccine_id3 = '' AND z.vaccine_id2 != '' THEN z.vaccine_id2
        ELSE z.vaccine_id1
        END as 'box',
        CASE
        WHEN z.vaccine_id3 != '' THEN z.vac_bottle3
        WHEN z.vaccine_id3 = '' AND z.vaccine_id2 != '' THEN z.vac_bottle2
        ELSE z.vac_bottle1
        END as 'bottle',
        CASE
        WHEN z.vaccine_id3 != '' THEN z.vac_syringe3
        WHEN z.vaccine_id3 = '' AND z.vaccine_id2 != '' THEN z.vac_syringe2
        ELSE z.vac_syringe1
        END as 'syring',
        z.vac_plan_id,
        CASE 
        WHEN ae.dose_time = '1' and n_day = '0'  THEN date(ae.aefi_datetime) 
        WHEN ae.dose_time = '2' and n_day = '0'  THEN date(ae.aefi_datetime) 
        END as schedule_date
        FROM opd_visits a 
        INNER JOIN cid_hn c ON a.HN=c.HN AND a.IS_CANCEL=0
        INNER JOIN population p ON p.CID=c.CID
        LEFT JOIN prescriptions k ON k.VISIT_ID=a.VISIT_ID AND k.IS_CANCEL=0
        left JOIN drugs d ON d.DRUG_ID=k.DRUG_ID
        left JOIN usage_units u on u.UUNIT_ID=d.UUNIT_ID
        left JOIN routes r on r.ROUTE_ID=k.ROUTE_ID
        left JOIN frequency f ON f.FRQ_ID=k.FRQ_ID
        left JOIN opd_diagnosis o on o.visit_id=a.visit_id AND o.is_cancel=0
        left JOIN icd10new i on i.icd10=o.icd10
        LEFT JOIN cid_vaccinate z ON p.cid=z.cid

        LEFT JOIN vaccinate_aefi ae on ae.cid = z.cid and ae.is_cancel = 0 and z.is_cancel= 0
        LEFT JOIN vaccinate_schedule_plan  vp ON vp.vac_plan_id = z.vac_plan_id #and vp.vac_dose = 2
        WHERE date(a.REG_DATETIME) BETWEEN CURDATE()-7 AND NOW()
        AND i.icd10_tm in ('U119')
        AND d.drug_id IN ('2790','2813','2043','2244','2332','2372') 
        GROUP BY a.visit_id )as k
        GROUP BY regdate
        ORDER BY regdate DESC ";
        $rawVaccine = \yii::$app->db2->createCommand($sql)->queryAll();
        try {
            $rawVaccine = \Yii::$app->db2->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $vaccineProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawVaccine,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        ############################ SERVER  192.168.200.14 ###############################################

        $sql = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
    FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
    UNION
    SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
    FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2
    UNION
    SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
    FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
    UNION
    SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
    FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
    UNION
    SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
    FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
    UNION
    SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
    FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
     ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
        try {
            $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $data14Provider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);
        ############################ SERVER  192.168.200.70 ###############################################

        $sql1 = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
    FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
    UNION
    SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
    FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2
    UNION
    SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
    FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
    UNION
    SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
    FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
    UNION
    SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
    FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
    UNION
    SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
    FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
     ";
        $rawData70 = \yii::$app->db70->createCommand($sql1)->queryAll();
        try {
            $rawData = \Yii::$app->db70->createCommand($sql1)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $data70Provider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData70,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);
        ############################ SERVER  192.168.200.7 ###############################################

        $sql2 = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
   FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
   UNION
   SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
   FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2
   UNION
   SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
   FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
   UNION
   SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
   FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
   UNION
   SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
   FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
   UNION
   SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
   FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
    ";
        $rawData70 = \yii::$app->db7->createCommand($sql2)->queryAll();
        try {
            $rawData7 = \Yii::$app->db7->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $data7Provider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData7,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);

        $sqladmit = "SELECT date_format(date_add(i.ADM_DT, INTERVAL 543 YEAR),'%d-%m-%Y') admit_date,
  COUNT(CASE WHEN i.WARD_NO ='22' THEN '1' END) AS 'LR',
  COUNT(CASE WHEN i.WARD_NO ='38' THEN '2' END) AS 'Ward2',
  COUNT(CASE WHEN i.WARD_NO ='39' THEN '3' END) AS 'Ward1',
  COUNT(CASE WHEN i.WARD_NO ='50' THEN '4' END) AS 'Ward3',
  COUNT(CASE WHEN i.WARD_NO ='55' THEN '5' END) AS 'Ward4',
  COUNT(CASE WHEN i.WARD_NO ='57' THEN '6' END) AS 'HI',
  COUNT(CASE WHEN i.WARD_NO in ('22','38','39','50','55','57') THEN '7' END) AS 'TOTAL'
  FROM ipd_reg i
  INNER JOIN service_units u ON u.unit_id = i.ward_no 
  WHERE i.ADM_DT >= CURDATE()-5 AND i.IS_CANCEL = 0
  GROUP BY date(i.ADM_DT)
  ORDER BY i.adm_dt DESC ";
        $admit = \yii::$app->db2->createCommand($sqladmit)->queryAll();
        try {
            $admit = \yii::$app->db2->createCommand($sqladmit)->queryAll();
        } catch (\yii\db\Epception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $admitProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $admit,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);


        return $this->render('dashboard_m30', [
            // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data14Provider' => $data14Provider,
            'data70Provider' => $data70Provider,
            'data7Provider' => $data7Provider,
            'admitProvider' => $admitProvider,
            'vaccineProvider' => $vaccineProvider,
            'epidemProvider' => $epidemProvider,

        ]);
    }
}
