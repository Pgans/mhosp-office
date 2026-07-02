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


class ClosefdhController extends \yii\web\Controller
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
    ################# ดึงข้อมูลให้ฟอร์มรายชื่อ ########################
  public function actionIndex()
{
    $data = Yii::$app->request->post();
    $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
    $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';

    $whereDate = (!empty($date1) && !empty($date2)) 
        ? "a.reg_datetime BETWEEN :date1 AND :date2" 
        : "a.reg_datetime BETWEEN CURDATE() AND NOW()";

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
                    CONCAT(TRIM(p.fname), ' ', p.lname) AS fullname,
                    CASE WHEN p.SEX = 1 THEN 'ชาย'
                         WHEN p.SEX = 2 THEN 'หญิง'
                    END AS `เพศ`,
                    TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
                    i.ICD10_TM AS diag,
                    ic.CODE, 
                    ic.NICKNAME, 
                    b.STAFF_ID,
                    p_staff.fname AS STAFF_fname, 
                    b.SURGEON_ID, 
                    p1.fname AS SURGEON_fname, 
                    u.unit_id, 
                    u.unit_name,
                    ic.CGD_ID,
                    IFNULL(ak.claimcode, '') AS claimcode,
					IFNULL(cv.claimcode, '') AS enpoint,
                    CASE 
				WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
				THEN 50.00 
				ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
				END AS amount
				
                FROM opd_visits a
                INNER JOIN cid_hn c ON a.HN = c.HN
                INNER JOIN population p ON c.CID = p.CID
                LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
                INNER JOIN icd9cm ic ON b.icd9 = ic.icd9 AND ic.CGD_ID = 15
                LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
                LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
                LEFT JOIN main_inscls d ON a.inscl = d.inscl
                LEFT JOIN service_units u ON u.unit_id = a.unit_reg
                LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)
                LEFT JOIN staff s ON s.staff_id = b.staff_id
                LEFT JOIN population p_staff ON s.cid = p_staff.cid 
                LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
                LEFT JOIN population p1 ON s1.cid = p1.cid 
                LEFT JOIN close_visits cv ON cv.visit_id = a.visit_id 
				LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
                LEFT JOIN uc_inscl uc ON uc.CID=p.CID AND (uc.date_abort = date(a.REG_DATETIME) OR (day(uc.date_abort)=0 AND trim(uc.hospmain) <> '') ) 
                WHERE $whereDate
                AND a.is_cancel = 0
                #AND ISNULL(cv.claimcode)
                GROUP BY a.visit_id
				ORDER BY cv.claimcode DESC
            ) AS data,
            (SELECT @n := 0) AS init
            ORDER BY No DESC";

    try {
        $rawData = \Yii::$app->db14->createCommand($sql)
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

    // คำขอ CURL
    //$url = 'http://192.168.200.31:8189/api/smartcard/read';
	$url = 'http://192.168.200.31:8189/api/smartcard/read';
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

    if ($response === false || $http_code !== 200) {
        return [
            'status' => 'error',
            'message' => '❌ ไม่สามารถติดต่อเซิร์ฟเวอร์ได้ (' . $curl_error . ')',
        ];
    }

    $agent = json_decode($response, true);

    if (!isset($agent['pid'])) {
        return ['status' => 'error', 'message' => '❌ ข้อมูลที่ได้จาก API ไม่ถูกต้อง'];
    }

    // เก็บค่า pid ลงใน session
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
	public function actionCheck()
{
   // ดึงค่า pid จาก session
    $pid = Yii::$app->session->get('pid');
    // ตรวจสอบว่า pid ถูกตั้งค่าหรือไม่
	if (!$pid) {
		Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน');
		return $this->redirect(['index']); // แก้เป็น action หรือ view ที่ต้องการ
	}

	$sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
	$data = \Yii::$app->db2->createCommand($sqltoken)->queryAll();

	$token_fdh = isset($data[0]['token30']) ? $data[0]['token30'] : null;

	if (!$token_fdh) {
		Yii::$app->session->setFlash('error', '❌ ไม่พบ Token');
		return $this->redirect(['index']); // แก้เป็น action หรือ view ที่ต้องการ
	}


    $vnList = Yii::$app->request->post('chkDel', []); // รับค่า visit จาก form
    $resultArray = [];

    foreach ($vnList as $r) {
        $hn = substr($r, 10);
        $visit = substr($r, 0, 10);

        $strVn = "SELECT 
            @n := @n + 1 AS 'No',
            data.*
            FROM (SELECT DISTINCT 
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
                CASE WHEN p.SEX = 1 THEN 'ชาย'
                     WHEN p.SEX = 2 THEN 'หญิง'
                END AS `เพศ`,
                TIMESTAMPDIFF(YEAR, p.BIRTHDATE, a.REG_DATETIME) AS `age`,
                i.ICD10_TM AS diag,
                ic.CODE, 
                ic.NICKNAME, 
                b.STAFF_ID,
                p_staff.fname AS STAFF_fname, 
                b.SURGEON_ID, 
                p1.fname AS SURGEON_fname, 
                u.unit_id, 
                u.unit_name,
                ic.CGD_ID,
                IFNULL(ak.claimcode, '') AS claimcode,
                CASE 
            WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
            THEN 50.00 
            ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
            END AS amount
            FROM opd_visits a
            INNER JOIN cid_hn c ON a.HN = c.HN
            INNER JOIN population p ON c.CID = p.CID
            LEFT JOIN opd_operations b ON a.visit_id = b.visit_id AND a.is_cancel = 0
            INNER JOIN icd9cm ic ON b.icd9 = ic.icd9 AND ic.CGD_ID = 15
            LEFT JOIN opd_diagnosis od ON a.VISIT_ID = od.VISIT_ID AND od.IS_CANCEL = 0 AND od.DXT_ID = 1
            LEFT JOIN icd10new i ON od.ICD10 = i.ICD10 
            LEFT JOIN main_inscls d ON a.inscl = d.inscl
            LEFT JOIN service_units u ON u.unit_id = a.unit_reg
            LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(a.REG_DATETIME) = DATE(ak.d_update)
            LEFT JOIN staff s ON s.staff_id = b.staff_id
            LEFT JOIN population p_staff ON s.cid = p_staff.cid 
            LEFT JOIN staff s1 ON s1.staff_id = b.SURGEON_ID
            LEFT JOIN population p1 ON s1.cid = p1.cid 
            LEFT JOIN close_visits cv ON cv.visit_id = a.visit_id 
            LEFT JOIN cost_visits cos ON cos.visit_id = a.visit_id AND cos.is_cancel = 0
            LEFT JOIN uc_inscl uc ON uc.CID=p.CID AND (uc.date_abort = date(a.REG_DATETIME) OR (day(uc.date_abort)=0 AND trim(uc.hospmain) <> '') ) 
            WHERE a.visit_id = '$visit'
            AND a.is_cancel = 0
            AND ISNULL(cv.claimcode)
            GROUP BY a.visit_id
            ) AS data, (SELECT @n := 0) AS init
            ORDER BY No DESC, data.claimcode ASC";

        $closeData = \Yii::$app->db14->createCommand($strVn)->queryAll();

        foreach ($closeData as $closeRow) {
        // แก้ไขตรงนี้ไม่ใช้ array[] แต่ใช้ object key-value
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
        }
    

    //return json_encode($resultArray, JSON_PRETTY_PRINT);
	$resultText = json_encode($resultArray, JSON_PRETTY_PRINT);
	//echo $resultText;

// ตั้งค่า cURL
  $url = "https://fdh.moph.go.th/api/v1/authen_code";
//$url = "https://uat-fdh.inet.co.th/api/v1/authen_code";
$curl = curl_init($url);
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $resultText,
    CURLOPT_HTTPHEADER => array(
        "Content-type: application/json",
        "Authorization: Bearer " . $token_fdh
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
//echo $response;

// ตรวจสอบว่า API ตอบกลับหรือไม่
if (!$response || $err) {
    Yii::$app->session->setFlash('error', '❌ กรุณาเสียบบัตรประชาชน: ' . $err);
    return $this->redirect(['index']); // แก้เป็น action หรือ view ที่ต้องการ
}

$closevisit = json_decode($response, true);

if (!isset($closevisit['data'])) {
    // ตั้งค่า session flash สำหรับแจ้งเตือน
    Yii::$app->session->setFlash('error', 'Invalid response: ' . $response);
}

// ดึงค่าจาก API
$message = isset($closevisit['message']) ? addslashes($closevisit['message']) : "";
$message_th = isset($closevisit['message_th']) ? addslashes($closevisit['message_th']) : "";
#$claimcode = isset($closevisit['data']['authen_code']) ? $closevisit['data']['authen_code'] : "";
$transaction_id = isset($closevisit['data']['transaction_id']) ? $closevisit['data']['transaction_id'] : "";
$claimcode = isset($closevisit['data']['authen_code']) ? $closevisit['data']['authen_code'] : "";
echo "Claimcode: " . $claimcode . "<br>";  // พิมพ์ค่าออกมาดู


// บันทึกข้อมูลลงฐานข้อมูล
if (!empty($message) && !empty($transaction_id)) {
    $visit = $closeRow['visit']; // ตรวจสอบให้แน่ใจว่า $visit มีค่า
    $cid = $closeRow['cid'];
    $regdate = $closeRow['regdate'];
	$total_amount = $closeRow['amount'];
    $recorder_pid = isset($closeRow['recorder_pid']) ? $closeRow['recorder_pid'] : "";

    $strSQL = "INSERT INTO close_visits (
        visit_id, cid, transaction_id, claimtype, claimcode, recorder_pid, message, claim_datetime,total_amount, d_update
    ) VALUES (
        '$visit', 
        '$cid', 
        '$transaction_id', 
        '', 
        '$claimcode', 
        '$recorder_pid', 
        '$message', 
        '$regdate',
		'$total_amount',			
        NOW()
    )";

    Yii::$app->db2->createCommand($strSQL)->execute();
}

	}  
		Yii::$app->session->setFlash('info', $message_th);
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
