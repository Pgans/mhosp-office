<?php

namespace app\controllers;


use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

//include Yii::getAlias('@common').'/config/thai_date.php';
class ReadmitController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	#################### หารายที่ยังไม่สิ้นสุดบริการ #########################################################
	public function actionFinish(){
	   $date1x = Yii::$app->request->get('date1', date('Y-m-d'));
	   $date2x = Yii::$app->request->get('date2', date('Y-m-d'));

	   $date1 = date('Y-m-d 00:01', strtotime($date1x));
	   $date2 = date('Y-m-d 23:59', strtotime($date2x));
			
        $sql = "
			SELECT a.HN,a.REG_DATETIME, CONCAT(trim(c.FNAME),'  ' ,trim(c.LNAME)) as 'ชื่อ สกุล', 
j.ICD10_TM,GROUP_CONCAT(DISTINCT trim(i9.NICKNAME)) as 'หัตถการ',
(k.cg01+k.cg01_1+k.cg01_2+k.cg02+k.cg03+k.cg04+k.cg05+k.cg06+k.cg07+k.cg08+k.cg09+k.cg10+k.cg11+k.cg12+k.cg13+k.cg14+k.cg15+k.cg16+k.cg17+k.cg18+k.cg19) as 'แจ้งหนี้',
s.UNIT_NAME as 'แผนกลงทะเบียน',ss.UNIT_NAME as 'finish',
CASE
    WHEN a.INSCL IN ('18', '19') THEN f.INSCL_NAME
    WHEN a.INSCL IN ('03', '04') AND g.HOSPMAIN IS NULL THEN 'สิทธิ์ผิดพลาด โปรดตรวจสอบ'
    WHEN g.HOSPMAIN != '' AND g.UC_TYPE = '74' THEN CONCAT(f.INSCL_NAME, ' (ผู้พิการ รักษาฟรีทุกที่)')
    WHEN g.HOSPMAIN = '10953' THEN CONCAT(f.INSCL_NAME, ' (ใน CUP ม่วงสามสิบ)')
    WHEN g.HOSPMAIN IN (SELECT hosp3400.HOSP_ID FROM hosp3400) THEN CONCAT(f.INSCL_NAME, ' (นอก CUP ในจังหวัดอุบลฯ)')
    WHEN g.HOSPMAIN NOT IN (SELECT hosp3400.HOSP_ID FROM hosp3400) THEN CONCAT(f.INSCL_NAME, ' (นอก CUP นอกจังหวัดอุบลฯ)')
    ELSE f.INSCL_NAME
END AS `INSCL_claim`,

CASE
 #WHEN oo.OP_BEGIN !='0000-00-00 00:00:00' then CONCAT(TIMEDIFF(oo.OP_END,oo.OP_BEGIN),'--A') 
 WHEN a.FINISH_DATETIME ='0000-00-00 00:00:00' then 'ไม่สิ้นสุดบริการ/ไม่บันทึกเวลาทำหัตถการ'
 WHEN oo.OP_BEGIN !='0000-00-00 00:00:00' then CONCAT(TIMEDIFF(a.FINISH_DATETIME,a.REG_DATETIME),'--B')
ELSE 'xxx'
END as 'ระยะเวลารอคอย'
FROM opd_visits a LEFT JOIN cid_hn b on a.HN = b.HN
LEFT JOIN population c on b.CID = c.CID
LEFT JOIN main_inscls f ON a.INSCL=f.INSCL
LEFT JOIN uc_inscl g ON c.CID= g.CID AND (g.date_abort > date(a.REG_DATETIME) OR DAY(g.DATE_ABORT)=0)  and trim(g.hospmain) <>''
LEFT JOIN hosp_sss h ON c.CID=h.CID AND (h.date_abort > date(a.REG_DATETIME) OR DAY(h.DATE_ABORT) = 0 )and trim(h.HOSP_ID) <>'' 
LEFT JOIN opd_diagnosis i ON a.VISIT_ID=i.VISIT_ID AND i.IS_CANCEL = 0 AND i.DXT_ID = '1'
LEFT JOIN icd10new j ON i.ICD10=j.ICD10
LEFT JOIN cost_visits k on k.visit_id=a.VISIT_ID
LEFT JOIN receipts r on a.VISIT_ID=r.VISIT_ID AND r.IS_CANCEL !=1
LEFT JOIN authen_kiosk ak ON c.cid=ak.cid AND ak.visit_id = a.visit_id
LEFT JOIN service_units s on s.UNIT_ID=a.UNIT_REG
LEFT JOIN ipd_reg ip on ip.VISIT_ID=a.VISIT_ID AND ip.IS_CANCEL = 0
LEFT JOIN service_units ss on ss.UNIT_ID=a.UNIT_ID
LEFT JOIN mobile_visits m ON m.visit_id=a.visit_id
LEFT JOIN opd_operations oo on oo.VISIT_ID=a.VISIT_ID
LEFT JOIN icd9cm i9 on i9.ICD9=oo.icd9
WHERE a.IS_CANCEL = 0 
AND a.REG_DATETIME BETWEEN '$date1' AND '$date2'
AND a.UNIT_REG = 53
GROUP BY a.VISIT_ID
ORDER BY a.REG_DATETIME
              ";
        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('finish', [
					'searchModel'=>$searchModel,
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
				  'date1' => $date1,
		          'date2' => $date2,
                    ]);   
   }
   ###############################################################################################################
	public function actionReadmit(){
		$data = Yii::$app->request->post();
		$date1 = isset($data['date1']) ? $data['date1'] : '';
		$date2 = isset($data['date2']) ? $data['date2'] : '';
		
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
			WHERE ip1.adm_dt BETWEEN '$date1' AND '$date2'
			#AND ((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24))) <=28
			#AND (date(o2.REG_DATETIME)- (date(o1.REG_DATETIME))) <=28
			AND timestampdiff(day,ip1.adm_dt,ip2.adm_dt) <=28
			AND ip2.visit_id > ip1.visit_id  
			AND i1.icd10_tm = i2.icd10_tm
			AND ip1.is_cancel = 0
			GROUP BY c.hn
			HAVING count(c.hn)>1     
         ";
        $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('readmit', [
					'searchModel'=>$searchModel,
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                    ]);   
   }
   public function actionRevisit(){
		$data = Yii::$app->request->post();
		$date1 = isset($data['date1']) ? $data['date1'] : '';
		$date2 = isset($data['date2']) ? $data['date2'] : '';
		
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
			WHERE o1.REG_DATETIME between'$date1' AND  '$date2'
			AND o2.visit_id > o1.visit_id
			AND i1.icd10_tm = i2.icd10_tm  AND left(i1.icd10_tm,1) not in ('Z','U')
			AND (((to_days(o2.REG_DATETIME)*24)- ((to_days(o1.REG_DATETIME)*24)) + (( time_to_sec(o2.REG_DATETIME))/3600)) - (( time_to_sec(o1.REG_DATETIME))/3600)) between 0.001 and 48 
			group by c.hn
			having count(c.hn)>1
              ";
        $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('revisit', [
					'searchModel'=>$searchModel,
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                    ]);   
   }
    public function actionUnplan(){
		$data = Yii::$app->request->post();
		$date1 = isset($data['date1']) ? $data['date1'] : '';
		$date2 = isset($data['date2']) ? $data['date2'] : '';
		
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
WHERE r.RF_DT BETWEEN '$date1' AND '$date2' 
AND ((to_days(r.RF_DT)*24)- (to_days(i.ADM_DT)*24))/24 = '0' AND abs((time_to_sec(r.RF_DT)/3600) - (time_to_sec(i.ADM_DT)/3600)) <= '1.0'
              ";
        $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('unplanrefer', [
					'searchModel'=>$searchModel,
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                    ]);   
   }
     public function actionReferopd(){
		$data = Yii::$app->request->post();
		$date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
        $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';
		
        $sql = "
			SELECT op.VISIT_ID ,op.HN,
 op.REG_DATETIME as REGDATE,
 r.RF_DT ,
  abs((time_to_sec(op.REG_DATETIME)/3600) - (time_to_sec(r.RF_DT)/3600)) as Times, 
  u.unit_name,
 ic.ICD10_TM  as PostRefer,
 r.HOSP_ID,
 h.hosp_name,
 r.transport,
 CASE
WHEN r.transport = '2' THEN 'รถโรงพยาบาล พยาบาลนำส่ง 1 คน'
WHEN r.transport = '3' THEN 'รถโรงพยาบาล พยาบาลนำส่ง 2 คน'
WHEN r.transport = '4' THEN 'รถโรงพยาบาล พยาบาลนำส่ง 3 คน'
WHEN r.transport = '5' THEN 'รถโรงพยาบาล ไม่มีพยาบาลนำส่ง '
WHEN r.transport = '6' THEN 'รถโรงพยาบาลเอกชน'
WHEN r.transport = '1' THEN 'เดินทางไปเอง'
END AS 'การนำส่ง'
FROM refers r 
LEFT  JOIN opd_visits op ON op.visit_id = r.visit_id AND op.is_cancel = 0  AND r.IS_CANCEL = 0 AND r.rf_type = 2
LEFT  JOIN ipd_reg e on e.VISIT_ID = r.VISIT_ID  AND e.is_cancel = 0
LEFT JOIN opd_diagnosis o ON op.VISIT_ID = o.VISIT_ID AND o.IS_CANCEL = 0 AND o.DXT_ID = 1 
LEFT JOIN icd10new ic ON o.ICD10 = ic.ICD10
LEFT JOIN hospitals h ON h.hosp_id = r.hosp_id  
LEFT JOIN service_units u ON u.unit_id = op.unit_reg
WHERE r.RF_DT BETWEEN '$date1' AND '$date2' 
AND r.transport <> '1'  AND op.unit_reg = '11'
AND op.visit_id NOT IN (SELECT visit_id FROM ipd_reg)
AND ((to_days(op.REG_DATETIME)*24)- (to_days(r.RF_DT)*24))/24 = '0' AND abs((time_to_sec(op.REG_DATETIME)/3600) - (time_to_sec(r.RF_DT)/3600)) >= '2.00'

              ";
        $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
     $sql2 = "
			SELECT 
    MONTHNAME(op.REG_DATETIME) AS EnglishMonth,
    CASE MONTH(op.REG_DATETIME)
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
    YEAR(op.REG_DATETIME) + 543 AS ปี,
    COUNT(CASE WHEN r.transport <> '1' THEN op.VISIT_ID END) AS 'จำนวนครั้งโดยรถโรงพยาบาล',
    COUNT(CASE WHEN r.transport = '1' THEN op.VISIT_ID END) AS 'ไปเอง',
    COUNT(op.VISIT_ID) AS 'จำนวนทั้งหมด',
    COUNT(CASE WHEN ABS((TIME_TO_SEC(op.REG_DATETIME)/3600) - (TIME_TO_SEC(r.RF_DT)/3600)) >= 2 THEN op.VISIT_ID END) AS 'จำนวนที่ใช้เวลามากกว่า 2 ชั่วโมง',
    COUNT(CASE WHEN r.transport = '1' AND ABS((TIME_TO_SEC(op.REG_DATETIME)/3600) - (TIME_TO_SEC(r.RF_DT)/3600)) >= 2 THEN op.VISIT_ID END) AS 'ไปเอง (มากกว่า 2 ชั่วโมง)',
    COUNT(CASE WHEN r.transport <> '1' AND ABS((TIME_TO_SEC(op.REG_DATETIME)/3600) - (TIME_TO_SEC(r.RF_DT)/3600)) >= 2 THEN op.VISIT_ID END) AS 'โดยรถโรงพยาบาล (มากกว่า 2 ชั่วโมง)'
FROM refers r 
LEFT JOIN opd_visits op 
    ON op.visit_id = r.visit_id 
    AND op.is_cancel = 0  
    AND r.IS_CANCEL = 0 
    AND r.rf_type = 2
LEFT JOIN ipd_reg e 
    ON e.VISIT_ID = r.VISIT_ID  
    AND e.is_cancel = 0
LEFT JOIN opd_diagnosis o 
    ON op.VISIT_ID = o.VISIT_ID 
    AND o.IS_CANCEL = 0 
    AND o.DXT_ID = 1 
LEFT JOIN icd10new ic 
    ON o.ICD10 = ic.ICD10
LEFT JOIN hospitals h 
    ON h.hosp_id = r.hosp_id  
LEFT JOIN service_units u 
    ON u.unit_id = op.unit_reg
WHERE r.RF_DT BETWEEN '$date1' AND '$date2'
  AND op.unit_reg = '11'
  AND op.visit_id NOT IN (SELECT visit_id FROM ipd_reg)
  AND ((TO_DAYS(op.REG_DATETIME)*24) - (TO_DAYS(r.RF_DT)*24))/24 = '0'
GROUP BY MONTH(op.REG_DATETIME), YEAR(op.REG_DATETIME)
ORDER BY YEAR(op.REG_DATETIME), MONTH(op.REG_DATETIME);


              ";
        $rawData = \yii::$app->db14->createCommand($sql2)->queryAll();
       try {
           $rawData = \Yii::$app->db14->createCommand($sql2)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $monthProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
       return $this->render('referopd', [
					//'searchModel'=>$searchModel,
                   'dataProvider' => $dataProvider,
				   'monthProvider' => $monthProvider,
                   'sql'=>$sql,
                    ]);   
   }
}
