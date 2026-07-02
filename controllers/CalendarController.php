<?php

namespace app\controllers;

use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Url;

/**
 * EventController implements the CRUD actions for Event model.
 */
class CalendarController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Event models.
     * @return mixed
     */
    // ฟังก์ชันสร้างกิจกรรมใหม่
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view']); // เปลี่ยนเส้นทางไปยังหน้าหลัก
        }
		$model->start = date('Y-m-d');
		$model->end = date('Y-m-d');
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // ฟังก์ชันแสดงปฏิทินพร้อมกิจกรรม
    public function actionIndex()
    {
        return $this->render('index'); // แสดง view ที่มีปฏิทิน
    }
	
	/*
    // ฟังก์ชันดึงกิจกรรมในรูปแบบ JSON สำหรับ FullCalendar
    public function actionGetEvents()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    $events = Event::find()->all(); // ค้นหากิจกรรมทั้งหมด
    $eventArray = [];

    foreach ($events as $event) {
        // สร้าง URL สำหรับลิงก์ไปยังหน้า view
        $eventUrl = Url::to(['calendar/view', 'id' => $event->id]);  // แทน 'list->id' ด้วย 'event->id'

        $eventArray[] = [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'description' => $event->description,
            'url' => $eventUrl, // เพิ่ม URL สำหรับคลิก
        ];
    }

    return $eventArray; // ส่งกลับในรูปแบบ JSON
}
*/
    // ฟังก์ชันแก้ไขกิจกรรม
    public function actionUpdate($id)
    {
        $model = Event::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['calendar']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    // ฟังก์ชันลบกิจกรรม
    public function actionDelete($id)
    {
        $model = Event::findOne($id);
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }
	

public function actionCalendar() {
    $searchModel = new EventSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $events = Event::find()->all(); // ดึงกิจกรรมทั้งหมด
    $eventArray = [];

    foreach ($events as $event) {
        // สร้าง URL สำหรับลิงก์ไปยังหน้า view ของกิจกรรม
        $eventUrl = Url::to(['calendar/view', 'id' => $event->id]);

        $eventArray[] = [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'description' => $event->description,
            'url' => $eventUrl, // เพิ่ม URL สำหรับลิงก์
        ];
    }

    // ส่ง eventArray ไปยัง view
    return $this->render('calendar', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'events' => $eventArray, // ส่ง eventArray ไปยัง view
    ]);
}
public function actionView($id) {
    // ค้นหาโมเดลตาม ID
    $model = $this->findModel($id);

    if (!$model) {
        // ถ้าไม่พบโมเดล, แสดง NotFoundHttpException
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // แสดงผลใน view.php โดยส่งโมเดลไป
    return $this->render('view', ['model' => $model]);
}

// ฟังก์ชันที่ใช้ค้นหาโมเดลโดยใช้ ID
protected function findModel($id) {
    // ตรวจสอบว่าโมเดลนั้นมีอยู่หรือไม่
    if (($model = Event::findOne($id)) !== null) {
        return $model;
    } else {
        throw new NotFoundHttpException('The requested page does not exist.'); // กรณีที่ไม่พบ
    }
}
}