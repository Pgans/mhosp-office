<?php

namespace app\modules\opdcard\controllers;

use Yii;
use app\models\Permits;
use app\modules\opdcard\models\PermitsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่



/**
 * PermitsController implements the CRUD actions for Permits model.
 */
class PermitsController extends Controller
{
    /**
     * @inheritdoc
     */
    /*
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=> ['index','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions'=>['index','create','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_USER,
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN

                        ]
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
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
    */
     public function sendLine($model)  {
       
            $line_token = 'XTvLuqnWGaQ7h2P4smFrxbekF1GJrSBEfLuU9NimrG3';//สิทธิบัตร
            //$line_token = 'cfdpRl44nox1LUTTPWYppxN98w4WS0j1jB6dpPNB2FU';//Line_Notify
            

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".'ยืมเวชระเบียน:'.'AN:'.$model->AN.''.'HN:'.$model->HN.'   '.'ชื่อ'.$model->fullname.'  '.'ผู้ยืม:'.$model->created_by.'  '.'วันทีต้องการ'.$model->day_want);
           //  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->cid.' '.$model->cmu.'สาหตุ'.$model->cdeath.' '.'วันตาย'.$model->ddeath);
          //  <!--if(!empty(Yii::$app->request->getFirstImage($model->request_text))) {
              //  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->fullname."imageThumbnail".Yii::$app->request->getFirstImage($model->request_text)."$imageFullsize=".Yii::$app->request->getFirstImage($model->request_text));
          // }else{
             //   curl_setopt($ch, CURLOPT_POSTFIELDS, "message=".$model->fullname);-->
            
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
            Yii::$app->getSession()->setFlash('alert', [
                'body' =>'ระบบทำการแจ้งเข้ากลุ่มไลน์เวขระเบียน..... เป็นที่เรียบร้อยแล้วครับ',
                'options'=>['class'=>'alert-info'],
                  ]);
             $this->sendLine($model);//ส่งline notify
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
