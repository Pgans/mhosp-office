<?php

namespace app\controllers;

use yii;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class DhfController extends \yii\web\Controller
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
                'only'=> ['index','admit','create','update','view','lepto'],
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
                        'actions'=>['create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['lepto','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','lepto','create','update','view'],
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
        return $this->render('index');
    }
    public function actionLepto() {
       
        $sql = "SELECT DATE_FORMAT(a.REG_DATETIME,'%d-%m-%Y') as 'regdate', 
        CASE 
        WHEN k.ICD10_TM BETWEEN 'a270' AND 'a279' THEN 'Lepto' 
        WHEN k.ICD10_TM = 'a753' THEN 'Scrub Typhus' 
        WHEN k.ICD10_TM = 'a920' THEN 'Chikungunya' 
        WHEN k.ICD10_TM BETWEEN 'a90' AND 'a999' THEN 'DHF' 
        WHEN k.ICD10_TM = 't620' THEN 'Mushroom poisoning' 
        ELSE 'อื่นๆ' 
        end as 'diag', 
        TRIM(n.HOSP_NAME) as 'sub_main',a.HN as hn, 
        CONCAT(CASE 
        WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME) 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW())< '20' AND p.sex='1' AND p.MARRIAGE = '4'THEN 'สามเณร' 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '20' AND p.sex='1' AND p.MARRIAGE = '4'THEN 'พระภิกษุ' 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15' AND p.sex='1' THEN 'ด.ช.' 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย' 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15' AND p.sex='2' THEN 'ด.ญ.' 
        WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'น.ส.' 
        ELSE 'นาง' 
        END ,TRIM(p.FNAME), ' ',TRIM(p.LNAME), ' อายุ ',FLOOR(DATEDIFF(NOW(),p.BIRTHDATE)/365.25),' ปี') as 'fullname',
		TRIM(p.HOME_ADR) as 'home',
		TRIM(t.TOWN_NAME) as 'บ้าน',
		TRIM(tt.TOWN_NAME) as 'ตำบล',
        TRIM(ttt.TOWN_NAME) as 'อำเภอ',
		TRIM(tttt.TOWN_NAME) as 'จังหวัด',
        #CONCAT(TRIM(p.HOME_ADR), ' บ้าน',TRIM(t.TOWN_NAME), ' ต.',tt.TOWN_NAME,' อ.',ttt.TOWN_NAME,' จ.',tttt.TOWN_NAME) as 'ที่อยู่ตามบัตรประชาชน' ,
		p.CONTACT_ADR as 'ที่อยู่ญาติ', 
        CASE 
        WHEN l.WARD_NO = 22 THEN 'ห้องคลอด' 
        WHEN l.WARD_NO = 38 THEN 'WARD 2' 
        WHEN l.WARD_NO = 39 THEN 'WARD 1' 
        WHEN l.WARD_NO = 50 THEN 'Day Care' 
        ELSE '-OPD-' 
        END as สถานะรับบริการ, 
        CASE 
        WHEN ISNULL(l.ADM_DT) THEN 'ไม่ได้ admit' 
        ELSE l.ADM_DT 
        END as 'Admit' 
        , CASE 
        WHEN ISNULL(l.DSC_DT) THEN 'ไม่ได้ admit' 
        ELSE l.DSC_DT 
        END as 'Discharge', 
        CASE 
        WHEN a.visit_id in (SELECT refers.VISIT_ID FROM refers WHERE refers.IS_CANCEL=0 AND refers.RF_TYPE= 2) THEN 'ส่งต่อ' 
        ELSE 'ไม่ได้ส่งต่อ' 
        END as 'Refer' , 
        case 
        WHEN a.INSCL in (03,04) AND u.HOSPMAIN ='10953' THEN CONCAT(m.INSCL_NAME,' --ในเขต') 
        WHEN a.INSCL in (03,04) AND u.HOSPMAIN !='10953' THEN CONCAT(m.INSCL_NAME,' --นอกเขต') 
        ELSE m.INSCL_NAME 
        END as 'สิทธิ์การรักษา' 
        FROM opd_visits a LEFT JOIN cid_hn b on a.HN = b.HN 
        LEFT JOIN population p on b.CID = p.CID 
        LEFT JOIN service_units d on a.UNIT_ID=d.UNIT_ID 
        LEFT JOIN service_units f on a.UNIT_REG=f.UNIT_ID 
        LEFT JOIN opd_diagnosis h on a.VISIT_ID=h.VISIT_ID AND h.IS_CANCEL = 0 
        LEFT JOIN icd10new k on k.ICD10=h.ICD10 
        LEFT JOIN ipd_reg l ON a.VISIT_ID= l.VISIT_ID AND l.IS_CANCEL=0 
        INNER JOIN towns t on t.TOWN_ID = p.TOWN_ID 
        INNER JOIN towns tt on CONCAT(left(p.TOWN_ID,6),'00') = tt.TOWN_ID 
        INNER JOIN towns ttt on CONCAT(left(p.TOWN_ID,4),'0000') = ttt.TOWN_ID 
        INNER JOIN towns tttt on CONCAT(left(p.TOWN_ID,2),'000000') = tttt.TOWN_ID 
        LEFT JOIN hospitals n ON n.HOSP_ID=t.HOSPSUB 
        INNER JOIN main_inscls m ON m.INSCL=a.INSCL 
        LEFT JOIN uc_inscl u ON u.CID=p.CID AND (u.date_abort = date(a.REG_DATETIME) or day(u.date_abort)=0 and trim(u.hospmain) <>'' ) 
        WHERE a.IS_CANCEL = 0 
		AND a.REG_DATETIME BETWEEN SUBDATE(CURDATE() ,INTERVAL 15 DAY) AND CURDATE()
        #AND a.REG_DATETIME BETWEEN '2022-01-01' AND '2022-12-31 ' 
        and a.VISIT_ID not in (SELECT mobile_visits.VISIT_ID from mobile_visits WHERE mobile_visits.is_cancel = 0) 
        AND (k.ICD10_TM BETWEEN 'a270' AND 'a279' or k.ICD10_TM BETWEEN 'a90' AND 'a999' or k.ICD10_TM = 't620') 
        GROUP BY a.HN  ORDER BY a.REG_DATETIME  DESC";
         $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
          //print_r($rawData);
          try {
              $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
          } catch (\yii\db\Exception $e) {
              throw new \yii\web\ConflictHttpException('sql error');
          }
          $dhfdataProvider = new \yii\data\ArrayDataProvider([
              'allModels' => $rawData,
              'pagination' => FALSE,
          ]);
        
          return $this->render('dhf',[
              'dhfdataProvider' => $dhfdataProvider,
              'sql'=>$sql,
              
          ]);
      }
  }



