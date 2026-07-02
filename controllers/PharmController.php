<?php

namespace app\controllers;
use yii;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class PharmController extends \yii\web\Controller
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
                'only'=> ['index','allergy','create','update','view','a15er'],
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
                        'actions'=>['create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','allergy','create','update','view'],
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
    public function actionAllergy(){
        $data = Yii::$app->request->post();
        $date1 = isset($data['date1']) ? $data['date1'] : '';
        $date2 = isset($data['date2']) ? $data['date2'] : '';

        $sql = "SELECT DISTINCT h.HN,c.FNAME , c.LNAME ,a.UPD_DT AS ALLERGY_DATE,
CASE 
WHEN c.SEX = 1 THEN 'ชาย'
WHEN c.SEX = 2 THEN 'หญิง'
END AS SEX ,c.BIRTHDATE,FLOOR(DATEDIFF(NOW(),c.BIRTHDATE)/365.25)AS AGE,
c.HOME_ADR,c.TOWN_ID, b.DRUG_NAME ,
CASE
WHEN a.level = 1 THEN 'Unlikely'
WHEN a.level = 2 THEN 'Possible'
WHEN a.level = 3 THEN 'Probable'
WHEN a.level = 4 THEN 'Certain'
END AS LEVEL, a.ALLERGY_NOTE
FROM cid_drug_allergy a, drugs b, population c, cid_hn h
WHERE a.DRUG_ID = b.DRUG_ID
AND a.IS_CANCEL = 0
AND a.CID = c.CID
AND c.cid = h.cid";

      $rawData = \yii::$app->db2->createCommand($sql)->queryAll();

      // print_r($rawData);
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
        'pagesize'=> 15
     ],
       ]);
       Yii::$app->session['date1'] = $date1;
       Yii::$app->session['date2'] = $date2;
       return $this->render('allergy', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1' =>$date1,
                   'date2' =>$date2,

       ]); 
   }
}
