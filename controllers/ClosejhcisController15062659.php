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
    

public function actionIndex()
{
    $data = Yii::$app->request->post();
    
    // Default เป็นวันปัจจุบัน
    $date1 = isset($data['date1']) && $data['date1'] !== '' 
        ? $data['date1'] 
        : date('Y-m-d');
        
    $date2 = isset($data['date2']) && $data['date2'] !== '' 
        ? $data['date2'] 
        : date('Y-m-d');
   
    $sql = "SELECT 
        @n := @n + 1 AS 'No',
        data.*
      FROM 
(SELECT DISTINCT v.dateupdate as regdate, v.visitno as visit, CONCAT('10953','',v.visitno) as invoice_number,
        CASE
            WHEN v.claimcode_nhso IS NULL THEN ak.claimcode
            ELSE v.claimcode_nhso
        END as claimcode,
        cv.claimcode as enpoint,
		v.weight AS weight,
        v.height AS height,
        v.hiciauthen_nhso, v.pid, p.idcard as cid, COALESCE(NULLIF(TRIM(p.mobile), ''), p.telephoneperson) AS telephone,
        c.rightcode, c.rightname, v.symptoms,
        CONCAT(p.fname,' ',lname) as fullname, TIMESTAMPDIFF(YEAR, p.birth, v.visitdate) AS age,
        REPLACE(IF(cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode), '.', '') AS DIAGCODE,
        RIGHT(vd.dxtype, 1) as DXTYPE,
        IF(IFNULL(v.money1, 0) = 0, '', v.money1) AS money1
        FROM visit v
        LEFT JOIN person p ON p.pid = v.pid
        LEFT JOIN cright c ON c.rightcode = v.rightcode
        LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = 01 
        LEFT JOIN cdisease ON (vd.diagcode = cdisease.diseasecode) 
        LEFT JOIN authen_pcu ak ON p.idcard = ak.cid AND v.visitdate = DATE(ak.d_update)
        LEFT JOIN close_visits cv ON cv.visit_id = v.visitno
        WHERE DATE(v.visitdate) BETWEEN :date1 AND :date2
		GROUP BY v.visitno
        ORDER BY v.visitdate DESC
    ) AS data,
    (SELECT @n := 0) AS init
	
    ORDER BY No DESC";

    try {
        $rawData = \Yii::$app->db_jhcis->createCommand($sql, [
            ':date1' => $date1,
            ':date2' => $date2,
        ])->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error: ' . $e->getMessage());
    }

    $visitProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pageSize' => 1000,
        ],
    ]);
    


        #############################################################################

        $sqlCount1 = "SELECT COUNT(DISTINCT v.visit_id) as amount
            FROM authen_pcu  v 
            WHERE 
           v.d_update BETWEEN CURDATE() AND NOW()";

        $data = \yii::$app->db_jhcis->createCommand($sqlCount1)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amount = $data[$i]['amount'];
        }
		#############################################################################
        $sqlCamount = "SELECT COUNT(DISTINCT v.visit_id) as amountx
             FROM log_closevisits v 
             WHERE v.messagecode <> 'success'
			 AND v.users = 'จองเคลม'
             AND v.send_date BETWEEN CURDATE() AND NOW()";
        $data = \yii::$app->db74->createCommand($sqlCamount)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amountx = $data[$i]['amountx'];
        }
        $total = "SELECT COUNT(DISTINCT v.visit_id) as total
            FROM log_closevisits v 
            WHERE v.messagecode = 'success'
			AND v.users = 'จองเคลม'
            AND v.send_date BETWEEN '2026-09-01' AND NOW()
             ";

        $data = \yii::$app->db4->createCommand($total)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $total = $data[$i]['total'];
        }
		#############################################################################
		########################################################################################################
		#### JHCIS ################################################################
$sqlj = "SELECT DISTINCT 
    DATE(k.visitdate) AS regdate, 
    COUNT(DISTINCT k.seq) AS visitj,
    COUNT(DISTINCT k.claimcode_nhso) AS authenj,
    COUNT(DISTINCT k.closej) AS closevisitj,
    SUM(CASE WHEN k.status = '200' THEN 1 ELSE 0 END) AS jongclaimj,
    SUM(CASE WHEN k.status != '200' THEN 1 ELSE 0 END) AS nojongclaimj
    FROM 
    (SELECT v.visitdate, v.visitno AS seq, lc.status,
        COALESCE(v.claimcode_nhso, '') AS claimcode_nhso,
        cv.claimcode AS closej,
        v.hiciauthen_nhso, v.pid, p.idcard AS cid,
        c.rightcode, c.rightname,
        CONCAT(p.fname, ' ', lname) AS fullname, 
        TIMESTAMPDIFF(YEAR, p.birth, v.visitdate) AS age
   FROM visit v
    LEFT JOIN person p ON p.pid = v.pid
    LEFT JOIN cright c ON c.rightcode = v.rightcode
    LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = '01'
    LEFT JOIN cdisease ON vd.diagcode = cdisease.diseasecode
    LEFT JOIN log_closevisitsj lc ON lc.visit_id = v.visitno
    LEFT JOIN authen_pcu cl ON cl.visit_id = v.visitno
		LEFT JOIN close_visits cv ON cv.visit_id =  v.visitno
    WHERE DATE(v.visitdate) BETWEEN :date1 AND :date2) as k
    GROUP BY k.visitdate 
    ORDER BY regdate DESC";

$dataJ = \Yii::$app->db_jhcis->createCommand($sqlj, [
    ':date1' => $date1,
    ':date2' => $date2,
])->queryAll();

// reset ค่าเริ่มต้น
$visitj      = 0;
$authenj     = 0;
$noauthenj   = 0;
$closevisitj = 0;
$noclosevisitj = 0;
$jongclaimj  = 0;
$nojongclaimj = 0;

foreach ($dataJ as $row) {
    $visitj      += (int)$row['visitj'];
    $authenj     += (int)$row['authenj'];
    $jongclaimj  += (int)$row['jongclaimj'];
    $nojongclaimj += (int)$row['nojongclaimj'];
    $closevisitj += (int)$row['closevisitj'];
}
$noauthenj     = $visitj - $authenj;
$noclosevisitj = $visitj - $closevisitj;
       


        ########################################################################################################
        $sqlPass = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date
        FROM log_closevisits l 
        WHERE l.send_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
        AND l.messagecode = 'success' AND l.users = 'จองเคลม'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db4->createCommand($sqlPass)->queryAll();

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
    'visitProvider'  => $visitProvider,
    'amount'         => $amount,
    'amountx'        => $amountx,
    'todayx'         => $todayx,
    'alien'          => $alien,
    'homeward'       => $homeward,
    'todayipd'       => $todayipd,
    'total'          => $total,
    'passProvider'   => $passProvider,
    'errorProvider'  => $errorProvider,
    // JHCIS
    'visitj'         => $visitj,
    'authenj'        => $authenj,
    'noauthenj'      => $noauthenj,
    'jongclaimj'     => $jongclaimj,
    'nojongclaimj'   => $nojongclaimj,
    'closevisitj'    => $closevisitj,
    'noclosevisitj'  => $noclosevisitj,
    // วันที่ที่เลือก
    'date1'          => $date1,
    'date2'          => $date2,
]);
    }
#########################################################################################
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

#######################################################################################
public function actionCheck1()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // ตรวจสอบ Login
    if (Yii::$app->user->isGuest || empty(Yii::$app->user->identity->username)) {
        return ['success' => false, 'message' => '❌ กรุณาล๊อกอิน'];
    }

    $pid = Yii::$app->user->identity->username;

    // รับค่า visit
    $visit = trim(Yii::$app->request->post('visit', ''));
    if (empty($visit)) {
        return ['success' => false, 'message' => '❌ ไม่พบข้อมูล visit'];
    }

    // ดึง Token จาก DB
    $tokenRow  = Yii::$app->db74->createCommand(
        "SELECT MAX(token) AS token30 FROM fdh_token WHERE staff_id = 'pgans'"
    )->queryOne();
    $token_fdh = $tokenRow['token30'] ?? null;

    if (empty($token_fdh)) {
        return ['success' => false, 'message' => '❌ ไม่พบ Token'];
    }

    // ดึงข้อมูล visit
    $sql = "
        SELECT
            '10953' AS sourceId,
            CONCAT(v.visitno, DATE_FORMAT(NOW(), '%H%i')) AS transId,
            p.idcard AS cid,
            TRIM(p.fname) AS firstName,
            'M' AS midName,
            TRIM(p.lname) AS lastName,
            p.sex,
            COALESCE(NULLIF(TRIM(p.mobile), ''), p.telephoneperson) AS phone,
            p.pid AS hn,
            '' AS an,
            :pid AS recorderPid,
            'PG0060001' AS serviceCode,
            p.hnomoi AS house,
            LEFT(village.villcode, 6) AS subDistrictCode,
            cs.subdistname AS subDistrict,
            LEFT(village.villcode, 4) AS districtCode,
            cd.distname AS district,
            LEFT(village.villcode, 2) AS provinceCode,
            cp.provname AS province,
            '34140' AS postCode,
            p.birth AS birthDay,
            ROUND(v.weight,0) AS weight,
			ROUND(v.height,0) AS height,
            #p.rightcode AS mainInscl,
CASE
		WHEN p.rightcode = 'AC2'  THEN 'UCS'
    WHEN p.rightcode = ''  THEN 'UCS'
    ELSE 'UCS' 
    END  AS mainInscl,
            v.visitno AS visit,
            p.mobile AS telephone
        FROM visit v
        LEFT JOIN person p ON p.pid = v.pid
        LEFT JOIN house ON p.hcode = house.hcode AND p.pcucodeperson = house.pcucode
        LEFT JOIN village ON house.villcode = village.villcode AND house.pcucode = village.pcucode
        LEFT JOIN csubdistrict cs ON CONCAT(cs.provcode, cs.distcode, cs.subdistcode) = LEFT(village.villcode, 6)
        LEFT JOIN cdistrict cd ON CONCAT(cd.provcode, cd.distcode) = LEFT(village.villcode, 4)
        LEFT JOIN cprovince cp ON cp.provcode = LEFT(village.villcode, 2)
        WHERE v.visitno = :visit
        LIMIT 1
    ";

    $row = Yii::$app->db_jhcis->createCommand($sql)
        ->bindValue(':visit', $visit)
        ->bindValue(':pid',   $pid)
        ->queryOne();

    if (empty($row)) {
        return ['success' => false, 'message' => "❌ ไม่พบข้อมูล visit: {$visit}"];
    }

    // เตรียม Payload
    $payload = [
        'sourceId'        => $row['sourceId'],
        'transId'         => $row['transId'],
        'pid'             => $row['cid'],
        'firstName'       => $row['firstName'],
        'midName'         => $row['midName'],
        'lastName'        => $row['lastName'],
        'sex'             => $row['sex'],
        'phone'           => [$row['phone']],
        'latitude'        => 0,
        'longitude'       => 0,
        'hcode'           => $row['sourceId'],
        'hn'              => $row['hn'],
        'an'              => $row['an'],
        'recorderPid'     => $row['recorderPid'],
        'serviceCode'     => $row['serviceCode'],
        'house'           => $row['house'],
        'subDistrictCode' => $row['subDistrictCode'],
        'subDistrict'     => $row['subDistrict'],
        'districtCode'    => $row['districtCode'],
        'district'        => $row['district'],
        'provinceCode'    => $row['provinceCode'],
        'province'        => $row['province'],
        'postCode'        => $row['postCode'],
        'birthDay'        => $row['birthDay'],
        'weight'          => $row['weight'],
        'height'          => $row['height'],
        'maininscl'       => $row['mainInscl'],
    ];

    // ✅ ใช้ $payloadJson (ไม่ใช่ $resultText)
    $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE);

    // เรียก API
    $curl = curl_init('https://nhsoapi.nhso.go.th/nhsoendpoint/api/AuthenCode');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $payloadJson,   // ✅ แก้จาก $resultText
        CURLOPT_HTTPHEADER => [
        'Authorization: Bearer e0634600-f8d9-4289-a29a-4630b5b130c1',
        'Content-Type: application/json',
        'Cookie: TS01304219=013bd252cb3290ae23925080178311fed52a45df35254537ba23df8c4a70e6cca4026c62c841b713fa74f687dc713dc94b38a7d0d6'
    ]
    ]);

    $response  = curl_exec($curl);
    $curlError = curl_error($curl);           // ✅ ชื่อตัวแปรเดียวกันทั้ง block
    curl_close($curl);

    if ($curlError) {
        return ['success' => false, 'message' => "❌ เชื่อมต่อ API ไม่สำเร็จ: {$curlError}"];
    }

    if (empty($response)) {
        return ['success' => false, 'message' => '❌ API ไม่ตอบกลับ (empty response)'];
    }

    $apiResult = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['success' => false, 'message' => '❌ API ตอบกลับ JSON ไม่ถูกต้อง: ' . substr($response, 0, 200)];
    }

    if (empty($apiResult['authenCode'])) {
        return [
            'success' => false,
            'message' => '❌ ไม่พบ authenCode: ' . json_encode($apiResult, JSON_UNESCAPED_UNICODE),
        ];
    }

    // บันทึกลง DB
    $claimCode = $apiResult['authenCode'];
    $cid       = $row['cid'];
    $visitId   = $row['visit'];
    $claimType = $row['serviceCode'];
    $mobile    = $row['telephone'] ?? '';

    try {
        $db = Yii::$app->db_jhcis;

        $exists = (int) $db->createCommand(
            "SELECT COUNT(*) FROM authen_pcu WHERE claimcode = :code"
        )->bindValue(':code', $claimCode)->queryScalar();

        if ($exists > 0) {
            $db->createCommand("
                UPDATE authen_pcu
                SET visit_id = :visit_id, d_update = NOW()
                WHERE claimcode = :code
            ")->bindValues([
                ':visit_id' => $visitId,
                ':code'     => $claimCode,
            ])->execute();
        } else {
            $db->createCommand("
                INSERT INTO authen_pcu (cid, visit_id, claimtype, claimcode, mobile, dep_name, d_update)
                VALUES (:cid, :visit_id, :claimtype, :code, :mobile, 'web', NOW())
            ")->bindValues([
                ':cid'       => $cid,
                ':visit_id'  => $visitId,
                ':claimtype' => $claimType,
                ':code'      => $claimCode,
                ':mobile'    => $mobile,
            ])->execute();
        }

        return [
            'success'   => true,
            'message'   => "✅ สำเร็จ รหัส: {$claimCode}",
            'claimCode' => $claimCode,
        ];

    } catch (\yii\db\Exception $e) {
        return ['success' => false, 'message' => '❌ บันทึกไม่สำเร็จ: ' . $e->getMessage()];
    }
}
##################################################################################################################################################
    public function actionCheckNhso($cid, $visit_id)
{
    if (empty($cid) || empty($visit_id)) {
        Yii::$app->session->setFlash('error', 'ข้อมูลไม่ครบถ้วน');
        return $this->redirect(['index']);
    }

    // เรียก API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'http://192.168.200.63:8189/api/nhso-service/latest-5-authen-code-all-hospital/' . $cid,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Cookie: TS01e80146=013bd252cb92f51720a1ea0f8eeca789f1467d7859c5d018175e4a4e5556b950058f436b39aa661efbf11e2f2a90391a334d4abf07'
        ],
    ]);
$response = curl_exec($curl);
curl_close($curl);

$authen = json_decode($response, true);

if (!is_array($authen) || empty($authen)) {
    Yii::$app->session->setFlash('error', 'ไม่พบข้อมูลจาก API');
    return $this->redirect(['index']);
}

// กรณี API ส่งมาเป็น Object เดียว
if (isset($authen['claimCode'])) {
    $authen = [$authen];
}

$today = date('Y-m-d');
$selectedClaims = [];

foreach ($authen as $item) {

    $claimCode     = trim($item['claimCode'] ?? '');
    $claimDateTime = $item['claimDateTime'] ?? '';
    $hcode         = $item['hcode'] ?? '';

    if (empty($claimCode) || empty($claimDateTime)) {
        continue;
    }

    $claimDate = date('Y-m-d', strtotime($claimDateTime));

    // เฉพาะ รพ. ของเรา
    if ($hcode != '10953') {
        continue;
    }

    // เฉพาะวันนี้
    if ($claimDate != $today) {
        continue;
    }

    // เฉพาะ PP
    if (strpos($claimCode, 'PP') !== 0) {
        continue;
    }

    $selectedClaims[] = $item;
}

if (empty($selectedClaims)) {
    Yii::$app->session->setFlash(
        'error',
        '❌ ไม่พบ AuthenCode ประเภท PP ของวันนี้'
    );
    return $this->redirect(['index']);
}

// เรียงจากล่าสุด -> เก่าสุด
usort($selectedClaims, function ($a, $b) {
    return strtotime($b['claimDateTime']) - strtotime($a['claimDateTime']);
});

$importedCount = 0;
$claimList = [];

foreach ($selectedClaims as $claim) {

    $claimType     = $claim['claimType'] ?? '';
    $claimCode     = $claim['claimCode'] ?? '';
    $claimDateTime = $claim['claimDateTime'] ?? '';

    // เก็บไว้แสดงผล
    $dt = date('d/m/Y H:i:s', strtotime($claimDateTime));
    $claimList[] = "• {$claimCode} ({$dt})";

    // ตรวจสอบว่ามี claimcode แล้วหรือยัง
    $exists = Yii::$app->db_jhcis->createCommand("
        SELECT COUNT(*)
        FROM authen_pcu
        WHERE claimcode = :claimcode
    ")
    ->bindValue(':claimcode', $claimCode)
    ->queryScalar();

    if ($exists == 0) {

        Yii::$app->db_jhcis->createCommand("
            INSERT INTO authen_pcu
            (
                cid,
                visit_id,
                claimtype,
                claimcode,
                d_update
            )
            VALUES
            (
                :cid,
                :visit_id,
                :claimtype,
                :claimcode,
                NOW()
            )
        ")
        ->bindValues([
            ':cid'       => $cid,
            ':visit_id'  => $visit_id,
            ':claimtype' => $claimType,
            ':claimcode' => $claimCode,
        ])
        ->execute();

        $importedCount++;
    }
}

$message  = "✅ พบ PP AuthenCode จำนวน " . count($claimList) . " รายการ";
$message .= "<br><br>";
$message .= implode('<br>', $claimList);

Yii::$app->session->setFlash(
    'success',
    $message
);

return $this->redirect(['index']);
}

}
