<?php

namespace app\controllers;

use Yii;
use app\models\Jobcom;
use app\models\JobcomSearch;
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
class JobcomController extends Controller
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
                        'actions' => ['index', 'view','create'],
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
	public function sendLine($model) {
    $telegram_token = '7559782200:AAHvRkNmDm5-bGe3NKUGIsvjzEecJQDKuQA'; // Telegram Bot Token
    $chat_id = '-4602745497'; // Chat ID หรือ Group ID ที่ต้องการส่งข้อความไป

    // แปลงข้อมูลแผนกจาก dep_id เป็นชื่อแผนก
    $dep_id = $model->dep_id;
    $departments = '';
    switch ($dep_id) {
     
    case '1': $departments = 'ผู้ป่วยนอก'; break;
    case '2': $departments = 'อุบัติเหตุฉุกเฉิน'; break;
    case '3': $departments = 'เภสัชกรรม'; break;
    case '4': $departments = 'บริหารงานทั่วไป'; break;
    case '5': $departments = 'งานเวชสถิติข้อมูล'; break;
    case '6': $departments = 'ห้องคลอด'; break;
    case '7': $departments = 'กลุ่มการพยาบาล'; break;
    case '8': $departments = 'คลินิกพิเศษ'; break;
    case '9': $departments = 'งานไต'; break;
    case '10': $departments = 'งานรังสีวิทยา'; break;
    case '11': $departments = 'เทคนิคการแพทย์'; break;
    case '12': $departments = 'งานทันตกรรม'; break;
    case '13': $departments = 'งานผู้ป่วยใน1'; break;
    case '14': $departments = 'งานผู้ป่วยใน2'; break;
    case '15': $departments = 'หน่วยจ่ายกลาง'; break;
    case '16': $departments = 'งานเวชปฏิบัติ'; break;
    case '17': $departments = 'ศูนย์สุขภาพชุมชนม่วง'; break;
    case '18': $departments = 'งานแพทย์แผนไทย'; break;
    case '19': $departments = 'สิทธิบัตร'; break;
    case '20': $departments = 'งานวัณโรค'; break;
    case '21': $departments = 'งานยาเสพติด'; break;
    case '22': $departments = 'งานให้คำปรึกษา'; break;
    case '23': $departments = 'ตรวจสุขภาพVIP'; break;
    case '24': $departments = 'ห้องกอบสุข'; break;
    case '25': $departments = 'ศูนย์คอมพิวเตอร์'; break;
    case '26': $departments = 'โสตทัศนศึกษา'; break;
    case '27': $departments = 'กายภาพบำบัด'; break;
    case '28': $departments = 'งานโภชนาการ'; break;
    case '29': $departments = 'งานซักฟอก'; break;
    case '30': $departments = 'งานพัสดุและซ่อมบำรุง'; break;
    case '31': $departments = 'งานการเงินการบัญชี'; break;
    case '32': $departments = 'งานยุทธศาสตร์'; break;
    case '33': $departments = 'องค์กรแพทย์'; break;
    case '34': $departments = 'งานยานพาหนะ'; break;
    case '35': $departments = 'งานผู้ป่วยใน4'; break;
    default: $departments = 'ไม่ระบุแผนก'; break;
}
       
    // ข้อความที่จะส่ง
    $message = "*💬 การแจ้งซ่อมคอมพิวเตอร์:* \n" .
               "*📝 รายละเอียด:* " . $model->detail . "\n\n" .
               "*👤 ผู้แจ้ง:* " . $model->send_by . "\n" .
               "*🏢 แผนก:* " . $departments . "\n" .
               "*📅 วันที่:* " . $model->dateline;

    // ส่งข้อมูลไปยัง Telegram API
    $url = "https://api.telegram.org/bot" . $telegram_token . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message) . "&parse_mode=Markdown";

    // ส่งคำขอ HTTP
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
}
  ################################################################################################
    public function actionIndex()
    {
        $searchModel = new JobcomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		 $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	 public function actionAdmin()
    {
        $searchModel = new JobcomSearch();
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
    $model = new Jobcom();

    if ($model->load(Yii::$app->request->post())) {
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ระบบทำการแจ้งเข้ากลุ่มไลน์..... เรียบร้อยแล้วครับ',
                'options' => ['class' => 'alert-info'],
            ]);
            $this->sendLine($model); // ส่ง line notification

            return $this->redirect(['calendar', 'id' => $model->id]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'body' => 'กรุณาเลือกแผนก',
                'options' => ['class' => 'alert-danger'],
            ]);
        }
    }

    $model->dateline = date('Y-m-d');
    // Debug statement
    Yii::info('Flash error message: ' . print_r(Yii::$app->session->getFlash('error'), true));
    return $this->render('create', [
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
        if (($model = Jobcom::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
	 public function actionCalendar() {
        $searchModel = new JobcomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $events = [];
        $lists = Jobcom::find()->all();
        foreach ($lists as $list) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $list->id;
			$event->url = Url::to(['jobcom/view', 'id' => $list->id]);  // ทำ link view
            //$event->url = Url::to(['rental/userview', 'id' => $list->id]);  // ทำ link view
            //$event->title = $list->title.'-'.$list->booking_name.'-'.$list->carBookingStatus->car_booking_status;
            $event->title = $list->detail . '->' . $list->send_by . '->' . $list->send_at . '->' . $list->updater->firstname;  // ชื่อบน Lable
            $event->color = $list->jstatus->color; // สีพื้นหลังตามสถานะ
            $event->start = $list->send_at; // วันเริ่ม
           // $event->end = $list->date_end; // วันสิ้นสุด
            //$event->startEditable = FALSE;
            //$event->constraint = $list->id;
            $events[] = $event;
        }
        return $this->render('calendar', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'events' => $events
        ]);
    }
}
