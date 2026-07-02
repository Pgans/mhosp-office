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
use app\models\Logclosevisitsj;
use yii\web\NotFoundHttpException;




class ClosevisitjhcisController extends \yii\web\Controller
{
    public function actionIndexxxx()
    {
        // $_token = $model->token;


        return $this->render('indexxxx');
    }
    ################# ดึงข้อมูลให้ฟอร์มรายชื่อ ########################
    public function actionIndex()
    {
        $sqlvisits = "SELECT 
        @n := @n + 1 AS 'No',
        data.*
      FROM 
(SELECT DISTINCT v.dateupdate as regdate,v.visitno as visit_id,CONCAT('99809','',v.visitno) as invoice_number,
		CASE
	WHEN v.claimcode_nhso is null THEN ak.claimcode
		ELSE v.claimcode_nhso
		END as claimcode_nhso
		, v.hiciauthen_nhso, v.pid, m.hn, p.idcard as cid, p.telephoneperson, p.mobile,
		 c.rightcode, c.rightname, v.symptoms,
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
		WHERE DATE_FORMAT( v.visitdate, '%Y-%m-%d' ) BETWEEN CURDATE() AND NOW()
        AND v.visitno not in (SELECT visit_id FROM log_closevisitsj)
		#AND (claimcode_nhso = '' OR claimcode_nhso is null)
		ORDER BY v.visitdate 
        ) AS data,
			  (SELECT @n := 0) AS init
				ORDER BY  No DESC 
         ";

        $rawData = \yii::$app->db_jhcis->createCommand($sqlvisits)->queryAll();
        try {
            $rawData = \Yii::$app->db_jhcis->createCommand($sqlvisits)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $visitProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        #########################################################################
        $sqlCount1 = "SELECT COUNT(DISTINCT v.visit_id) as amount
            FROM log_closevisitsj v 
            WHERE v.messagecode = 'success'
            AND v.send_date BETWEEN CURDATE() AND NOW()";

        $data = \yii::$app->db_jhcis->createCommand($sqlCount1)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amount = $data[$i]['amount'];
        }
        $sqlCamount = "SELECT COUNT(DISTINCT v.visit_id) as amountx
             FROM log_closevisitsj v 
             WHERE v.messagecode <> 'success'
             AND v.send_date BETWEEN CURDATE() AND NOW()";
        $data = \yii::$app->db_jhcis->createCommand($sqlCamount)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $amountx = $data[$i]['amountx'];
        }
        $total = "SELECT COUNT(DISTINCT v.visit_id) as total
            FROM log_closevisitsj v 
            WHERE v.messagecode = 'success'
            AND v.send_date BETWEEN '2024-09-01' AND NOW()
             ";

        $data = \yii::$app->db_jhcis->createCommand($total)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $total = $data[$i]['total'];
        }
		$todays = "SELECT COUNT(pid) as today
        FROM visit
        WHERE visitdate BETWEEN CURDATE() and NOW()
             ";

        $data = \yii::$app->db_jhcis->createCommand($todays)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $todayx = $data[$i]['today'];
        }
        ########################################################################################################
        $sqlPass = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date, transaction_uid
        FROM log_closevisitsj l 
        WHERE l.send_date BETWEEN CURDATE() AND NOW()
        AND l.messagecode = 'success' AND l.users = 'jhcis'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db_jhcis->createCommand($sqlPass)->queryAll();

        // สร้าง Flash Alert
        //Yii::$app->session->setFlash('success', 'รายการที่ไม่ผ่านตามเงื่อนไข');

        $passProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        ########################################################################################################
        $sqlError = "select l.id, l.visit_id, l.pid , l.messagecode, l.response, l.users, l.send_date, transaction_uid
        FROM log_closevisitsj l 
        WHERE l.send_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
        AND l.messagecode <> 'success' AND l.users = 'jhcis'
        ORDER BY l.send_date DESC
        
         ";
        $rawData = \Yii::$app->db_jhcis->createCommand($sqlError)->queryAll();

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
            'total' => $total,
			'todayx' => $todayx,
            'passProvider' => $passProvider,
            'errorProvider' => $errorProvider,

        ]);
    }

    ################ ActionHt-> ActionCheck #########################
    public function actionCheck()
    {
		$sqltoken = "SELECT MAX(token) as token30 FROM fdh_token WHERE staff_id = 'pgans'";
       // $sqltoken = "SELECT MAX(token) as token30 FROM fdh_token";

        $data = \yii::$app->db2->createCommand($sqltoken)->queryAll();
        for ($i = 0; $i < sizeof($data); $i++) {
            $token_fdh = $data[$i]['token30'];
        }
        ##################################################################     
        // $vn =  Yii::$app->request->post('chkDel');
        $vn = Yii::$app->request->post('chkDel', []);

        foreach ($vn  as $r) {
            $hn = substr($r, 6);
            //echo $hn.'<br />';
            $visit = substr($r, 0, 6);
			
            ############ ดึงข้อมูลมาประกอบ Json ############################

            $strVn = "SELECT DISTINCT '10953' as hcode, DATE_FORMAT(v.dateupdate, '%Y-%m-%d %H:%i') as 'regdate',
            v.visitno as visit_id,CONCAT('99809','',v.visitno) as invoice_number
            , v.hiciauthen_nhso as authencode, v.pid, m.hn, p.idcard as cid, 
             c.rightcode, c.rightname,
            CONCAT(p.fname,' ',lname) as fullname, timestampdiff(year,p.birth,v.visitdate) AS age,
            REPLACE( IF( cdisease.mapdisease <> '', cdisease.mapdisease, cdisease.diseasecode ), '.', '' ) AS DIAGCODE ,
            RIGHT( vd.dxtype, 1 ) as DXTYPE, #vd.dxtype,
            IFNULL(NULLIF(v.money1, 0), 50.00) AS money1
            FROM visit v
            LEFT JOIN person p ON p.pid = v.pid
            LEFT JOIN cright c ON c.rightcode = v.rightcode
            LEFT JOIN visitdiag vd ON vd.visitno = v.visitno AND vd.dxtype = 01 
            LEFT JOIN cdisease ON ( vd.diagcode = cdisease.diseasecode ) 
            LEFT JOIN authen_pcu ak ON p.idcard = ak.cid  AND v.visitdate  = date(ak.d_update)
            LEFT JOIN mathhn m ON m.pid = p.pid
            WHERE DATE_FORMAT( v.visitdate, '%Y-%m-%d' ) BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND NOW()
           #AND (claimcode_nhso = '' OR claimcode_nhso is null)
            AND v.visitno = '$visit'
            ORDER BY v.visitdate ";

            $closeData = \yii::$app->db_jhcis->createCommand($strVn)->queryAll();

            $resultArray = [];

            foreach ($closeData as $closeRow) {
                $resultArray = [
					"transaction_uid" => $closeRow['uuid'],
                    "service_date_time" => $closeRow['regdate'],
                    "cid" => $closeRow['cid'],
                    "hcode" => $closeRow['hcode'],
                    "total_amout" => $closeRow['money1'],
                    "invoice_number" => $closeRow['invoice_number'],
                    "vn" => $closeRow['visit_id']
                ];
            }

            $resultText = json_encode($resultArray, JSON_PRETTY_PRINT);

            //echo $resultText;

            ########################################################################################
            //$token = $token_fdh;

            # $url = "https://epidemcenter.moph.go.th/epidem/api/SendEPIDEM";
            #$url = "https://uat-fdh.inet.co.th/api/v1/reservation";  //Production : https://fdh.moph.go.th  
			  //$url = "https://uat-fdh.inet.co.th/api/v1/reservation";
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
            //echo $response;

            ############################INSERT TABLE Log_closevisits #############################   cost_visits->visit_id-> return send_date-> booK-id->status 200

            if (strlen($response) > 0) {
                $strSQL = "INSERT INTO log_closevisitsj (visit_id, pid, status, messagecode, response, transaction_uid, users, send_date, regdate) 
           VALUES ('$visit', '$hn', $status, '$message', '$message_th', '$transaction_uid', 'jhcis', NOW(), '{$closeRow['regdate']}')
                        ON DUPLICATE KEY UPDATE 
                            pid = VALUES(pid), 
                            status = VALUES(status), 
                            messagecode = VALUES(messagecode), 
                            response = VALUES(response), 
                            transaction_uid = VALUES(transaction_uid), 
                            users = VALUES(users), 
                            send_date = VALUES(send_date), 
                            regdate = VALUES(regdate)";
                Yii::$app->db_jhcis->createCommand($strSQL)->execute();

            }
        }            
        return $this->redirect(['index']);
    }
    public function actionDelete($id)
{
    $model = $this->findModel($id);
    $model->delete();
    
    return $this->redirect(['index']);
}


protected function findModel($id)
{
    if (($model = Logclosevisitsj::findOne($id)) !== null) {
        return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
}


    public function actionDeleteSpecific()
    {
        // คำสั่ง SQL สำหรับลบ 10 รายการที่ไม่สำเร็จ
        $sql = "DELETE FROM log_closevisitsj
                WHERE messagecode <> 'success'
                AND users = 'jhcis'
                LIMIT 10";

        Yii::$app->db_jhcis->createCommand($sql)->execute(); // ดำเนินการลบ
        
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

    #########################// Action สำหรับลบหลายรายการ ######################################################
    public function actionDeleteMultiple()
    {
        // รับค่าจาก POST ที่เป็นอาร์เรย์ของ id ที่ถูกเลือก
        $ids = Yii::$app->request->post('selection', []); // 'selection' คือชื่อของ checkbox ที่ GridView ใช้โดยปริยาย
        
        if (!empty($ids)) {
            // สมมติว่ามี model ที่ชื่อ CloseVisit
            CloseVisit::deleteAll(['id' => $ids]); // ลบรายการทั้งหมดที่ id ตรงกับในอาร์เรย์ $ids
            Yii::$app->session->setFlash('success', 'ลบรายการที่เลือกเรียบร้อยแล้ว.');
        } else {
            Yii::$app->session->setFlash('error', 'ไม่มีรายการที่ถูกเลือก.');
        }
        
        return $this->redirect(['index']); // เปลี่ยนเส้นทางกลับไปยังหน้า index หรือหน้าอื่นที่ต้องการ
    }
    
}

