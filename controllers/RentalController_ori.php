<?php

namespace app\controllers;

use Yii;
use app\models\Rental;
//use app\models\User;
use app\models\person;
use app\models\Vehicle;
use app\models\Driver;
use app\models\RentalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

use arturoliveira\ExcelView;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่
/**
 * RentalController implements the CRUD actions for Rental model.
 */
class RentalController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['index','create'],
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


    /**
     * Lists all Rental models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /* * เรียงข้อมูลรออนุมัติขึ้นก่อน */
        $dataProvider->setSort([
            'defaultOrder' => [ 'status' => SORT_ASC],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionAdmin()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /* * เรียงข้อมูลรออนุมัติขึ้นก่อน */
        $dataProvider->setSort([
            'defaultOrder' => [ 'status' => SORT_ASC],
        ]);
        Yii::$app->getSession()->setFlash('success', 'ระบบได้ทำการอนุมัติการจองแล้ว !');
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function sendLine($model)  {
       
        #$line_token = 'cfdpRl44nox1LUTTPWYppxN98w4WS0j1jB6dpPNB2FU'; //ตัวLine Notify
        $line_token = 'OrtVqN4trmpr2dJXD6226vOCRlDSZvSW3vIpqHpAsH4';//ซ่อมบำรุง
		$vehicle = $model->vehicle->license;
		$driver = $model->driver->driver_name;
		$firstname = $model->user->firstname;
		$lastname = $model->user->lastname;
		$firstname1 = $model->updater->firstname;
		$lastname1 = $model->updater->lastname;
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".'ข้อมูลการขอใช้รถ'."\n".'-วันที่ขอ'.$model->date_start."\n". '-ถึงวันที่:::' .$model->date_end."\n".'-ไปที:::'.$model->description."\n".'-เพื่อ:::'.$model->destination."\n".'-ผู้ขอ:::'.$firstname."\n".'-ผู้จัดรถ:::'.$firstname1."\n".'-รถที่ใช้:::'.$vehicle."\n".'-พขร:::'.$driver."\n".'-มีคนนั่ง:::'.$model->passenger.' '.'คน'.'');
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
    public function actionCalendar() {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $events = [];
        $lists = Rental::find()->all();
        foreach ($lists as $list) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $list->id;
            $event->url = Url::to(['rental/userview', 'id' => $list->id]);  // ทำ link view
            //$event->title = $list->title.'-'.$list->booking_name.'-'.$list->carBookingStatus->car_booking_status;
            $event->title = $list->destination . '->' . $list->user->firstname . '->' . $list->date_start . '->' . $list->passenger;  // ชื่อบน Lable
            $event->color = $list->vehicle->color; // สีพื้นหลังตามสถานะ
            $event->start = $list->date_start; // วันเริ่ม
            $event->end = $list->date_end; // วันสิ้นสุด
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

    public function actionUserindex()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->identity->id]); //เอาเฉพาะของตัวเอง
        /*
         * เรียงข้อมูลรออนุมัติขึ้นก่อน
         */
        $dataProvider->setSort([
            'defaultOrder' => ['status' => SORT_ASC],
        ]);

        return $this->render('userindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUserview($id)

    {

        return $this->render('userview', [

            'model' => $this->findModel($id),

        ]);

    }
    public function actionAccept($id)
    {
        
        $model = $this->findModel($id);
        
        $model->status = '1';
        $model->save();
        
        $uid = $model->user_id;
        $user = User::findOne($uid);
        $vid = $model->vehicle_id;
        $vehicle = Vehicle::findOne($vid);
        /*
        if ($model->load(Yii::$app->request->post())) {
            $user_model = User::find()->where(['id' => $model->user_id]);
        } 
        */
        /*
        Yii::$app->mailer->compose(['html' => 'acceptRequest-html'], ['user' => $user->username, 'destination' => $model->destination, 'description' => $model->description, 'passenger' => $model->passenger, 'start' => $model->date_start, 'end' => $model->date_end, 'license'=>$vehicle->license]) 
            // ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ''])
            ->setTo($user->email)
            ->setSubject('อนุมัติการจองพาหนะ')
            ->send();
        */
        Yii::$app->getSession()->setFlash('success', 'ระบบได้ทำการอนุมัติการจองแล้ว !');

        return $this->redirect(['admin']);

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
    public function actionIndex2()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Rental model.
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
     * Creates a new Rental model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rental();

        if ($model->load(Yii::$app->request->post())) {
            //ตรวจสอบการจองซ้ำ
            $rent = Rental::find()
                    ->where(['vehicle_id' => $model->vehicle_id])
                    ->andWhere(['between', 'date_start', $model->date_start, $model->date_end])
                    //->orWhere(['between', 'date_end', $model->date_start, $model->date_end])
                    ->one();
            if(empty($rent)){ //เมื่อไม่มีการจองซ้ำ
                $model->user_id = Yii::$app->user->getId();
                $model->save();
                $vid = $model->vehicle_id;
                $vehicle = Vehicle::findOne($vid);
                /*
                //ส่งอีเมลล์
                Yii::$app->mailer->compose(['html' => 'mailRequest-html'], ['user' => Yii::$app->user->identity->username, 'destination' => $model->destination, 'description' =>$model->description, 'start' =>$model->date_start, 'end' => $model->date_end, 'license'=>$vehicle->license]) //สามารพเลือกเฉพาะ html หรือ text ในการส่ง
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ''])
                    ->setTo(Yii::$app->user->identity->email)
                    ->setSubject('ยืนยันการจองพาหนะ')
                    ->send();
                
                Yii::$app->mailer->compose(['html' => 'mailRequestAdmin-html'], ['user' => Yii::$app->user->identity->username, 'destination' => $model->destination, 'description' =>$model->description, 'start' =>$model->date_start, 'end' => $model->date_end, 'license'=>$vehicle->license]) //สามารพเลือกเฉพาะ html หรือ text ในการส่ง
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ''])
                    ->setTo('admin@gmail.com')
                    ->setSubject('มีการจองพาหนะจากผู้ใช้')
                    ->send();
                
                Yii::$app->getSession()->setFlash('success', 'การจองสำเร็จ!! กรุณาตรวจสอบอีเมลล์ หากไม่ได้รับอีเมลล์ยืนยันการอนุมัติการจอง กรุณาติดต่อกองอาคารสถานที่และยานพาหนะ');
                /*if($model->save()){
                    $last_id = $model->id;
                }
                 * 
                 */            
                //return $this->redirect(['view', 'id' => $model->id]); //ไปยังหน้าวิวadmin
                Yii::$app->session()->setFlash('info', 'การจองสำเร็จ!! ระบบจะส่งข้อความทางไลน์แก่ผู้รับผิดชอบอัตโนมัติคะ่');
                return $this->redirect(['rental/userindex','id' => $model->id]); //ไปยังหน้าวิวuser
            }else{
                Yii::$app->session()->setFlash('danger', 'การจองไม่สำเร็จ!! พาหนะถูกจองในวันและเวลาที่ระบุแล้ว');
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    // public function actionCreate()
    // {
    //     $model = new Rental();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }else{
    //         $model->date_start = date('Y-m-d');
    //         $model->date_end = date('Y-m-d');
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing Rental model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session()->setFlash('success', 'แก้ไขเรียบร้อย !');
			$this->sendLine($model);//ส่งline notif
            return $this->redirect(['admin', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rental model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', 'ระบบได้ทำการยกเลิกและลบการจองแล้ว !');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Rental model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rental the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = rental::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('ไม่พบผลลัพธ์');
        }
    }

}
