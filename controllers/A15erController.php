<?php

namespace app\controllers;
use yii;
use app\models\LogA15er;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class A15erController extends \yii\web\Controller
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
                'only'=> ['index','admit','create','update','view','a15er'],
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
                        'actions'=>['a15er','create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['a15er','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','a15er','create','update','view'],
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
	public function actionA15er() {
		$data = Yii::$app->request->post();
        $date1 =isset($data['date1'])  ? $data['date1'] : '';
        $date2 =isset($data['date2'])  ? $data['date2'] : '';
       
	    $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogA15er();
			$log->name = 'a15er'; // เพิ่มบรรทัดนี้เพื่อระบุค่า name
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
		
        $sql = "SELECT DISTINCT
			o.visit_id as visit_id,
			date(o.REG_DATETIME) as regdate,
			o.HN as hn,
			concat(trim(p.fname),' ',p.lname) as 'fullname',
			TIMESTAMPDIFF(year,p.birthdate, o .reg_datetime ) as age,
			CASE
			WHEN p.sex =1 THEN 'ชาย'
			WHEN p.sex =2 THEN 'หญิง'
			END as sex,
			s.unit_name,
			i.icd10_tm,
			i.icd_name
			FROM opd_visits o 
			INNER JOIN cid_hn c ON o.HN = c.HN AND o.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis dx on dx.visit_id = o.visit_id AND dx.is_cancel=0
			LEFT  JOIN icd10new i on i.icd10= dx.icd10
			LEFT JOIN ipd_reg l ON o.visit_id = l.visit_id AND l.is_cancel = 0
			INNER JOIN service_units s ON o.unit_reg = s.unit_id
			WHERE o.REG_DATETIME BETWEEN '$date1'  AND '$date2'
			AND i.icd10_tm BETWEEN 'A150' AND 'A182'
			AND o.unit_reg = '11'
			GROUP BY o.visit_id 
		";
         $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
          //print_r($rawData);
          try {
              $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
          } catch (\yii\db\Exception $e) {
              throw new \yii\web\ConflictHttpException('sql error');
          }
          $dataProvider = new \yii\data\ArrayDataProvider([
              'allModels' => $rawData,
              'pagination' => FALSE,
          ]);
         $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_a15er v  where v.name = 'a15er'
			";
        
         $data = \yii::$app->db->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
          return $this->render('a15',[
              'dataProvider' => $dataProvider,
              'sql'=>$sql,
			  'date1'=>$date1,
			  'date2'=>$date2,
			  'amount'=>$amount, 
          ]);
      }
	  ################ รายงานครองเตียง ########################################
	public function actionSharing() {
    $data = Yii::$app->request->post();
    
    // Default = วันปัจจุบัน
    $date1 = isset($data['date1']) && $data['date1'] != '' ? $data['date1'] : date('Y-m-d');
    $date2 = isset($data['date2']) && $data['date2'] != '' ? $data['date2'] : date('Y-m-d');
    $range = isset($data['range']) ? $data['range'] : 'today';

    // ปรับช่วงวันตาม shortcut
    if (isset($data['range'])) {
        switch ($data['range']) {
            case 'today':
                $date1 = date('Y-m-d');
                $date2 = date('Y-m-d');
                break;
            case '7days':
                $date1 = date('Y-m-d', strtotime('-6 days'));
                $date2 = date('Y-m-d');
                break;
            case '1month':
                $date1 = date('Y-m-d', strtotime('-1 month'));
                $date2 = date('Y-m-d');
                break;
        }
    }

    if (\Yii::$app->request->isPost) {
        $log = new LogA15er();
        $log->name = 'sharing';
        $log->username = \Yii::$app->user->identity->username;
        $log->patient_cid = $date1;
        $log->datetime = date('Y-m-d H:i:s');
        $log->ip = \Yii::$app->request->getUserIP();
        $log->save();
    }

    $sql = "SELECT s.date_serv, s.lr, s.ward1, s.ward2, s.ward3, s.ward4, s.ward5, s.total, s.d_update
        FROM sharings s 
        WHERE s.d_update BETWEEN '$date1' AND '$date2 23:59:59'
        GROUP BY s.date_serv
        ORDER BY s.date_serv DESC";

    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }

    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => FALSE,
    ]);

    $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount FROM log_a15er v WHERE name = 'sharing'";
    $countData = \Yii::$app->db->createCommand($sqlCount1)->queryAll();
    $amount = $countData[0]['amount'] ?? 0;

    return $this->render('sharing', [
        'dataProvider' => $dataProvider,
        'sql'   => $sql,
        'date1' => $date1,
        'date2' => $date2,
        'range' => $range,
        'amount'=> $amount,
    ]);
}
  }



