<?php

namespace app\controllers;
use yii;
use yii\data\ArrayDataProvider;
use app\models\LogThaimed;

class ThaimedController extends \yii\web\Controller
{
    public function actionIndex()
    {	 
	     $sqlCount1 = "SELECT COUNT(DISTINCT v.id) as amount
			FROM log_thaimed v 
			";
        
         $data = \yii::$app->db->createCommand($sqlCount1)->queryAll();
             for ($i = 0; $i < sizeof($data); $i++) {
                 $amount = $data[$i]['amount'];    
             }
        //return $this->render('index');
		return $this->render('index',[
              'dataProvider' => $dataProvider,
              'sql'=>$sql,
			  'date1'=>$date1,
			  'date2'=>$date2,
			  'amount'=>$amount, 
          ]);
    }
	########################################################################################################################################
// ===== dropdown เฉพาะชื่อ Provider =====
private function getLaborProviderList()
{
    // ✅ ไม่ต้องดึงจาก DB แค่ hardcode ชื่อ
    return [
        ''           => '-- ทั้งหมด --',
        'อรชร'       => 'อรชร  ดวงแก้ว',
        'รัชนีกร'    => 'รัชนีกร  จันทริมา',
        'ปิยะนันท์'  => 'ปิยะนันท์ ไชยเสนา',
    ];
}

// ===== map ชื่อ → hospsub list =====
private function getProviderHospsub()
{
    return [
        'อรชร'      => "('10953','99809','03707','03704','03701','03702','03706','03714')",
        'รัชนีกร'   => "('03703','03693','03709','03710','03708','')",
        'ปิยะนันท์' => "('03713','03694','03696','03700','03692','03711')",
    ];
}

// ===== ดึงข้อมูลหลัก =====
private function getLaborData($dateStart, $dateEnd, $provider = '')
{
    $params = [
        ':dateStart' => $dateStart,
        ':dateEnd'   => $dateEnd,
    ];

    // ✅ กรองที่ WHERE hospsub แทน HAVING
    $providerWhere = '';
    $hospsubMap = $this->getProviderHospsub();
    if (!empty($provider) && isset($hospsubMap[$provider])) {
        $providerWhere = "AND c.hospsub IN " . $hospsubMap[$provider];
    }

    $sql = "SELECT 
        CASE
            WHEN c.hospsub IN ('10953','99809','03707','03704','03701','03702','03706','03714')
                THEN CONCAT('อรชร  ดวงแก้ว', ' -', hh.hosp_name)
            WHEN c.hospsub IN ('03703','03693','03709','03710','03708','')
                THEN CONCAT('รัชนีกร  จันทริมา', ' -', hh.hosp_name)
            WHEN c.hospsub IN ('03713','03694','03696','03700','03692','03711')
                THEN CONCAT('ปิยะนันท์ ไชยเสนา', ' -', hh.hosp_name)
            ELSE 'all'
        END AS provider,
        cc.hn AS HN,
        CASE
            WHEN LEFT(TRIM(a.MOTHER),1) BETWEEN '0' AND '9'
                AND TRIM(a.MOTHER) NOT IN (SELECT cid_hn.CID FROM cid_hn)
                THEN CONCAT(TRIM(p.fname),'___',TRIM(p.lname))
            WHEN LEFT(TRIM(a.MOTHER),1) BETWEEN 'ก' AND 'ฮ'
                THEN a.mother
            WHEN LEFT(TRIM(a.MOTHER),1) BETWEEN '0' AND '9'
                THEN (
                    SELECT CONCAT(
                        CASE
                            WHEN population.PRENAME NOT IN ('') THEN TRIM(population.PRENAME)
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) <  20 AND population.sex='1' AND population.MARRIAGE='4' THEN 'สามเณร'
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) >= 20 AND population.sex='1' AND population.MARRIAGE='4' THEN 'พระภิกษุ'
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) <  15 AND population.sex='1' THEN 'ด.ช.'
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) >= 15 AND population.sex='1' THEN 'นาย'
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) <  15 AND population.sex='2' THEN 'ด.ญ.'
                            WHEN TIMESTAMPDIFF(YEAR,population.BIRTHDATE,NOW()) >= 15 AND population.sex='2' AND population.MARRIAGE='1' THEN 'น.ส.'
                            ELSE 'นาง'
                        END,
                        TRIM(population.FNAME),'_',TRIM(population.LNAME),
                        '  --สิทธิ์', TRIM(main_inscls.INSCL_NAME)
                    )
                    FROM population, main_inscls, cid_hn
                    WHERE population.CID = a.mother
                      AND population.INSCL = main_inscls.INSCL
                      AND population.CID   = cid_hn.CID
                )
        END AS `ข้อมูลมารดาหลังคลอด`,
        TIMESTAMPDIFF(YEAR, p.birthdate, NOW()) AS age,
        p.telephone AS `เบอร์โทรศัพท์มารดา`,
        COUNT(od.VISIT_ID) AS `จำนวนครั้ง`,
        b.HN AS `HN บุตร`,
        CONCAT(
            CASE
                WHEN a.PRENAME NOT IN ('') THEN TRIM(a.PRENAME)
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) <  20 AND a.sex='1' AND a.MARRIAGE='4' THEN 'สามเณร'
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) >= 20 AND a.sex='1' AND a.MARRIAGE='4' THEN 'พระภิกษุ'
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) <  15 AND a.sex='1' THEN 'ด.ช.'
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) >= 15 AND a.sex='1' THEN 'นาย'
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) <  15 AND a.sex='2' THEN 'ด.ญ.'
                WHEN TIMESTAMPDIFF(YEAR, a.BIRTHDATE, NOW()) >= 15 AND a.sex='2' AND a.MARRIAGE='1' THEN 'น.ส.'
                ELSE 'นาง'
            END,
            ' ', TRIM(a.FNAME), ' ', TRIM(a.LNAME)
        ) AS `ชื่อ-สกุล บุตร`,
        a.birthdate,
        TIMESTAMPDIFF(MONTH, a.BIRTHDATE, CURDATE()) % 12 AS months,
        TIMESTAMPDIFF(DAY,
            ADDDATE(a.BIRTHDATE, INTERVAL TIMESTAMPDIFF(MONTH, a.BIRTHDATE, CURDATE()) MONTH),
            CURDATE()
        ) AS days,
        TRIM(a.HOME_ADR)  AS `บ้านเลขที่`,
        TRIM(c.TOWN_NAME) AS `บ้าน-หมู่ที่`,
        TRIM(d.TOWN_NAME) AS `ตำบล`,
        TRIM(e.TOWN_NAME) AS `อำเภอ`,
        TRIM(f.TOWN_NAME) AS `จังหวัด`

    FROM population a
    LEFT JOIN cid_hn b      ON a.CID = b.cid
    LEFT JOIN population p  ON TRIM(a.mother) = p.cid
    LEFT JOIN towns c       ON a.TOWN_ID = c.TOWN_ID
    LEFT JOIN hospitals hh  ON hh.hosp_id = c.hospsub
    LEFT JOIN towns d       ON CONCAT(LEFT(a.TOWN_ID, 6), '00')     = d.TOWN_ID
    LEFT JOIN towns e       ON CONCAT(LEFT(a.TOWN_ID, 4), '0000')   = e.TOWN_ID
    LEFT JOIN towns f       ON CONCAT(LEFT(a.TOWN_ID, 2), '000000') = f.TOWN_ID
    LEFT JOIN cid_hn cc     ON cc.cid = TRIM(a.mother)
    LEFT JOIN opd_visits o
        ON o.HN = cc.hn AND o.IS_CANCEL = 0
    LEFT JOIN main_inscls m ON m.inscl = o.inscl
    LEFT JOIN opd_operations od
        ON o.VISIT_ID = od.VISIT_ID
       AND od.IS_CANCEL = 0
       AND od.icd9 IN ('0000016034','0000015388','0000015274')
       AND o.REG_DATETIME > a.BIRTHDATE

    WHERE a.CID NOT IN (SELECT deaths.CID FROM deaths WHERE deaths.is_cancel = 0)
      AND a.birthdate BETWEEN :dateStart AND :dateEnd
      {$providerWhere}          -- ✅ กรองที่ WHERE โดยตรง

    GROUP BY b.cid
    ORDER BY a.birthdate
    ";

    return \Yii::$app->db14->createCommand($sql, $params)->queryAll();
}

// ===== actionLabor แก้เรียก getLaborProviderList ไม่ต้องส่ง date =====
public function actionLabor()
{
    $request   = \Yii::$app->request;
    $dateStart = $request->get('date_start', date('Y-m-d', strtotime('-90 days')));
    $dateEnd   = $request->get('date_end',   date('Y-m-d'));
    $provider  = $request->get('provider', '');

    // ✅ บันทึก log การเข้าใช้งาน
    $this->writeAccessLog($dateStart, $dateEnd, $provider);

    $providerList = $this->getLaborProviderList();
    $data         = $this->getLaborData($dateStart, $dateEnd, $provider);
    $amount       = count($data);

    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels'  => $data,
        'pagination' => ['pageSize' => 200],
        'sort'       => ['attributes' => ['HN', 'birthdate']],
    ]);

// อ่านจำนวนครั้งทั้งหมด
$countFile  = \Yii::getAlias('@webroot') . '/log/thaimedlabor/total_count.txt';
$totalCount = file_exists($countFile) ? (int) file_get_contents($countFile) : 0;

    return $this->render('labor', [

        'dataProvider'     => $dataProvider,
        'amount'           => $amount,
        'dateStart'        => $dateStart,
        'dateEnd'          => $dateEnd,
        'providerSelected' => $provider,
        'providerList'     => $providerList,
		'totalCount' => $totalCount,
    ]);
}

// =====================================================
//  LOG การเข้าใช้งาน
// =====================================================
private function writeAccessLog($dateStart, $dateEnd, $provider)
{
    // --- path โฟลเดอร์ log ---
    $logDir = \Yii::getAlias('@webroot') . '/log/thaimedlabor';

    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // --- ไฟล์ log รายเดือน เช่น access_2025_05.log ---
    $logFile = $logDir . '/access_' . date('Y_m') . '.log';

    // --- ไฟล์นับจำนวนครั้งรวม ---
    $countFile = $logDir . '/total_count.txt';

    // อ่านและเพิ่มจำนวนครั้ง
    $count = file_exists($countFile)
        ? (int) file_get_contents($countFile) + 1
        : 1;
    file_put_contents($countFile, $count);

    // --- ข้อมูล user ---
    $user     = \Yii::$app->has('user') && !\Yii::$app->user->isGuest
                    ? \Yii::$app->user->identity->username ?? 'unknown'
                    : 'guest';
    $ip       = \Yii::$app->request->userIP ?? '-';
    $now      = date('Y-m-d H:i:s');
    $provTxt  = !empty($provider) ? $provider : 'ทั้งหมด';

    // --- เขียน log ---
    $line = implode(' | ', [
        "#{$count}",
        $now,
        "user:{$user}",
        "ip:{$ip}",
        "date:{$dateStart}~{$dateEnd}",
        "provider:{$provTxt}",
    ]) . PHP_EOL;

    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}
############################################################################################	
	public function actionGroup10(){
		$data = Yii::$app->request->post();
        $date1 =isset($data['date1'])  ? $data['date1'] : '';
        $date2 =isset($data['date2'])  ? $data['date2'] : '';


		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }

      $sql = "SELECT  k.didstd, k.drug_id,k.group_id,k.herb_id,k.group_name ,k.drug_name,
			count(k.visits) as visits ,k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total, k.code_thai
							FROM (
							  SELECT DISTINCT
				c.didstd, 
				c.drug_id,
				h.herb_id,
				ph.group_id,
				ph.group_name,
				CONCAT(
					TRIM(c.DRUG_NAME), ' ', d1.DFORM_SNAME, 
					' (', CAST(c.STRENGTH AS DECIMAL(8,0)), ' ', s.strength_name, '/', 
					CAST(c.ST_NUM_UUNIT AS DECIMAL(8,0)), '', u.UUNIT_NAME, ')'
				) AS DRUG_NAME,
				u.UUNIT_ID AS UNIT,
				d.UUNIT_NAME AS UNIT_PACKING,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				a.visit_id AS visits,
				#SUM(b.rx_amount) AS amount,
				b.rx_amount AS amount,
				ph.code_thai
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL = 0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT JOIN routes r ON r.route_id = b.route_id 
			LEFT JOIN frequency f ON f.frq_id = b.frq_id
			LEFT JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			LEFT JOIN usage_units u ON c.ST_TXT_UUNIT = u.UUNIT_ID
			LEFT JOIN usage_units us ON c.UUNIT_ID = us.UUNIT_ID
			INNER JOIN herbs h ON c.drug_id = h.drug_id
			LEFT JOIN planthai_herbs ph ON h.herb_id = ph.herb_id
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2'
			  AND a.is_cancel = 0
			GROUP BY a.visit_id, c.didstd
			ORDER BY ph.herb_id
			) AS k
			GROUP BY  k.didstd

			";
		
     // ดึงข้อมูลจากฐานข้อมูล
    $rawData = Yii::$app->db14->createCommand($sql, [
        ':date1' => $date1,
        ':date2' => $date2
    ])->queryAll();

    // สร้าง DataProvider
    $dataProvider = new ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => false,
    ]);
	
	#### รายงานเดือน ############################################	
	
	$sql2= "SELECT   
    CASE k.report_month
        WHEN 1 THEN 'มกราคม'
        WHEN 2 THEN 'กุมภาพันธ์'
        WHEN 3 THEN 'มีนาคม'
        WHEN 4 THEN 'เมษายน'
        WHEN 5 THEN 'พฤษภาคม'
        WHEN 6 THEN 'มิถุนายน'
        WHEN 7 THEN 'กรกฎาคม'
        WHEN 8 THEN 'สิงหาคม'
        WHEN 9 THEN 'กันยายน'
        WHEN 10 THEN 'ตุลาคม'
        WHEN 11 THEN 'พฤศจิกายน'
        WHEN 12 THEN 'ธันวาคม'
    END AS report_month_name,
    k.report_year,
    SUM(CASE WHEN k.drug_id = '0664' THEN k.visits ELSE 0 END) AS '0664_visits',
    SUM(CASE WHEN k.drug_id = '0664' THEN k.amount ELSE 0 END) AS '0664_amount',
    SUM(CASE WHEN k.drug_id = '2358' THEN k.visits ELSE 0 END) AS '2358_visits',
    SUM(CASE WHEN k.drug_id = '2358' THEN k.amount ELSE 0 END) AS '2358_amount',
    SUM(CASE WHEN k.drug_id = '2486' THEN k.visits ELSE 0 END) AS '2486_visits',
    SUM(CASE WHEN k.drug_id = '2486' THEN k.amount ELSE 0 END) AS '2486_amount',
    SUM(CASE WHEN k.drug_id = '0491' THEN k.visits ELSE 0 END) AS '0491_visits',
    SUM(CASE WHEN k.drug_id = '0491' THEN k.amount ELSE 0 END) AS '0491_amount',
    SUM(CASE WHEN k.drug_id = '0262' THEN k.visits ELSE 0 END) AS '0262_visits',
    SUM(CASE WHEN k.drug_id = '0262' THEN k.amount ELSE 0 END) AS '0262_amount',
    SUM(CASE WHEN k.drug_id = '2443' THEN k.visits ELSE 0 END) AS '2443_visits',
    SUM(CASE WHEN k.drug_id = '2443' THEN k.amount ELSE 0 END) AS '2443_amount',
    SUM(CASE WHEN k.drug_id = '1393' THEN k.visits ELSE 0 END) AS '1393_visits',
    SUM(CASE WHEN k.drug_id = '1393' THEN k.amount ELSE 0 END) AS '1393_amount',
    SUM(CASE WHEN k.drug_id = '0263' THEN k.visits ELSE 0 END) AS '0263_visits',
    SUM(CASE WHEN k.drug_id = '0263' THEN k.amount ELSE 0 END) AS '0263_amount',
    SUM(CASE WHEN k.drug_id = '1392' THEN k.visits ELSE 0 END) AS '1392_visits',
    SUM(CASE WHEN k.drug_id = '1392' THEN k.amount ELSE 0 END) AS '1392_amount',
    SUM(CASE WHEN k.drug_id = '0261' THEN k.visits ELSE 0 END) AS '0261_visits',
    SUM(CASE WHEN k.drug_id = '0261' THEN k.amount ELSE 0 END) AS '0261_amount',
    SUM(CASE WHEN k.drug_id = '2466' THEN k.visits ELSE 0 END) AS '2466_visits',
    SUM(CASE WHEN k.drug_id = '2466' THEN k.amount ELSE 0 END) AS '2466_amount',
    SUM(CASE WHEN k.drug_id = '2733' THEN k.visits ELSE 0 END) AS '2733_visits',
    SUM(CASE WHEN k.drug_id = '2733' THEN k.amount ELSE 0 END) AS '2733_amount',
    SUM(CASE WHEN k.drug_id = '2295' THEN k.visits ELSE 0 END) AS '2295_visits',
    SUM(CASE WHEN k.drug_id = '2295' THEN k.amount ELSE 0 END) AS '2295_amount',
    SUM(CASE WHEN k.drug_id = '2051' THEN k.visits ELSE 0 END) AS '2051_visits',
    SUM(CASE WHEN k.drug_id = '2051' THEN k.amount ELSE 0 END) AS '2051_amount',
    SUM(CASE WHEN k.drug_id = '0266' THEN k.visits ELSE 0 END) AS '0266_visits',
    SUM(CASE WHEN k.drug_id = '0266' THEN k.amount ELSE 0 END) AS '0266_amount',
	SUM(CASE WHEN k.drug_id = '2363' THEN k.visits ELSE 0 END) AS '2363_visits',
    SUM(CASE WHEN k.drug_id = '2363' THEN k.amount ELSE 0 END) AS '2363_amount',
	SUM(CASE WHEN k.drug_id = '2419' THEN k.visits ELSE 0 END) AS '2419_visits',
    SUM(CASE WHEN k.drug_id = '2419' THEN k.amount ELSE 0 END) AS '2419_amount',
	SUM(CASE WHEN k.drug_id = '2289' THEN k.visits ELSE 0 END) AS '2289_visits',
    SUM(CASE WHEN k.drug_id = '2289' THEN k.amount ELSE 0 END) AS '2289_amount',
	SUM(CASE WHEN k.drug_id = '2439' THEN k.visits ELSE 0 END) AS '2439_visits',
    SUM(CASE WHEN k.drug_id = '2439' THEN k.amount ELSE 0 END) AS '2439_amount',
	SUM(CASE WHEN k.drug_id = '2314' THEN k.visits ELSE 0 END) AS '2314_visits',
    SUM(CASE WHEN k.drug_id = '2314' THEN k.amount ELSE 0 END) AS '2314_amount',
    SUM(CASE WHEN k.drug_id = '2362' THEN k.visits ELSE 0 END) AS '2362_visits',
    SUM(CASE WHEN k.drug_id = '2362' THEN k.amount ELSE 0 END) AS '2362_amount'
FROM (
    SELECT DISTINCT
        c.didstd, 
        c.drug_id,
        MONTH(a.reg_datetime) AS report_month,
        YEAR(a.reg_datetime) AS report_year,
        COUNT(a.visit_id) AS visits,
        SUM(b.rx_amount) AS amount
    FROM 
        opd_visits a
    INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL = 0
    INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID 
    WHERE 
        a.reg_datetime BETWEEN '$date1' AND '$date2' 
        AND a.is_cancel = 0
    GROUP BY 
        c.didstd, MONTH(a.reg_datetime), YEAR(a.reg_datetime)
) AS k
GROUP BY k.report_month, k.report_year
ORDER BY k.report_year, k.report_month;

	";
	// ดึงข้อมูลจากฐานข้อมูล
    $raw2Data = Yii::$app->db14->createCommand($sql2, [
        ':date1' => $date1,
        ':date2' => $date2
    ])->queryAll();

    // สร้าง DataProvider
    $monthProvider = new ArrayDataProvider([
        'allModels' => $raw2Data,
        'pagination' => false,
    ]);
	
    // ส่งข้อมูลไปยัง view
	 ###### รายงานสมุนไพร (sqlSmonpai) ######
    $sqlSmonpai = "
        SELECT DISTINCT
				c.didstd, 
				c.drug_id,
				h.herb_id,
				ph.group_id,
				ph.group_name,
				CONCAT(
					TRIM(c.DRUG_NAME), ' ', d1.DFORM_SNAME, 
					' (', CAST(c.STRENGTH AS DECIMAL(8,0)), ' ', s.strength_name, '/', 
					CAST(c.ST_NUM_UUNIT AS DECIMAL(8,0)), '', u.UUNIT_NAME, ')'
				) AS DRUG_NAME,
				u.UUNIT_ID AS UNIT,
				d.UUNIT_NAME AS UNIT_PACKING,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				a.reg_datetime,
				a.visit_id AS visits,
				a.hn AS hn,
				b.rx_amount AS amount,
				ph.code_thai
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL = 0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT JOIN routes r ON r.route_id = b.route_id 
			LEFT JOIN frequency f ON f.frq_id = b.frq_id
			LEFT JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			LEFT JOIN usage_units u ON c.ST_TXT_UUNIT = u.UUNIT_ID
			LEFT JOIN usage_units us ON c.UUNIT_ID = us.UUNIT_ID
			INNER JOIN herbs h ON c.drug_id = h.drug_id
			LEFT JOIN planthai_herbs ph ON h.herb_id = ph.herb_id
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2'
			  AND a.is_cancel = 0
			GROUP BY a.visit_id, c.didstd
			ORDER BY ph.herb_id
    ";
    $rawSmonpai = Yii::$app->db14->createCommand($sqlSmonpai)->queryAll();
    $smonpaiProvider = new ArrayDataProvider([
        'allModels' => $rawSmonpai,
        'pagination' => false,
    ]);

    ###### reset ค่า default ถ้าไม่ส่งมา ######
    $date1 = empty($date1) ? date('Y-m-d') : $date1;
    $date2 = empty($date2) ? date('Y-m-d') : $date2;

    return $this->render('group10', [
        'dataProvider' => $dataProvider,
        'monthProvider' => $monthProvider,
        'smonpaiProvider' => $smonpaiProvider,
        'date1' => $date1,
        'date2' => $date2,
        'rawData' => $rawData,
    ]);
}
	
	
#########################################################################################################################


	public function actionRespiratory(){
		$data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));

		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }

      $sql = "SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total,k.visits
			FROM (
			SELECT DISTINCT
			    count(c.didstd) as visits,
				c.didstd, 
				c.drug_id,
				#CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
				CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
				CASE 
				  WHEN TRIM(c.STRENGTH)='' THEN ''
				  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
				  ELSE REPLACE(c.STRENGTH,'.00','')
				  END
				,' ',s.strength_name ) as drug_name,
				u.UUNIT_ID as UNIT,
				d.UUNIT_NAME as UNIT_PACKING ,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				SUM(b.rx_amount) as amount
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT  JOIN routes r ON r.route_id = b.route_id 
			LEFT  JOIN frequency f on f.frq_id = b.frq_id
			LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			#LEFT  JOIN usage_units u ON c.ST_TXT_UUNIT = u.UUNIT_ID
			LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID 
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
				AND a.is_cancel =0
				AND  c.drug_id in ('2280','1393','2283','2364','2362','0262','2359')
			  GROUP BY c.didstd
				) as k
			";
     $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		 $sql2 = "SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total,k.visits
			FROM (
			SELECT DISTINCT
				count(c.didstd)as visits,
				c.didstd, 
				c.drug_id,
				#CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
				CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
				CASE 
				  WHEN TRIM(c.STRENGTH)='' THEN ''
				  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
				  ELSE REPLACE(c.STRENGTH,'.00','')
				  END
				,' ',s.strength_name ) as drug_name,
				u.UUNIT_ID as UNIT,
				d.UUNIT_NAME as UNIT_PACKING ,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				SUM(b.rx_amount) as amount
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT  JOIN routes r ON r.route_id = b.route_id 
			LEFT  JOIN frequency f on f.frq_id = b.frq_id
			LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			#LEFT  JOIN usage_units u ON c.ST_TXT_UUNIT = u.UUNIT_ID
			LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID 
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
			AND a.is_cancel =0
			AND  c.drug_id in ('0217','0218','1754','0429','1169','0336','1752')
			GROUP BY c.didstd
				) as k

			";

     $rawData1 = \yii::$app->db14->createCommand($sql2)->queryAll();
        $nowProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData1,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
        Yii::$app->session['date1'] =$date1;
        Yii::$app->session['date2'] =$date2;
        return $this->render('respiratory', [
                    'dataProvider' => $dataProvider,
					'nowProvider' => $nowProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,

        ]);
    }
	public function actionGastro(){
		$data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));

		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
      $sql = "  SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total,k.visits
			FROM (
			SELECT DISTINCT
				count(c.didstd) as visits,
				c.didstd, 
				c.drug_id,
				#CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
				CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
				CASE 
				  WHEN TRIM(c.STRENGTH)='' THEN ''
				  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
				  ELSE REPLACE(c.STRENGTH,'.00','')
				  END
				,' ',s.strength_name ) as drug_name,
				u.UUNIT_ID as UNIT,
				d.UUNIT_NAME as UNIT_PACKING ,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				SUM(b.rx_amount) as amount
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT  JOIN routes r ON r.route_id = b.route_id 
			LEFT  JOIN frequency f on f.frq_id = b.frq_id
			LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			#LEFT  JOIN usage_units u ON c.ST_TXT_UUNIT = u.UUNIT_ID
			LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID 
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
				AND a.is_cancel =0
			  #AND  c.drug_id in ('0094','0704','1113','0440','0097','0098','0184','0185','0186','0187')
				AND  c.drug_id in ('1392','1394','0263','2314','0666','0261')
			  GROUP BY c.didstd
				) as k
			";
     $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		 $sql2 = " SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total, k.visits
			FROM (
			SELECT DISTINCT
				count(c.didstd) as visits,
				c.didstd, 
				c.drug_id,
				#CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
				CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
				CASE 
				  WHEN TRIM(c.STRENGTH)='' THEN ''
				  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
				  ELSE REPLACE(c.STRENGTH,'.00','')
				  END
				,' ',s.strength_name ) as drug_name,
				u.UUNIT_ID as UNIT,
				d.UUNIT_NAME as UNIT_PACKING ,
				'' AS DRUGPRICE,
				c.PRICE AS DRUGCOST,
				SUM(b.rx_amount) as amount
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT  JOIN routes r ON r.route_id = b.route_id 
			LEFT  JOIN frequency f on f.frq_id = b.frq_id
			LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID 
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
			AND a.is_cancel =0
			AND  c.drug_id in ('1473','0147','0152','0148','0166','0168','1039','1740','0171','0173','0448','2019','2270','0158','0159','0161')
			#AND  c.drug_id in ('2314','0664','2358','0491','2466','0266')
			GROUP BY c.didstd
				) as k

			";

     $rawData1 = \yii::$app->db14->createCommand($sql2)->queryAll();
        $nowProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData1,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
        Yii::$app->session['date1'] =$date1;
        Yii::$app->session['date2'] =$date2;
        return $this->render('gastro', [
                    'dataProvider' => $dataProvider,
					'nowProvider' => $nowProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,

        ]);
    }
	
	public function actionMuscles(){
		 $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {
            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
      $sql = " SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total,k.visits
		FROM (
		SELECT DISTINCT
			count(c.didstd)as visits,
			c.didstd, 
			c.drug_id,
			#CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
			CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
			CASE 
			  WHEN TRIM(c.STRENGTH)='' THEN ''
			  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
			  ELSE REPLACE(c.STRENGTH,'.00','')
			  END
			,' ',s.strength_name ) as drug_name,
			u.UUNIT_ID as UNIT,
			d.UUNIT_NAME as UNIT_PACKING ,
			'' AS DRUGPRICE,
			c.PRICE AS DRUGCOST,
			SUM(b.rx_amount) as amount
		FROM opd_visits a
		INNER JOIN cid_hn c1 ON a.hn = c1.hn
		INNER JOIN population p ON p.cid = c1.cid
		INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
		INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
		LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
		LEFT  JOIN routes r ON r.route_id = b.route_id 
		LEFT  JOIN frequency f on f.frq_id = b.frq_id
		LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
		LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
		LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID
		WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
		AND a.is_cancel =0
		AND  c.drug_id in ('2314','0664','2358','0491','2466','0266')
		 GROUP BY c.didstd
			) as k

			";

     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		 $sql2 = " SELECT  k.didstd,k.drug_id, k.drug_name, k.amount, k.unit_packing, k.drugcost, (amount * drugcost) as total,k.visits
			FROM (
			SELECT DISTINCT
			    count(c.didstd) as visits,
				c.didstd, 
				c.drug_id,
				-- CONCAT(trim(c.DRUG_NAME),' ',d1.DFORM_SNAME,' (',cast(c.STRENGTH as DECIMAL(8,0)),' ',s.strength_name,'/',cast(c.ST_NUM_UUNIT as DECIMAL(8,0)),'',u.UUNIT_NAME,')') as DRUG_NAME,
			    CONCAT(TRIM(c.DRUG_NAME),' (', u.UUNIT_NAME, ') ',
			CASE 
				  WHEN TRIM(c.STRENGTH)='' THEN ''
				  WHEN TRIM(c.STRENGTH)='0.00' THEN ''
				  ELSE REPLACE(c.STRENGTH,'.00','')
				  END
				,' ',s.strength_name ) as drug_name,
				u.UUNIT_ID as UNIT,
				d.UUNIT_NAME as UNIT_PACKING ,
					'' AS DRUGPRICE,
					c.PRICE AS DRUGCOST,
				SUM(b.rx_amount) as amount
			FROM opd_visits a
			INNER JOIN cid_hn c1 ON a.hn = c1.hn
			INNER JOIN population p ON p.cid = c1.cid
			INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID AND b.IS_CANCEL =0
			INNER JOIN drugs c ON b.DRUG_ID = c.DRUG_ID AND c.didstd <> ''
			LEFT JOIN usage_units d ON c.PACKAGE = d.UUNIT_ID
			LEFT  JOIN routes r ON r.route_id = b.route_id 
			LEFT  JOIN frequency f on f.frq_id = b.frq_id
			LEFT  JOIN strength_units s ON s.strength_unit = c.strength_unit
			LEFT  JOIN dosage_forms d1 ON d1.DFORM_ID = c.DFORM_ID
			LEFT  JOIN usage_units u ON c.UUNIT_ID = u.UUNIT_ID
			WHERE a.reg_datetime BETWEEN '$date1' AND '$date2' 
			AND a.is_cancel =0
			AND  c.drug_id in ('0094','0704','1113','0440','0097','0098','0184','0185','0186','0187')
			#AND  c.drug_id in ('2314','0664','2358','0491','2466','0266')
			GROUP BY c.didstd
				) as k

			";

     $rawData1 = \yii::$app->db14->createCommand($sql2)->queryAll();
        $nowProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData1,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
		
		
        Yii::$app->session['date1'] =$date1;
        Yii::$app->session['date2'] =$date2;
        return $this->render('muscles', [
                    'dataProvider' => $dataProvider,
					'nowProvider' => $nowProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,

        ]);
    }
	
	public function actionMarijuana() {
       $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


        $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
      $sql = "SELECT DISTINCT
			date(a.REG_DATETIME) as 'regdate',
			c.HN as hn,
			concat(trim(p.fname),' ',p.lname) as 'fullname',
			trim(i.icd10_tm) as 'Diag_primary',
			GROUP_CONCAT(DISTINCT trim(i1.ICD10_TM)) as 'Diag_other',
			#GROUP_CONCAT(DISTINCT trim(i1.ICD_THAI)) as 'Diag_name',
			m.inscl_name,
			GROUP_CONCAT(DISTINCT d.drug_id) as 'Drug',
			GROUP_CONCAT(DISTINCT d.drug_name) as 'drug_name'
			FROM opd_visits a 
			INNER JOIN cid_hn c ON a.HN=c.HN AND a.IS_CANCEL=0
			INNER JOIN population p ON p.CID=c.CID
			LEFT JOIN opd_diagnosis o on o.visit_id=a.visit_id AND o.is_cancel=0 AND o.dxt_id = '1'
			LEFT  JOIN icd10new i on i.icd10=o.icd10
			LEFT JOIN opd_diagnosis o1 on o1.visit_id = a.visit_id AND o1.is_cancel = 0
			LEFT  JOIN icd10new i1 on i1.icd10 = o1.icd10
			INNER  JOIN prescriptions pr ON pr.visit_id  = a.visit_id and pr.IS_CANCEL = 0
			INNER JOIN drugs d ON  pr.drug_id = d.drug_id
			LEFT JOIN main_inscls m ON m.inscl = a.inscl
			WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2' 
			AND d.drug_id in ('2051','2333','2734','2733','2412','2466')
			#AND a.visit_id not in (SELECT ipd_reg.VISIT_ID FROM ipd_reg WHERE ipd_reg.IS_CANCEL=0)
			GROUP BY a.visit_id  ORDER BY a.reg_datetime DESC
			";

     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

        //print_r($rawData);
        try {
            $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
        } catch (\yii\db7\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
        Yii::$app->session['date1'] =$date1;
        Yii::$app->session['date2'] =$date2;
        return $this->render('marijuana', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,

        ]);
    }
    public function actionOperation() {
       $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


	$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
      $sql = "SELECT date(a.REG_DATETIME), 
      COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
      COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
      COUNT(CODE) AS Total 
		FROM opd_visits a
		INNER JOIN opd_operations b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
		INNER JOIN  icd9cm c ON b.icd9 = c.icd9 AND c.cgd_id in (13,14,15)
		WHERE  a.REG_DATETIME BETWEEN '$date1' AND '$date2'
		AND a.is_cancel = 0
		GROUP BY DATE(a.REG_DATETIME) ORDER BY DATE(a.REG_DATETIME)
   ";

     $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

        //print_r($rawData);
        try {
            $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
        } catch (\yii\db2\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
            //'pagination' => ['pagesize' => 5],
        ]);
        Yii::$app->session['date1'] =$date1;
        Yii::$app->session['date2'] =$date2;
        return $this->render('operation', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,
                    'date1' => $date1,
                    'date2' => $date2,

        ]);
    }
    public function actionOutstan(){
           $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


        $sql = "SELECT UNIT_ID,UNIT_NAME, COUNT(UNIT_ID) AS amount
FROM mb_outstan  WHERE mu_date BETWEEN '$date1' AND '$date2'
GROUP BY UNIT_NAME ORDER BY amount";
       $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

      // print_r($rawData);
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       Yii::$app->session['date1']=$date1;
       Yii::$app->session['date2']=$date2;
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       return $this->render('outstan', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
   }
        public function actionOutstan_list($mudate){
          $date1 = isset($data['date1']) ? date('Y-m-d 00:01', strtotime($data['date1'])) : '';
          $date2 = isset($data['date2']) ? date('Y-m-d 23:59', strtotime($data['date2'])) : '';

            $sql = "SELECT * FROM mb_outstan
            WHERE mu_date = $mudate AND mu_date BETWEEN '$date1' AND '$date2' GROUP BY VISIT_ID";
        $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

        // print_r($rawData);
        try {
            $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
        } catch (\yii\db14\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        return $this->render('outstan_list', [
                    'dataProvider' => $dataProvider,
                    'sql'=>$sql,

        ]);
    }
     public function actionCormore(){
           $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));

		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        #$sql = "SELECT * FROM mb_common_cold WHERE REG_DATETIME BETWEEN '$date1' AND '$date2'";
		 $sql = "SELECT a.VISIT_ID ,a.HN , a.REG_DATETIME, c.DRUG_ID, ICD10_TM ,d.DXT_ID 
		FROM  opd_visits a, drugs b, prescriptions c ,opd_diagnosis d, icd10new e
		WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
		AND a.IS_CANCEL =0
		AND a.VISIT_ID = c.VISIT_ID
		AND c.DRUG_ID = b.DRUG_ID
		AND c.DRUG_ID = '0262'
		AND  a.VISIT_ID = d.VISIT_ID 
		AND d.ICD10 = e.ICD10
		AND e.ICD10_TM BETWEEN  'j00' AND  'j99'";

       $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

      // print_r($rawData);
       try {
           $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       //Yii::$app->session['date1']=$date1;
       //Yii::$app->session['date2']=$date2;
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => FALSE,
       ]);
       Yii::$app->session['date1'] =$date1;
       Yii::$app->session['date2'] =$date2;
       return $this->render('cormore', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
     }
     public function actionSmonpri_replace(){
         $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


        $sql = "SELECT month(a.REG_DATETIME) AS month, 
        COUNT(CASE WHEN(c.DRUG_ID = '0262') THEN '1' END) AS 'ฟ้าทะลายโจร' ,
        COUNT(CASE WHEN(c.DRUG_ID = '0263') THEN '2' END) AS 'ขมิ้นชัน',
        COUNT(CASE WHEN(c.DRUG_ID = '2280') THEN '3' END) AS 'แก้ไอมะขาม',
        COUNT(CASE WHEN(c.DRUG_ID = '0266') THEN '4' END) AS 'น้ำมันไพล',
        COUNT(CASE WHEN(c.DRUG_ID = '0261') THEN '5' END) AS 'เพชรสังฆาต',
        COUNT(CASE WHEN(c.DRUG_ID = '2359') THEN '6' END) AS 'ยาเขียวหอม',
        COUNT(CASE WHEN(c.DRUG_ID IN (0262,0263,2280,0266,0261,2359) ) THEN '7' END) AS 'รวม'
        FROM opd_visits a, prescriptions b, drugs c
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.VISIT_ID = b.VISIT_ID
        AND a.IS_CANCEL =0
        AND b.DRUG_ID = c.DRUG_ID
        AND a.VISIT_ID NOT IN (SELECT VISIT_ID FROM ipd_reg)
        GROUP BY month";

       $sData = \yii::$app->db14->createCommand($sql)->queryAll();

      // print_r($rawData);
       try {
           $sData = \Yii::$app->db14->createCommand($sql)->queryAll();
       } catch (\yii\db14\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $smondataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $sData,
           'pagination' => FALSE,
       ]);
       return $this->render('smonpri_replace', [
                   'dataProvider' => $smondataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
     }
        public function actionOperation_month(){
            $date1 = Yii::$app->session['date1'];
            $date2 = Yii::$app->session['date2'];
        $sql = "SELECT MONTH(REG_DATETIME)AS MONTH, 
      COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
      COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
      COUNT(CODE) AS Total  
        FROM mb_opd_operations 
        WHERE REG_DATETIME BETWEEN  '$date1' AND '$date2'
        AND VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
        GROUP BY MONTH(REG_DATETIME) ORDER BY MONTH(REG_DATETIME)";
       $iData = \yii::$app->db2->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
       } catch (\yii\db2\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       $iidataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $iData,
           'pagination' => FALSE,
       ]);
       $sql2 = "SELECT MONTH(REG_DATETIME)AS MONTH, 
      COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
      COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
      COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
      COUNT(CODE) AS Total  
        FROM mb_opd_operations 
        WHERE REG_DATETIME BETWEEN  '$date1' AND '$date2'
        AND VISIT_ID in (SELECT VISIT_ID FROM mobile_visits)
        GROUP BY MONTH(REG_DATETIME) ORDER BY MONTH(REG_DATETIME)";
    $oData = \Yii::$app->db2->createCommand($sql2)->queryAll();
       $oodataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $oData,
           'pagination' => FALSE,
       ]);
       return $this->render(operation_monthlist, [
                   'imonthData' => $iidataProvider,
                   'omonthData' => $oodataProvider,
                   'sql'=>$sql,
                   'date1'=>$date1,
                   'date2'=>$date2,

       ]);   
     }
     public function actionStaff_operation(){
      $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));


		$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

           // $cid = \Yii::$app->request->post('cid');
           // Yii::$app->session['cid'] = $cid;

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
    $sql = "SELECT a.STAFF_ID,CONCAT(c.FNAME,'',TRIM(c.LNAME))AS Provider,
    COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
    COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
    COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
    COUNT(CODE) AS Total 
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.STAFF_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
    AND a.CGD_ID = 15
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    GROUP BY a.STAFF_ID ORDER BY a.STAFF_ID";
   $rawData = \yii::$app->db14->createCommand($sql)->queryAll();

  // print_r($rawData);
   try {
       $rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
   } catch (\yii\db\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   Yii::$app->session['date1']=$date1;
   Yii::$app->session['date2']=$date2;
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => FALSE,
   ]);
   return $this->render('staff_operation', [
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
               'date1'=>$date1,
               'date2'=>$date2,

   ]);   
}
    public function actionStaff_operation_list($staffid){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT a.REG_DATETIME,a.VISIT_ID,a.HN ,a.NICKNAME,a.CODE ,a.STAFF_ID
        FROM mb_opd_operations a
        INNER JOIN staff b ON a.STAFF_ID = b.STAFF_ID
        LEFT JOIN population c ON b.CID = c.CID
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
        AND a.STAFF_ID = $staffid ORDER BY a.NICKNAME";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();

   
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => FALSE,
    ]);
    return $this->render('staff_operation_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,

    ]);
} 
public function actionSurgeon_operation(){
   $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));



$sql = "SELECT a.SURGEON_ID,CONCAT(c.FNAME,'',TRIM(c.LNAME))AS Provider,
COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
COUNT(CODE) AS Total 
FROM mb_opd_operations a
INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
LEFT JOIN population c ON b.CID = c.CID
WHERE DATE(a.REG_DATETIME) between '$date1' AND '$date2'
AND a.CGD_ID = 15
AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
GROUP BY a.SURGEON_ID ORDER BY a.SURGEON_ID";
$iData = \yii::$app->db2->createCommand($sql)->queryAll();
Yii::$app->session['date1']=$date1;
Yii::$app->session['date2']=$date2;
$dataProvider = new \yii\data\ArrayDataProvider([
   'allModels' => $iData,
   'pagination' => [
       'pagesize'=> 15
   ],
]);
return $this->render('surgeon_operation', [
           'dataProvider' => $dataProvider,
           //'outData' => $outdataProvider,
           'sql'=>$sql,
           'date1'=>$date1,
           'date2'=>$date2,

 ]);   
}
public function actionSurgeon_operation_list($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT a.REG_DATETIME,a.VISIT_ID,a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionSurgeon_songserm($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT  DISTINCT a.REG_DATETIME,a.VISIT_ID,a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND SUBSTR(a.NICKNAME,4,8) = 'ส่งเสริม' ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
     ],
    ]);
    return $this->render('surgeon_operation_list', [
        'dataProvider' => $dataProvider,
        'sql'=>$sql,

]);
} 
public function actionSurgeon_acupencture($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT DISTINCT a.REG_DATETIME,a.VISIT_ID,a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND a.code= 99.92 ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionSurgeon_nursing($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT DISTINCT a.REG_DATETIME,a.VISIT_ID, a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND SUBSTR(a.NICKNAME,4,6) = 'บริบาล' ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionSurgeon_massage($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT DISTINCT a.REG_DATETIME,a.VISIT_ID, a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND left(NICKNAME,6) = 'การนวด' ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionSurgeon_baked($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
    $sql = "SELECT DISTINCT a.REG_DATETIME,a.VISIT_ID, a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND SUBSTR(a.NICKNAME,4,2) = 'อบ' ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionSurgeon_compression($surgeonid){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
	$connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
    $sql = "SELECT DISTINCT a.REG_DATETIME,a.VISIT_ID, a.HN ,a.NICKNAME,a.CODE, a.SURGEON_ID
    FROM mb_opd_operations a
    INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
    LEFT JOIN population c ON b.CID = c.CID
    WHERE DATE(a.REG_DATETIME) BETWEEN '$date1' AND '$date2'
    AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
    AND a.SURGEON_ID = $surgeonid AND SUBSTR(a.NICKNAME,4,5) = 'ประคบ' ORDER BY a.NICKNAME";
$rawData = \yii::$app->db2->createCommand($sql)->queryAll();

// print_r($rawData);
try {
    $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
} catch (\yii\db\Exception $e) {
    throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $rawData,
    'pagination' => [
        'pagesize'=> 15
    ],
]);
return $this->render('surgeon_operation_list', [
            'dataProvider' => $dataProvider,
            'sql'=>$sql,

    ]);
  } 
  public function actionOp_count(){
     $date1 = Yii::$app->session['date1'];
     $date2 = Yii::$app->session['date2'];
     $sql = " SELECT MONTH(REG_DATETIME)AS MONTH, 
     #COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'acupencture', 
     COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'nursing', 
     COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'massage',
     COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'baked', 
     COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'compression', 
     COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'songserm', 
     COUNT(CODE) AS Total  
       FROM mb_opd_operations 
       WHERE REG_DATETIME BETWEEN  '$date1' AND '$date2'
       AND CGD_ID = 15  
       GROUP BY MONTH(REG_DATETIME) ORDER BY MONTH(REG_DATETIME)";
 $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
     $itopdataProvider = new \yii\data\ArrayDataProvider([
         'allModels' => $rawData,
         'pagination' => [
             'pagesize'=> 8
         ],
     ]);

        $sql = " SELECT MONTH(REG_DATETIME)AS MONTH, 
        COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'nursing', 
        COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'massage',
        COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'baked', 
        COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'compression', 
        COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'songserm', 
        COUNT(CODE) AS Total  
          FROM mb_opd_operations 
          WHERE REG_DATETIME BETWEEN  '$date1' AND '$date2'
          AND VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
          AND CGD_ID = 15
          GROUP BY MONTH(REG_DATETIME) ORDER BY MONTH(REG_DATETIME)";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
        $topdataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pagesize'=> 8
            ],
        ]);

       $sql = "SELECT DISTINCT MONTH(a.DATE_SERV)AS MONTH,
      # COUNT(CASE WHEN a.PROCEDCODE = 9992 THEN '2' END) AS 'ฝังเข็ม', 
       COUNT(CASE WHEN SUBSTR(b.NAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
       COUNT(case WHEN left(b.NAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
       COUNT(CASE WHEN SUBSTR(b.NAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
       COUNT(CASE WHEN SUBSTR(b.NAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
       COUNT(CASE WHEN SUBSTR(b.NAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
       COUNT(a.PROCEDCODE) AS Total 
       FROM procedure_opd a
       INNER JOIN icd43_planthai1 b ON a.PROCEDCODE = b.CODE
       INNER JOIN service c ON a.SEQ = c.SEQ
       WHERE a.DATE_SERV BETWEEN '$date1' AND '$date2' 
       GROUP BY MONTH(a.DATE_SERV)";
        $fData = \yii::$app->db4->createCommand($sql)->queryAll();
        $procedataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $fData,
            'pagination' => FALSE,
        ]);

        $sql = "SELECT DISTINCT MONTH(a.DATE_SERV)AS MONTH,
        #COUNT(CASE WHEN a.PROCEDCODE = 99.92 THEN '2' END) AS 'ฝังเข็ม', 
        COUNT(CASE WHEN SUBSTR(b.desc_r,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
        COUNT(case WHEN left(b.desc_r,6) = 'การนวด' THEN '4'END) AS 'การนวด',
        COUNT(CASE WHEN SUBSTR(b.desc_r,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
        COUNT(CASE WHEN SUBSTR(b.desc_r,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
        COUNT(CASE WHEN SUBSTR(b.desc_r,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
        COUNT(a.PROCEDCODE) AS Total 
        FROM procedure_opd a
        INNER JOIN cicd9ttm_planthai b ON a.PROCEDCODE = b.code
        INNER JOIN service c ON a.SEQ = c.SEQ
        WHERE a.DATE_SERV BETWEEN '$date1' AND '$date2'
        AND a.CLINIC = '00126' AND c.SERVPLACE =2
        GROUP BY MONTH(a.DATE_SERV)";
    $fData = \yii::$app->db4->createCommand($sql)->queryAll();
    $oprocedataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $fData,
        'pagination' => FALSE,
    ]);

return $this->render('op_countlist', [
           'outData' =>$itopdataProvider,
           'inData' => $topdataProvider,
           'oproceData'=>$oprocedataProvider,
           'iproceData'=>$procedataProvider,
           'date1'=> $date1,
           'date2'=>$date2,
           
    ]);   
  }
  public function actionSurgeon_inout(){
    $date1 = Yii::$app->session['date1'];
    $date2 = Yii::$app->session['date2'];
$sql2 = "SELECT a.SURGEON_ID,CONCAT(c.FNAME,'',TRIM(c.LNAME))AS Provider,
COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
COUNT(CODE) AS Total 
FROM mb_opd_operations a
INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
LEFT JOIN population c ON b.CID = c.CID
WHERE DATE(a.REG_DATETIME) between '$date1' AND '$date2'
AND a.CGD_ID = 15
AND a.VISIT_ID NOT in (SELECT VISIT_ID FROM mobile_visits)
GROUP BY a.SURGEON_ID ORDER BY a.SURGEON_ID";
$iData = \yii::$app->db2->createCommand($sql2)->queryAll();
$indataProvider = new \yii\data\ArrayDataProvider([
   'allModels' => $iData,
   'pagination' => [
       'pagesize'=> 15
   ],
]);
$sql = "SELECT a.SURGEON_ID,CONCAT(c.FNAME,'',TRIM(c.LNAME))AS Provider,
COUNT(CASE WHEN CODE= 99.92 THEN '2' END) AS 'ฝังเข็ม', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,6) ='บริบาล' THEN '3' END) AS 'บริบาล', 
COUNT(case WHEN left(NICKNAME,6) = 'การนวด' THEN '4'END) AS 'การนวด',
COUNT(CASE WHEN SUBSTR(NICKNAME,4,2) = 'อบ' THEN '5' END) AS 'อบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,5) = 'ประคบ' THEN '6' END) AS 'ประคบ', 
COUNT(CASE WHEN SUBSTR(NICKNAME,4,8) = 'ส่งเสริม' THEN '7' END) AS 'ส่งเสริม', 
COUNT(CODE) AS Total 
FROM mb_opd_operations a
INNER JOIN staff b ON a.SURGEON_ID = b.STAFF_ID
LEFT JOIN population c ON b.CID = c.CID
WHERE DATE(a.REG_DATETIME) between '$date1' AND '$date2'
AND a.CGD_ID = 15
AND a.VISIT_ID  in (SELECT VISIT_ID FROM mobile_visits)
GROUP BY a.SURGEON_ID ORDER BY a.SURGEON_ID";
$oData = \yii::$app->db2->createCommand($sql)->queryAll();
$outdataProvider = new \yii\data\ArrayDataProvider([
   'allModels' => $oData,
   'pagination' => [
       'pagesize'=> 15
   ],
]);
return $this->render('inout', [
           'insData' => $indataProvider,
           'outsData' => $outdataProvider,
           'sql'=>$sql,
           'date1'=>$date1,
           'date2'=>$date2,

 ]);
}
public function actionCheck_operations(){
    $sql = "SELECT a.CODE AS 43F, b.43CODE AS MCODE,b.CODE AS HCODE ,a.NAME, b.NICKNAME,COST,CGD_ID
    FROM icd43_planthai1 a
    RIGHT  JOIN icd9cm_planthai b ON a.CODE = b.43CODE";
    $iData = \yii::$app->db4->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $iData,
       'pagination' => [
           'pagesize'=> 8
       ],
    ]);
    return $this->render('check_operations', [
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
               
         ]);      
        }
 public function actionCheck_operation(){
$sql = "SELECT a.CODE AS 43F, b.43CODE AS MCODE,b.CODE AS HCODE ,a.NAME, b.NICKNAME,COST,CGD_ID
FROM icd43_planthai1 a
RIGHT  JOIN icd9cm_planthai b ON a.CODE = b.43CODE";
$iData = \yii::$app->db4->createCommand($sql)->queryAll();
$dataProvider = new \yii\data\ArrayDataProvider([
   'allModels' => $iData,
   'pagination' => [
       'pagesize'=> 8
   ],
]);
return $this->render('ck_operation', [
           'dataProvider' => $dataProvider,
           'sql'=>$sql,
           

     ]);      
    }
    public function actionCheck_procudure(){
        $sql = "SELECT a.CODE AS 43F, b.43CODE AS MCODE,b.CODE AS HCODE ,a.NAME, b.NICKNAME,COST,CGD_ID
        FROM icd43_planthai1 a
        LEFT JOIN icd9cm_planthai b ON a.CODE = b.43CODE";
        $ioData = \yii::$app->db4->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $ioData,
           'pagination' => [
               'pagesize'=> 8
           ],
        ]);
        return $this->render('ck_procudure', [
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   
        
             ]);      
            }
    public function actionNo_procudure(){
                $sql = "SELECT 
                (CASE WHEN ISNULL(a.CODE) THEN ''
                ELSE a.CODE END) AS 43F,
                 b.43CODE AS MCODE,b.CODE AS HCODE, b.NICKNAME,COST,CGD_ID
                FROM icd43_planthai1 a
                RIGHT  JOIN icd9cm_planthai b ON a.CODE = b.43CODE
                WHERE
                a.CODE  IS NULL";
                $ioData = \yii::$app->db4->createCommand($sql)->queryAll();
                $dataProvider = new \yii\data\ArrayDataProvider([
                   'allModels' => $ioData,
                   'pagination' => [
                       'pagesize'=> 8
                   ],
                ]);
                return $this->render('ck_procudure', [
                           'dataProvider' => $dataProvider,
                           'sql'=>$sql,         
         ]);               
    }
    public function actionSurgeon_9007810(){
        $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));

    $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
    $sql = "SELECT DISTINCT k.INSCL, k.INSCL_NAME, COUNT(k.INSCL) as AMOUNT
    FROM (
    SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID
    FROM opd_visits a
    INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0
    INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = 9007810 AND c.CGD_ID = 15
    INNER JOIN main_inscls d ON a.inscl = d.inscl
    WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2'
    AND a.visit_id NOT in (SELECT VISIT_ID FROM mobile_visits)
    ) as k 
    GROUP BY k.INSCL ORDER BY COUNT(k.INSCL) DESC";
    $rowData = \yii::$app->db2->createCommand($sql)->queryAll();
    Yii::$app->session['date1']=$date1;
    Yii::$app->session['date2']=$date2;
    $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rowData,
       'pagination' => [
           'pagesize'=> 15
       ],
    ]);
    return $this->render('surgeon_9007810', [
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
               'date1'=>$date1,
               'date2'=>$date2,
     ]);   
    }
    public function actionSurgeon_9007810_list($inscl){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
	 $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        $sql = "SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID
        FROM opd_visits a
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = 9007810 AND c.CGD_ID = 15
        INNER JOIN main_inscls d ON a.inscl = d.inscl
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2'
        AND d.inscl =$inscl
        AND a.visit_id NOT in (SELECT VISIT_ID FROM mobile_visits)";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    
    // print_r($rawData);
    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('surgeon_9007810_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2,
    
        ]);
      } 
      public function actionSurgeon_9007810all(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
		 $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        $sql = "SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID
        FROM opd_visits a
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = 9007810 AND c.CGD_ID = 15
        INNER JOIN main_inscls d ON a.inscl = d.inscl
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2'
        AND a.visit_id NOT in (SELECT VISIT_ID FROM mobile_visits) ORDER BY inscl";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('surgeon_9007810_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
    
        ]);
      } 
      public function actionSurgeon_9007810month(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT DISTINCT YEAR(REG_DATETIME) AS YEAR,MONTH(a.REG_DATETIME) as MONTH,
        COUNT(CASE WHEN d.INSCL in(01,12,25) THEN 1 END) AS 'ข้าราชการ',
        COUNT(CASE WHEN d.INSCL in(08,09) THEN 1 END) AS 'ประกันสังคม',
        COUNT(CASE WHEN d.INSCL in(14,36) THEN 1 END) AS 'รัฐวิสาหกิจ/ข้าราชการ กทม',
        COUNT(CASE WHEN d.INSCL =23 THEN 1 END) AS 'มาตรา8',
        COUNT(CASE WHEN d.INSCL =03 THEN 1 END) AS 'ประกันสุขภาพ',
        COUNT(CASE WHEN d.INSCL BETWEEN '01' AND '37' THEN 1 END) AS 'รวม'
        FROM opd_visits a
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = 9007810 AND c.CGD_ID = 15
        INNER JOIN main_inscls d ON a.inscl = d.inscl
        WHERE a.REG_DATETIME > '2018-10-01' 
        AND a.visit_id NOT in (SELECT VISIT_ID FROM mobile_visits)
        GROUP BY MONTH WITH ROLLUP ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('surgeon_9007810_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_thaimed(){
        $data = Yii::$app->request->post();
        $date1 = isset($data['date1']) ? $data['date1'] : '';
        $date2 = isset($data['date2']) ? $data['date2'] : '';
    
        $sql = "SELECT 'ประเภทU (ยกเว้นU778)' as 'ICD10_NAME',
        COUNT(k.VISIT_ID) AS VISITS,
        COUNT(DISTINCT k.hn)  AS KON
        FROM
        (SELECT a.REG_DATETIME ,a.VISIT_ID,a.HN ,c.ICD10_TM,c.ICD_NAME
        FROM opd_visits a
        INNER JOIN opd_diagnosis b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
        INNER JOIN icd10new c ON b.ICD10 = c.ICD10 AND LEFT(c.icd10_tm,1) = 'U' AND c.icd10_tm not in ('U778','U771')
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.is_inscl = 0) as k 
         ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    Yii::$app->session['date1']=$date1;
    Yii::$app->session['date2']=$date2;
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('u_thaimed', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_krung(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT a.REG_DATETIME ,a.VISIT_ID,a.HN ,c.ICD10_TM,c.ICD_NAME
        FROM opd_visits a
        INNER JOIN opd_diagnosis b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
        INNER JOIN icd10new c ON b.ICD10 = c.ICD10 AND LEFT(c.icd10_tm,1) = 'U' AND c.icd10_tm not in ('U778','U771')
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.is_inscl = 0 ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('u_krung', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_kon(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT a.REG_DATETIME ,a.VISIT_ID,a.HN ,c.ICD10_TM,c.ICD_NAME
        FROM opd_visits a
        INNER JOIN opd_diagnosis b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
        INNER JOIN icd10new c ON b.ICD10 = c.ICD10 AND LEFT(c.icd10_tm,1) = 'U' AND c.icd10_tm not in ('U778','U771')
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.is_inscl = 0 GROUP BY a.HN";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('u_kon', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      }
      public function actionU_list(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT k.ICD10_TM, k.ICD_NAME, COUNT(k.ICD10_TM) AS TOTAL
        FROM 
        (SELECT a.REG_DATETIME ,a.VISIT_ID,a.HN ,c.ICD10_TM,c.ICD_NAME
        FROM opd_visits a
        INNER JOIN opd_diagnosis b ON a.VISIT_ID = b.VISIT_ID AND b.is_cancel = 0
        INNER JOIN icd10new c ON b.ICD10 = c.ICD10 AND LEFT(c.icd10_tm,1) = 'U' AND c.icd10_tm not in ('U778','U771')
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.is_inscl = 0) as k  GROUP BY k.ICD10_TM  ORDER BY TOTAL DESC";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('u_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_9007712(){
        $data = Yii::$app->request->post();
        $date1 = isset($data['date1']) ? $data['date1'] : '';
        $date2 = isset($data['date2']) ? $data['date2'] : '';
		 $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        $sql = "SELECT k.INSCL, k.INSCL_NAME , COUNT(CODE) AS AMOUNT
        FROM 
        (SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID 
        FROM opd_visits a 
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0 
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = '900-77-12' AND c.CGD_ID = 15 
        INNER JOIN main_inscls d ON a.inscl = d.inscl 
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2' ) AS k
        GROUP BY k.INSCL_NAME order by AMOUNT DESC ";
    $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
    Yii::$app->session['date1']=$date1;
    Yii::$app->session['date2']=$date2;
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('9007712', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_9007712_list($inscl){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN ,a.VISIT_ID ,c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID 
        FROM opd_visits a 
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0 
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = '900-77-12' AND c.CGD_ID = 15 
        INNER JOIN main_inscls d ON a.inscl = d.inscl 
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2' 
        AND d.INSCL =$inscl ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('9007712_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2,
    
        ]);
      } 
      public function actionU_9007712month(){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT MONTH(k.REGDATE)AS MONTH, COUNT(CODE) AS AMOUNT 
        FROM (SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , 
        c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID FROM opd_visits a 
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0 
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code = '900-77-12' AND c.CGD_ID = 15 
        INNER JOIN main_inscls d ON a.inscl = d.inscl 
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2' ) AS k 
        GROUP BY MONTH(k.REGDATE) WITH ROLLUP ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('9007712_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      }
      public function actionU_9007800(){
        $data = Yii::$app->request->post();

// กำหนดค่า date1
$date1 = isset($data['date1']) && !empty($data['date1'])
    ? date('Y-m-d 00:01', strtotime($data['date1']))
    : date('Y-m-d 00:01', strtotime('-1 day'));

// กำหนดค่า date2
$date2 = isset($data['date2']) && !empty($data['date2'])
    ? date('Y-m-d 23:59', strtotime($data['date2']))
    : date('Y-m-d 23:59', strtotime('-1 day'));

		 $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        $sql = "SELECT k.INSCL, k.INSCL_NAME , COUNT(CODE) AS AMOUNT
        FROM 
        (SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN , c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID 
        FROM opd_visits a 
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0 
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code in ('900-78-00','9007800') AND c.CGD_ID = 15 
        INNER JOIN main_inscls d ON a.inscl = d.inscl 
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2' ) AS k
        GROUP BY k.INSCL_NAME order by AMOUNT DESC ";
    $rawData = \yii::$app->db14->createCommand($sql)->queryAll();
    Yii::$app->session['date1']=$date1;
    Yii::$app->session['date2']=$date2;
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('9007800', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      } 
      public function actionU_9007800_list($inscl){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT DISTINCT date(a.REG_DATETIME) as REGDATE, d.INSCL , d.INSCL_NAME,a.HN ,a.VISIT_ID ,c.CODE, c.NICKNAME, b.STAFF_ID ,b.SURGEON_ID 
        FROM opd_visits a 
        INNER JOIN opd_operations b ON a.visit_id = b.visit_id and a.is_cancel = 0 
        INNER JOIN icd9cm c ON b.icd9 = c.icd9 AND c.code in ('900-78-00','9007800') AND c.CGD_ID = 15 
        INNER JOIN main_inscls d ON a.inscl = d.inscl 
        WHERE a.REG_DATETIME BETWEEN '$date1' and '$date2' 
        AND d.INSCL =$inscl ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('9007800_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2,
    
        ]);
      } 
      public function actionInscl_smonpai6(){
       $data = Yii::$app->request->post();

$date1 = isset($data['date1']) && !empty($data['date1']) 
         ? $data['date1'] 
         : date('Y-m-d', strtotime('-1 day'));

$date2 = isset($data['date2']) && !empty($data['date2']) 
         ? $data['date2'] 
         : date('Y-m-d', strtotime('-1 day'));

		 $connection = Yii::$app->db;
		if (\Yii::$app->request->isPost) {

            $log = new LogThaimed();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $date1;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();
            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        $sql = "SELECT k.DRUG_ID, k.DRUG_NAME,
        COUNT(CASE WHEN(k.INSCL in (01,25,35,37,40)) THEN '1' END) AS 'ข้าราชการ' ,
        COUNT(CASE WHEN(k.INSCL in (08,09,21)) THEN '2' END) AS 'ประกันสังคม' ,
        COUNT(CASE WHEN(k.INSCL in (11,12)) THEN '3' END) AS 'อปท' ,
        COUNT(CASE WHEN(k.INSCL = 23) THEN '4' END) AS 'มาตรา8' ,
        COUNT(CASE WHEN(k.INSCL  = 00) THEN '5' END) AS 'สิทธิ์ว่าง' ,
COUNT(CASE WHEN(k.INSCL IN(00,08,09,11,12,21,23,01,25,35,37,40)) THEN '6' END) AS 'รวม'
FROM (
SELECT a.VISIT_ID,a.HN, f.CID, CONCAT(trim(f.FNAME),'   ',f.LNAME) as FULLNAME,FLOOR(DATEDIFF(a.REG_DATETIME,f.BIRTHDATE)/365.25) as AGE,
d.INSCL, d.INSCL_NAME,c.DRUG_ID,c. DRUG_NAME
FROM opd_visits a
INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID
INNER JOIN drugs c ON  b.DRUG_ID = c.DRUG_ID
                INNER JOIN main_inscls d ON a.INSCL = d.INSCL AND d.inscl IN(00,08,09,11,12,21,23,01,25,35,37,40)
                INNER JOIN cid_hn e on a.HN = e.HN
                INNER JOIN population f ON e.CID = f.CID
        WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
        AND a.IS_CANCEL =0
        AND a.VISIT_ID NOT IN (SELECT VISIT_ID FROM ipd_reg)
        AND c.DRUG_ID in (0664,2358,0491,2443,2280,1393,2364,2282,0262,0263,0266,2362,2359,2363,2295,2314,0261,1392,2289,
                1389,2294,1395,2419,0666,0265,1394,'1388','2360','2354','2311','2051','2439','2486','2503')
        ) as k  GROUP BY k.DRUG_ID ";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    Yii::$app->session['date1']=$date1;
    Yii::$app->session['date2']=$date2;
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => false
    ]);
    return $this->render('inscl_smonpai6', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2
    
        ]);
      }  
      public function actionInscl_drugttm_list($drugid){
        $date1 = Yii::$app->session['date1'];
        $date2 = Yii::$app->session['date2'];
        $sql = "SELECT a.VISIT_ID,a.HN, f.CID, CONCAT(trim(f.FNAME),'   ',f.LNAME) as FULLNAME,FLOOR(DATEDIFF(a.REG_DATETIME,f.BIRTHDATE)/365.25) as AGE,
        d.INSCL, d.INSCL_NAME,c.DRUG_ID,c. DRUG_NAME
                FROM opd_visits a
                        INNER JOIN prescriptions b ON a.VISIT_ID = b.VISIT_ID
                        INNER JOIN drugs c ON  b.DRUG_ID = c.DRUG_ID
                        INNER JOIN main_inscls d ON a.INSCL = d.INSCL AND d.inscl IN(00,08,09,11,12,21,23,01,25,35,37,40)
                        INNER JOIN cid_hn e on a.HN = e.HN
                        INNER JOIN population f ON e.CID = f.CID
                WHERE a.REG_DATETIME BETWEEN '$date1' AND '$date2'
                AND a.IS_CANCEL =0
                AND c.DRUG_ID = $drugid
                AND a.VISIT_ID NOT IN (SELECT VISIT_ID FROM ipd_reg)
                AND c.DRUG_ID in (0664,2358,0491,2443,2280,1393,2364,2282,0262,0263,0266,2362,2359,2363,2295,2314,0261,1392,2289,
                        1389,2294,1395,2419,0666,0265,1394,1388,2360,2354,2311)
                ORDER BY d.inscl";
    $rawData = \yii::$app->db2->createCommand($sql)->queryAll();
    try {
        $rawData = \Yii::$app->db2->createCommand($sql)->queryAll();
    } catch (\yii\db\Exception $e) {
        throw new \yii\web\ConflictHttpException('sql error');
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $rawData,
        'pagination' => [
            'pagesize'=> 10
        ],
    ]);
    return $this->render('drugttm_list', [
                'dataProvider' => $dataProvider,
                'sql'=>$sql,
                'date1'=>$date1,
               'date2'=>$date2,
    
        ]);
      } 
	  
}

