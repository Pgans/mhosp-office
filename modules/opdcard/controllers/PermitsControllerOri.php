<?php

namespace app\modules\opdcard\controllers;

use Yii;
use app\models\Permits;
use yii\data\ActiveDataProvider;
use app\modules\opdcard\models\Status;
use app\modules\opdcard\models\PermitsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\SearchFormx;

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
	########################################################################################
	

     public function sendTelegram($model) {
    $updater = $model->updater;

    if ($updater !== null) {
        $firstname = $updater->firstname;
        $lastname = $updater->lastname;
		
        $telegramToken = '7559782200:AAHvRkNmDm5-bGe3NKUGIsvjzEecJQDKuQA'; // Telegram Bot Token
        $chatId = "-4721636170"; // Chat ID กลุ่มเวชระเบียน

        $message = "<b>📢 ยืมเวชระเบียน</b>\n".
                   "🆔 <b>AN:</b> {$model->AN}\n".
                   "🏥 <b>HN:</b> {$model->HN}\n".
                   "👤 <b>ชื่อ:</b> {$model->fullname}\n".
                   "📌 <b>ผู้ยืม:</b> $firstname $lastname\n".
                   "📅 <b>วันที่ต้องการ:</b> {$model->day_want}";

        $telegramApi = "https://api.telegram.org/bot$telegramToken/sendMessage";

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegramApi);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        Yii::info("Telegram response: " . $server_output, 'app');

        $result = json_decode($server_output, true);
        if (!$result['ok']) {
            Yii::error("Telegram API Error: " . $result['description'], 'app');
            return false;
        }
        return true;
    }

    return false;
}


    public function actionIndex()
    {
        $searchModel = new PermitsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

		$searchModel = new SearchFormx();

        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            $connection = Yii::$app->db14;
            $an = $searchModel->an;

            // Check if $cid is exactly 6 digits
            if (strlen($an) === 6) {
                Yii::$app->session['an'] = $an;
				//echo $an;
                $sql = "SELECT c.hn, i.adm_id as 'an' ,c.cid,
				p.fname, p.lname,
				p.birthdate, p.sex, u.unit_name,i.adm_dt,i.dsc_dt,
                    p.TELEPHONE as 'tel',
                    t.TOWN_NAME 'บ้าน',
                    tt.TOWN_NAME as 'ตำบล',
                    ttt.TOWN_NAME as 'อำเภอ',
                    tttt.TOWN_NAME as 'จังหวัด'
                    FROM mbase_data1.ipd_reg i
					LEFT JOIN mbase_data1.opd_visits o ON o.visit_id = i.visit_id
				    LEFT JOIN mbase_data1.cid_hn c ON c.hn = o.hn
                    INNER JOIN mbase_data1.population p ON p.cid = c.cid
					LEFT JOIN mbase_data1.service_units u ON u.unit_id = i.WARD_NO
                    INNER JOIN mbase_data1.towns t on t.TOWN_ID = p.TOWN_ID 
                    INNER JOIN mbase_data1.towns tt on CONCAT(left(p.TOWN_ID,6),'00') = tt.TOWN_ID 
                    INNER JOIN mbase_data1.towns ttt on CONCAT(left(p.TOWN_ID,4),'0000') = ttt.TOWN_ID 
                    INNER JOIN mbase_data1.towns tttt on CONCAT(left(p.TOWN_ID,2),'000000') = tttt.TOWN_ID 
                    WHERE  i.adm_id = :an
                    LIMIT 1";

                $data = $connection->createCommand($sql, [':an' => $an])->queryOne();

                // Assuming you have a model for RequestHistory and a search model for it (RequestHistorySearch)
                $dataProvider1 = new ActiveDataProvider([
                    'query' => \app\models\Permits::find()->where(['an' => $an]),
                ]);

                if ($data || $dataProvider->totalCount > 0) {
                    return $this->render('index', [
                        'searchModel' => $searchModel,
                        'data' => $data,
                        'dataProvider' => $dataProvider1,
                    ]);
                } else {
                    Yii::$app->session->setFlash('warning', 'ไม่พบข้อมูลที่ค้นหา');
                }
            } else {
                Yii::$app->session->setFlash('warning', 'AN ต้องเป็นตัวเลข 6 หลัก');
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider1' => null, // Set a default value if needed
            'dataProvider' => $dataProvider,
        ]);
    }
 
    public function actionSaveRequest($an, $hn, $fname, $lname)
{
    $requestHistory = new \app\models\Permits();
    $requestHistory->AN = $an;
    $requestHistory->HN = $hn;
    $requestHistory->fullname = trim($fname) . ' ' . $lname;
    $requestHistory->created_by = Yii::$app->user->identity->id;
    $requestHistory->created_at = date('Y-m-d H:i:s');
    $requestHistory->treatments_id = '1';
    $requestHistory->status_id = '1';
    $requestHistory->day_want = date('Y-m-d H:i:s');

    if ($requestHistory->save()) {
        $result = $this->sendTelegram($requestHistory);  // ✅ เปลี่ยนตรงนี้

        if ($result === true) {
            Yii::$app->session->setFlash('success', 'บันทึกข้อมูลและส่ง Telegram เรียบร้อย');
        } else {
            Yii::$app->session->setFlash('warning', 'บันทึกข้อมูลเรียบร้อย แต่มีปัญหาในการส่ง Telegram');
        }
    } else {
        Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
    }

    return $this->redirect(['permits/index']);
}

/*
     private function sendLineNotification($model)
    {
        $username = Yii::$app->user->identity->username;
        #$person = Yii::$app->user->identity->person->firstname;
       //$person2 =  Yii::$app->user->identity->profile->firstname;
        $datetoday = date('Y-m-d H:i:s');
        $username = Yii::$app->user->identity->username;
        $user = User::findOne(['username' => $username]);
            $person = $user->person;
                $firstname = $person->firstname;
                $lastname = $person->lastname;
                $name = $person->firstname . ' ' . $person->lastname;

        $line_token = 'XTvLuqnWGaQ7h2P4smFrxbekF1GJrSBEfLuU9NimrG3';//สิทธิบัตร
        #$line_token = 'cfdpRl44nox1LUTTPWYppxN98w4WS0j1jB6dpPNB2FU'; //Line_Notify


        $message = 'ขอยืมเวชระเบียน:' . "\n" .
            'วันที่:' . $datetoday . "\n" .
            'AN:' . $model->AN . "\n" .
            'HN: ' . $model->HN . "\n" .
            'ชื่อผู้ป่วย: ' . $model->fullname . "\n" .
            'ผู้ขอยืม: ' . $name . "\n" ;
            //'เพื่อ: ' . $model->created_at;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "message=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
            "Authorization: Bearer $line_token",
        ]);

        $result = curl_exec($ch);

        // Logging for troubleshooting
        Yii::info("Line Notify Message: $message", 'app');
        Yii::info("Line Notify Result: $result", 'app');

        if ($result === false) {
            Yii::error("cURL Error: " . curl_error($ch), 'app');
            curl_close($ch);
            return false;
        }

        $result = json_decode($result, true);

        if ($result['status'] != 200) {
            Yii::error("Line Notify API Error: " . $result['message'], 'app');
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        return true;
    }
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
                'body' =>'ระบบทำการแจ้งเข้ากลุ่ม Telegram เวขระเบียน..... เรียบร้อยแล้วครับ',
                'options'=>['class'=>'alert-info'],
                  ]);
           //  $this->sendLine($model);//ส่งline notify
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
			$model->day_want = date('Y-m-d');
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
