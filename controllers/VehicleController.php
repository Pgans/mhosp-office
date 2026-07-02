<?php

namespace app\controllers;

use Yii;
use app\models\Vehicle;
use app\models\VehicleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * VehicleController implements the CRUD actions for Vehicle model.
 */
class VehicleController extends Controller
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
     * Lists all Vehicle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VehicleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vehicle model.
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
     * Creates a new Vehicle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vehicle();

        if ($model->load(Yii::$app->request->post())) {
			Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลข้อมูลสำเร็จ');
            $file = UploadedFile::getInstance($model, 'vehicle_img');
         
            if($file->size!=0){
                $model->photo = $file->name;
                //$file->saveAs('uploads/vehicles/'.$file->name);
                $file->saveAs('uploads/vehicles/'.$model->license.'.'.$file->extension);
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->vehicle_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    // public function actionCreate()
    // {
    //     $model = new Vehicle();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->vehicle_id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing Vehicle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			Yii::$app->session->setFlash('info','แก้ไขข้อมูลยานพาหนะสำเร็จ');
            $file = UploadedFile::getInstance($model, 'vehicle_img');
            if(isset($file->size) && $file->size!==0){
                $model->photo = $file->name;
                $file->saveAs('uploads/vehicles/'.$file->name);
            }
            $model->save();
            return $this->redirect(['index', 'id' => $model->vehicle_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->vehicle_id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing Vehicle model.
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
     * Finds the Vehicle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vehicle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vehicle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('ไม่พบผลลัพธ์');
        }
    }
}
