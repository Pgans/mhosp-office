<?php

namespace app\controllers;

use Yii;
use app\models\Jobcomad;
use app\models\JobcomadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JobcomadController implements the CRUD actions for Jobcomad model.
 */
class JobcomadController extends Controller
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
     * Lists all Jobcomad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JobcomadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jobcomad model.
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
     * Creates a new Jobcomad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jobcomad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Jobcomad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionUpdate($id)
{
    // ตรวจสอบว่าผู้ใช้ login หรือยัง ถ้าไม่ได้ login ให้แสดงข้อความแจ้งเตือน
    if (Yii::$app->user->isGuest) {
        Yii::$app->session->setFlash('warning', 'กรุณาเข้าสู่ระบบก่อนทำการอัปเดตข้อมูล');
        return $this->redirect(['site/login']); // เปลี่ยน 'site/login' เป็น URL ที่คุณต้องการพาไป เช่นหน้า login
    }

    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post())) {
        // เพิ่มข้อมูล user ที่ทำการ login ลงในฟิลด์ของ model
        $model->repair_by = Yii::$app->user->id; // สมมติว่าคุณมีฟิลด์ 'repair_by' ในตารางที่เก็บข้อมูลว่าใครเป็นคนแก้ไข

        if ($model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}



    /**
     * Deletes an existing Jobcomad model.
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
     * Finds the Jobcomad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jobcomad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jobcomad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
