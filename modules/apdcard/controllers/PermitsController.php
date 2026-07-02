<?php

namespace app\modules\apdcard\controllers;

use Yii;
use app\models\Permits;
use app\modules\apdcard\models\PermitsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\User;

use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
//use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


/**
 * PermitsController implements the CRUD actions for Permits model.
 */
class PermitsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
    return [
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
            ],
        ],
        'access' => [
            'class' => AccessControl::class,
            'only' => ['index', 'index2', 'update', 'view', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'index', 'index2', 'create', 'update'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 96, 16, 153 ,285,286,289 ]; #96=yoo 16=ดาว  285= Earth 286=phichitphorn
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 96, 16,153 ,285, 286,289]; #96=yoo 16=ดาว  285= Earth 286=phichitphorn
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
    ];
}


    /**
     * Lists all Permits models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermitsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Permits model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
         return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Permits model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permits();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
             return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Permits model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
             return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Permits model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Permits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permits::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

