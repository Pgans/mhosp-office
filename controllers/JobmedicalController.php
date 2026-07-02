<?php

namespace app\controllers;

use Yii;
use app\models\Jobmedical;
use app\models\JobmedicalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

//* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

/**
 * JobcomController implements the CRUD actions for Jobcom model.
 */
class JobmedicalController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                'only'=> ['index','admin','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions' => ['index', 'view','create','update'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['index','create','view','update'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['index','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','index','create','update','view'],
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
	######################################################################
	public function sendLine($model)  {
	// กำหนด Token และ Chat ID ของ Telegram
$telegramToken = "7824960142:AAFtZTRlbOpjrJEuz04Z3fdmLgga_bTSUoM"; // Token ของ Pgans_Bot
$chatId = "-4672710318"; // Chat ID ของกลุ่มเครื่องมือแพทย์

	// ข้อความแจ้งเตือน
	$message = "*🚑 แจ้งซ่อมเครื่องมือแพทย์:*\n" .
			   "*⚠️ ปัญหา:* " . $model->detail . "\n\n" .
			   "*👤 ผู้แจ้ง:* " . $model->send_by . "\n" .
			   "*📅 วันที่ต้องการ:* " . $model->dateline;

	// API Telegram URL
	$telegramApi = "https://api.telegram.org/bot$telegramToken/sendMessage";

	// ตั้งค่าการส่งข้อมูล
	$data = [
		'chat_id' => $chatId,
		'text' => $message,
		'parse_mode' => 'Markdown' // ใช้ Markdown ให้ข้อความดูดีขึ้น
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
       
        //$line_token = 'cfdpRl44nox1LUTTPWYppxN98w4WS0j1jB6dpPNB2FU';//LIne Notify
        $line_token = 'Itiid8OoWek59l9SQx3hjKQNoqU90PGIlqL6IOUpzZ5'; //เครื่องมือแพทย์
        
        

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".'แจ้งซ่อมเครื่องมือแพทย์:'.'ปัญหา:'.$model->detail.'    '.'ผู้แจ้ง:'.$model->send_by.'    '.'วันทีต้องการ'.$model->dateline);
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
	##########################################################################
    /**
     * Lists all Jobcom models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JobmedicalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		 $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	 public function actionAdmin()
    {
        $searchModel = new JobmedicalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];


        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionPrint($id)
    {
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('printview', ['model' => $this->findModel($id),]);
       // $content = $this->renderPartial('_reportView', ['model' => $data]);

        // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
          'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css', 
          //'cssFile' => '@app/web/css/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
                //'SetHeader' => ['แบบขออนุมัติใช้รถส่วนกลาง'],
                //'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();

    }
    /**
     * Displays a single Jobcom model.
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
     * Creates a new Jobcom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jobmedical();
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
     * Updates an existing Jobcom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'บันทึกการรับงานสำเร็จ');
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Jobcom model.
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
     * Finds the Jobcom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jobcom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jobmedical::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
