<?php

namespace app\controllers;

use Yii;
use app\models\Logclosevisitsj;
use app\models\LogclosevisitsjSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LogclosevisitsjController implements the CRUD actions for Logclosevisitsj model.
 */
class LogclosevisitsjController extends Controller
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
     * Lists all Logclosevisitsj models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogclosevisitsjSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// ตรวจสอบว่ามี DataProvider หรือไม่ และตั้งค่าให้เรียงลำดับ DESC ตามคอลัมน์ที่ต้องการ
    if ($dataProvider) {
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC] // เรียงจากมากไปน้อยตาม id
        ]);
    } else {
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => [], // ไม่มีข้อมูลในตอนแรก
        ]);
    }

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}

    /**
     * Displays a single Logclosevisitsj model.
     * @param integer $id
     * @param string $visit_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $visit_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $visit_id),
        ]);
    }

    /**
     * Creates a new Logclosevisitsj model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Logclosevisitsj();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'visit_id' => $model->visit_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Logclosevisitsj model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $visit_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $visit_id)
    {
        $model = $this->findModel($id, $visit_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'visit_id' => $model->visit_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Logclosevisitsj model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $visit_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $visit_id)
    {
        $this->findModel($id, $visit_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logclosevisitsj model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $visit_id
     * @return Logclosevisitsj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $visit_id)
    {
        if (($model = Logclosevisitsj::findOne(['id' => $id, 'visit_id' => $visit_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
