<?php

namespace app\controllers;

use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use Yii;
use kartik\mpdf\Pdf;
//use mpdf\src\Config\ConfigVariables;
//use mpdf\src\Config\FontVariables;
use mPDF;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadCSV;
use yii\web\UploadedFile;
use app\models\Closevisits;
use yii\web\NotFoundHttpException;
use app\models\CloseVisit; 
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


class CloseallController extends \yii\web\Controller
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
                'only'=> ['index','admit','create','update','view','a15er'],
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
                        'actions'=>['a15er','create','view'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['a15er','index','update','view'],
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
    public function actionIndexxxx()
    {
        // $_token = $model->token;


        return $this->render('indexxxx');
    }
    ################# ดึงข้อมูลให้ฟอร์มรายชื่อ ########################
  public function actionIndex()
{
   
    $sql = "SELECT 
                @n := @n + 1 AS 'No',
                data.*
            FROM (
                SELECT DISTINCT 
                    a.REG_DATETIME as regdate, 
                    a.visit_id as visit,
                    d.inscl, 
                    d.inscl_name, 
                    a.hn, 
                    p.cid,
					p.telephone,
                    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
                    CASE WHEN p.SEX = 1 THEN 'ชาย'
                         WHEN p.SEX = 2 THEN 'หญิง'
                    END AS `เพศ`,
                    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
                    i.ICD10_TM AS diag,                    
                    u.unit_id, 
                    u.unit_name,
					i.icd10_tm,
                    IFNULL(ak.claimcode, '') AS claimcode,
					IFNULL(cv.claimcode, '') AS enpoint,
                    CASE 
				WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
				THEN 0.00 
				ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
				END AS amount
				
                FROM opd_visits a
                INNER JOIN cid_hn c ON a.HN = c.HN
                INNER JOIN population p ON c.CID = p.CID
                LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1  
                LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
                LEFT JOIN main_inscls d ON a.inscl = d.inscl
                LEFT JOIN service_units u ON u.unit_id = a.unit_reg
                LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)
                LEFT JOIN close_visits cv ON cv.visit_id = a.visit_id 
				LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
                LEFT JOIN uc_inscl uc ON uc.CID=p.CID AND (uc.date_abort = date(a.REG_DATETIME) OR (day(uc.date_abort)=0 AND trim(uc.hospmain) <> '') ) 
                WHERE a.REG_DATETIME BETWEEN CURDATE() AND NOW()
                AND a.is_cancel = 0
				AND a.visit_id not in (select visit_id from ipd_reg)
				#AND od.icd10 <> ''
				AND a.unit_reg NOT IN ('42','51')
                #AND ISNULL(cv.claimcode)
                GROUP BY a.visit_id
				#ORDER BY u.unit_name DESC
            ) AS data,
            (SELECT @n := 0) AS init
            ORDER BY No DESC ";

    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
    }

    $visitProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pageSize' => 300,
        ],
        'sort' => [
            'attributes' => [
                'cid' => [
                    'asc' => ['cid' => SORT_ASC],
                    'desc' => ['cid' => SORT_DESC],
                    'label' => 'CID'
                ],
                'claimcode' => [
                    'asc' => ['claimcode' => SORT_ASC],
                    'desc' => ['claimcode' => SORT_DESC],
                    'label' => 'Authen Code'
                ],
                'enpoint' => [
                    'asc' => ['enpoint' => SORT_ASC],
                    'desc' => ['enpoint' => SORT_DESC],
                    'label' => 'End Point'
                ],
				'icd10_tm' => [
                    'asc' => ['icd10_tm' => SORT_ASC],
                    'desc' => ['icd10_tm' => SORT_DESC],
                    'label' => 'Diag'
                ],
				'unit_name' => [
                    'asc' => ['unit_name' => SORT_ASC],
                    'desc' => ['unit_name' => SORT_DESC],
                    'label' => 'แผนก'
                ],
            ],
        ],
    ]);
    
    


        #########################################################################
        $sqlCount1 = "SELECT COUNT(DISTINCT v.visit_id) as amount
            FROM log_closevisits v 
            WHERE v.messagecode = 'success'
			AND v.users = 'จองเคลม'
            AND v.send_date BETWEEN CURDATE() AND NOW()";

        $data = \yii::$app->db2->createCommand($sqlCount1)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amount = $data[$i]['amount'];
        }
        $sqlCamount = "SELECT COUNT(DISTINCT v.visit_id) as amountx
             FROM log_closevisits v 
             WHERE v.messagecode <> 'success'
			 AND v.users = 'จองเคลม'
             AND v.send_date BETWEEN CURDATE() AND NOW()";
        $data = \yii::$app->db2->createCommand($sqlCamount)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amountx = $data[$i]['amountx'];
        }
        $total = "SELECT COUNT(DISTINCT v.visit_id) as total
            FROM log_closevisits v 
            WHERE v.messagecode = 'success'
			AND v.users = 'จองเคลม'
            AND v.send_date BETWEEN '2024-09-01' AND NOW()
             ";

        $data = \yii::$app->db14->createCommand($total)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $total = $data[$i]['total'];
        }
		$todays = "SELECT COUNT(distinct o.VISIT_ID) as today
		FROM opd_visits o 
		INNER JOIN cid_hn c on o.HN= c.HN
		INNER JOIN population p on c.CID=p.CID #AND left(p.cid,5) <> '00000'
		LEFT JOIN service_units e ON o.UNIT_REG=e.unit_id          
		LEFT JOIN main_inscls f ON o.INSCL = f.INSCL
		LEFT JOIN authen_kiosk ak ON p.CID = ak.cid  AND date(o.REG_DATETIME)=date(ak.d_update)
		LEFT JOIN cost_visits cos ON cos.visit_id = o.visit_id  AND cos.is_cancel = 0
		LEFT  JOIN opd_diagnosis d1 ON d1.visit_id = o.visit_id AND d1.is_cancel = 0 AND d1.dxt_id = 1
		LEFT JOIN icd10new icd1 ON icd1.icd10 = d1.icd10 
		WHERE o.REG_DATETIME BETWEEN CURDATE() AND NOW()
		#AND o.unit_reg <> '42'  ### HD ######
        AND p.NATN_ID = 99
        AND o.is_cancel = 0
        #AND o.visit_id not in (SELECT visit_id FROM ipd_reg)
    
             ";

        $data = \yii::$app->db2->createCommand($todays)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $todayx = $data[$i]['today'];
        }
        $cidalien = "SELECT COUNT(DISTINCT o.VISIT_ID) as alienx
        FROM opd_visits o
        LEFT JOIN cid_hn c ON c.hn = o.hn
        LEFT JOIN population p ON p.cid = c.cid
        WHERE o.REG_DATETIME BETWEEN CURDATE() AND NOW() 
        AND o.IS_CANCEL = 0
        AND p.natn_id <> '99'";
        $data = \yii::$app->db2->createCommand($cidalien)->queryOne();
        $alien = $data['alienx']; 
		
		 $homeward = "SELECT COUNT(VISIT_ID) as homeward  FROM ipd_reg WHERE  adm_dt BETWEEN CURDATE() AND NOW()  AND WARD_NO = '50'";
        $data = \yii::$app->db2->createCommand($homeward)->queryOne();
        $homeward = $data['homeward']; 

        $today_ipd = "SELECT COUNT(DISTINCT o.VISIT_ID) as todayxx
        FROM opd_visits o
        WHERE o.REG_DATETIME BETWEEN CURDATE() AND NOW() 
        AND o.IS_CANCEL = 0
        #AND o.VISIT_ID NOT IN (SELECT VISIT_ID FROM ipd_reg)";

        $data = \yii::$app->db2->createCommand($today_ipd)->queryOne();
        $todayipd = $data['todayxx'];

       


        ########################################################################################################
        $sqlPass = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date
        FROM log_closevisits l 
        WHERE l.send_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
        AND l.messagecode = 'success' AND l.users = 'จองเคลม'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db14->createCommand($sqlPass)->queryAll();

        // สร้าง Flash Alert
        //Yii::$app->session->setFlash('success', 'รายการที่ไม่ผ่านตามเงื่อนไข');

        $passProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        ########################################################################################################
        $sqlError = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date
        FROM log_closevisits l 
        WHERE l.send_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
        AND l.messagecode <> 'success' AND l.users = 'จองเคลม'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db14->createCommand($sqlError)->queryAll();

        // สร้าง Flash Alert
        //Yii::$app->session->setFlash('success', 'รายการที่ไม่ผ่านตามเงื่อนไข');

        $errorProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
		
		
        return $this->render('index', [
            // 'searchModel' => $searchModel,
            'visitProvider' => $visitProvider,
            'amount' => $amount,
            'amountx' => $amountx,
            'todayx' => $todayx,
            'alien' => $alien,
			'homeward' => $homeward,
            'todayipd' => $todayipd,
			'total' => $total,
            'passProvider' => $passProvider,
            'errorProvider' => $errorProvider,
			

        ]);
    }
    #########  อ่านบัตรประชาชน ######################################################
	
   public function actionReadSmartCard()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    // API URL
    $url = 'http://192.168.200.31:8189/api/smartcard/read';

    // คำขอ CURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Cookie: TS01e80166=013bd252cb92f51716a1ea0f8eeca789f1667d7859c5d016175e4a4e5556b950058f436b39aa661efbf11e2f2a90391a334d4abf07'
        ],
    ]);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);
    curl_close($curl);

    // ถ้าไม่สามารถติดต่อเซิร์ฟเวอร์ได้
    if ($response === false || $http_code !== 200) {
        return [
            'status' => 'error',
            'message' => '❌ ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ (' . $curl_error . ')',
        ];
    }

    $agent = json_decode($response, true);

    // ✅ ถ้าไม่มีค่า pid (กรณีบัตรถูกถอดออก)
    if (!isset($agent['pid']) || empty($agent['pid'])) {
        Yii::$app->session->destroy(); // ล้าง session ทั้งหมด
        Yii::$app->session->open(); // เปิด session ใหม่
        return [
            'status' => 'error',
            'message' => '❌ กรุณาเสียบบัตรประชาชน',
        ];
    }

    $current_pid = Yii::$app->session->get('pid');

    // ✅ ถ้าเสียบบัตรใหม่ และ pid เปลี่ยนไป → ล้าง session
    if ($current_pid && $current_pid !== $agent['pid']) {
        Yii::$app->session->destroy();
        Yii::$app->session->open();
    }

    // ✅ บันทึก pid ลง session ใหม่
    Yii::$app->session->set('pid', $agent['pid']);

    return [
        'status' => 'success',
        'pid' => $agent['pid'],
        'title' => isset($agent['titleName']) ? $agent['titleName'] : '',
        'fname' => isset($agent['fname']) ? $agent['fname'] : '',
        'lname' => isset($agent['lname']) ? $agent['lname'] : '',
        'birthdate' => isset($agent['birthDate']) ? $agent['birthDate'] : '',
        'sex' => isset($agent['sex']) ? $agent['sex'] : '',
        'age' => isset($agent['age']) ? $agent['age'] : '',
        'maininscl' => isset($agent['mainInscl']) ? $agent['mainInscl'] : '',
        'subinscl' => isset($agent['subInscl']) ? $agent['subInscl'] : '',
        'claimTypes' => isset($agent['claimTypes']) ? $agent['claimTypes'] : [],
        'correlationId' => isset($agent['correlationId']) ? $agent['correlationId'] : '',
    ];
}


#########################################################################################
    ################ ActionHt-> ActionCheck #########################
	// สำหรับฟังก์ชัน check สำหรับ API fdh.moph.go.th
public function actionCheck()
{
    // ดึงค่า pid จาก session
    $pid = Yii::$app->session->get('pid', '3341400051241');
    // ดึงค่า pid จาก session
	/*
    $pid = Yii::$app->session->get('pid');

    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        return $this->redirect(['index']);
    }
	*/
	
    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        return $this->redirect(['index']);
    }

    // ดึง Token
    $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
    $data = \Yii::$app->db2->createCommand($sqltoken)->queryOne();
    $token_fdh = isset($data['token30']) ? $data['token30'] : null;

    if (!$token_fdh) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบ Token');
        return $this->redirect(['index']);
    }

    $visit = Yii::$app->request->post('visit');
    echo $visit;

    // ดึงข้อมูล visit จากฐานข้อมูล
    $strVn = "SELECT 
                DATE_FORMAT(a.REG_DATETIME, '%Y-%m-%d %H:%i') AS regdate,
                DATE_FORMAT(cos.dt_timestamp, '%Y-%m-%d %H:%i') AS invoicedate,
                a.visit_id as visit,
                '10953' as hospital_code,
                0.00 as paid_amount,
                'WEL' as inscl, 
                '2' as type,
                '$pid' as recorder_pid,
                '' as authen_code_source_id,
                'e0634600-f8d9-4289-a29a-4630b5b130c1' as authen_code_token,
                d.inscl_name, 
                a.hn, 
                p.cid,
                CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
                TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS age,
                CASE 
                    WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
                    THEN 50.00 
                    ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
                END AS amount
            FROM opd_visits a
            INNER JOIN cid_hn c ON a.HN = c.HN
            INNER JOIN population p ON c.CID = p.CID
            LEFT JOIN main_inscls d ON a.inscl = d.inscl
            LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
            WHERE a.visit_id = '$visit'
            AND a.is_cancel = 0
            LIMIT 1";

    $closeRow = Yii::$app->db2->createCommand($strVn)->bindValue(':visit', $visit)->bindValue(':pid', $pid)->queryOne();

    if (!$closeRow) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบข้อมูล visit ในฐานข้อมูล');
      //  return $this->redirect(['index']);
    }

    // เตรียม JSON สำหรับส่ง API
    $resultArray = [
        "hospital_code" => $closeRow['hospital_code'],
        "main_inscl" => $closeRow['inscl'],
        "service_date_time" => $closeRow['regdate'],
        "invoice_date_time" => $closeRow['invoicedate'],
        "total_amount" => $closeRow['amount'],
        "paid_amount" => $closeRow['paid_amount'],
        "privilege_amount" => $closeRow['amount'],
        "pid" => $closeRow['cid'],
        "recorder_pid" => $closeRow['recorder_pid'],
        "visit_number" => $closeRow['visit'],
        "type" => $closeRow['type'],
        "authen_code_source_id" => $closeRow['authen_code_source_id'],
        "authen_code_token" => $closeRow['authen_code_token']
    ];

    $resultText = json_encode($resultArray, JSON_PRETTY_PRINT);

    // ตั้งค่า cURL
    $url = "https://fdh.moph.go.th/api/v1/authen_code";
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $resultText,
        CURLOPT_HTTPHEADER => [
            "Content-type: application/json",
            "Authorization: Bearer " . $token_fdh
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
//echo $response;
    // ตรวจสอบข้อผิดพลาด cURL
    if (!$response || $err) {
        Yii::$app->session->setFlash('error', '❌ ไม่สามารถเชื่อมต่อ API ได้: ' . $err);
       // return $this->redirect(['index']);
    }

    $closevisit = json_decode($response, true);

    if (!isset($closevisit['data'])) {
        Yii::$app->session->setFlash('error', '❌ API ตอบกลับข้อมูลผิดพลาด: ' . $response);
        //return $this->redirect(['index']);
    }

    // ดึงค่าจาก API
    $message = isset($closevisit['message']) ? addslashes($closevisit['message']) : "";
    $message_th = isset($closevisit['message_th']) ? addslashes($closevisit['message_th']) : "";
    $transaction_id = isset($closevisit['data']['transaction_id']) ? $closevisit['data']['transaction_id'] : "";
    $claimcode = isset($closevisit['data']['authen_code']) ? $closevisit['data']['authen_code'] : "";

    // บันทึกข้อมูลลงฐานข้อมูล
    if (!empty($message) && !empty($transaction_id)) {
        $strSQL = "INSERT INTO close_visits (
            visit_id, cid, transaction_id, claimtype, claimcode, recorder_pid, message, claim_datetime, total_amount, d_update
        ) VALUES (
            :visit, :cid, :transaction_id, '', :claimcode, :recorder_pid, :message, :regdate, :total_amount, NOW()
        )";

        Yii::$app->db2->createCommand($strSQL)
            ->bindValue(':visit', $closeRow['visit'])
            ->bindValue(':cid', $closeRow['cid'])
            ->bindValue(':transaction_id', $transaction_id)
            ->bindValue(':claimcode', $claimcode)
            ->bindValue(':recorder_pid', $closeRow['recorder_pid'])
            ->bindValue(':message', $message)
            ->bindValue(':regdate', $closeRow['regdate'])
            ->bindValue(':total_amount', $closeRow['amount'])
            ->execute();
    }

    Yii::$app->session->addFlash('info', $message_th);
Yii::$app->session->addFlash('success', "✅ เพิ่มข้อมูลสำเร็จ!VISIT: {$closeRow['visit']}Endpoint: {$claimcode}");
    return $this->redirect(['index']);
}
########################################################################################################################
// สำหรับฟังก์ชัน check สำหรับ API nhso-service
public function actionCheckNhso($cid, $visit_id)
{
    if (empty($cid) || empty($visit_id)) {
        Yii::$app->session->setFlash('error', 'ข้อมูลไม่ครบถ้วน');
       // return $this->redirect(['index']);
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'http://192.168.200.63:8189/api/nhso-service/latest-5-authen-code-all-hospital/' . $cid,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Cookie: TS01e80146=013bd252cb92f51720a1ea0f8eeca789f1467d7859c5d018175e4a4e5556b950058f436b39aa661efbf11e2f2a90391a334d4abf07'
        ],
    ]);

    $response = curl_exec($curl);
    $authen = json_decode($response, true);
    curl_close($curl);
echo $Response;
    if (!is_array($authen) || empty($authen)) {
        Yii::$app->session->setFlash('error', 'ไม่พบข้อมูลจาก API');
       // return $this->redirect(['index']);
    }

    if (isset($authen['claimCode'])) {
        $authen = [$authen];
    }

    $claimsByDate = [];
    $today = date('Y-m-d');

    foreach ($authen as $item) {
        $claimDateTime = isset($item['claimDateTime']) ? $item['claimDateTime'] : null;
        $claimCode = isset($item['claimCode']) ? $item['claimCode'] : null;
        $hcode = isset($item['hcode']) ? $item['hcode'] : null;

        if ($claimDateTime && $claimCode && $hcode == 10953) {
            $claimDate = date('Y-m-d', strtotime($claimDateTime));

            if (!isset($claimsByDate[$claimDate])) {
                $claimsByDate[$claimDate] = [
                    'EP' => []  // เก็บแค่ EP เท่านั้น
                ];
            }

            // เลือกเฉพาะ claimCode ที่ขึ้นต้นด้วย 'EP'
            if (strpos($claimCode, 'EP') === 0) {
                $claimsByDate[$claimDate]['EP'][] = $item;
            }
        }
    }

    // เลือก Claim ที่เข้าเงื่อนไข
    $selectedClaims = [];
    foreach ($claimsByDate as $date => $claims) {
        if ($date === $today && !empty($claims['EP'])) {
            // เลือก EP ถ้ามี และเป็นวันนี้
            $selectedClaims = array_merge($selectedClaims, $claims['EP']);
        }
    }

    // ตรวจสอบว่ามีการเลือกข้อมูลหรือไม่
    if (!empty($selectedClaims)) {
        $importedCount = 0; // ตัวนับจำนวนที่นำเข้า
        foreach ($selectedClaims as $claim) {
            $claimType = isset($claim['claimType']) ? $claim['claimType'] : null;
            $telephone = isset($claim['telephone']) ? $claim['telephone'] : '';
            $claimCode = isset($claim['claimCode']) ? $claim['claimCode'] : null;
            $claimDateTime = isset($claim['claimDateTime']) ? $claim['claimDateTime'] : null;

            // ตรวจสอบและนำเข้าข้อมูล
            $searchSQL = "SELECT COUNT(*) FROM close_visits WHERE claimcode = :claimcode";
            $existingCount = Yii::$app->db2->createCommand($searchSQL)
                ->bindValue(':claimcode', $claimCode)
                ->queryScalar();

            if ($existingCount == 0) {
                // เพิ่มข้อมูลใหม่
                $insertSQL = "
                    INSERT INTO close_visits (cid, visit_id, claimtype, claimcode, claim_datetime, d_update)
                    VALUES (:cid, :visit_id, :claimtype, :claimcode, :claim_datetime, NOW())
                ";
                Yii::$app->db2->createCommand($insertSQL)
                    ->bindValues([
                        ':cid' => $cid,
                        ':visit_id' => $visit_id,
                        ':claimtype' => $claimType,
                        ':claimcode' => $claimCode,
                        ':claim_datetime' => $claimDateTime,
                    ])
                    ->execute();

                $importedCount++;
            } else {
                // อัปเดตข้อมูลที่มีอยู่แล้ว
                $updateSQL = "
                    UPDATE close_visits
                    SET visit_id = :visit_id, claim_datetime = :claim_datetime, d_update = NOW()
                    WHERE claimcode = :claimcode
                ";
                Yii::$app->db2->createCommand($updateSQL)
                    ->bindValues([
                        ':visit_id' => $visit_id,
                        ':claim_datetime' => $claimDateTime,
                        ':claimcode' => $claimCode,
                    ])
                    ->execute();
            }
        }

        // แสดงผลจำนวนที่นำเข้า
       Yii::$app->session->addFlash('info', "📊 นำเข้าข้อมูลทั้งหมด $importedCount รายการ! cid: $cid, claimCode: $claimCode");

    } else {
        // ถ้าไม่มีข้อมูลที่เลือก
        Yii::$app->session->setFlash('error', 'ไม่พบการปิดสิทธิ์. cid: ' . $cid );
    }

    return $this->redirect(['index']);
}
#######################################################################################
public function actionCheck1()
{
	/*
	 // ดึงค่า pid จาก session
    $pid = Yii::$app->session->get('pid');

    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        return $this->redirect(['index']);
    }
	
	*/
    // pid fix
     $pid = Yii::$app->session->get('pid', '1340900258476');

    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        //return $this->redirect(['index']);
    }

    // ดึง Token
    $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
    $data = \Yii::$app->db2->createCommand($sqltoken)->queryOne();
    $token_fdh = isset($data['token30']) ? $data['token30'] : null;

    if (!$token_fdh) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบ Token');
       // return $this->redirect(['index']);
    }
 $visit = Yii::$app->request->post('visit');
echo $visit; 

if (!$visit) {
    Yii::$app->session->setFlash('error', '❌ ไม่พบข้อมูล visit');
    //return $this->redirect(['index']);
}

// ดึงข้อมูล visit จากฐานข้อมูล
$strVn = "SELECT
    '10953' AS sourceId,
    b.visit_id AS transId, 
    p.cid,
    TRIM(p.fname) AS firstName,
    'M' AS midName,
    TRIM(p.lname) AS lastName,
    p.sex,
    p.telephone AS phone,
    '0' AS latitude,
    '0' AS longitude,
    e.unit_name,
    b.hn,
    '' AS an,
    '3341400051241' AS recorderPid,
    'PG0060001' AS serviceCode,
    p.HOME_ADR AS house,
    p.TOWN_ID AS subDistrictCode,
    TRIM(t1.TOWN_NAME) AS subDistrict,
    LEFT(p.TOWN_ID, 4) AS districtCode,
    t2.TOWN_NAME AS district,
    LEFT(p.town_id, 2) AS provinceCode,
    t3.TOWN_NAME AS province,
    LEFT(p.town_id, 5) AS postCode,
    p.birthdate AS birthDay,
    b.weight AS weight,
    b.height AS height,
    m.nhso_code AS mainInscl,
    b.REG_DATETIME AS adm_dt,
    b.FINISH_DATETIME AS dsc_dt,
    CONCAT(TRIM(p.FNAME), ' ', TRIM(p.LNAME)) AS fullname,
    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, b.REG_DATETIME) AS age,
    #ak.visit_id,
    b.visit_id AS visit,
    i.icd10_tm,
    p.telephone,
    p.rl_phone,
    p.mother,
    m.inscl_name,
    IFNULL(ak.claimtype, '') AS claimtype,
    IFNULL(ak.claimcode, '') AS claimKiosk,
    COALESCE(cv.claimcode, '') AS closevisit
FROM opd_visits b
INNER JOIN cid_hn c ON b.HN = c.HN
LEFT JOIN population p ON c.CID = p.CID
LEFT JOIN service_units e ON b.UNIT_REG = e.unit_id
LEFT JOIN authen_kiosk ak ON ak.visit_id = b.VISIT_ID AND DATE(ak.d_update) = DATE(b.reg_datetime)
LEFT JOIN close_visits cv ON cv.visit_id = b.VISIT_ID
LEFT JOIN opd_diagnosis od ON od.visit_id = b.visit_id
LEFT JOIN icd10new i ON i.icd10 = od.ICD10
LEFT JOIN main_inscls m ON m.inscl = b.inscl
LEFT JOIN towns t ON p.town_id = t.town_id
LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id,6),'00') = t1.town_id
LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id,4),'0000') = t2.town_id
LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id,2),'000000') = t3.town_id
WHERE 
    b.IS_CANCEL = 0
    AND b.visit_id = :visit
    AND b.UNIT_REG NOT IN ('42','51')
    AND p.NATN_ID = '99'
    AND cv.claimcode IS NULL  
GROUP BY 
    b.VISIT_ID, p.cid, p.fname, p.lname, p.sex, p.telephone, e.unit_name, p.TOWN_ID
ORDER BY ak.claimcode
        LIMIT 1";

  $closeRow = Yii::$app->db2->createCommand($strVn)->bindValue(':visit', $visit)->queryOne();

    if (!$closeRow) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบข้อมูล visit ในฐานข้อมูล');
       // return $this->redirect(['index']);
    }

    // เตรียม JSON สำหรับส่ง API
   
$resultArray = [
    "sourceId" => $closeRow['sourceId'],
    "transId" => $closeRow['transId'],
    "pid" => $closeRow['cid'],
	"firstName" => $closeRow['firstName'],
	"midName" => $closeRow['midName'],
	"lastName" => $closeRow['lastName'],
    "sex" => $closeRow['sex'],
    "phone" => [
        $closeRow['phone']
    ],
    "latitude" => 0,
    "longitude" => 0,
    "hcode" => $closeRow['sourceId'],
    "hn" => $closeRow['hn'],
    "an" => $closeRow['an'],
    "recorderPid" => $closeRow['recorderPid'],
    "serviceCode" => $closeRow['serviceCode'],
    "house" => $closeRow['house'],
    "subDistrictCode" => $closeRow['subDistrictCode'],
    "subDistrict" => $closeRow['subDistrict'],
    "districtCode" => $closeRow['districtCode'],
    "district" => $closeRow['district'],
    "provinceCode" => $closeRow['provinceCode'],
    "province" => $closeRow['province'],
    "postCode" => $closeRow['postCode'],
    "birthDay" => $closeRow['birthDay'],
    "weight" => $closeRow['weight'],
    "height" => $closeRow['height'],
    "maininscl" => $closeRow['mainInscl']
];

// เปลี่ยนเป็น JSON
$resultText = json_encode($resultArray, JSON_PRETTY_PRINT);
echo "<pre>🔹 JSON ที่ส่งไปยัง API:</pre>";
echo "<pre>" . print_r($resultText, true) . "</pre>";

// ตั้งค่า cURL
$url = "https://nhsoapi.nhso.go.th/nhsoendpoint/api/AuthenCode"; 
$curl = curl_init($url);

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $resultText,  
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer e0634600-f8d9-4289-a29a-4630b5b130c1',
        'Content-Type: application/json',
        'Cookie: TS01304219=013bd252cb3290ae23925080178311fed52a45df35254537ba23df8c4a70e6cca4026c62c841b713fa74f687dc713dc94b38a7d0d6'
    ]
]);// ✅ ดำเนินการ cURL
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// ✅ แสดง Response ที่ได้จาก API
//echo "<pre>🔹 Response จาก API:</pre>";
echo "<pre>" . print_r($response, true) . "</pre>";

// ✅ แปลง Response เป็น JSON
$closevisit = json_decode($response, true);

// ✅ ตรวจสอบว่า API ส่งข้อมูล JSON ที่ถูกต้องหรือไม่
if (json_last_error() !== JSON_ERROR_NONE) {
    Yii::$app->session->setFlash('error', '❌ รูปแบบ JSON ไม่ถูกต้อง: ' . json_last_error_msg());
    //return; // ออกจากฟังก์ชัน
}

// ✅ ตรวจสอบว่า API ตอบกลับมี authenCode หรือไม่
if (!isset($closevisit['authenCode'])) {
    Yii::$app->session->setFlash('error', '❌ API ตอบกลับข้อมูลผิดพลาด: ' . json_encode($closevisit, JSON_PRETTY_PRINT));
   // return; // ออกจากฟังก์ชัน
}

// ✅ ดึงข้อมูลจาก API และฐานข้อมูล
$claimCode = $closevisit['authenCode'];
$cid = isset($closeRow['cid']) ? $closeRow['cid'] : null;
$visitid = isset($closeRow['visit']) ? $closeRow['visit'] : null;
$claimType = isset($closeRow['serviceCode']) ? $closeRow['serviceCode'] : null;
$telephone = isset($closeRow['telephone']) ? $closeRow['telephone'] : null;
echo "<pre>";
echo "CID: $cid\n";
echo "Visit ID: $visitid\n";
echo "Claim Type: $claimType\n";
echo "Telephone: $telephone\n";
echo "</pre>";

// ✅ ตรวจสอบค่าที่ต้องใช้
if (!$cid || !$visitid || !$claimType || !$telephone) {
    Yii::$app->session->setFlash('error', '❌ ข้อมูลไม่ครบถ้วนสำหรับการบันทึก!');
    //return; // ออกจากฟังก์ชัน
}

// ✅ ตรวจสอบว่า ClaimCode มีอยู่ในฐานข้อมูลหรือไม่
$checkSQL = "SELECT COUNT(*) FROM authen_kiosk WHERE claimcode = :authencode";
$command = Yii::$app->db2->createCommand($checkSQL);
$command->bindValue(':authencode', $claimCode);
$claimExists = $command->queryScalar();

try {
    if ($claimExists) {
        // 🔹 มีรหัส claimCode ในตาราง -> ทำการ UPDATE
        $updateSQL = "
            UPDATE authen_kiosk
            SET visit_id = :visit_id, d_update = NOW()
            WHERE claimcode = :authencode
        ";

        $command = Yii::$app->db2->createCommand($updateSQL);
        $command->bindParam(':visit_id', $visitid);
        $command->bindParam(':authencode', $claimCode);
        $command->execute();

        Yii::$app->session->setFlash('success', '✅ บันทึกข้อมูลสำเร็จ! ' . $claimCode);

    } else {
        // 🔹 ไม่มีข้อมูล -> ทำการ INSERT
        $insertSQL = "
            INSERT INTO authen_kiosk (cid, visit_id, claimtype, claimcode, mobile, dep_name, d_update)
            VALUES (:cid, :visit_id, :claimtype, :authencode, :mobile, 'name', NOW())
        ";

        $command = Yii::$app->db2->createCommand($insertSQL);
        $command->bindParam(':cid', $cid);
        $command->bindParam(':visit_id', $visitid);
        $command->bindParam(':claimtype', $claimType);
        $command->bindParam(':authencode', $claimCode);
        $command->bindParam(':mobile', $telephone);
        $command->execute();

       Yii::$app->session->setFlash('success', "✅ บันทึกข้อมูลสำเร็จ! cid: $cid, claimCode: $claimCode");

    }
} catch (\yii\db\Exception $e) {
    Yii::$app->session->setFlash('error', '❌ ไม่สามารถดำเนินการกับฐานข้อมูล: ' . $e->getMessage());
}

// ✅ กลับไปที่หน้าเดิม
 return $this->redirect(['index']);
}
###############################################################################################################################################################
// สำหรับฟังก์ชัน check สำหรับ API nhso-service
public function actionCheckNhso1($cid, $visit_id)
{
    if (empty($cid) || empty($visit_id)) {
        Yii::$app->session->setFlash('error', 'ข้อมูลไม่ครบถ้วน');
        //return $this->redirect(['index']);
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'http://192.168.200.63:8189/api/nhso-service/latest-5-authen-code-all-hospital/' . $cid,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Cookie: TS01e80146=013bd252cb92f51720a1ea0f8eeca789f1467d7859c5d018175e4a4e5556b950058f436b39aa661efbf11e2f2a90391a334d4abf07'
        ],
    ]);

    $response = curl_exec($curl);
    $authen = json_decode($response, true);
    curl_close($curl);
  echo $response;
    // ตรวจสอบว่า response เป็น array หรือไม่
    if (!is_array($authen) || empty($authen)) {
        Yii::$app->session->setFlash('error', 'ไม่พบข้อมูลจาก API');
       // return $this->redirect(['index']);
    }

    // กรณี response เป็น object ที่มี claimCode เดียว
    if (isset($authen['claimCode'])) {
        $authen = [$authen]; 
    }

    // ตรวจสอบและจัดกลุ่ม claim ตามวันที่
    $claimsByDate = [];
    foreach ($authen as $item) {
        $claimDateTime = isset($item['claimDateTime']) ? $item['claimDateTime'] : null;
        $claimCode = isset($item['claimCode']) ? $item['claimCode'] : null;
        $hcode = isset($item['hcode']) ? $item['hcode'] : null;

        if ($claimDateTime && $claimCode && $hcode == 10953) {
            $claimDate = date('Y-m-d', strtotime($claimDateTime));

            if (!isset($claimsByDate[$claimDate])) {
                $claimsByDate[$claimDate] = [
                    'PP' => [],
                    'EP' => []
                ];
            }

            if (strpos($claimCode, 'PP') === 0) {
                $claimsByDate[$claimDate]['PP'][] = $item;
            } elseif (strpos($claimCode, 'EP') === 0) {
                $claimsByDate[$claimDate]['EP'][] = $item;
            }
        }
    }

    // เลือก Claim ที่เข้าเงื่อนไข
    $selectedClaims = [];
    $today = date('Y-m-d');

    foreach ($claimsByDate as $date => $claims) {
        if ($date === $today && !empty($claims['EP'])) {
            // เลือก EP ถ้ามี และเป็นวันนี้
            $selectedClaims = array_merge($selectedClaims, $claims['EP']);
        }
    }

    // ตรวจสอบว่าเลือกข้อมูลหลายรายการแล้วหรือไม่
    if (!empty($selectedClaims)) {
        $importedCount = 0; // ตัวนับจำนวนที่นำเข้า
        foreach ($selectedClaims as $claim) {
            $claimType = isset($claim['claimType']) ? $claim['claimType'] : null;
            $telephone = isset($claim['telephone']) ? $claim['telephone'] : '';
            $claimCode = isset($claim['claimCode']) ? $claim['claimCode'] : null;
            $claimDateTime = isset($claim['claimDateTime']) ? $claim['claimDateTime'] : null;

            // ตรวจสอบว่ามีข้อมูลอยู่ในฐานข้อมูลหรือไม่
            $searchSQL = "SELECT COUNT(*) FROM authen_kiosk WHERE claimcode = :authencode";
$existingCount = Yii::$app->db2->createCommand($searchSQL)
    ->bindValue(':authencode', $claimCode) // ✅ ใช้ชื่อเดียวกัน
    ->queryScalar();


            if ($existingCount == 0) {
                // เพิ่มข้อมูลใหม่
               $insertSQL = "
                    INSERT INTO close_visits (cid, visit_id, claimtype, claimcode, claim_datetime, d_update)
                    VALUES (:cid, :visit_id, :claimtype, :claimcode, :claim_datetime, NOW())
                ";
                Yii::$app->db2->createCommand($insertSQL)
                    ->bindValues([
                        ':cid' => $cid,
                        ':visit_id' => $visit_id,
                        ':claimtype' => $claimType,
                        ':claimcode' => $claimCode,
                        ':claim_datetime' => $claimDateTime,
                    ])
                    ->execute();

                $importedCount++;
                Yii::$app->session->addFlash('success', "✅ เพิ่มข้อมูลสำเร็จ!VISIT: $visit_id,  Endpoint: $claimCode");
            } else {
                // อัปเดตข้อมูลที่มีอยู่แล้ว
                $updateSQL = "
                     UPDATE authen_kiosk
						SET visit_id = :visit_id, d_update = NOW()
						WHERE claimcode = :authencode
                ";
                $command = Yii::$app->db2->createCommand($updateSQL);
				$command->bindParam(':visit_id', $visitid);
				$command->bindParam(':authencode', $claimCode);
				$command->execute();

                Yii::$app->session->addFlash('info', "🔄 อัปเดตข้อมูลสำเร็จ!VISIT:$visit_id,  Authen: $claimCode");
            }
        }
        Yii::$app->session->addFlash('info', "📊 นำเข้าข้อมูลทั้งหมด $importedCount รายการ! " . $visit_id . " " . (isset($claimCode) ? $claimCode : 'ไม่มีข้อมูล'));





    } else {
        Yii::$app->session->setFlash('error', 'ไม่พบการ Authen ' . $visit_id);

    }

   return $this->redirect(['index']);
}

	
	####################  ลบข้อมูล ##################################################################
    public function actionDelete($id)
{
    // ค้นหา model ตาม ID และลบมัน
    $model = $this->findModel($id);
    if ($model !== null) {
        $model->delete();
    }

    // เปลี่ยนเส้นทางไปยัง index หลังจากลบ
    return $this->redirect(['index']);
}

protected function findModel($id)
{
    if (($model = CloseVisit::findOne($id)) !== null) {
        return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
}
####################  End ลบข้อมูล ##################################################################
public function actionDeleteMultiple()
{
    // รับค่าจากฟอร์ม POST โดยใช้ 'selection' ซึ่งเป็นชื่อที่ `CheckboxColumn` สร้างขึ้น
    $selection = Yii::$app->request->post('selection', []); 

    if (!empty($selection)) {
        Logclosevisit::deleteAll(['id' => $selection]); // ลบรายการตาม ID ที่เลือก
        Yii::$app->session->setFlash('success', 'ลบรายการที่เลือกสำเร็จ');
    } else {
        Yii::$app->session->setFlash('error', 'ไม่มีรายการที่เลือก');
    }

    return $this->redirect(['index']); // กลับไปยังหน้า index หรือหน้าเดิม
}

    public function actionDeleteSpecific()
    {
        // คำสั่ง SQL สำหรับลบ 10 รายการที่ไม่สำเร็จ
        $sql = "DELETE FROM log_closevisits
        WHERE messagecode <> 'success'
        AND users = 'จองเคลม'
        LIMIT 10";

        Yii::$app->db2->createCommand($sql)->execute(); // ดำเนินการลบ
        
        Yii::$app->session->setFlash('success', 'ลบรายการที่ไม่สำเร็จ 10 รายการสำเร็จ');

        return $this->redirect(['index']); // กลับไปยังหน้า index หรือหน้าเดิม
    }
	  public function actionRunCurl()
    {
        // เริ่มต้นการตั้งค่า Flash
        Yii::$app->response->format = Response::FORMAT_JSON;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fdh.moph.go.th/token?Action=get_moph_access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
             //SSL USE
             CURLOPT_SSL_VERIFYHOST => 0,
             CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
				  'user' => 'chatree.10953',
                'password_hash' => 'EA83F69D2E86DD5DB0EFEDFA4580F37D147477460C1703E466474B2C2DD7FC69',
                'hospital_code' => '10953'
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ));

        $response = curl_exec($curl);  // รัน cURL และเก็บผลลัพธ์
        $err = curl_error($curl);     // ใช้ตัวแปร $curl ที่ถูกต้อง
        curl_close($curl);            // ปิด cURL


        if ($err) {
            Yii::$app->session->setFlash('error', "cURL Error: $err");
            return $this->redirect(['index']); 
        }

        try {
            Yii::$app->db2->createCommand()->insert('fdh_token', [
                'token_dt' => date('Y-m-d H:i:s'),
                'token' => $response,
                'staff_id' => 'pgans',
            ])->execute();

            Yii::$app->session->setFlash('success', 'New token สร้างสำเร็จ');
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', "Database Error: " . $e->getMessage());
        }

        return $this->redirect(['index']); 
    }
}
