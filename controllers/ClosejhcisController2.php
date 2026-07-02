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


class ClosejhcisController extends \yii\web\Controller
{
	
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
	
    public function actionIndexxxx()
    {
        // $_token = $model->token;


        return $this->render('indexxxx');
    }
    

  public function actionIndex()
{
    $data = Yii::$app->request->post();
    $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
    $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';

   

    $sql = "SELECT 
        @n := @n + 1 AS 'No',
        data.*
      FROM 
(SELECT DISTINCT v.dateupdate as regdate,v.visitno as visit,CONCAT('99809','',v.visitno) as invoice_number,
		CASE
	WHEN v.claimcode_nhso is null THEN ak.claimcode
		ELSE v.claimcode_nhso
		END as claimcode,
		cv.claimcode as enpoint
		, v.hiciauthen_nhso, v.pid, m.hn, p.idcard as cid, p.telephoneperson, p.mobile,
		 c.rightcode, c.rightname , v.symptoms,
		CONCAT(p.fname,' ',lname) as fullname, timestampdiff(year,p.birth,v.visitdate) AS age,
		REPLACE( IF( cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode ), '.', '' ) AS DIAGCODE ,
		RIGHT( vd.dxtype, 1 ) as DXTYPE, #vd.dxtype,
		IF(IFNULL(v.money1, 0) = 0, 50.00, v.money1) AS money1
		FROM visit v
		LEFT JOIN person p ON p.pid = v.pid
		LEFT JOIN cright c ON c.rightcode = v.rightcode
		LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = 01 
		LEFT JOIN cdisease ON ( vd.diagcode = cdisease.diseasecode ) 
		LEFT JOIN authen_pcu ak ON p.idcard = ak.cid  AND v.visitdate  = date(ak.d_update)
		LEFT JOIN mathhn m ON m.pid = p.pid
		LEFT JOIN close_visits cv ON cv.visit_id = v.visitno
		WHERE v.visitdate BETWEEN CURDATE()-1 AND NOW()
        #AND v.visitno not in (SELECT visit_id FROM log_closevisitsj)
		#AND (claimcode_nhso = '' OR claimcode_nhso is null)
		ORDER BY v.visitdate 
        ) AS data,
			  (SELECT @n := 0) AS init
				ORDER BY  No DESC  ";

     $rawData = \yii::$app->db_jhcis->createCommand($sql)->queryAll();
        try {
            $rawData = \Yii::$app->db_jhcis->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $visitProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 1000,
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
    $url = 'http://192.168.200.119:8189/api/smartcard/read';

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

    // ถ้าไม่มีค่า pid ในข้อมูลที่ได้
    if (!isset($agent['pid'])) {
        return ['status' => 'error', 'message' => '❌ ข้อมูลที่ได้จาก API ไม่ถูกต้อง'];
    }

    $current_pid = Yii::$app->session->get('pid');

    // ตรวจสอบว่า pid ที่ได้มาเปลี่ยนแปลงหรือไม่
    if ($current_pid && $current_pid !== $agent['pid']) {
        Yii::$app->session->destroy(); // ล้าง session ทั้งหมด
        Yii::$app->session->open(); // เปิด session ใหม่
    }

    // บันทึก pid ลง session ใหม่
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
	/*
	// ดึงค่า pid จาก session
    $pid = Yii::$app->session->get('pid');

    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        return $this->redirect(['index']);
    }
	*/
	
    // ดึงค่า pid จาก session
    $pid = Yii::$app->session->get('pid', '3410101900563');

    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        return $this->redirect(['index']);
    }

    // ดึง Token
    $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
    $data = \Yii::$app->db_jhcis->createCommand($sqltoken)->queryOne();
    $token_fdh = isset($data['token30']) ? $data['token30'] : null;

    if (!$token_fdh) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบ Token');
        //return $this->redirect(['index']);
    }
    $visit = Yii::$app->request->post('visit');
	echo " visit: " . htmlspecialchars($visit);
    
    

    // ดึงข้อมูล visit จากฐานข้อมูล
    $strVn = "
SELECT 
                DATE_FORMAT(v.dateupdate, '%Y-%m-%d %H:%i') AS regdate,
                DATE_FORMAT(v.dateupdate, '%Y-%m-%d %H:%i') AS invoicedate,
                v.visitno as visit,
                '10953' as hospital_code,
                0.00 as paid_amount,
                'WEL' as inscl, 
                '2' as type,
                '$pid' as recorder_pid,
                'PP' as authen_code_source_id,
                'e0634600-f8d9-4289-a29a-4630b5b130c1' as authen_code_token,
v.visitno as visit,CONCAT('99809','',v.visitno) 
as invoice_number,
		CASE
	WHEN v.claimcode_nhso is null THEN ak.claimcode
		ELSE v.claimcode_nhso
		END as claimcode
		, v.hiciauthen_nhso, v.pid, m.hn, p.idcard as cid, p.telephoneperson, p.mobile,
		 c.rightcode, c.rightname , v.symptoms,
		CONCAT(p.fname,' ',lname) as fullname, timestampdiff(year,p.birth,v.visitdate) AS age,
		REPLACE( IF( cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode ), '.', '' ) AS DIAGCODE ,
		RIGHT( vd.dxtype, 1 ) as DXTYPE, #vd.dxtype,
		IF(IFNULL(v.money1, 0) = 0, 50.00, v.money1) AS amount
		FROM visit v
		LEFT JOIN person p ON p.pid = v.pid
		LEFT JOIN cright c ON c.rightcode = v.rightcode
		LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = 01 
		LEFT JOIN cdisease ON ( vd.diagcode = cdisease.diseasecode ) 
		LEFT JOIN authen_pcu ak ON p.idcard = ak.cid  AND v.visitdate  = date(ak.d_update)
		LEFT JOIN mathhn m ON m.pid = p.pid
		WHERE v.visitno = '$visit'
        #AND v.visitno not in (SELECT visit_id FROM log_closevisitsj)
		#AND (claimcode_nhso = '' OR claimcode_nhso is null)
		ORDER BY v.visitno
            LIMIT 1";

   $closeRow = Yii::$app->db_jhcis->createCommand($strVn)->queryOne();


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

// แปลงเป็น JSON โดยไม่ให้มี []
$resultText = json_encode($resultArray, JSON_PRETTY_PRINT);

// แสดงผล
echo $resultText;

  
    
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
    echo $response;
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

        Yii::$app->db_jhcis->createCommand($strSQL)
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

    Yii::$app->session->setFlash('info', $message_th);
	 $visit_id = isset($closeRow['visit']) ? $closeRow['visit'] : 'N/A';
$claimCode = isset($claimcode) ? $claimcode : 'N/A';

Yii::$app->session->setFlash('info', $message_th);
Yii::$app->session->setFlash('success', "✅ เพิ่มข้อมูลสำเร็จ!<br>VISIT: $visit_id<br>Endpoint: $claimCode");


	# Yii::$app->session->setFlash('success', "✅ เพิ่มข้อมูลสำเร็จ!<br>VISIT: $visit_id<br>Endpoint: $claimCode");
    //return $this->redirect(['index']);
//  return $this->redirect(Yii::$app->request->referrer);

}

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
   // ตรวจสอบว่ามีการเลือกข้อมูลหรือไม่
if (!empty($selectedClaims)) {
    $importedCount = 0; // ตัวนับจำนวนที่นำเข้า
    foreach ($selectedClaims as $claim) {
        $claimType = isset($claim['claimType']) ? $claim['claimType'] : null;
        $telephone = isset($claim['telephone']) ? $claim['telephone'] : '';
        $claimCode = isset($claim['claimCode']) ? $claim['claimCode'] : null;
        $claimDateTime = isset($claim['claimDateTime']) ? $claim['claimDateTime'] : null;

        // แสดงข้อมูลเพื่อการตรวจสอบ
      //  echo "Selected Claim: $claimCode | ClaimType: $claimType <br>";
       // echo "Claim DateTime: $claimDateTime <br>";
      //  echo "Telephone: $telephone <br><br>";

        // ตรวจสอบว่า visit_id ถูกกำหนดหรือไม่
      //  echo "Visit ID: " . ($visit_id ? $visit_id : "ไม่กำหนด") . "<br>";

        // ตรวจสอบและนำเข้าข้อมูล
        $searchSQL = "SELECT COUNT(*) FROM close_visits WHERE claimcode = :claimcode";
        $existingCount = Yii::$app->db_jhcis->createCommand($searchSQL)
            ->bindValue(':claimcode', $claimCode)
            ->queryScalar();

        if ($existingCount == 0) {
            // เพิ่มข้อมูลใหม่
            $insertSQL = "
                INSERT INTO close_visits (cid, visit_id, claimtype, claimcode, claim_datetime, d_update)
                VALUES (:cid, :visit_id, :claimtype, :claimcode, :claim_datetime, NOW())
            ";
            Yii::$app->db_jhcis->createCommand($insertSQL)
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
            Yii::$app->db2_jhcis->createCommand($updateSQL)
                ->bindValues([
                    ':visit_id' => $visit_id,
                    ':claim_datetime' => $claimDateTime,
                    ':claimcode' => $claimCode,
                ])
                ->execute();
        }
    }

    // แสดงผลจำนวนที่นำเข้า
    Yii::$app->session->addFlash('info', "📊 นำเข้าข้อมูลทั้งหมด $importedCount รายการ!");
} else {
    // ถ้าไม่มีข้อมูลที่เลือก
    Yii::$app->session->setFlash('error', 'ไม่พบการปิดสิทธิ์');
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
            Yii::$app->db_jhcis->createCommand()->insert('fdh_token', [
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
