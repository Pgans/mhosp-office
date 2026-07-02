<?php

namespace app\controllers;

use Yii;
use app\models\Agendasubx;
use app\models\AgendasubxSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;
use yii\web\Response;
use yii\helpers\Url;
use yii\helpers\Html;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


/**
 * AgendasubxController implements the CRUD actions for Agendasubx model.
 */
class AgendasubxController extends Controller
{
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
	/*
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['index','create','view','userview'],
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
	*/
    /**
     * {@inheritdoc}
     */
	 /*
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
*/
    /**
     * Lists all Agendasubx models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgendasubxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agendasubx model.
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
    public function actionViewmeet($meetingId)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Agendasubx::find()->where(['meeting_id' => $meetingId]),
        ]);

        return $this->render('viewmeet', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new Agendasubx model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AgendaSubx();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
    
            if ($model->file) {
                $model->filename = $model->file->baseName . '.' . $model->file->extension;
                $model->file->saveAs('uploads/meetings/' . $model->filename);
    
                // กำหนดสิทธิ์ให้เป็น 0777
                chmod('uploads/meetings/' . $model->filename, 0775);
            }
    
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->sub_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Agendasubx model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
        {
            $model = $this->findModel($id);

            $oldFilename = $model->filename;
			$uploadPath = Yii::getAlias('@webroot/uploads/meetings/');
            if ($model->load(Yii::$app->request->post())) {
                $model->file = UploadedFile::getInstance($model, 'file');
				
                if ($model->file) {
                    if ($oldFilename !== null && file_exists('uploads/meetings/' . $oldFilename)) {
                         //unlink('uploads/meetings/' . $oldFilename);
                    }

                    // แปลงชื่อไฟล์เป็น UTF-8
                    $model->filename = iconv(mb_detect_encoding($model->file->baseName), 'UTF-8', $model->file->baseName)
                                        . '.' . $model->file->extension;

                    $model->file->saveAs($uploadPath . $model->filename);

                    // เปลี่ยนสิทธิ์ไฟล์
                   // chmod($uploadPath . $model->filename, 0777);

                    // บันทึก path ไฟล์ในฐานข้อมูล
                    $model->path = $uploadPath . $model->filename;
                } else {
                    $model->filename = $oldFilename;
                }

                if ($model->save()) {
                    return $this->redirect(['meetingagenda/view', 'id' => $model->meeting_id]);
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }

    /**
     * Deletes an existing Agendasubx model.
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
     * Finds the Agendasubx model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agendasubx the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agendasubx::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionViewFile($id)
        {
            $model = $this->findModel($id);
            $filePath = 'uploads/meetings/' . $model->filename;

            if (file_exists($filePath)) {
                // อ่านไฟล์และแสดงเนื้อหา (เช่นรูปภาพหรือเอกสาร PDF)
                return Yii::$app->response->sendFile($filePath, null, ['inline' => true]);
            } else {
                throw new NotFoundHttpException('The requested file does not exist.');
            }
        }

    public function actionDownloadFile($id)
    {
        $model = $this->findModel($id);
        $filePath = 'uploads/meetings/' . $model->filename;

        if (file_exists($filePath)) {
            // ดาวน์โหลดไฟล์
            return Yii::$app->response->sendFile($filePath, $model->filename);
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
    }
}
