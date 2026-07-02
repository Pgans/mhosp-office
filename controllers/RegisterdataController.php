<?php

namespace app\controllers;

use Yii;
use app\models\Registerdata;
use app\models\RegisterdataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegisterdataController implements the CRUD actions for Registerdata model.
 */
class RegisterdataController extends Controller
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
     * Lists all Registerdata models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegisterdataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sql'=>$sql,
            'pagination' => [
            'pageSize' => 100,
            ],
        ]);
    }

    /**
     * Displays a single Registerdata model.
     * @param string $ECLAIM_NO
     * @param string $HCODE
     * @param string $HN
     * @param string $DATEADM
     * @param string $TIMEADM
     * @param string $PERSON_TYPE
     * @param string $PID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID)
    {
        return $this->render('view', [
            'model' => $this->findModel($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID),
        ]);
    }

    /**
     * Creates a new Registerdata model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registerdata();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ECLAIM_NO' => $model->ECLAIM_NO, 'HCODE' => $model->HCODE, 'HN' => $model->HN, 'DATEADM' => $model->DATEADM, 'TIMEADM' => $model->TIMEADM, 'PERSON_TYPE' => $model->PERSON_TYPE, 'PID' => $model->PID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Registerdata model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $ECLAIM_NO
     * @param string $HCODE
     * @param string $HN
     * @param string $DATEADM
     * @param string $TIMEADM
     * @param string $PERSON_TYPE
     * @param string $PID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID)
    {
        $model = $this->findModel($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ECLAIM_NO' => $model->ECLAIM_NO, 'HCODE' => $model->HCODE, 'HN' => $model->HN, 'DATEADM' => $model->DATEADM, 'TIMEADM' => $model->TIMEADM, 'PERSON_TYPE' => $model->PERSON_TYPE, 'PID' => $model->PID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Registerdata model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $ECLAIM_NO
     * @param string $HCODE
     * @param string $HN
     * @param string $DATEADM
     * @param string $TIMEADM
     * @param string $PERSON_TYPE
     * @param string $PID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID)
    {
        $this->findModel($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Registerdata model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $ECLAIM_NO
     * @param string $HCODE
     * @param string $HN
     * @param string $DATEADM
     * @param string $TIMEADM
     * @param string $PERSON_TYPE
     * @param string $PID
     * @return Registerdata the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ECLAIM_NO, $HCODE, $HN, $DATEADM, $TIMEADM, $PERSON_TYPE, $PID)
    {
        if (($model = Registerdata::findOne(['ECLAIM_NO' => $ECLAIM_NO, 'HCODE' => $HCODE, 'HN' => $HN, 'DATEADM' => $DATEADM, 'TIMEADM' => $TIMEADM, 'PERSON_TYPE' => $PERSON_TYPE, 'PID' => $PID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
