<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Booking;
use app\models\Room;
use app\models\BookingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['booking_id' => SORT_DESC];

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    $dataProvider->pagination = [
                        'pageSize' => 10,
                    
                    ]
        ]);
        
    }

    /**
     * Displays a single Booking model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    // return $this->renderAjax('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
    $model = new booking();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = $model->uploadFiles($model, 'file'); //upload file
                $model->booking_status = 1;
                $model->booking_cur_date = date('Y-m-d');
               // $model->save();      
            //ตรวจสอบการจองซ้ำ
            $rent = booking::find()
                    ->where(['booking_room' => $model->booking_room])
                    ->andWhere(['between', 'booking_start', $model->booking_start, $model->booking_end])
                    //->andWhere(['between', 'booking_start', $model->booking_start, $model->booking_end])
                   // ->orWhere(['between', 'booking_end', $model->booking_start, $model->booking_end])
                    ->one();
            if(empty($rent)){ //เมื่อไม่มีการจองซ้ำ
                //$model->load(Yii::$app->request->post()) && $model->validate()) {
                $vid = $model->booking_room;
                $room = Room::findOne($vid);  
                //$model->file = $model->uploadFiles($model, 'file'); //upload file
               // $model->booking_status = 1;
                //$model->booking_cur_date = date('Y-m-d');
                  $model->save();      
                //return $this->redirect(['view', 'id' => $model->id]); //ไปยังหน้าวิวadmin
                Yii::$app->getSession()->setFlash('info', 'การจองห้องประชุมสำเร็จ!! ระบบจะส่งข้อความทางไลน์แก่ผู้รับผิดชอบอัตโนมัติคะ่');
                return $this->redirect(['booking/index','id' => $model->booking_id]); //ไปยังหน้าวิวuser
            }else {
                Yii::$app->getSession()->setFlash('danger', 'การจองห้องประชุมไม่สำเร็จ!! ห้องประชุมถูกจองในวันและเวลาที่ระบุแล้ว');
                return $this->redirect(['create']);
                   }
         } else {
             return $this->render('create', [
                 'model' => $model,
             ]);
        }
    }
   /* public function actionCreate() {
        $model = new Booking();

        //$booking_start = Booking::find()->where(['booking_start' => $model->booking_status])->all();


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->file = $model->uploadFiles($model, 'file'); //upload file
            $model->booking_status = 1;
            $model->booking_cur_date = date('Y-m-d');
            $model->save();
            //print_r($model);
            return $this->redirect(['view', 'id' => $model->booking_id]);
        }
        return $this->render('create', [
        //return $this->renderAjax('create', [
                    //return $this->renderAjax('create', [
                    'model' => $model,
        ]);
    }
   */
    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        /*
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['view', 'id' => $model->booking_id]);
          }

          return $this->render('update', [
          //return $this->renderAjax('update', [
          'model' => $model,
          ]);
          }
         */
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->photo = $model->upload($model, 'photo');
            $model->file = $model->uploadFiles($model, 'file'); //
            $model->save();
            // $model->notifyLine($id); // ส่งไลน์ตามกลุ่มต่างๆ
            return $this->redirect(['view', 'id' => $model->booking_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCalendar() {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $events = [];
        $lists = Booking::find()->all();
        foreach ($lists as $list) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $list->booking_id;
            $event->url = Url::to(['booking/view', 'id' => $list->booking_id]);  // ทำ link view
            //$event->title = $list->title.'-'.$list->booking_name.'-'.$list->carBookingStatus->car_booking_status;
            $event->title = $list->bookingRoom->room_name . '->' . $list->booking_user . '->' . $list->booking_title . '->' . $list->booking_seate;  // ชื่อบน Lable
            $event->color = $list->bookingStatus->booking_statust_color; // สีพื้นหลังตามสถานะ
            $event->start = $list->booking_start; // วันเริ่ม
            $event->end = $list->booking_end; // วันสิ้นสุด
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

    // upload file
    public function uploadFiles($model, $attribute) {
        $file = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadFilePath();
        if ($this->validate() && $file !== null) {

            $filesName = md5($file->baseName . time()) . '.' . $file->extension;
            if ($file->saveAs($path . $filesName)) {
                return $filesName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    public function getUploadFilePath() {
        return Yii::getAlias('@webroot') . '/' . $this->upload_foler_file . '/';
    }

    public function getUploadFileUrl() {
        return Yii::getAlias('@web') . '/' . $this->upload_foler_file . '/';
    }

    public function getFileViewer() {
        return empty($this->file) ? Yii::getAlias('@web') . '/uploads/img/nofile.png' : $this->getUploadFileUrl() . $this->file;
    }

}
