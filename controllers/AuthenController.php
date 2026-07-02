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


class AuthenController extends \yii\web\Controller
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
    
    ################# ดึงข้อมูลให้ฟอร์มรายชื่อ ########################
  public function actionIndex()
{
   $date1x = Yii::$app->request->get('date1', date('Y-m-d'));
   $date2x = Yii::$app->request->get('date2', date('Y-m-d'));

   $date1 = date('Y-m-d 00:01', strtotime($date1x));
   $date2 = date('Y-m-d 23:59', strtotime($date2x));
   
    $sql = "SELECT 
                @n := @n + 1 AS 'No',
                data.*
            FROM (
                SELECT 
                    e.unit_name,
                    b.hn,
                    '' AS 'an',
                    b.REG_DATETIME AS regdate,
                    b.FINISH_DATETIME AS dsc_dt,
                    CONCAT(TRIM(p.FNAME), ' ', TRIM(p.LNAME)) AS 'fullname',
                    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, b.REG_DATETIME) AS 'age',
                    p.cid,
                    ak.visit_id,
                    b.visit_id AS visit,
                    i.icd10_tm,
                    p.telephone,
                    p.rl_phone,
                    p.mother,
                    m.inscl_name,
                    IFNULL(ak.claimtype, '') AS 'claimtype',
                    IFNULL(ak.claimcode, '') AS 'claimKiosk',
                    COALESCE(cv.claimcode, '') AS 'closevisit', 
                    CASE
                        WHEN b.claim_code = '' THEN 'ว่าง'
                        ELSE b.claim_code
                    END AS claim_code
                FROM opd_visits b 
                INNER JOIN cid_hn c ON b.HN = c.HN
                LEFT JOIN population p ON c.CID = p.CID
                LEFT JOIN service_units e ON b.UNIT_REG = e.unit_id
                LEFT JOIN authen_kiosk ak ON ak.visit_id = b.VISIT_ID AND ak.cid = p.cid
                LEFT JOIN close_visits cv ON cv.visit_id = b.VISIT_ID 
                LEFT JOIN opd_diagnosis od ON od.visit_id = b.visit_id 
                LEFT JOIN icd10new i ON i.icd10 = od.ICD10
                LEFT JOIN main_inscls m on m.inscl = b.inscl
                WHERE b.IS_CANCEL = 0
                AND b.REG_DATETIME BETWEEN :date1 AND :date2
                #AND b.visit_id not in (select visit_id from ipd_reg)
                AND b.UNIT_REG NOT IN ('42','51')
                AND p.NATN_ID = '99'
                AND (ak.claimcode IS NULL OR ak.claimcode = '')
                #AND b.visit_id not in (select visit_id from authen_kiosk)
                GROUP BY b.VISIT_ID 
                ORDER BY b.REG_DATETIME 
            ) AS data,
            (SELECT @n := 0) AS init
			ORDER BY 
				(data.regdate ),    -- 🔹 ย้ำอีกชั้นเพื่อให้แน่ใจว่าอยู่บนสุดในลำดับสุดท้าย
				No DESC;  ";
          

    try {
        $rawData = \Yii::$app->db2->createCommand($sql)
            ->bindValue(':date1', $date1)
            ->bindValue(':date2', $date2)
            ->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('SQL error: ' . $e->getMessage());
    }

    $pageSize = 200;
    $visitProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => ['pageSize' => $pageSize],
    ]);


        #########################################################################
        


        ########################################################################################################
        $sqlPass = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date
        FROM log_closevisits l 
        WHERE l.send_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
        AND l.messagecode = 'success' AND l.users = 'จองเคลม'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db2->createCommand($sqlPass)->queryAll();

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
        $rawData = \Yii::$app->db2->createCommand($sqlError)->queryAll();

        // สร้าง Flash Alert
        //Yii::$app->session->setFlash('success', 'รายการที่ไม่ผ่านตามเงื่อนไข');

        $errorProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
		########## แสดงจำนวน Dashboard  ######################################################
					 
	$todays = "SELECT 
    
    COUNT(DISTINCT b.VISIT_ID) AS todayopd,
    COUNT(DISTINCT CASE WHEN p.NATN_ID != '99' THEN b.visit_id END) AS alien,
    COUNT(DISTINCT ak.visit_id) AS authen,
    COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.visit_id) AS noauthen,
    COUNT(DISTINCT cl.visit_id) AS closevisits,
    COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT cl.visit_id) AS noclosevisit,
    COUNT(DISTINCT CASE WHEN lc.status = '200' THEN b.VISIT_ID END) AS jongclaim,
    COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT CASE WHEN lc.status = '200' THEN b.VISIT_ID END) AS nojongclaim,
    COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode) AS non,
    ROUND(COUNT(DISTINCT ak.claimcode) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS percent_authen,
    ROUND((COUNT(DISTINCT b.VISIT_ID) - COUNT(DISTINCT ak.claimcode)) / COUNT(DISTINCT b.VISIT_ID) * 100, 2) AS nonx
FROM opd_visits b
INNER JOIN cid_hn c ON b.HN = c.HN
INNER JOIN population p ON c.CID = p.CID
INNER JOIN service_units e ON b.UNIT_REG = e.unit_id
LEFT JOIN authen_kiosk ak ON b.visit_id = ak.visit_id AND ak.cid = p.cid
LEFT JOIN log_closevisits lc ON lc.visit_id = b.visit_id
LEFT JOIN close_visits cl ON cl.visit_id = b.visit_id
WHERE b.IS_CANCEL = 0
  AND p.NATN_ID = '99'
  #AND b.VISIT_ID NOT IN (SELECT visit_id FROM ipd_reg WHERE is_cancel = 0)
  AND b.UNIT_REG NOT IN ('42','51')
  AND b.reg_datetime BETWEEN CURDATE() AND NOW()
	  
		 ";
	   
	$data = \yii::$app->db2->createCommand($todays)->queryAll();
	for ($i = 0; $i < sizeof($data); $i++) {
		$authen = $data[$i]['authen'];
		$noauthen = $data[$i]['noauthen'];
		$closevisits = $data[$i]['closevisits'];
		$noclosevisit = $data[$i]['noclosevisit'];
		$jongclaim = $data[$i]['jongclaim'];
		$nojongclaim = $data[$i]['nojongclaim'];
		
	}
        return $this->render('index', [
            // 'searchModel' => $searchModel,
            'visitProvider' => $visitProvider,
            
            'passProvider' => $passProvider,
            'errorProvider' => $errorProvider,
			'authen' => $authen,
			'noauthen' => $noauthen,
			'closevisits' => $closevisits,
			'noclosevisit' => $noclosevisit,
			'jongclaim' => $jongclaim,
			'nojongclaim' => $nojongclaim,
			'date1' => $date1,
		    'date2' => $date2,

        ]);
    }
    #########  อ่านบัตรประชาชน ######################################################
	
   public function actionReadSmartCard()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    // API URL
    $url = 'http://192.168.200.31:8189/:8189/api/smartcard/read';

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
    ################ Authen-> ActionCheck #########################
	public function actionCheck()
{
	

    $pid = Yii::$app->user->identity->username;

	
    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณากรุณาล๊อกอิน');
        return $this->redirect(['index']);
    }
	
    if (!$pid) {
        Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
        //return $this->redirect(['index']);
    }
/*
    // ดึง Token
    $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
    $data = \Yii::$app->db2->createCommand($sqltoken)->queryOne();
    $token_fdh = isset($data['token30']) ? $data['token30'] : null;

    if (!$token_fdh) {
        Yii::$app->session->setFlash('error', '❌ ไม่พบ Token');
       // return $this->redirect(['index']);
    }
	*/
 $visit = Yii::$app->request->post('visit');
echo $visit; // แสดงค่า visit

if (!$visit) {
    Yii::$app->session->setFlash('error', '❌ ไม่พบข้อมูล visit');
    //return $this->redirect(['index']);
}

// ดึงข้อมูล visit จากฐานข้อมูล
$strVn = "SELECT
    '10953' AS sourceId,
    CONCAT(b.visit_id, DATE_FORMAT(NOW(), '%H%i')) AS transId,
    COALESCE(p.cid, '') AS cid,
    COALESCE(TRIM(p.fname), '') AS firstName,
    'M' AS midName,
    COALESCE(TRIM(p.lname), '') AS lastName,
    COALESCE(p.sex, '') AS sex,
    COALESCE(p.telephone, '') AS phone,
    '0' AS latitude,
    '0' AS longitude,
    COALESCE(e.unit_name, '') AS unit_name,
    COALESCE(b.hn, '') AS hn,
    '' AS an,
    '$pid' AS recorderPid,
    'PG0060001' AS serviceCode,
    COALESCE(p.HOME_ADR, '') AS house,
    COALESCE(p.TOWN_ID, '') AS subDistrictCode,
    COALESCE(TRIM(t1.TOWN_NAME), '') AS subDistrict,
    LEFT(COALESCE(p.TOWN_ID, ''), 4) AS districtCode,
    COALESCE(t2.TOWN_NAME, '') AS district,
    LEFT(COALESCE(p.town_id, ''), 2) AS provinceCode,
    COALESCE(t3.TOWN_NAME, '') AS province,
    LEFT(COALESCE(p.town_id, ''), 5) AS postCode,
    COALESCE(p.birthdate, '') AS birthDay,
    CAST(COALESCE(b.weight, 0) AS UNSIGNED) AS weight,
    CAST(COALESCE(b.height, 0) AS UNSIGNED) AS height,
    CASE
        WHEN m.nhso_code = 'AC2' THEN 'UCS'
        WHEN m.nhso_code = '' THEN 'UCS'
        ELSE 'UCS'
    END AS mainInscl,
    COALESCE(b.REG_DATETIME, '') AS adm_dt,
    COALESCE(b.FINISH_DATETIME, '') AS dsc_dt,
    CONCAT(TRIM(COALESCE(p.FNAME, '')), ' ', TRIM(COALESCE(p.LNAME, ''))) AS fullname,
    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, b.REG_DATETIME) AS age,
    b.visit_id AS visit,
    COALESCE(i.icd10_tm, '') AS icd10_tm,
    COALESCE(p.telephone, '') AS telephone,
    COALESCE(p.rl_phone, '') AS rl_phone,
    COALESCE(p.mother, '') AS mother,
    COALESCE(m.inscl_name, '') AS inscl_name,
    COALESCE(ak.claimtype, '') AS claimtype,
    COALESCE(ak.claimcode, '') AS claimKiosk,
    COALESCE(cv.claimcode, '') AS closevisit
FROM opd_visits b
INNER JOIN cid_hn c ON b.HN = c.HN
LEFT JOIN population p ON c.CID = p.CID AND p.NATN_ID = '99'
LEFT JOIN service_units e ON b.UNIT_REG = e.unit_id
LEFT JOIN authen_kiosk ak ON ak.visit_id = b.VISIT_ID  AND ak.cid = p.cid
LEFT JOIN close_visits cv ON cv.visit_id = b.VISIT_ID
LEFT JOIN opd_diagnosis od ON od.visit_id = b.visit_id
LEFT JOIN icd10new i ON i.icd10 = od.ICD10
LEFT JOIN main_inscls m ON m.inscl = b.inscl
LEFT JOIN towns t ON p.town_id = t.town_id
LEFT JOIN towns t1 ON CONCAT(LEFT(p.town_id, 6), '00') = t1.town_id
LEFT JOIN towns t2 ON CONCAT(LEFT(p.town_id, 4), '0000') = t2.town_id
LEFT JOIN towns t3 ON CONCAT(LEFT(p.town_id, 2), '000000') = t3.town_id
WHERE 
    b.IS_CANCEL = 0
    AND b.visit_id = :visit
    AND b.UNIT_REG NOT IN ('42','51')
GROUP BY 
    b.VISIT_ID, p.cid, p.fname, p.lname, p.sex, p.telephone, e.unit_name, p.TOWN_ID
ORDER BY ak.claimcode
LIMIT 2";


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


    // === เรียก API ===
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
        ]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

   
// ✅ แสดง Response ที่ได้จาก API
echo "<pre>🔹 Response จาก API:</pre>";
echo "<pre>" . print_r($response, true) . "</pre>";

// ✅ แปลง Response เป็น JSON
$closevisit = json_decode($response, true);

// ✅ ตรวจสอบว่า API ส่งข้อมูล JSON ที่ถูกต้องหรือไม่
if (json_last_error() !== JSON_ERROR_NONE) {
    Yii::$app->session->setFlash('error', '❌ รูปแบบ JSON ไม่ถูกต้อง: ' . json_last_error_msg());
    return; // ออกจากฟังก์ชัน
}

// ✅ ตรวจสอบว่า API ตอบกลับมี authenCode หรือไม่
if (!isset($closevisit['authenCode'])) {
    Yii::$app->session->setFlash('error', '❌ API ตอบกลับข้อมูลผิดพลาด: ' . json_encode($closevisit, JSON_PRETTY_PRINT));
    return; // ออกจากฟังก์ชัน
}

// ✅ ดึงข้อมูลจาก API และฐานข้อมูล
$claimCode = $closevisit['authenCode'];
$cid = isset($closeRow['cid']) ? $closeRow['cid'] : null;
$visitid = isset($closeRow['visit']) ? $closeRow['visit'] : null;
$claimType = isset($closeRow['serviceCode']) ? $closeRow['serviceCode'] : null;
$telephone = isset($closeRow['telephone']) ? $closeRow['telephone'] : null;
//echo "<pre>";
//echo "CID: $cid\n";
//echo "Visit ID: $visitid\n";
//echo "Claim Type: $claimType\n";
//echo "Telephone: $telephone\n";
//echo "</pre>";

// ✅ ตรวจสอบค่าที่ต้องใช้
if (!$cid || !$visitid || !$claimType || !$telephone) {
    Yii::$app->session->setFlash('error', '❌ ข้อมูลไม่ครบถ้วนสำหรับการบันทึก!');
    return; // ออกจากฟังก์ชัน
}

if (empty($claimCode)) {
    Yii::$app->session->setFlash('error', '❌ รหัส ClaimCode ว่าง! กรุณาระบุข้อมูลให้ครบถ้วน');
    return $this->redirect(['index']);
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

        Yii::$app->session->setFlash('success', '✅ อัปเดตข้อมูลสำเร็จ! ' . $claimCode);

    } else {
        // 🔹 ไม่มีข้อมูล -> ทำการ INSERT
        $insertSQL = "
            INSERT INTO authen_kiosk (cid, visit_id, claimtype, claimcode, mobile, dep_name, d_update)
            VALUES (:cid, :visit_id, :claimtype, :authencode, :mobile, 'web', NOW())
        ";
        $command = Yii::$app->db2->createCommand($insertSQL);
        $command->bindParam(':cid', $cid);
        $command->bindParam(':visit_id', $visitid);
        $command->bindParam(':claimtype', $claimType);
        $command->bindParam(':authencode', $claimCode);
        $command->bindParam(':mobile', $telephone);
        $command->execute();

       // Yii::$app->session->setFlash('success', '✅ บันทึกข้อมูลใหม่สำเร็จ! ' . $claimCode);
    }
} catch (\yii\db\Exception $e) {
    Yii::$app->session->setFlash('error', '❌ ไม่สามารถดำเนินการกับฐานข้อมูล: ' . $e->getMessage());
}

// ✅ กลับไปที่หน้าเดิม
return $this->redirect(['index']);
}

###############################################################################################################################################################
// สำหรับฟังก์ชัน check สำหรับ API nhso-service
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
    $authen = json_decode($response, true);
    curl_close($curl);

    if (!is_array($authen) || empty($authen)) {
        Yii::$app->session->setFlash('error', 'ไม่พบข้อมูลจาก API');
        return $this->redirect(['index']);
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

    // เลือก Claim ที่เป็น PP และตรงกับวันนี้
    $selectedClaims = [];
    $today = date('Y-m-d');

    foreach ($claimsByDate as $date => $claims) {
        if ($date === $today && !empty($claims['PP'])) {
            $selectedClaims = array_merge($selectedClaims, $claims['PP']);
        }
    }

    // นำเข้าหรืออัปเดตข้อมูลในฐานข้อมูล
    if (!empty($selectedClaims)) {
        $importedCount = 0; // ตัวนับจำนวนที่นำเข้า
        foreach ($selectedClaims as $claim) {
            $claimType = isset($claim['claimType']) ? $claim['claimType'] : null;
            $telephone = isset($claim['telephone']) ? $claim['telephone'] : '';
            $claimCode = isset($claim['claimCode']) ? $claim['claimCode'] : null;
            $claimDateTime = isset($claim['claimDateTime']) ? $claim['claimDateTime'] : null;

            // ตรวจสอบว่ามีข้อมูลอยู่ในฐานข้อมูลหรือไม่
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
                Yii::$app->session->addFlash('success', "✅ เพิ่มข้อมูลสำเร็จ!VISIT: $visit_id  Endpoint: $claimCode");
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

                Yii::$app->session->addFlash('info', "🔄 อัปเดตข้อมูลสำเร็จ!<br>VISIT: $visit_id<br>Endpoint: $claimCode");
            }
        }
        //Yii::$app->session->addFlash('info', "📊 นำเข้าข้อมูลทั้งหมด $importedCount รายการ!");
    } else {
        Yii::$app->session->setFlash('error', 'ไม่พบการปิดสิทธิ์ PP ของวันนี้');
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
	####################  Export File Text ######################################################################################
		####################  Export File Text ######################################################################################
	public function actionExportAuthen()
{
    $connection = Yii::$app->db2;

    $sql = "
        SELECT  
            a.REG_DATETIME as regdate, 
            a.visit_id as visit,
            d.inscl, 
            d.inscl_name, 
            p.cid as hn, 		
            p.telephone,                   
            u.unit_id, 
            IFNULL(ak.claimcode, '') AS claimcode,
            IFNULL(cv.claimcode, '') AS enpoint
        FROM opd_visits a
        INNER JOIN cid_hn c ON a.HN = c.HN
        INNER JOIN population p ON c.CID = p.CID
        LEFT JOIN main_inscls d ON a.inscl = d.inscl
        LEFT JOIN service_units u ON u.unit_id = a.unit_reg
        LEFT JOIN authen_kiosk ak ON ak.visit_id = a.visit_id AND ak.cid = p.cid 
        LEFT JOIN close_visits cv ON cv.visit_id = a.visit_id 
        WHERE a.REG_DATETIME BETWEEN '2025-10-16 00:00' AND '2025-10-16 23:59'
          AND a.is_cancel = 0
          AND p.NATN_ID = '99'
          AND a.unit_reg NOT IN ('42','51')  
          AND ISNULL(ak.claimcode)
        GROUP BY a.visit_id
        ORDER BY a.REG_DATETIME DESC
    ";

    $rows = $connection->createCommand($sql)->queryAll();

    $path = Yii::getAlias('@webroot/files/');
    if (!file_exists($path)) mkdir($path, 0777, true);

    // ✅ ใช้ชื่อเดียวกันทุกครั้ง
    $filename = $path . 'authen_export.txt';

    $handle = fopen($filename, 'w');
    foreach ($rows as $row) {
        $line = implode('|', [
            $row['regdate'],
            $row['visit'],
            $row['inscl'],
            $row['inscl_name'],
            $row['hn'],
            $row['telephone'],
            $row['unit_id'],
            $row['claimcode'],
            $row['enpoint']
        ]);
        fwrite($handle, $line . PHP_EOL);
    }
    fclose($handle);

    Yii::$app->session->setFlash('success', "ส่งออกข้อมูลเรียบร้อย: " . basename($filename));
    return $this->redirect(['index']);
}

##########################################
# 🔹 IMPORT (นำเข้า)
##########################################
public function actionImportAuthen()
{
    $model = new \yii\base\DynamicModel(['textFile']);
    $model->addRule(['textFile'], 'file', ['skipOnEmpty' => true, 'extensions' => 'txt']);

    $connection = Yii::$app->db2;
    $count = 0;

    // ✅ ถ้ามีการอัปโหลดไฟล์ใหม่
    if (Yii::$app->request->isPost) {
        $model->textFile = UploadedFile::getInstance($model, 'textFile');

        if ($model->textFile && $model->validate()) {
            $filePath = Yii::getAlias('@webroot/files/') . 'authen_export.txt';
            $model->textFile->saveAs($filePath);
        }
    } else {
        // ✅ ถ้าไม่อัปโหลด ให้ใช้ไฟล์ authen_export.txt ที่มีอยู่
        $filePath = Yii::getAlias('@webroot/files/') . 'authen_export.txt';
    }

    if (!file_exists($filePath)) {
        Yii::$app->session->setFlash('error', "ไม่พบไฟล์นำเข้า: " . basename($filePath));
        return $this->redirect(['index']);
    }

    // ✅ อ่านไฟล์นำเข้า
    $handle = fopen($filePath, "r");
    while (($line = fgets($handle)) !== false) {
        $parts = explode('|', trim($line));
        if (count($parts) < 9) continue;

        try {
            $connection->createCommand("
                INSERT INTO authen_kiosk
                (cid, visit_id, claimtype, claimcode, mobile, dep_name, d_update)
                VALUES (:cid, :visit_id, 'PG0060001', :enpoint, :mobile, 'เก็บตก', NOW())
            ")
            ->bindValues([
                ':cid' => $parts[4],
                ':visit_id' => $parts[1],
                ':enpoint' => $parts[8],
                ':mobile' => $parts[5],
            ])->execute();
            $count++;
        } catch (\Exception $e) {
            Yii::error("Insert failed: " . $e->getMessage());
            continue;
        }
    }
    fclose($handle);

    Yii::$app->session->setFlash('success', "นำเข้าข้อมูลสำเร็จ $count รายการ");
    return $this->redirect(['index']);
}
}