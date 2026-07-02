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
use yii\filters\AccessControl;
use app\models\User;
use app\components\AccessRule;

/**
 * PermitsController implements the CRUD actions for Permits model.
 */
class PermitsController extends Controller
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
                'only' => ['index', 'admin', 'create', 'update', 'view', 'delete'],
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['index', 'create', 'view', 'userview'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_USER,
                        ]
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'view'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions' => ['admin', 'index', 'create', 'update', 'view'],
                        'allow' => true,
                        'roles' => [
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

    /**
     * ฟังก์ชันส่ง Telegram (ปรับปรุงแล้ว)
     */
    /**
 * ฟังก์ชันส่ง Telegram (ปรับปรุงแล้ว)
 */
private function sendTelegram($model)
{
    try {
        // ตรวจสอบและโหลดข้อมูลผู้ยืม
        $updater = $model->updater ?? $model->createdBy;
        
        if ($updater === null) {
            Yii::error("ไม่พบข้อมูลผู้ยืมสำหรับ Permit ID: {$model->id}", 'telegram');
            return false;
        }

        $firstname = $updater->firstname ?? '';
        $lastname = $updater->lastname ?? '';
        
        // ดึงข้อมูลจาก config
        $telegramToken = Yii::$app->params['telegram']['token'] ?? '7559782200:AAHvRkNmDm5-bGe3NKUGIsvjzEecJQDKuQA';
        $chatId = Yii::$app->params['telegram']['chatId'] ?? '-4721636170';

        // วันที่ปัจจุบัน (NOW)
        $currentDateTime = date('Y-m-d H:i:s');

        // สร้างข้อความ
        $message = "<b>📢 แจ้งเตือนการยืมเวชระเบียน</b>\n\n" .
                   "🆔 <b>AN:</b> " . htmlspecialchars($model->AN) . "\n" .
                   "🏥 <b>HN:</b> " . htmlspecialchars($model->HN) . "\n" .
                   "👤 <b>ชื่อผู้ป่วย:</b> " . htmlspecialchars($model->fullname) . "\n" .
                   "📌 <b>ผู้ยืม:</b> " . htmlspecialchars("$firstname $lastname") . "\n" .
                   "📅 <b>วันที่ยืม:</b> " . htmlspecialchars($currentDateTime) . "\n" .
                   "⏰ <b>วันที่ต้องการ:</b> " . htmlspecialchars($model->day_want) . "\n\n" .
                   "⚠️ <i>**กรุณาส่งคืนภายใน 7 วัน**</i>";

        $telegramApi = "https://api.telegram.org/bot$telegramToken/sendMessage";

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        // ส่งข้อความผ่าน cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegramApi);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $server_output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ตรวจสอบ cURL error
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Yii::error("cURL Error: " . $error, 'telegram');
            return false;
        }

        curl_close($ch);

        // Log response
        Yii::info("Telegram response (HTTP $httpCode): " . $server_output, 'telegram');

        // ตรวจสอบผลลัพธ์
        $result = json_decode($server_output, true);

        if (!isset($result['ok']) || !$result['ok']) {
            $errorMsg = $result['description'] ?? 'Unknown error';
            Yii::error("Telegram API Error: " . $errorMsg, 'telegram');
            return false;
        }

        return true;

    } catch (\Exception $e) {
        Yii::error("Exception in sendTelegram: " . $e->getMessage(), 'telegram');
        return false;
    }
}

    /**
     * Index action
     */
    public function actionIndex()
    {
        $searchModel = new PermitsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        $searchModel = new SearchFormx();

        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            $connection = Yii::$app->db14;
            $an = $searchModel->an;

            // ตรวจสอบว่า AN เป็นตัวเลข 6 หลัก
            if (strlen($an) === 6) {
                Yii::$app->session['an'] = $an;

                $sql = "SELECT c.hn, i.adm_id as 'an', c.cid,
                        p.fname, p.lname,
                        p.birthdate, p.sex, u.unit_name, i.adm_dt, i.dsc_dt,
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
                        WHERE i.adm_id = :an
                        LIMIT 1";

                $data = $connection->createCommand($sql, [':an' => $an])->queryOne();

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
            'dataProvider1' => null,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * บันทึกข้อมูลการยืมเวชระเบียนจากการค้นหา
     */
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
            // ส่ง Telegram
            $telegramResult = $this->sendTelegram($requestHistory);

            if ($telegramResult === true) {
                Yii::$app->session->setFlash('success', '✅ บันทึกข้อมูลและส่ง Telegram เรียบร้อยแล้ว');
            } else {
                Yii::$app->session->setFlash('warning', '⚠️ บันทึกข้อมูลเรียบร้อย แต่มีปัญหาในการส่ง Telegram กรุณาตรวจสอบ Log');
            }
        } else {
            Yii::error('Save Error: ' . json_encode($requestHistory->errors), 'app');
            Yii::$app->session->setFlash('error', '❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

        return $this->redirect(['permits/index']);
    }

    /**
     * แสดงรายละเอียด
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * สร้างรายการยืมเวชระเบียนใหม่
     */
    public function actionCreate()
    {
        $model = new Permits();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->id;
            $model->created_at = date('Y-m-d H:i:s');
            
            if ($model->save()) {
                // ส่ง Telegram
                $telegramResult = $this->sendTelegram($model);
                
                if ($telegramResult === true) {
                    Yii::$app->session->setFlash('success', '✅ บันทึกข้อมูลและแจ้งเตือน Telegram เรียบร้อยแล้ว');
                } else {
                    Yii::$app->session->setFlash('warning', '⚠️ บันทึกข้อมูลเรียบร้อย แต่มีปัญหาในการส่ง Telegram');
                }
                
                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                Yii::error('Create Error: ' . json_encode($model->errors), 'app');
                Yii::$app->session->setFlash('error', '❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        } else {
            $model->day_want = date('Y-m-d');
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * แก้ไขข้อมูล
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_by = Yii::$app->user->identity->id;
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '✅ อัพเดทข้อมูลเรียบร้อยแล้ว');
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * ลบข้อมูล
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '🗑️ ลบข้อมูลเรียบร้อยแล้ว');
        return $this->redirect(['index']);
    }

    /**
     * ค้นหา Model จาก ID
     */
    protected function findModel($id)
    {
        if (($model = Permits::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('ไม่พบข้อมูลที่ต้องการ');
    }
}