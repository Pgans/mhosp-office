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
use yii\helpers\ArrayHelper;
//use yii2mod\alert\Alert;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่
/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller {

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
                        'actions'=>['index','create','view'],
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
    public function actionCreate()
{
    $data = Yii::$app->request->post();
    $start = isset($data['booking_start']) ? $data['booking_start'] : '';
    $end = isset($data['booking_end']) ? $data['booking_end'] : '';

    $model = new Booking();
	 
	### Query builder #########################
        if ($model->load(Yii::$app->request->post())) {
		$rent = Booking::find()
			->where([
			'or',
			['between', 'booking_start', $model->booking_start, $model->booking_end],
			['between', 'booking_end', $model->booking_start, $model->booking_end],
			['and', ['<=', 'booking_start', $model->booking_start], ['>=', 'booking_end', $model->booking_start]],
			['and', ['<=', 'booking_start', $model->booking_end], ['>=', 'booking_end', $model->booking_end]],
		])
		->andWhere(['booking_room' => $model->booking_room])
		->andWhere(['!=', 'booking_status', '3'])
		->all();

//echo $rent;
		/*
		SELECT *
		FROM booking
		WHERE (
			(booking_start BETWEEN $model->booking_start and $model->booking_end)
			OR (booking_end BETWEEN $model->booking_start and $model->booking_end)
			OR ($model->booking_start BETWEEN booking_start AND booking_end)
			OR ($model->booking_end BETWEEN booking_start AND booking_end)
		)
		AND (booking_room = '8')
		AND (booking_status <> '3');
		*/
	/*
    if ($model->load(Yii::$app->request->post())) {
        $rent = Booking::find()
            ->where(['between', 'booking_start', $model->booking_start, $model->booking_end])
            ->orWhere(['between', 'booking_end', $model->booking_start, $model->booking_end])
			->orWhere 'booking_start', $model->booking_start, $model->booking_end])
            ->andWhere(['booking_room' => $model->booking_room])
            ->one();
			*/
	/* SELECT *
		FROM booking
			WHERE (
				(booking_start BETWEEN '2023-12-19 08:00' AND '2023-12-19 16:40')
				OR (booking_end BETWEEN '2023-12-19 08:00' AND '2023-12-19 16:40')
				OR ('2023-12-19 08:00' BETWEEN booking_start AND booking_end)
				OR ('2023-12-19 16:40' BETWEEN booking_start AND booking_end)
			)
			AND (booking_room = '8')
			AND (booking_status <> '3');

	*/
	
        $maxDuration = 3 * 24 * 60 * 60; // 3 days in seconds
        $startTimestamp = strtotime($model->booking_start);
        $endTimestamp = strtotime($model->booking_end);
        if (empty($rent) && ($endTimestamp - $startTimestamp <= $maxDuration)) { // When there is no overlapping booking and the duration is within 3 days
            $model->booking_status = 1;
            $model->booking_cur_date = date('Y-m-d');
            $model->save();
            $vid = $model->booking_id;
            $room = Room::findOne($vid);

            Yii::$app->session->setFlash('info', 'จองห้องประชุมสำเร็จ! .');
            return $this->redirect(['booking/calendar', 'id' => $model->booking_id]);
        } else if ($endTimestamp - $startTimestamp > $maxDuration) {
            Yii::$app->session->setFlash('warning', 'จองห้องประชุมเกิน 2วัน !  period cannot exceed 2 days.');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('warning', 'มีการจองห้องประชุมนี้แล้ว!  กรุณาเลือกห้องใหม่.');
            return $this->redirect(['index']);
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
		if ($list->booking_status <> '3') {
			$event = new \yii2fullcalendar\models\Event();
			$event->id = $list->booking_id;
			$event->url = Url::to(['booking/view', 'id' => $list->booking_id]);
			$event->title = $list->bookingRoom->room_name . '->' . $list->booking_user . '->' . $list->booking_title . '->' . $list->booking_seate;
			$event->color = $list->bookingRoom->color;
			$event->start = $list->booking_start;
			$event->end = $list->booking_end;
			$events[] = $event;
		}
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
