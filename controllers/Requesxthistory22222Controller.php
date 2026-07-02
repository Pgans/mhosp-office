<?php

namespace app\controllers;

use Yii;
use app\models\Requesxthistory;
use app\models\RequesxthistoryrSearch;
use app\models\Person;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RequestHistory; // เพิ่ม model ที่ใช้บันทึกข้อมูล
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use app\models\SearchForm;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
//use yii\web\User;
use yii\web\ForbiddenHttpException;

/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class RequesxthistoryController extends Controller
{
     public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'delete'],
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_USER,
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN]
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new RequesxthistoryrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Set pagination size to 5
        $dataProvider->pagination->pageSize = 5;

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        $searchModel = new SearchForm();

        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            $connection = Yii::$app->db14;
            $cid = $searchModel->cid;

            // Check if $cid is exactly 13 digits
            if (strlen($cid) === 13) {
                Yii::$app->session['cid'] = $cid;

                $sql = "SELECT c.hn, c.cid, p.fname, p.lname, p.birthdate, p.sex,
                    p.TELEPHONE as 'tel',
                    t.TOWN_NAME 'บ้าน',
                    tt.TOWN_NAME as 'ตำบล',
                    ttt.TOWN_NAME as 'อำเภอ',
                    tttt.TOWN_NAME as 'จังหวัด'
                    FROM mbase_data1.population p
                    INNER JOIN mbase_data1.cid_hn c ON c.cid = p.cid
                    INNER JOIN mbase_data1.towns t on t.TOWN_ID = p.TOWN_ID 
                    INNER JOIN mbase_data1.towns tt on CONCAT(left(p.TOWN_ID,6),'00') = tt.TOWN_ID 
                    INNER JOIN mbase_data1.towns ttt on CONCAT(left(p.TOWN_ID,4),'0000') = ttt.TOWN_ID 
                    INNER JOIN mbase_data1.towns tttt on CONCAT(left(p.TOWN_ID,2),'000000') = tttt.TOWN_ID 
                    WHERE  p.cid = :cid 
                    LIMIT 1";

                $data = $connection->createCommand($sql, [':cid' => $cid])->queryOne();

                // Assuming you have a model for RequestHistory and a search model for it (RequestHistorySearch)
                $dataProvider1 = new ActiveDataProvider([
                    'query' => \app\models\Requesxthistory::find()->where(['cid' => $cid]),
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
                Yii::$app->session->setFlash('warning', 'CID ต้องเท่ากับ 13 หลัก');
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider1' => null, // Set a default value if needed
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Requesxthistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Requesxthistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Requesxthistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Requesxthistory model.
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
     * Finds the Requesxthistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requesxthistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requesxthistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionSaveRequest($cid, $hn, $fname, $lname)
    {
        $requestHistory = new \app\models\Requesxthistory();
        $requestHistory->cid = $cid;
        $requestHistory->hn = $hn;
        $requestHistory->fullname = trim($fname) . ' ' . $lname;
        $requestHistory->created_by = Yii::$app->user->identity->id;
        $requestHistory->created_at = date('Y-m-d H:i:s');
		// Format start_date and end_date as Y-m-d
    //$requestHistory->start_date = Yii::$app->formatter->asDate($start_date, 'yyyy-MM-dd');
   // $requestHistory->end_date = Yii::$app->formatter->asDate($end_date, 'yyyy-MM-dd');
    
    // Set orther
    //$requestHistory->orther = $orther;
			
		$latestRequestHistory = RequestHistory::find()
    ->orderBy(['id' => SORT_DESC])
    ->one();

if ($latestRequestHistory !== null) {
    // แยกหมายเลขที่เป็นเลขจากฟิลด์ no เช่น '127/2567'
    $noParts = explode('/', $latestRequestHistory->no);
    $id = (int)$noParts[0];  // เลขหมายเลขที่เป็นตัวเลข เช่น 127
    $year = (int)$noParts[1]; // ปี เช่น 2567

    // เพิ่ม 1 ให้กับเลขหมายเลขที่เป็นตัวเลข
    $newId = $id + 1;

    // ตรวจสอบให้ปีใหม่ถูกต้องตามปีปัจจุบัน
    if ($year != (date('Y') + 543)) {
        // ถ้าปีไม่ตรงกับปีปัจจุบัน ให้รีเซ็ตเลขใหม่เป็น 1
        $newId = 1;
    }

    // สร้างหมายเลขใหม่ เช่น '128/2567' หรือ '1/2568'
    $requestHistory->no = $newId . '/' . (date('Y') + 543);
} else {
    // กรณีไม่มีประวัติ ให้เริ่มจาก 1
    $requestHistory->no = '1/' . (date('Y') + 543);
}


		$requestHistory->assemble_id =  '1' ;
		$requestHistory->status_id =  '1' ;
		$requestHistory->day_want = date('Y-m-d H:i:s');
		$username = Yii::$app->user->identity->username;
        
        if ($requestHistory->save()) {
            // Line Notify logic here
           // $result = $this->sendLineNotification($requestHistory);

            if ($result === true) {
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลและส่ง Line Notify เรียบร้อย');
            } else {
                Yii::$app->session->setFlash('warning', 'บันทึกข้อมูลเรียบร้อย แต่มีปัญหาในการส่ง Line Notify');
            }
        } else {
            Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

        return $this->redirect(['requesxthistory/index']);
    }
	###############################################################################################
	private function sendLineNotification($model)
{
    $datetoday = date('Y-m-d H:i:s');
    $username = Yii::$app->user->identity->username;
    $user = User::findOne(['username' => $username]);
    $person = $user->person;
    $name = $person->firstname . ' ' . $person->lastname;

    //$telegramToken = "7824960142:AAFtZTRlbOpjrJEuz04Z3fdmLgga_bTSUoM"; // Token ของ pgns_Bot
	 $telegramToken = '7559782200:AAHvRkNmDm5-bGe3NKUGIsvjzEecJQDKuQA'; // Telegram Bot Token
     $chatId = "-4721636170"; //Chat ID ของกลุ่มเวชระเบียน  

    // จัดรูปแบบข้อความให้กระชับและอ่านง่าย
    $message = "<b>📢 ขอประวัติการรักษา</b>\n".
               "📅 <b>วันที่:</b> $datetoday\n".
               "🆔 <b>CID:</b> {$model->cid}\n".
               "🏥 <b>HN:</b> {$model->hn}\n".
               "👤 <b>ชื่อผู้ป่วย:</b> {$model->fullname}\n".
               "✍️ <b>ผู้บันทึก:</b> $name";

    // API Telegram URL
    $telegramApi = "https://api.telegram.org/bot$telegramToken/sendMessage";

    // ตั้งค่าการส่งข้อมูล
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML' // รองรับ HTML formatting
    ];

    // ส่งข้อมูลผ่าน cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegramApi);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    // Logging สำหรับตรวจสอบ
    Yii::info("Telegram Message: $message", 'app');
    Yii::info("Telegram Result: $result", 'app');

    // ตรวจสอบผลลัพธ์
    $result = json_decode($result, true);
    if (!$result['ok']) {
        Yii::error("Telegram API Error: " . $result['description'], 'app');
        return false;
    }

    return true;
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


        $message = 'ขอประวัติการรักษา:' . "\n" .
            'วันที่:' . $datetoday . "\n" .
            'CID:' . $model->cid . "\n" .
            'HN: ' . $model->hn . "\n" .
            'ชื่อผู้ป่วย: ' . $model->fullname . "\n" .
            'ผู้บันทึก: ' . $name . "\n" ;
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
	*/
    /*
    public function actionSaveRequest($cid, $hn, $fname, $lname)
    {
        $requestHistory = new \app\models\RequestHistory();
        $requestHistory->cid = $cid;
        $requestHistory->hn = $hn;
        $requestHistory->fullname = trim($fname) . ' ' . $lname; // ใช้ CONCAT ใน SQL ก็ได้
        $requestHistory->created_by = Yii::$app->user->identity->id; // ให้ username เท่ากับ created_by
        // คำนวณวันเวลาใน created_at
        $requestHistory->created_at = date('Y-m-d H:i:s');
        $requestHistory->updated_at = '0000-00-00';
        $requestHistory->day_want = date('Y-m-d H:i:s');
        $requestHistory->status_id = 1;
        $requestHistory->assemble_id = 1;
        $requestHistory->no = '-';
        // คุณอาจต้องกำหนดค่าอื่น ๆ ตามที่ต้องการ

        if ($requestHistory->save()) {
            Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

        return $this->redirect(['requesxthistory/index']);
    }

    // Action สำหรับบันทึกไปที่ request_history
    public function actionSaveToHistory($cid, $hn, $fname)
    {
        try {
            $requestHistory = new RequestHistory();
            $requestHistory->cid = $cid;
            $requestHistory->hn = $hn;
            $requestHistory->fullname = $fname;
            // ทำการบันทึกข้อมูลลงใน request_history
            $requestHistory->save();
            Yii::$app->session->setFlash('success', 'บันทึกเรียบร้อยแล้ว');
        } catch (ErrorException $e) {
            Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการบันทึก');
        }

        // ปิด Modal หลังจากบันทึกเสร็จสิ้น
        $script = <<< JS
        $('#saveModal').modal('hide');
JS;

        $this->getView()->registerJs($script);

        return $this->redirect(['requesxthistory/index']);
    }
    */
    public function actionViewModal($id)
    {
        // Fetch data based on $id (e.g., from the database)
        $model = Requesxthistory::findOne($id);

        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    public function actionEditModal($id)
    {

        // Fetch data based on $id (e.g., from the database)
        $model = Requesxthistory::findOne($id);

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
	
}
