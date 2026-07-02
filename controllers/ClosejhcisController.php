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
            'only' => ['index', 'index2', 'update', 'view', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'index', 'index2', 'create', 'update'],
                    'matchCallback' => function ($rule, $action) {
                        // ตรวจสอบว่า user_id อยู่ในรายชื่อที่อนุญาต
                        $allowedUsers = ['6', '29', '52', '190', '285','286', '289','291','432']; // ตัวอย่าง user_id ที่ได้รับอนุญาต  6=pgans, 29=boom2518  toa=52  289=junmane 190=name  285=earth 286:gob 291=john 
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['@'], // หมายถึงผู้ใช้ที่เข้าสู่ระบบแล้ว
                    'matchCallback' => function ($rule, $action) {
                        $allowedUsers = ['6', '29', '52', '190', '285','286', '289','291','432']; // ตรวจสอบกับรายชื่อ
                        return in_array(Yii::$app->user->id, $allowedUsers);
                    },
                ],
            ],
        ],
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
 ###################################  การปิดสิทธิ์   ######################################################
#########################################################################################
    ################ ActionHt-> ActionCheck #########################
	// สำหรับฟังก์ชัน check สำหรับ API fdh.moph.go.th
public function actionCheck()
{
    // ปิด output buffer ป้องกัน echo อื่นปน
    ob_start();

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // ✅ ตรวจสอบ Login
    if (Yii::$app->user->isGuest || empty(Yii::$app->user->identity->username)) {
        ob_end_clean();
        return ['success' => false, 'message' => 'กรุณาล๊อกอิน'];
    }

    $pid   = Yii::$app->user->identity->username;
    $visit = Yii::$app->request->post('visit');

    if (!$visit) {
        ob_end_clean();
        return ['success' => false, 'message' => '❌ ไม่พบข้อมูล visit'];
    }

    // ✅ ดึงข้อมูล visit
    $strVn = "SELECT 
        '10953' as hcode,
        DATE_FORMAT(v.dateupdate, '%Y-%m-%d %H:%i') AS regdate,
        '01500' as `department.code`,
        'pcu'   as `department.name`,
        CASE
            WHEN p.rightcode = 'AC2' THEN 'UCS'
            WHEN p.rightcode = ''    THEN 'UCS'
            ELSE 'UCS'
        END as mainInsclCode,
        CONCAT('10953', v.visitno) as transactionId,
        IF(IFNULL(v.money1, 0) = 0, 50.00, v.money1) AS totalAmount,
        v.visitno  as visitNumber,
        0.00       as paidAmount,
        IF(IFNULL(v.money1, 0) = 0, 50.00, v.money1) AS privilegeAmount,
        'PG0060001' as claimServiceCode,
        p.idcard   as pid,
        'MBASE_PCU' as sourceId,
        p.mobile    AS phone,
        '0'         AS latitude,
        '0'         AS longitude,
        :pid        AS recorderPid,
        p.idcard    as cid
    FROM visit v
    LEFT JOIN person p ON p.pid = v.pid
    WHERE v.visitno = :visit
    LIMIT 1";

    $closeRow = Yii::$app->db_jhcis->createCommand($strVn)
        ->bindValue(':visit', $visit)
        ->bindValue(':pid',   $pid)
        ->queryOne();

    if (!$closeRow) {
        ob_end_clean();
        return ['success' => false, 'message' => '❌ ไม่พบข้อมูล visit ในฐานข้อมูล'];
    }

    $currentTimestampMs = round(microtime(true) * 1000);

    $resultArray = [
        'hcode'             => $closeRow['hcode'],
        'department'        => [
            'code' => $closeRow['department.code'],
            'name' => $closeRow['department.name'],
        ],
        'mainInsclCode'     => $closeRow['mainInsclCode'],
        'serviceDateTime'   => $currentTimestampMs,
        'invoiceDateTime'   => $currentTimestampMs,
        'service_date_time' => $closeRow['regdate'],
        'transactionId'     => $closeRow['transactionId'],
        'totalAmount'       => (float)$closeRow['totalAmount'],
        'paidAmount'        => (float)$closeRow['paidAmount'],
        'privilegeAmount'   => (float)$closeRow['privilegeAmount'],
        'claimServiceCode'  => $closeRow['claimServiceCode'],
        'pid'               => $closeRow['pid'],
        'sourceId'          => $closeRow['sourceId'],
        'visitNumber'       => $closeRow['visitNumber'],
        'recorderPid'       => $closeRow['recorderPid'],
        'mobile'            => $closeRow['phone'],
        'tel'               => $closeRow['phone'],
        'latitude'          => (float)$closeRow['latitude'],
        'longitude'         => (float)$closeRow['longitude'],
    ];

    $resultText = json_encode($resultArray, JSON_UNESCAPED_UNICODE);

    // ✅ cURL
    $url  = "https://nhsoapi.nhso.go.th/nhsoendpoint/api/nhso-claim-detail";
    $curl = curl_init($url);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => $resultText,
       CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer e0634600-f8d9-4289-a29a-4630b5b130c1',
            'Content-Type: application/json',
            'Cookie: TS01304219=013bd252cb3290ae23925080178311fed52a45df35254537ba23df8c4a70e6cca4026c62c841b713fa74f687dc713dc94b38a7d0d6'
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err      = curl_error($curl);
    curl_close($curl);

    // ✅ บันทึก log แทน echo
    Yii::warning("[actionCheck] HTTP={$httpCode} | JSON Sent={$resultText} | Response={$response}", 'api');

    if ($err) {
        ob_end_clean();
        return ['success' => false, 'message' => "❌ cURL Error: $err"];
    }

    if (empty(trim($response))) {
        ob_end_clean();
        return ['success' => false, 'message' => "❌ API ส่งกลับว่างเปล่า HTTP={$httpCode} — Token อาจหมดอายุ"];
    }

    $response   = trim($response);
    $response   = preg_replace('/^\xEF\xBB\xBF/', '', $response);
    $closevisit = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        ob_end_clean();
        return ['success' => false, 'message' => '❌ JSON ผิดรูปแบบ: ' . json_last_error_msg() . ' | Raw: ' . substr($response, 0, 300)];
    }

    if (!isset($closevisit['authenCode'])) {
        ob_end_clean();
        return ['success' => false, 'message' => '❌ API ตอบกลับผิดพลาด HTTP=' . $httpCode . ': ' . json_encode($closevisit, JSON_UNESCAPED_UNICODE)];
    }

    // ✅ INSERT
    $visitid        = $closeRow['visitNumber'];
    $cid            = $closeRow['cid'];
    $transaction_id = '10953' . $visitid;
    $claimcode      = $closevisit['authenCode'];
    $message        = isset($closevisit['seq']) ? "seq: " . $closevisit['seq'] : '';
    $claimType      = $closeRow['claimServiceCode'];
    $recorderPid    = $closeRow['recorderPid'];
    $regdate        = $closeRow['regdate'];
    $total_amount   = $closeRow['totalAmount'];

    $strSQL = "INSERT INTO close_visits 
        (visit_id, cid, transaction_id, claimtype, claimcode, recorder_pid, message, claim_datetime, total_amount, d_update)
        VALUES 
        (:visit, :cid, :transaction_id, :claimtype, :claimcode, :recorder_pid, :message, :regdate, :total_amount, NOW())";

    Yii::$app->db_jhcis->createCommand($strSQL)
        ->bindValue(':visit',          $visitid)
        ->bindValue(':cid',            $cid)
        ->bindValue(':transaction_id', $transaction_id)
        ->bindValue(':claimtype',      $claimType)
        ->bindValue(':claimcode',      $claimcode)
        ->bindValue(':recorder_pid',   $recorderPid)
        ->bindValue(':message',        $message)
        ->bindValue(':regdate',        $regdate)
        ->bindValue(':total_amount',   $total_amount)
        ->execute();

    ob_end_clean();
    return [
        'success'   => true,
        'message'   => "✅ บันทึกสำเร็จ! CID: {$cid}, ClaimCode: {$claimcode}",
        'claimcode' => $claimcode,
        'cid'       => $cid,
        'visitid'   => $visitid,
    ];
}
#############################การขอ Authen  ##########################################################
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
