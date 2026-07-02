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
use app\models\Logclosevisit;
use yii\web\NotFoundHttpException;
use app\models\CloseVisit; 
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


class Closevisit1Controller extends \yii\web\Controller
{
	/*
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
*/
    public function actionIndexxxx()
    {
        // $_token = $model->token;


        return $this->render('indexxxx');
    }
    ################# ดึงข้อมูลให้ฟอร์มรายชื่อ ########################
   public function actionIndex()
{
    $pageSize = Yii::$app->request->get('pageSize', 50); // รับค่าจำนวนรายการต่อหน้าจาก request หรือใช้ค่าเริ่มต้นเป็น 50

    // ตรวจสอบว่าค่าที่รับมาถูกต้องหรือไม่
    if (!in_array($pageSize, [50, 100, 200])) {
        $pageSize = 50;
    }

    $sqlvisits = "SELECT 
    @n := @n + 1 AS 'No',
    data.*
FROM 
(
    SELECT 
        date(o.reg_datetime) AS 'regdate',
        TIME(o.reg_datetime) AS time_start,
        TIME(o.finish_datetime) AS time_end,
        o.visit_id,
        o.hn,
        p.cid,
        p.NATN_ID as nation,
		TIMESTAMPDIFF(YEAR, p.BIRTHDATE, o.REG_DATETIME) AS 'age',
       # ak.claimcode,
        #icd1.icd10_tm AS diag,
        CONCAT(
            CASE 
                WHEN p.PRENAME <> '' THEN TRIM(p.PRENAME)
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'สามเณร'
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 20 AND p.sex = '1' AND p.MARRIAGE = '4' THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15 AND p.sex = '1' THEN 'เด็กชาย'
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) < 15 AND p.sex = '2' THEN 'เด็กหญิง'
                WHEN TIMESTAMPDIFF(YEAR, p.BIRTHDATE, NOW()) >= 15 AND p.sex = '2' AND p.MARRIAGE = '1' THEN 'นางสาว'
                ELSE 'นาง' 
            END, 
            TRIM(p.FNAME), ' ', TRIM(p.LNAME)) AS 'fullname',
        CASE 
    WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
    THEN 50.00 
    ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
END AS amount,
        o.inscl,
        LEFT(e.unit_name, 10) AS 'unit_name',
        COALESCE(cos.auto_id, RIGHT(o.visit_id, 7)) AS 'invoice_number'
     
    FROM opd_visits o 
    INNER JOIN cid_hn c ON o.HN = c.HN
    INNER JOIN population p ON c.CID = p.CID AND LEFT(p.cid, 5) <> '00000'
    LEFT JOIN service_units e ON o.UNIT_REG = e.unit_id          
    LEFT JOIN main_inscls f ON o.INSCL = f.INSCL
    #LEFT JOIN authen_kiosk ak ON p.CID = ak.cid AND DATE(o.REG_DATETIME) = DATE(ak.d_update)
    LEFT JOIN cost_visits cos ON cos.visit_id = o.visit_id AND cos.is_cancel = 0
   # LEFT JOIN opd_diagnosis d1 ON d1.visit_id = o.visit_id AND d1.is_cancel = 0 AND d1.dxt_id = 1
   # LEFT JOIN icd10new icd1 ON icd1.icd10 = d1.icd10 
    LEFT JOIN log_closevisits lc ON lc.visit_id = o.visit_id  
    #LEFT JOIN ipd_reg ir ON o.visit_id = ir.visit_id  -- เพิ่ม LEFT JOIN สำหรับการกรองข้อมูล
    WHERE o.REG_DATETIME BETWEEN CURDATE() AND NOW()
	#AND o.VISIT_ID NOT IN (SELECT visit_id FROM ipd_reg WHERE is_cancel = 0)
  AND o.UNIT_REG NOT IN ('42','51')
        AND o.is_cancel = '0'
        AND p.NATN_ID = '99'
        AND o.visit_id NOT IN (SELECT visit_id FROM log_closevisits )
		#AND o.visit_id not in (SELECT ipd_reg.VISIT_ID FROM ipd_reg WHERE ipd_reg.IS_CANCEL=0 AND ward_no <> '50')
    GROUP BY o.visit_id
) AS data,
(SELECT @n := 0) AS init
ORDER BY No DESC, amount DESC;
 ";
        
    try {
        $rawData = \Yii::$app->db2->createCommand($sqlvisits)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }

    $visitProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pageSize' => $pageSize,
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

        $data = \yii::$app->db2->createCommand($total)->queryAll();
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

    ################ ActionHt-> ActionCheck #########################
    public function actionCheck()
    {
		$sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
       ## $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token";

        $data = \yii::$app->db2->createCommand($sqltoken)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $token_fdh = $data[$i]['token30'];
        }
        ##################################################################     
        // $vn =  Yii::$app->request->post('chkDel');
        $vn = Yii::$app->request->post('chkDel', []);

        foreach ($vn  as $r) {
            $hn = substr($r, 10);
            //echo $hn.'<br />';
            $visit = substr($r, 0, 10);
			
            ############ ดึงข้อมูลมาประกอบ Json ############################

            $strVn = "SELECT 
        @n := @n + 1 AS 'No',
        data.*
			  FROM 
		(SELECT DATE_FORMAT(o.reg_datetime, '%Y-%m-%d %H:%i') as 'regdate'
		,time(o.reg_datetime) as time_start
		,time(o.finish_datetime) as time_end
		,c.uuid
		,o.visit_id as 'vn'
		,o.hn
		,'10953' as 'hcode'
		, CONCAT(
          CASE 
                 WHEN p.PRENAME not in('') THEN TRIM(p.PRENAME)
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW())< '20' AND p.sex='1' AND p.MARRIAGE = '4'THEN 'สามเณร'
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '20' AND p.sex='1' AND p.MARRIAGE  = '4'THEN 'พระภิกษุ'
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='1' THEN 'เด็กชาย'
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='1' THEN 'นาย'
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) < '15'  AND p.sex='2' THEN 'เด็กหญิง'
                       WHEN TIMESTAMPDIFF(year,p.BIRTHDATE,NOW()) >= '15' AND p.sex='2' AND p.MARRIAGE ='1' THEN 'นางสาว'
                       ELSE 'นาง' 
                END ,TRIM(p.FNAME),'  ',TRIM(p.LNAME)) as 'fullname'
          ,TIMESTAMPDIFF(year,p.BIRTHDATE,o.REG_DATETIME) as 'age'
          ,p.cid
,o.inscl
,left(e.unit_name,10) 'unit_name' 
,icd1.icd10_tm as diag
,COALESCE(cos.auto_id, RIGHT(o.visit_id, 7)) AS 'invoice_number'
,CASE 
    WHEN COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0) = 0 
    THEN 50.00 
    ELSE COALESCE((cg01 + cg02 + cg03 + cg04 + cg05 + cg06 + cg07 + cg08 + cg09 + cg10 + cg11 + cg12 + cg13 + cg14 + cg15 + cg16 + cg17 + cg18 + cg19), 0)
END AS amount
       , CASE
	    WHEN ak.claimcode = '' THEN 'ว่าง'
	    WHEN ak.claimcode <> '' THEN ak.claimcode
        END AS claimcode
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
		AND o.visit_id = '$visit'
		GROUP BY o.VISIT_ID 
		) AS data,
			  (SELECT @n := 0) AS init
				ORDER BY  No DESC ,data.claimcode ASC";

            $closeData = \yii::$app->db2->createCommand($strVn)->queryAll();

            $resultArray = [];

            foreach ($closeData as $closeRow) {
                $resultArray = [
					"transaction_uid" => $closeRow['uuid'],
                    "service_date_time" => $closeRow['regdate'],
                    "cid" => $closeRow['cid'],
                    "hcode" => $closeRow['hcode'],
                    "total_amout" => $closeRow['amount'],
                    "invoice_number" => $closeRow['invoice_number'],
                    "vn" => $closeRow['vn']
                ];
            }

            $resultText = json_encode($resultArray, JSON_PRETTY_PRINT);

           // echo $resultText;

            ########################################################################################
            //$token = $token_fdh;

            # $url = "https://epidemcenter.moph.go.th/epidem/api/SendEPIDEM";
            #$url = "https://uat-fdh.inet.co.th/api/v1/reservation";  //Production : https://fdh.moph.go.th  
			//  $url = "https://uat-fdh.inet.co.th/api/v1/reservation";
			  $url = "https://fdh.moph.go.th/api/v1/reservation";
            

            // $_token = $token30;
               $curl = curl_init($url);
               curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                //SSL USE
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,

                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $resultText,
                CURLOPT_HTTPHEADER => array(
                    "Content-type: application/json",
                    "Authorization: Bearer " . $token_fdh
                ),
            ));
            $response = curl_exec($curl);
            $closevisit = json_decode($response, true);
            $err = curl_error($curl);
            //curl_close($curl);
            // echo $response;
            curl_close($curl);
            // $cid = $closevisit['results']['cid'];
            $message = $closevisit['message'];
            $message_th = $closevisit['message_th'];
            $status = $closevisit['status'];
			$transaction_uid = $closevisit['data']['transaction_uid'];
            // echo $status;
           // echo $response;

            ############################INSERT TABLE Log_closevisits #############################   cost_visits->visit_id-> return send_date-> booK-id->status 200

            if (strlen($response) > 0) {
				 $strSQL = "REPLACE INTO log_closevisits (visit_id, pid, status, messagecode, response, transaction_uid, users, send_date, regdate) 
           VALUES ('$visit', '$hn', $status, '$message', '$message_th', '$transaction_uid', 'จองเคลม', NOW(), '{$closeRow['regdate']}')";
               // $strSQL = "REPLACE INTO log_closevisits (visit_id, pid,status, messagecode ,response , transaction_uid, users,send_date) VALUES ('$visit','$hn', $status,'$message','$message_th','$transaction_uid','จองเคลม',NOW())";
                Yii::$app->db2->createCommand($strSQL)->execute();
            }
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
