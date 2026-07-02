<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\ProcessModel;
use Yii;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


class ProcessController extends Controller
{
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
            'only' => ['index', 'kill', 'update', 'view', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'index', 'kill', 'create', 'update'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = [6, 29, 52, 190, 285, 289]; // ตัวอย่าง user_id ที่ได้รับอนุญาต  6=pgans, 29=boom2518  toa=52  289=junmane 190=name  285=earth
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = [6, 28, 52, 190, 285, 289]; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
    ];
}
    public function actionIndex()
    {
        $model = new ProcessModel();
        $processList = $model->getProcessList();

        return $this->render('index', [
            'processList' => $processList
        ]);
    }

    public function actionKill($id)
    {
        $model = new ProcessModel();
        if ($model->killProcess($id)) {
            Yii::$app->session->setFlash('success', 'Kill Process สำเร็จ');
        }
        return $this->redirect(['index']);
    }
}