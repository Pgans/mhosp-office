<?php

namespace app\module\personal\controllers;

use Yii;
use app\models\Person;
use app\module\personal\models\PersonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends Controller
{
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
                        'actions' => [ 'view'],
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
*/


    /**
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
       
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdmin()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
     * @param integer $user_id
     * @param string $dep_id
     * @param integer $positions_id
     * @return mixed
     */
    public function actionView($user_id, $dep_id, $positions_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $dep_id, $positions_id),
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Person();
        $user = new User();

        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
          $user->password_hash = Yii::$app->security->generatePasswordHash($user->password_hash);
          $user->auth_key = Yii::$app->security->generateRandomString();
          if ($user->save()) {
            $file = UploadedFile::getInstance($model, 'person_img');
            if ($file->size!=0) {
              $model->photo = $user->id.'.'.$file->extension;
              $file->saveAs('uploads/person/'.$user->id.'.'.$file->extension);
            }
           
            $model->user_id = $user->id;
            $model->save();
          }
            return $this->redirect(['admin', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id]);
        } else {
            $model->stop_date = '000-00-00';
            return $this->render('create', [
                'model' => $model,
                'user' => $user,
            ]);
        }
    }
	public function actionUpdate($user_id, $dep_id, $positions_id)
{
    $model = $this->findModel($user_id, $dep_id, $positions_id);
    $user = $model->user;
    $oldPass = $user->password_hash;

    if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
        
        // ตรวจสอบว่ารหัสผ่านเปลี่ยนหรือไม่ ถ้าเปลี่ยนให้ทำการ hash ใหม่
        if ($oldPass != $user->password_hash) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($user->password_hash);
        }

        // ถ้าผู้ใช้ถูกบันทึกเรียบร้อยแล้ว
        if ($user->save()) {

            // จัดการกับการอัปโหลดรูปภาพ
            $file = UploadedFile::getInstance($model, 'person_img');

            if ($file && $file->size != 0) {
                // ตั้งชื่อไฟล์เป็น user_id และนามสกุลไฟล์
                $fileName = $user->id . '.' . $file->extension;
                $filePath = 'uploads/person/' . $fileName;

                // บันทึกไฟล์ลงในโฟลเดอร์ uploads/person/
                if ($file->saveAs($filePath)) {
                    // อัปเดต path รูปภาพในคอลัมน์ photo ถ้าบันทึกไฟล์สำเร็จ
                    $model->photo = $fileName;
                }
            }

            // บันทึกข้อมูล model หลังจากจัดการรูปภาพ
            $model->save();
        }

        return $this->redirect(['admin', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id]);
    } else {
        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }
}


    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param string $dep_id
     * @param integer $positions_id
     * @return mixed
     */
	 /*
    public function actionUpdate($user_id, $dep_id, $positions_id)
    {
        $model = $this->findModel($user_id, $dep_id, $positions_id);
        $user = $model->user;
        $oldPass = $user->password_hash;

        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
          if ($oldPass!=$user->password_hash) {//เปลียนรหัสผ่านใหม่
            $user->password_hash = Yii::$app->security->generatePasswordHash($user->password_hash);
          }
          if ($user->save()) {
            $file = UploadedFile::getInstance($model, 'person_img');
            if (isset($file->size) && $file->size!==0) {
              $file->saveAs('uploads/person/' .$user->id.'.'.$file->extension);
            }
            $model->save();
          }

            return $this->redirect(['admin', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'user'=> $user,
            ]);
        }
    }
*/
    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param string $dep_id
     * @param integer $positions_id
     * @return mixed
     */
    public function actionDelete($user_id, $dep_id, $positions_id)
    {
        $this->findModel($user_id, $dep_id, $positions_id)->delete();

        return $this->redirect(['index']);
    }
	
    protected function findModel($user_id, $dep_id, $positions_id)
    {
        if (($model = Person::findOne(['user_id' => $user_id, 'dep_id' => $dep_id, 'positions_id' => $positions_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
