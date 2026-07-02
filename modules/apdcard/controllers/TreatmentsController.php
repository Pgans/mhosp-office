<?php

namespace backend\modules\opdcard\controllers;

use Yii;
use backend\models\Treatments;
use backend\modules\opdcard\models\TreatmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use common\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use common\components\AccessRule;   // เรียก
/**
 * TreatmentsController implements the CRUD actions for Treatments model.
 */
class TreatmentsController extends Controller
{
    /**
     * @inheritdoc
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
           'access' => [
                            'class' => AccessControl::className(),
                            'only' => ['index', 'create', 'view','delete'], // กำหนด action ทั้งหมดภายใน Controller นี้
                            'ruleConfig' => [
                                'class' => AccessRule::className() // เรียกใช้งาน accessRule (component) ที่เราสร้างขึ้นใหม่
                            ],
                            'rules' => [
                                [
                                    'actions' => ['index'],     // กำหนด rules ให้ actionIndex()
                                    'allow' => true,
                                    'roles' => [
                                        User::ROLE_USER,        // อนุญาตให้ "ผู้ใช้งาน / สมาชิก" ใช้งานได้
                                        User::ROLE_EMPLOYEE,    // อนุญาตให้ "พนักงาน" ใช้งานได้
                                        User::ROLE_ADMIN        // อนุญาตให้ "ผู้ดูแลระบบ" ใช้งานได้
                                    ]
                                ],
                                [
                                    'actions' => ['create'],    // กำหนด rules ให้ actionCreate()
                                    'allow' => true,
                                    'roles' => [
                                        User::ROLE_USER,    // อนุญาตให้ "พนักงาน" ใช้งานได้
                                        User::ROLE_ADMIN        // อนุญาตให้ "ผู้ดูแลระบบ" ใช้งานได้
                                    ]
                                ],
                                [
                                    'actions' => ['delete'],    // กำหนด rules ให้ actionDelete()
                                    'allow' => true,
                                    'roles' => [
                                        User::ROLE_ADMIN        // อนุญาตให้ "ผู้ดูแลระบบ" ใช้งานได้
                                    ]
                                ],
                                
                                [
                                    'actions' => ['view'],      // กำหนด rules ให้ actionView()
                                    'allow' => true,
                                    'roles' => [
                                        User::ROLE_USER,        // อนุญาตให้ "ผู้ใช้งาน / สมาชิก" ใช้งานได้
                                        User::ROLE_EMPLOYEE,    // อนุญาตให้ "พนักงาน" ใช้งานได้
                                        User::ROLE_ADMIN        // อนุญาตให้ "ผู้ดูแลระบบ" ใช้งานได้
                                    ]
                                ]
                            ],
                        ],
                    ];
                }

    /**
     * Lists all Treatments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TreatmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Treatments model.
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
     * Creates a new Treatments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Treatments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Treatments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Treatments model.
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
     * Finds the Treatments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Treatments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Treatments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
