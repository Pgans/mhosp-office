<?php

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


//include Yii::getAlias('@app').'/config/thai_date.php';

class ComputerxController extends \yii\web\Controller
{
	
     public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                 'only' => ['countdevices','saledevices','serviceout','devicenew'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	public function actionBreastcancer()
    {	 
	     $sql = "SELECT 
			DATE(k.REG_DATETIME) AS regdate,
			COUNT(k.visit_id) AS register,
			SUM(CASE WHEN k.claimcode <> '' THEN 1 ELSE 0 END) AS authen,
			SUM(CASE WHEN ISNULL(k.claimcode) OR k.claimcode = '' THEN 1 ELSE 0 END) AS no_authen,
			SUM(CASE WHEN k.drug_id IS NOT NULL THEN 1 ELSE 0 END) AS drug
		FROM 
			(SELECT 
				 o.REG_DATETIME, 
				 o.visit_id, 
				 o.hn, 
				 o.unit_reg, 
				 u.unit_name, 
				 a.claimcode,
				 d.drug_id, 
				 i.nickname 
			 FROM  
				 opd_visits o
			 LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			 LEFT JOIN authen_kiosk a ON a.visit_id = o.visit_id
			 LEFT JOIN prescriptions ps ON ps.visit_id = o.visit_id AND ps.is_cancel = 0
			 LEFT JOIN drugs d ON d.drug_id = ps.drug_id
			 LEFT JOIN opd_operations op ON op.visit_id = o.visit_id AND op.is_cancel = 0
			 LEFT JOIN icd9cm i ON i.icd9 = op.icd9
			 WHERE 
				 o.unit_reg = '90'  
				 AND o.is_cancel = 0
				 AND o.REG_DATETIME BETWEEN '2026-06-08 00:00' AND '2026-06-09 23:59'
			 GROUP BY 
				 o.visit_id  
			) AS k
		GROUP BY 
			DATE(k.REG_DATETIME);

			";
     $rawData = \yii::$app->db4->createCommand($sql)->queryAll();
        $dentalProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
     
		return $this->render('breastcancer',[
              'dentalProvider' => $dentalProvider,
             
          ]);
    }
	public function actionMobileposw()
    {	 
	     $sql = "SELECT 
			DATE(k.REG_DATETIME) AS regdate,
			COUNT(k.visit_id) AS register,
			SUM(CASE WHEN k.claimcode <> '' THEN 1 ELSE 0 END) AS authen,
			SUM(CASE WHEN ISNULL(k.claimcode) OR k.claimcode = '' THEN 1 ELSE 0 END) AS no_authen,
			SUM(CASE WHEN k.drug_id IS NOT NULL THEN 1 ELSE 0 END) AS drug
		FROM 
			(SELECT 
				 o.REG_DATETIME, 
				 o.visit_id, 
				 o.hn, 
				 o.unit_reg, 
				 u.unit_name, 
				 a.claimcode,
				 d.drug_id, 
				 i.nickname 
			 FROM  
				 opd_visits o
			 LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			 LEFT JOIN authen_kiosk a ON a.visit_id = o.visit_id
			 LEFT JOIN prescriptions ps ON ps.visit_id = o.visit_id AND ps.is_cancel = 0
			 LEFT JOIN drugs d ON d.drug_id = ps.drug_id
			 LEFT JOIN opd_operations op ON op.visit_id = o.visit_id AND op.is_cancel = 0
			 LEFT JOIN icd9cm i ON i.icd9 = op.icd9
			 WHERE 
				 o.unit_reg = '80'  
				 AND o.is_cancel = 0
				 AND o.REG_DATETIME BETWEEN '2025-09-4 00:00' AND '2025-09-4 23:59'
			 GROUP BY 
				 o.visit_id  
			) AS k
		GROUP BY 
			DATE(k.REG_DATETIME);

			";
     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dentalProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
     
		return $this->render('mobileposw',[
              'dentalProvider' => $dentalProvider,
             
          ]);
    }
	#############################################################################################
		public function actionDental()
    {	 
	     $sql = "SELECT 
			DATE(k.REG_DATETIME) AS regdate,
			COUNT(k.visit_id) AS register,
			SUM(CASE WHEN k.claimcode <> '' THEN 1 ELSE 0 END) AS authen,
			SUM(CASE WHEN ISNULL(k.claimcode) OR k.claimcode = '' THEN 1 ELSE 0 END) AS no_authen,
			SUM(CASE WHEN k.drug_id IS NOT NULL THEN 1 ELSE 0 END) AS drug
		FROM 
			(SELECT 
				 o.REG_DATETIME, 
				 o.visit_id, 
				 o.hn, 
				 o.unit_reg, 
				 u.unit_name, 
				 a.claimcode,
				 d.drug_id, 
				 i.nickname 
			 FROM  
				 opd_visits o
			 LEFT JOIN service_units u ON u.unit_id = o.unit_reg
			 LEFT JOIN authen_kiosk a ON a.visit_id = o.visit_id
			 LEFT JOIN prescriptions ps ON ps.visit_id = o.visit_id AND ps.is_cancel = 0
			 LEFT JOIN drugs d ON d.drug_id = ps.drug_id
			 LEFT JOIN opd_operations op ON op.visit_id = o.visit_id AND op.is_cancel = 0
			 LEFT JOIN icd9cm i ON i.icd9 = op.icd9
			 WHERE 
				 o.unit_reg = '73'  
				 AND o.is_cancel = 0
				 AND o.REG_DATETIME BETWEEN '2024-11-27 00:00' AND '2024-11-28 23:59'
			 GROUP BY 
				 o.visit_id  
			) AS k
		GROUP BY 
			DATE(k.REG_DATETIME);

			";
     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dentalProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
     
		return $this->render('dental',[
              'dentalProvider' => $dentalProvider,
             
          ]);
    }
	
#######################################################################################################	
	public function actionAuthen()
    {	 
	     $sql = "SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%แบต%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
			";
     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		//สรุปคอมพิวเตอร์และอุปกรณ์ต่อพ่วง
        $connection = Yii::$app->db14;
       $datam = $connection->createCommand("
        SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%แบต%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
		")->queryAll(); 

        $dataProvider = new ArrayDataProvider([
            'allModels' => $datam,
            'sort'=>[
                'attributes'=>['fiscal','PC','NoteBook']
            ],
        ]);
		 //เตรียมข้อมูลคอมพิวเตอร์ส่งให้กราฟ
        for ($i = 0; $i < sizeof($datam); $i++) {
            $year[] = (int) $datam[$i]['ปีงบประมาณ'];
            $total[] = (int) $datam[$i]['ราคารวม'];
            
        }
		
     
		return $this->render('authen',[
              'dataProvider' => $dataProvider,
              'year'=>$year,
                    'total'=>$total,
			  //'date2'=>$date2,
			 // 'amount'=>$amount, 
          ]);
    }
	public function actionInk()
    {	 
	     $sql = "SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%หมึก%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
			";
     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		//สรุปคอมพิวเตอร์และอุปกรณ์ต่อพ่วง
        $connection = Yii::$app->db14;
       $datam = $connection->createCommand("
        SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%หมึก%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
		")->queryAll(); 

        $cdataProvider = new ArrayDataProvider([
            'allModels' => $datam,
            'sort'=>[
                'attributes'=>['fiscal','PC','NoteBook']
            ],
        ]);
		 //เตรียมข้อมูลคอมพิวเตอร์ส่งให้กราฟ
        for ($i = 0; $i < sizeof($datam); $i++) {
            $year[] = (int) $datam[$i]['ปีงบประมาณ'];
            $total[] = (int) $datam[$i]['ราคารวม'];
            
        }
		
     
		return $this->render('ink',[
              'dataProvider' => $dataProvider,
              'year'=>$year,
                    'total'=>$total,
			  //'date2'=>$date2,
			 // 'amount'=>$amount, 
          ]);
    }
	public function actionBattery()
    {	 
	     $sql = "SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%แบต%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
			";
     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		//สรุปคอมพิวเตอร์และอุปกรณ์ต่อพ่วง
        $connection = Yii::$app->db14;
       $datam = $connection->createCommand("
        SELECT 
    fiscal as 'ปีงบประมาณ',
    FORMAT(SUM(TOTAL), 2) AS 'ราคารวม'
FROM 
    (
        SELECT DISTINCT 
            c.EXP_ID,
            c.IVS_DATE,
            a.IVT_ID,
            b.IVT_NAME,
            b.IVC_ID,
            a.QUANTITY,
            d.UUNIT_NAME, 
            ROUND(a.PACK_PRICE, 2) AS PACK_PRICE,
            ROUND(a.QUANTITY * a.PACK_PRICE, 2) AS TOTAL,
            IF(MONTH(c.IVS_DATE) > 9, YEAR(c.IVS_DATE) + 544, YEAR(c.IVS_DATE) + 543) AS fiscal
        FROM 
            order_details a
        JOIN 
            inventory b ON a.IVT_ID = b.IVT_ID
        JOIN 
            invoices c ON a.IVS_ID = c.IVS_ID
        JOIN 
            ivt_units d ON a.UUNIT_ID = d.UUNIT_ID
        WHERE 
            c.IVS_DATE >= '2019-10-01 00:00'
            AND b.IVT_NAME LIKE '%แบต%'
            AND b.IVC_ID = '04'
    ) AS k
GROUP BY 
    fiscal
ORDER BY 
    fiscal;
		")->queryAll(); 

        $dataProvider = new ArrayDataProvider([
            'allModels' => $datam,
            'sort'=>[
                'attributes'=>['fiscal','PC','NoteBook']
            ],
        ]);
		 //เตรียมข้อมูลคอมพิวเตอร์ส่งให้กราฟ
        for ($i = 0; $i < sizeof($datam); $i++) {
            $year[] = (int) $datam[$i]['ปีงบประมาณ'];
            $total[] = (int) $datam[$i]['ราคารวม'];
            
        }
		
     
		return $this->render('battery',[
              'dataProvider' => $dataProvider,
              'year'=>$year,
                    'total'=>$total,
			  //'date2'=>$date2,
			 // 'amount'=>$amount, 
          ]);
    }
	public function actionIndex2()
    {	 
	     $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_thaimed v 
			";
        
         $data = \yii::$app->db->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
        //return $this->render('index');
		return $this->render('index2',[
              'dataProvider' => $dataProvider,
             // 'sql'=>$sql,
			 // 'date1'=>$date1,
			  //'date2'=>$date2,
			 // 'amount'=>$amount, 
          ]);
    }
    public function actionIndex()
    {
		//ซือคอมพิวเตอร์และอุปกรณ์ต่อพ่วง
        $connection = Yii::$app->db;
       $datacom = $connection->createCommand("
         
                SELECT  k.fiscal , 
                COUNT(CASE WHEN (k.category_id = 1) THEN 1 END )  AS  PC,
                COUNT(CASE WHEN (k.category_id = 2) THEN 2 END )  AS  NB,
                COUNT(CASE WHEN (k.category_id =3) THEN 3 END )  AS  PrinLaser,
                COUNT(CASE WHEN (k.category_id = 4) THEN 4 END )  AS  PrinInk,
                COUNT(CASE WHEN (k.category_id = 5) THEN 5 END )  AS  UPS,
                COUNT(CASE WHEN (k.category_id = 9) THEN 9 END )  AS  LCD,
                COUNT(CASE WHEN (k.category_id = 10 ) THEN 10 END )  AS  Termal ,
                COUNT(CASE WHEN (k.category_id = 13) THEN 13  END )  AS  Scan,
				COUNT(CASE WHEN (k.category_id = 14) THEN 14  END )  AS  Ipad,
                COUNT(CASE WHEN (k.category_id >0) THEN 15  END )  AS  Total,
                SUM(k.price) AS Price
				FROM (
				SELECT a.device_serial , a.device_name, a.category_id ,b.category_name, a.purchase_date, a.sale_date, a.price,
				IF(MONTH(a.purchase_date)>9,YEAR(a.purchase_date)+544,YEAR(a.purchase_date)+543) AS fiscal
				FROM devices a, categories b
				WHERE a.sale_date = 0
				AND a.purchase_date >= '20171001'
				AND a.category_id = b.category_id) as k
				GROUP BY k.fiscal ")->queryAll(); 
			
        $comdataProvider = new ArrayDataProvider([
            'allModels' => $datacom,
            'sort'=>[
                'attributes'=>['fiscal','Price','Total']
            ],
        ]);
       //เตรียมข้อมูลคอมพิวเตอร์ส่งให้กราฟ
        for ($i = 0; $i < sizeof($datacom); $i++) {
            $cfiscal[] = $datacom[$i]['fiscal'];
            $total[] = (int) $datacom[$i]['Total'];
            $price[] = (int) $datacom[$i]['Price'];
        }
		
        //สรุปคอมพิวเตอร์และอุปกรณ์ต่อพ่วง
        $connection = Yii::$app->db;
       $datam = $connection->createCommand("
        SELECT  'จำนวนเครื่อง' AS ประเภท,
        COUNT(CASE WHEN (k.category_id = 1) THEN 1 END )  AS  PC,
        COUNT(CASE WHEN (k.category_id = 2) THEN 2 END )  AS  NoteBook,
        COUNT(CASE WHEN (k.category_id =3) THEN 3 END )  AS  PrinLaser,
        COUNT(CASE WHEN (k.category_id = 4) THEN 4 END )  AS  PrinInk,
        COUNT(CASE WHEN (k.category_id = 10 ) THEN 10 END )  AS  Termal ,
        COUNT(CASE WHEN (k.category_id = 13) THEN 13  END )  AS  Scan,
				COUNT(CASE WHEN (k.category_id = 14) THEN 14  END )  AS  Ipad,
        COUNT(CASE WHEN (k.category_id IN (1,2,3,4,10,13)) THEN 15  END )  AS  Total
        FROM  
		(SELECT a.device_serial , a.device_name, a.category_id ,b.category_name, a.purchase_date, a.sale_date, a.price,
		IF(MONTH(a.purchase_date)>9,YEAR(a.purchase_date)+544,YEAR(a.purchase_date)+543) AS fiscal
		FROM devices a, categories b
		WHERE a.sale_date = 0
		AND a.category_id = b.category_id) as k
		")->queryAll(); 

        $cdataProvider = new ArrayDataProvider([
            'allModels' => $datam,
            'sort'=>[
                'attributes'=>['fiscal','PC','NoteBook']
            ],
        ]);
		 //เตรียมข้อมูลคอมพิวเตอร์ส่งให้กราฟ
        for ($i = 0; $i < sizeof($datam); $i++) {
            $pc[] = (int) $datam[$i]['PC'];
            $nb[] = (int) $datam[$i]['NoteBook'];
            $laser[] = (int) $datam[$i]['PrinLaser'];
            $ink[] = (int) $datam[$i]['PrinInk'];
            $termal[] = (int) $datam[$i]['Termal'];
            $scan[] = (int) $datam[$i]['Scan'];
			$ipad[] = (int) $datam[$i]['Ipad'];
        }
		
        return $this->render('index',[
		            'cdataProvider'=> $cdataProvider,
                    'comdataProvider' =>$comdataProvider,
					 'total' =>$total,
                     'price'=>$price,
                     'cfiscal'=>$cfiscal,
					 'pc'=>$pc,
                    'nb'=>$nb,
                    'laser' =>$laser,
                    'ink'=>$ink,
                    'termal'=>$termal,
                    'scan'=>$scan,
					'ipad'=>$ipad,
                    
		]);
    }
    public function actionCountdevices(){
                $sql = "SELECT DISTINCT categories.category_id AS 'cateid',category_name AS 'ประเภท' ,COUNT(category_name) AS 'จำนวน'
FROM devices, departments,categories
WHERE devices.dep_id = departments.dep_id
AND devices.category_id = categories.category_id
AND devices.sale_date = '0000-00-00'
AND devices.sale_date = ''
GROUP BY categories.category_name ORDER BY COUNT(category_name) DESC";
        $rawData = \yii::$app->db->createCommand($sql)->queryAll();
 $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        
       // print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('countdevices', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                   
        ]);
        
    }
    public function actionDevicelist($cateid) {
    		$sql = "SELECT DISTINCT device_serial AS 'หมายเลขครุภัณฑ์' , departments.dep_name AS 'แผนก', categories.category_name AS 'ประเภท',
spec AS 'รุ่นยี่ห้อ', purchase_date AS 'วันที่ซื้อ', due_date AS 'วันครบกำหนด', price AS 'ราคา' 
FROM devices , departments,categories 
WHERE  devices.dep_id = departments.dep_id
AND devices.category_id = categories.category_id
AND categories.category_id = $cateid
AND devices.sale_date = '0000-00-00'
ORDER BY  device_serial, purchase_date";
		 $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        
       // print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('devicelist', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                   
        ]);
        
    }
        
    public function actionSaledevices(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';

    	$sql = "SELECT DISTINCT device_serial , departments.dep_name ,
        categories.category_name ,spec ,purchase_date ,sale_date, price , orther 
        FROM devices , departments,categories 
        WHERE  devices.dep_id = departments.dep_id
        AND devices.category_id = categories.category_id
        AND devices.sale_date != '0000-00-00'
        AND (devices.sale_date between '$date1' AND '$date2')
        ORDER BY  sale_date DESC";
        /* if (!empty($date1) && !empty($date2)) {
            $sql.= " AND (devices.sale_date between '$date1' AND '$date2')";
        }
            $sql.= "ORDER BY  sale_date DESC";
        
         $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        
       // print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        */
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('saledevices', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,
                   
        ]);
        
    }
    	public function actionServiceout(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';
                
        	 $sql = "SELECT c.device_serial,c.device_name, d.category_name, 
        a.date_sent,a.date_in , a.price, a.orther, b.store_name 
        FROM serviceout a, store b,devices c, categories d
        WHERE a.store_id = b.store_id
        AND (a.date_sent between '$date1' AND '$date2')
        AND c.category_id= d.category_id
		AND a.device_id = c.device_id";
       /* if (!empty($date1) && !empty($date2)) {
            $sql.= " AND (a.date_sent between '$date1' AND '$date2')";
        }*/
         $rawData = \yii::$app->db->createCommand($sql)->queryAll();
 
        //print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('serviceout', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,
        ]);
     }
   
    public function actionDevicenew(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';
                
 $sql = "SELECT DISTINCT device_serial, departments.dep_name, categories.category_name,
spec, purchase_date, price ,due_date,orther
FROM devices , departments,categories 
WHERE  devices.dep_id = departments.dep_id
AND devices.category_id = categories.category_id
AND devices.sale_date = '0000-00-00'
AND devices.purchase_date BETWEEN '$date1' AND '$date2'
ORDER BY  device_serial, purchase_date";

        $rawData = \yii::$app->db->createCommand($sql)->queryAll();
 
       //print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('devicenew', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,
                   
        ]);
        
    }
    public function actionDevice59(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';

     $sql = "SELECT DISTINCT categories.category_id AS 'catid',category_name ,COUNT(category_name) AS amount FROM devices, departments,categories
WHERE devices.dep_id = departments.dep_id AND devices.category_id = categories.category_id
AND devices.sale_date = '0000-00-00'AND devices.sale_date = ''AND devices.purchase_date BETWEEN '$date1' AND '$date2'
GROUP BY categories.category_name ORDER BY COUNT(category_name) DESC";
        $rawData = \yii::$app->db->createCommand($sql)->queryAll();
       // print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        Yii::$app->session['date1']=$date1;
        Yii::$app->session['date2']=$date2;
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('device_all', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' =>$date1,
                    'date2' =>$date2,
                   
        ]);  
    }
    public function actionDevicelist59($catid) {
     $date1 = Yii::$app->session['date1'];
     $date2 = Yii::$app->session['date2'];
    		$sql = "SELECT DISTINCT device_serial, departments.dep_name, categories.category_id,categories.category_name,spec,purchase_date, price  
FROM devices , departments,categories WHERE  devices.dep_id = departments.dep_id AND devices.category_id = categories.category_id
AND categories.category_id = $catid AND devices.sale_date = '0000-00-00'
AND devices.purchase_date BETWEEN '$date1' AND '$date2'
ORDER BY  device_serial, purchase_date";

		 $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        // print_r($rawData);
        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('devicelist59', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                   
        ]);  
    }
        public function actionDepdevices(){
        $data = Yii::$app->request->post();
        $depid = isset($data['depid']) ? $data['depid'] : 'null';
        
        $sql = "SELECT  a.device_serial , device_name,a.spec,b.category_name, c.dep_name, a.purchase_date,
                a.due_date, a.price
                FROM devices a, categories b , departments c
                WHERE a.category_id = b.category_id
                AND a.dep_id = c.dep_id
                AND c.dep_id = $depid
                AND a.sale_date = '0000-00-00' ORDER BY b.category_id";
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        // print_r($rawData);
            try {
                $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }
           // Yii::$app->session['date1']=$date1;
           //Yii::$app->session['date2']=$date2;
            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => FALSE,
            ]);
            return $this->render('depdevices', [
                        'dataProvider' => $dataProvider,
                        'sql'=>$sql,
                        'depid'=> $depid,
                        
                    
            ]);  
    }
    public function actionDepdevice_all(){
        $data = Yii::$app->request->post();
        $depid = isset($data['depid']) ? $data['depid'] : 'null';
        
        $sql = "SELECT  a.device_serial , device_name,a.spec,b.category_name, c.dep_name, a.purchase_date,
                a.due_date, a.price
                FROM devices a, categories b , departments c
                WHERE a.category_id = b.category_id
                AND a.dep_id = c.dep_id
                #AND c.dep_id = $depid
                AND a.sale_date = '0000-00-00' ORDER BY b.category_name";
            $rawData = \yii::$app->db->createCommand($sql)->queryAll();
        // print_r($rawData);
            try {
                $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }
           // Yii::$app->session['date1']=$date1;
           //Yii::$app->session['date2']=$date2;
            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => FALSE,
            ]);
            return $this->render('depdevices', [
                        'dataProvider' => $dataProvider,
                        'sql'=>$sql,
                        'depid'=> $depid,
                        
                    
            ]);  
    }
    public function actionMaterials(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';
                
 $sql = "SELECT * FROM mb_materials WHERE IVS_DATE BETWEEN '$date1' AND '$date2'
         AND IVC_ID = 04 ORDER BY IVS_DATE";

        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
 
       //print_r($rawData);
        try {
            $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('materials', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,
                   
        ]);  
    }
    public function actionMaterial13(){
       $data = Yii::$app->request->post();
       $date1 = isset($data['date1']) ? $data['date1'] : '';
       $date2 = isset($data['date2']) ? $data['date2'] : '';
                
 $sql = "SELECT * FROM mb_materials WHERE IVS_DATE BETWEEN '$date1' AND '$date2'
         ORDER BY IVS_DATE";

        $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
 
       //print_r($rawData);
        try {
            $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('material13', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,
                   
        ]);  
    } 
    public function actionDevices_all(){
        $data = Yii::$app->request->post();
        $date1 = isset($data['date1']) ? $data['date1'] : '';
        $date2 = isset($data['date2']) ? $data['date2'] : '';

    $sql = "SELECT b.category_id, b.category_name, COUNT(a.device_serial) AS amount
    FROM devices a
    INNER JOIN categories b ON a.category_id = b.category_id
    AND a.sale_date = '0000-00-00'
    GROUP BY b.category_name";
   $rawData = \yii::$app->db->createCommand($sql)->queryAll();

  // print_r($rawData);
   try {
       $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
   } catch (\yii\db\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   Yii::$app->session['date1']=$date1;
   Yii::$app->session['date2']=$date2;
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => FALSE,
   ]);
   return $this->render('devices_all', [
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
               'date1'=>$date1,
               'date2'=>$date2,

   ]);   
}
    public function actionDevices_all_list($catid){
        $sql = "SELECT a.device_serial, device_name, a.spec, a.purchase_date, a.sale_date, b.category_name, a.price
        FROM devices a
        INNER JOIN categories b ON a.category_id = b.category_id
        WHERE a.category_id = $catid and a.sale_date = 0";
    $rawData = \yii::$app->db->createCommand($sql)->queryAll();

    // print_r($rawData);
    try {
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => FALSE,
    ]);
    return $this->render('devices_all_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,

    ]);
} 
}
