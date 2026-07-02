<?php

namespace app\controllers;

use Yii;
use app\models\Rental;
//use app\models\User;
use app\models\person;
use app\models\Vehicle;
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
                        'actions'=>['index','create','view','userview','update'],
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


     public function sendLine($model)  {
       
        # $line_token = 'cfdpRl44nox1LUTTPWYppxN98w4WS0j1jB6dpPNB2FU';//LIne Notify
        #$line_token = '0iuNYdyzVeGozIAp9qgjsNBizj9tkr34gNoAmJTRAxl'; //IT
		$line_token = 'nnK5fAfUDAu8xVoGhonceKww8oYxBXexY8HlgkFiVVb'; //กลุ่มขอรถยนต์
        
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
		} elseif ($dep_id == '13') { $departments = 'งานผู้ป่วยใน2';
		} elseif ($dep_id == '14') { $departments = 'งานผู้ป่วยใน1';
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".'ขอรถยนต์:'.'ไปที่:'.$model->description.'\n'.'เรื่อง:'.$model->destination.'\n'.'ผู้ขอ:'.$model->user_id.'\n'.'แผนก:'.$departments.'\n'.'วันทีไป'.$model->date_start.'ถึง '.'วันที'.$model->date_end);
       
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

	 
    public function actionIndex()
    {
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /* * เรียงข้อมูลรออนุมัติขึ้นก่อน */
        $dataProvider->setSort([
			'defaultOrder' => [ 'id' => SORT_DESC],
          //  'defaultOrder' => [ 'status' => SORT_ASC],
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
    public function actionCalendar() {
    $searchModel = new RentalSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    // Fetch rentals excluding those with status 3
    $lists = Rental::find()
        ->where(['!=', 'status', 3]) // Exclude canceled events
        ->all();

    $events = [];
    foreach ($lists as $list) {
        $event = new \yii2fullcalendar\models\Event();
        $event->id = $list->id;
        $event->url = Url::to(['rental/view', 'id' => $list->id]);  // Link to view
        $event->title = $list->destination . '->' . $list->user->firstname . '->' . $list->date_start . '->' . $list->passenger;  // Label
        $event->color = $list->vehicle->color; // Background color based on status
        $event->start = $list->date_start; // Start date
        $event->end = $list->date_end; // End date
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
	public function actionCreate() {
    $model = new Rental();

    if ($model->load(Yii::$app->request->post())) {
        // Get the values of $date1 and $date2 from the model
        $date1 = $model->date_start;
        $date2 = $model->date_end;
        // Format the date values to a consistent format
        $date_start = strtotime($date1);
        $date_end = strtotime($date2);
        // Perform the comparison
        if ($date_end < $date_start) {
            // End date is before start date
            Yii::$app->session->setFlash('error', 'วันที่สิ้นสุดน้อยกว่าวันที่เริ่มต้น กรุณาแก้ไขและบันทึกการจองใหม่.');
        } elseif (($date_end - $date_start) / (60 * 60 * 24) > 1) {
            // Difference in days is more than 1
            Yii::$app->session->setFlash('error', 'คุณสามารถจองรถได้เพียง 1 วันเท่านั้น');
        } else {
            // Save the model
            if ($model->save()) {
                // Model saved successfully
                Yii::$app->session->setFlash('success', 'บันทึกการจองรถสำเร็จ.');
                return $this->redirect(['view', 'id' => $model->id]); // ไปยังหน้าวิว admin
            } else {
                // Model failed to save
                Yii::$app->session->setFlash('error', 'ไม่สามารถบันทึกการจองได้.');
            }
        }
    }
    return $this->render('create', [
        'model' => $model,
    ]);
}

   

	/*
	public function actionCreate(){
		$model = new Rental();

        if ($model->load(Yii::$app->request->post())) {
            // Get the values of $date1 and $date2 from the model
            $date1 = $model->date_start;
            $date2 = $model->date_end;
			 // Format the date values to a consistent format
            $date_start = date('Y-m-d', strtotime($date1));
            $date_end = date('Y-m-d', strtotime($date2));
          echo $date_start;
            // Perform the comparison
            if ($date_start <= $date_end) {
                // $date1 is greater than $date2
                // Save the model
                if ($model->save()) {
                    // Model saved successfully
                    Yii::$app->session->setFlash('success', 'บันทึกการจองรถสำเร็จ.');
                    return $this->redirect(['view', 'id' => $model->id]); //ไปยังหน้าวิวadmin
                } else {
                    // Model failed to save
                    Yii::$app->session->setFlash('error', 'ไม่สามารถบันทึกการจองได้ .');
                }
            } else {
                // $date1 is less than or equal to $date2
                Yii::$app->session->setFlash('error', 'วันที่สิ้นสุดน้อยกว่าวันที่เริ่มต้น กรุณา..แก้ไขและบันทึกการจองใหม่.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

*/
    /**
     * Creates a new Rental model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	 /*
    public function actionCreate()
    {
        $model = new Rental();
        if ($model->load(Yii::$app->request->post())) {
			$date_start = Yii::$app->request->post('date_start');
            $date_end = Yii::$app->request->post('date_end');
            //ตรวจสอบการจองซ้ำ
            $rent = Rental::find()
                    ->where(['vehicle_id' => $model->vehicle_id])
                    ->andWhere(['<', 'date_end',$date_start ])
                    //->orWhere(['between', 'date_end', $model->date_start, $model->date_end])
                    ->one();
            //if(empty($rent)){ //เมื่อไม่มีการจองซ้ำ
			if($model->date_start < $model->date_end){ //ตรวจสอบวันที่ Date_start > date_end
                $model->user_id = Yii::$app->user->getId();
                $model->save();
                $vid = $model->vehicle_id;
                $vehicle = Vehicle::findOne($vid);
				//$this->sendLine($model);//ส่งline notif
                //return $this->redirect(['view', 'id' => $model->id]); //ไปยังหน้าวิวadmin
                Yii::$app->getSession()->setFlash('info', 'การจองสำเร็จ!! ระบบจะส่งข้อความทางไลน์แก่ผู้รับผิดชอบอัตโนมัติคะ่');
                return $this->redirect(['rental/userindex','id' => $model->id]); //ไปยังหน้าวิวuser
            }else{
				 $model->date_start = date('Y-m-d');
                 $model->date_end = date('Y-m-d');
                Yii::$app->getSession()->setFlash('danger', 'การจองไม่สำเร็จ!! วันที่สิ้นสุดน้อยกว่าวันที่เริ่มต้น กรุณา...ทำการจองใหม่อีกครั้ง');
                return $this->redirect(['create']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	
	/*
    public function actionCreate()
     {
         $model = new Rental();

         //if ($model->load(Yii::$app->request->post()) && $model->save()) {
		 if ($model->load(Yii::$app->request->post()) &&  {
			 $rent = Rental::find()
			//$model->date_start = $rent->date_end;
			$model->date_start = $rent->date_end; // วันสิ้นสุด
             $model->save();
		   Yii::$app->getSession()->setFlash('success', 'บันทึกการจองรถสำเร็จ !');
             return $this->redirect(['view', 'id' => $model->id]);
         }else{
             $model->date_start = date('Y-m-d');
             $model->date_end = date('Y-m-d');
			 Yii::$app->getSession()->setFlash('Error', 'xxxxx !');
         }

         return $this->render('create', [
             'model' => $model,
         ]);
     }

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
            Yii::$app->getSession()->setFlash('success', 'แก้ไขเรียบร้อย !');
            return $this->redirect(['view', 'id' => $model->id]);
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
#### nnK5fAfUDAu8xVoGhonceKww8oYxBXexY8HlgkFiVVb   //Token Line กลุ่มจองรถยนต์ฌรงพยาบาลม่วงสามสิบ
}
