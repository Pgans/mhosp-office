<?php

namespace app\controllers;

use Yii;
use app\models\Jobservice;
use app\models\JobserviceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่
/**
 * JobserviceController implements the CRUD actions for Jobservice model.
 */
class JobserviceController extends Controller
{
    /**
     * {@inheritdoc}
     */
	 /*
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
*/
/*
 public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=> ['index','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
					 [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['index'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    
					[
                        'actions'=>['admin','index','create','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN

                        ]
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=> true,
                        'roles'=>[
                           // User::ROLE_EMPLOYEE,
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
	
    /**
     * Lists all Jobservice models.
     * @return mixed
     */
	
	 #########################################################################################
	 public function sendLine($model)  {
	 // กำหนดแผนกโดยใช้ switch-case
$departmentsList = [
    '1' => 'ผู้ป่วยนอก', '2' => 'อุบัติเหตุฉุกเฉิน', '3' => 'เภสัชกรรม',
    '4' => 'บริหารงานทั่วไป', '5' => 'งานเวชสถิติข้อมูล', '6' => 'ห้องคลอด',
    '7' => 'กลุ่มการพยาบาล', '8' => 'คลินิกพิเศษ', '9' => 'งานไต',
    '10' => 'งานรังสีวิทยา', '11' => 'เทคนิคการแพทย์', '12' => 'งานทันตกรรม',
    '13' => 'งานผู้ป่วยใน1', '14' => 'งานผู้ป่วยใน2', '15' => 'หน่วยจ่ายกลาง',
    '16' => 'งานเวชปฏิบัติ', '17' => 'ศูนย์สุขภาพชุมชนม่วง', '18' => 'งานแพทย์แผนไทย',
    '19' => 'สิทธิบัตร', '20' => 'งานวัณโรค', '21' => 'งานยาเสพติด',
    '22' => 'งานให้คำปรึกษา', '23' => 'ตรวจสุขภาพVIP', '24' => 'ห้องกอบสุข',
    '25' => 'ศูนย์คอมพิวเตอร์', '26' => 'โสตทัศนศึกษา', '27' => 'กายภาพบำบัด',
    '28' => 'งานโภชนาการ', '29' => 'งานซักฟอก', '30' => 'งานพัสดุและซ่อมบำรุง',
    '31' => 'งานการเงินการบัญชี', '32' => 'งานยุทธศาสตร์', '33' => 'องค์กรแพทย์',
    '34' => 'งานยานพาหนะ', '35' => 'งานผู้ป่วยใน4'
];

$departments = isset($departmentsList[$model->dep_id]) ? $departmentsList[$model->dep_id] : 'ไม่ระบุ';

// กำหนด Token และ Chat ID ของ Telegram
$telegramToken = "7824960142:AAFtZTRlbOpjrJEuz04Z3fdmLgga_bTSUoM"; // ใส่ Token ของ Bot
$chatId = "-4621406983"; // ใส่ Chat ID ของกลุ่มซ่อมบำรุง

// ข้อความแจ้งเตือน
$message = "<b>📢 รายการแจ้งเตือน</b>\n".
           "📌 <b>รายละเอียด:</b> {$model->detail}\n".
           "👤 <b>ผู้แจ้ง:</b> {$model->send_by}\n".
           "🏥 <b>แผนก:</b> $departments\n".
           "📅 <b>วันที่:</b> {$model->dateline}";

// API Telegram URL
$telegramApi = "https://api.telegram.org/bot$telegramToken/sendMessage";

// ตั้งค่าการส่งข้อมูล
$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'HTML' // รองรับ HTML formatting
];

// ส่งข้อมูลผ่าน cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramApi);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
curl_close($ch);

// ตรวจสอบผลลัพธ์
$result = json_decode($server_output, true);
if (!$result['ok']) {
    Yii::error("Telegram API Error: " . $result['description'], 'app');
    return false;
}
	 }

	 /*
	 public function sendLine($model)  {
       
        //$line_token = 'XTvLuqnWGaQ7h2P4smFrxbekF1GJrSBEfLuU9NimrG3';//สิทธิบัตร
        $line_token = 'OrtVqN4trmpr2dJXD6226vOCRlDSZvSW3vIpqHpAsH4';//Line_Notify
	 $dep_id = $model->dep_id; 
		if ($dep_id == '1') {$departments = 'ผู้ป่วยนอก';
		} elseif ($dep_id == '2') { $departments = 'อุบัติเหตุฉุกเฉิน';
		} elseif ($dep_id == '3') { $departments = 'เภสัชกรรม ';
		} elseif ($dep_id == '4') { $departments = 'บริหารงานทั่วไป';
		} elseif ($dep_id == '5') { $departments = 'งานเวชสถิติข้อมูล';
		} elseif ($dep_id == '6') { $departments = 'ห้องคลอด';
		} elseif ($dep_id == '7') { $departments = 'กลุ่มการพยาบาล';
		} elseif ($dep_id == '8') { $departments = 'คลินิกพิเศษ';
		} elseif ($dep_id == '9') { $departments = 'งานไต';
		} elseif ($dep_id == '10') { $departments = 'งานรังสีวิทยา';
		} elseif ($dep_id == '11') { $departments = 'เทคนิคการแพทย์';
		} elseif ($dep_id == '12') { $departments = 'งานทันตกรรม';
		} elseif ($dep_id == '13') { $departments = 'งานผู้ป่วยใน1';
		} elseif ($dep_id == '14') { $departments = 'งานผู้ป่วยใน2';
		} elseif ($dep_id == '15') { $departments = 'หน่วยจ่ายกลาง';
		} elseif ($dep_id == '16') { $departments = 'งานเวชปฏิบัติ';
		} elseif ($dep_id == '17') { $departments = 'ศูนย์สุขภาพชุมชนม่วง';
		} elseif ($dep_id == '18') { $departments = 'งานแพทย์แผนไทย';
		} elseif ($dep_id == '19') { $departments = 'สิทธิบัตร';
		} elseif ($dep_id == '20') { $departments = 'งานวัณโรค';
		} elseif ($dep_id == '21') { $departments = 'งานยาเสพติด';
		} elseif ($dep_id == '22') { $departments = 'งานให้คำปรึกษา';
		} elseif ($dep_id == '23') { $departments = 'ตรวจสุขภาพVIP';
		} elseif ($dep_id == '24') { $departments = 'ห้องกอบสุข';
		} elseif ($dep_id == '25') { $departments = 'ศูนย์คอมพิวเตอร์';
		} elseif ($dep_id == '26') { $departments = 'โสตทัศนศึกษา';
		} elseif ($dep_id == '27') { $departments = 'กายภาพบำบัด';
		} elseif ($dep_id == '28') { $departments = 'งานโภชนาการ';
		} elseif ($dep_id == '29') { $departments = 'งานซักฟอก';
		} elseif ($dep_id == '30') { $departments = 'งานพัสดุและซ่อมบำรุง';
		} elseif ($dep_id == '31') { $departments = 'งานการเงินการบัญชี';
		} elseif ($dep_id == '32') { $departments = 'งานยุทธศาสตร์';
		} elseif ($dep_id == '33') { $departments = 'องค์กรแพทย์';
		} elseif ($dep_id == '34') { $departments = 'งานยานพาหนะ';
		} elseif ($dep_id == '35') { $departments = 'งานผู้ป่วยใน4';
		}
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->detail.'    '.'ผู้แจ้ง:'.$model->send_by.'    '.'แผนก:'.$departments.' '.'วันที่'.$model->dateline);
       //  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->cid.' '.$model->cmu.'สาหตุ'.$model->cdeath.' '.'วันตาย'.$model->ddeath);
      //  <!--if(!empty(Yii::$app->request->getFirstImage($model->request_text))) {
          //  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->fullname."imageThumbnail".Yii::$app->request->getFirstImage($model->request_text)."$imageFullsize=".Yii::$app->request->getFirstImage($model->request_text));
      // }else{
         //   curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->fullname);-->
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$line_token,
        ]);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
    }
	*/
#############################################################################################
    public function actionIndex()
    {
        $searchModel = new JobserviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
            
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionAdmin()
    {
        $searchModel = new JobserviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
            
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jobservice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Jobservice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jobservice();
		 if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('alert', [
                'body' =>'ระบบทำการแจ้งเข้ากลุ่มไลน์..... เรียบร้อยแล้วครับ',
                'options'=>['class'=>'alert-info'],
                  ]);
             $this->sendLine($model);//ส่งline notif

            return $this->redirect(['index', 'id' => $model->id]);
        }
		$model->dateline = date('Y-m-d');
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Jobservice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionUpdate($id)
{
    $model = $this->findModel($id);

    // --- ส่วนนี้คือการเช็ค Ajax Validation (ห้ามมีคำสั่ง $model->save() ที่นี่) ---
    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\widgets\ActiveForm::validate($model);
    }

    // --- ส่วนนี้คือการบันทึกข้อมูลเมื่อกดปุ่ม Submit จริงๆ เท่านั้น ---
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}

    /**
     * Deletes an existing Jobservice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Jobservice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jobservice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jobservice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
