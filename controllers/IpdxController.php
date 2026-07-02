<?php

namespace app\controllers;

use yii;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class IpdxController extends \yii\web\Controller
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
                'only'=> ['index','admit','create','update','view','delete'],
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
                        'actions'=>['admit','create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['admit','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','admit','create','update','view'],
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
    public function actionAdmit() {
        $sql = "SELECT 
      CASE 
      WHEN a.WARD_NO = 22 THEN 'LR' 
      WHEN a.WARD_NO = 38 THEN 'Ward 2'
      WHEN a.WARD_NO = 39 THEN 'Ward 1' 
      ELSE 'DayCare' 
      END as 'WARD' 
      ,o.HN
      ,CONCAT(trim(p.fname),' ',p.lname) as 'NAME'
      , a.ADM_ID 
      ,a.ADM_DT as ADMITT, a.DSC_DT AS DSC, a.P_DIAG, 
      CASE 
      WHEN a.IS_CANCEL=0 THEN 'admit' 
      WHEN a.IS_CANCEL=1 THEN 'ยกเลิก admit แล้ว' 
      ELSE 'ผิดพลาด' 
      END as 'STATUS-Admit', 
      CASE 
      WHEN p.MARRIAGE = 4 THEN 'พระภิกษุ' 
      ELSE 'ประชาชน' 
      END as 'สมณะ', a.BED_NO as BED
     FROM ipd_reg a LEFT JOIN opd_visits o ON a.VISIT_ID=o.VISIT_ID
		LEFT JOIN cid_hn c on c.HN=o.HN
		LEFT JOIN population p ON c.CID=p.CID
		ORDER BY a.ADM_ID DESC
		LIMIT 30";
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
        
        $dataProvider->sort->attributes['ADM_ID'] = [
                'asc' => ['ADM_ID' => SORT_ASC],
               'desc'=>['ADM_ID' => SORT_DESC],
                //     //'label' => 'วันมารับบริการ'
                 ];
         $dataProvider->sort->attributes['ADMITT'] = [
                'asc' => ['ADMITT' => SORT_ASC],
                'desc'=>['ADMITT' => SORT_DESC],
                    //     //'label' => 'วันมารับบริการ'
                   ];
				   
        return $this->render('admit',[
            'dataProvider' => $dataProvider,
            'sql'=>$sql,
            
        ]);
    }
}
