<?php

//namespace modules\ehr\controllers;
namespace app\modules\ehr\controllers;


use yii\web\Controller;
use Yii;
use yii\data\ArrayDataProvider;
use kartik\tabs\TabsX;
use yii\filters\VerbFilter;
use app\modules\ehr\models\LogEhr;

/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่


class DefaultController extends Controller {

  //  public $enableCsrfValidation = false; //เพิ่ม
    
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
                'only'=> ['index','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions'=>['index','create','view'],
                        'allow'=> true,
                        'roles' => [
                            User::ROLE_USER,
                           User::ROLE_EMPLOYEE,
                           User::ROLE_ADMIN
                         ]
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
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
    public function actionIndex() {
        
        //throw ConflictHttpException('ระบบ EHR ถูกปิด');
        

        // connect database
        $connection = Yii::$app->db3;

        $tname = '';
        $taddr = '';
        $sex = '1';
        $chronic = '';
        $cid = '';
        $seq = '';
        $hospcode = '';
        $an = '';
        $date_serv = '';
        $cc = '';
        $sbp = '';
        $dbp = '';
        $pr = '';
        $rr = '';
        $btemp = '';
        $hospname = '';
        $timeserv = '';
        $birth = '';
        $allergy = '';
        $hn = '';
        $telephone='';


        if (\Yii::$app->request->isPost) {

            $cid = \Yii::$app->request->post('cid');
            Yii::$app->session['cid'] = $cid;

            $log = new LogEhr();
            $log->username = \Yii::$app->user->identity->username;
            $log->patient_cid = $cid;
            $log->datetime = date('Y-m-d H:i:s');
            $log->ip = \Yii::$app->request->getUserIP();

            if ($log->save()) {
                //MyHelper::setAlert('success','......');
            }
        }
        if (isset($_GET['hospcode'])) {
            $cid = Yii::$app->session['cid'];
            $seq = $_GET['seq'];
            $hospcode = $_GET['hospcode'];
            $an = $_GET['an'];
			$pid = $_GET['pid'];
        }

        if (isset($_GET['page'])) {
            $cid = Yii::$app->session['cid'];
        }
##########################################################################
        // ข้อมูลบุคคล
        $sql = "SELECT p.cid,CONCAT(n.prename,p.name,' ',p.lname) AS tname,sex,
        CONCAT('เลขที่ ',trim(h.HOUSE),' ต.',t.tambonname,' อ.',a.ampurname,' จ.',c.changwatname) AS taddr,h.TELEPHONE AS telephone,
        p.hn as hn,
        CONCAT(tc.chronic,' ',i.diagename)  as chronic,birth,
        GROUP_CONCAT(d.DNAME) AS allergy
        FROM person p
        LEFT JOIN cprename n ON n.id_prename = p.prename
        LEFT JOIN home h ON h.HOSPCODE = p.HOSPCODE AND h.HID = p.HN
        LEFT JOIN dhdc_tmp_chronic tc on tc.cid = p.cid
        LEFT JOIN cicd10tm i ON i.diagcode = tc.chronic
        LEFT JOIN campur a ON a.ampurcode = h.AMPUR AND a.changwatcode =  h.CHANGWAT
        LEFT JOIN cchangwat c  ON c.changwatcode = h.CHANGWAT
        LEFT JOIN ctambon t ON t.tamboncode = h.TAMBON AND t.ampurcode = CONCAT(c.changwatcode,a.ampurcode)
        LEFT JOIN drugallergy d ON p.cid = d.cid
        WHERE  p.cid = '$cid' 
        LIMIT 1";

        $data = $connection->createCommand($sql)
                ->queryAll();

        for ($i = 0; $i < sizeof($data); $i++) {
            $tname = $data[$i]['tname'];
            $taddr = $data[$i]['taddr'];
            $sex = $data[$i]['sex'];
            $hn = $data[$i]['hn'];
            $telephone = $data[$i]['telephone'];
            $chronic = $data[$i]['chronic'];
            $birth = $data[$i]['birth'];
            $allergy = $data[$i]['allergy'];
        }

##########################################################################
        // ข้อมูลวันที่มารักษา
        $sqld = "SELECT date(s.date_serv) as tdate,
		time(s.time_serv) as tidate,
                s.hospcode,s.seq,h.hosname as hospname,p.pid,
                IF(a.an IS NULL,'N','Y') AS tadmit,
                IF(a.an IS NULL,' ',a.AN) AS an,
								IF(l.lab_no IS NULL,' ','Y') AS lab,
								IF(d.DIDSTD is null, '','D') AS dru,
								IF(e.VACCINETYPE is null, '','V') AS vc,
								IF(pr.procedcode is null,' ','P') AS prc
                FROM service s
                LEFT JOIN person p ON p.hospcode = s.hospcode AND p.pid =s.pid
                LEFT JOIN chospital  h ON h.hoscode = s.hospcode
		        LEFT JOIN admission a ON a.HOSPCODE = s.HOSPCODE AND a.SEQ = s.SEQ
		        LEFT JOIN lab_ehr l ON s.seq = l.visit_id
				    LEFT JOIN drug_opd d ON d.SEQ = s.SEQ AND d.HOSPCODE = s.HOSPCODE
						LEFT JOIN epi e ON e.SEQ = s.SEQ AND s.HOSPCODE = e.HOSPCODE
						LEFT JOIN procedure_opd pr ON pr.SEQ = s.SEQ AND s.HOSPCODE = pr.HOSPCODE
                WHERE  p.cid = '$cid'
				AND s.date_serv >= '20200401'
                AND s.chiefcomp <> ''
		GROUP BY s.SEQ
                ORDER BY s.date_serv DESC ";
			    // WHERE  p.cid = '$cid'
        $rawData = $connection->createCommand($sqld)
                ->queryAll();

        $dataProvider = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 50
            ],
        ]);
##########################################################################
        //วินิจฉัย
        $sqli = "SELECT d.diagcode, i.diagename,d.diagtype 
                    FROM diagnosis_opd d 
                    LEFT JOIN cicd10tm i ON i.diagcode = d.diagcode
                    WHERE  -- cid ='$cid'
                     seq='$seq' AND hospcode = '$hospcode'    
                    UNION 
                     SELECT d.diagcode,i.diagename as icd_name ,d.diagtype
                    FROM diagnosis_ipd d
                    LEFT JOIN cicd10tm i ON i.diagcode = d.diagcode
                    WHERE an ='$an'  AND hospcode = '$hospcode' ";
        $rawi = $connection->createCommand($sqli)
                ->queryAll();

        $dataProvideri = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawi,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
##########################################################################
        //อาการ
        
        $sqlcc = "SELECT date_serv,CHIEFCOMP,sbp,dbp,pr,rr,btemp,h.hosname as hospname,
                    CONCAT(left(time_serv,2),':',SUBSTR(time_serv,3,2),':',right(time_serv,2)) as time_serv
                    FROM service s
                    LEFT JOIN chospital  h ON h.hoscode = s.hospcode
                    WHERE s.hospcode='$hospcode' AND seq ='$seq'
                    AND s.date_serv >= '20200401' 
                    LIMIT 1";
        $datacc = $connection->createCommand($sqlcc)
                ->queryAll();

        for ($i = 0; $i < sizeof($datacc); $i++) {
            $date_serv = $datacc[$i]['date_serv'];
            $cc = $datacc[$i]['CHIEFCOMP'];
            $sbp = $datacc[$i]['sbp'];
            $dbp = $datacc[$i]['dbp'];
            $pr = $datacc[$i]['pr'];
            $rr = $datacc[$i]['rr'];
            $btemp = $datacc[$i]['btemp'];
            $hospname = $datacc[$i]['hospname'];
            $hospname = str_replace("โรงพยาบาลส่งเสริมสุขภาพตำบล", "รพสต.", $hospname);
            $timeserv = $datacc[$i]['time_serv'];
            
        }
##########################################################################
	//****OPERATION หัตถการ*************************
        $sqlproce = "SELECT p.HOSPCODE,p.DATE_SERV,p.PROCEDCODE, IF(p.NAME is null ,i.pro_name ,p.NAME) AS NAME
		   FROM procedure_opd p
		   LEFT JOIN l_icd9cm i ON i.procedcode = p.PROCEDCODE
                   WHERE p.cid = '$cid'
                   AND p.seq='$seq' AND p.hospcode = '$hospcode'";
        $rawproce = $connection->createCommand($sqlproce)->queryAll();
       
        $dataProviderproce = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawproce,
            'pagination' => [
                'pageSize' => 20
            ],
        ]); 
##########################################################################
	//****วัคซีน Vaccin*************************
        $sqlvac = "SELECT e.HOSPCODE, e.DATE_SERV, e.SEQ ,e.PID, e.VACCINETYPE, e.VACCINEPLACE ,v.name_english, v.name_thai, v.category, v.Diag
						FROM epi e
						LEFT JOIN l_epi v ON v.vaccine = e.VACCINETYPE
						WHERE e.SEQ = '$seq'
						AND e.pid = '$pid'
						AND e.HOSPCODE = '$hospcode'";
        $rawvac = $connection->createCommand($sqlvac)->queryAll();
       
        $dataProvidervac = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawvac,
            'pagination' => [
                'pageSize' => 20
            ],
        ]); 

##################################################################
   /* //LAB
        $sqll = "SELECT l.labtest, t.labtest AS tlname,labresult
                    FROM  labfu l
                    LEFT JOIN clabtest t ON t.icd10_tm = l.labtest
                    WHERE  cid ='$cid'
                    and pid='$pid' AND hospcode = '$hospcode' ";*/
		
        //LAB
        $sqll = " SELECT  l.HOSPCODE,
		  l.regdate as DATE_SERV,
        	l.time_serv AS TIME_SERV, 
        	l.LAB_NAME, 
       	 	l.LAB_RESULT 
        	FROM lab_ehr l
        	WHERE   l.visit_id ='$seq'";
        $rawlm = $connection->createCommand($sqll)
        ->queryAll();
        $rawl = $connection->createCommand($sqll)
                ->queryAll();
        
        $dataProviderl = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawl,
            'pagination' => [
                'pageSize' => 20
            ],
        ]); 
##########################################################################
        //****************DRUG ยา*************************************
        $sqldr = "SELECT d.dname AS DNAME, d.USAGE_LINE1, d.USAGE_LINE2,d.AMOUNT
                FROM drug_opd d 
                WHERE   -- d.cid = '$cid'
                   d.HOSPCODE ='$hospcode' AND d.seq ='$seq' 
                 UNION ALL
                 SELECT d.dname,d.USAGE_LINE1, d.USAGE_LINE2,d.AMOUNT
                 FROM drug_ipd  d
                 WHERE d.an ='$an'  AND d.hospcode = '$hospcode' ";
        $rawdr = $connection->createCommand($sqldr)
                ->queryAll();

        $dataProviderdr = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawdr,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
 
##########################################################################
##########################################################################
        //****************DO หน้าเว็บไซต์ *************************************
        $sqlweb = "SELECT date(lg.datetime) as regdate ,count(DISTINCT lg.username) as users, count(lg.patient_cid) as cid
		FROM log_ehr lg 
		WHERE lg.datetime BETWEEN CURDATE() AND NOW()";
        $rawweb = $connection->createCommand($sqlweb)
                ->queryAll();

        $dataProviderweb = new ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawweb,
            'pagination' => [
                'pageSize' => 5
            ],
        ]);
 
##########################################################################
        return $this->render('index', ['cid' => $cid, 'tname' => $tname, 'taddr' => $taddr, 'sex' => $sex, 'chronic' => $chronic, 'birth' => $birth,
                    'dataProvider' => $dataProvider,
                    'dataProvideri' => $dataProvideri,
                    'dataProviderl' => $dataProviderl,
                    'ldataProvider' => $ldataProvider,
                    'dataProviderdr'   => $dataProviderdr,
		            'dataProviderproce' =>$dataProviderproce,
					'dataProvidervac' =>$dataProvidervac,
					'dataProviderweb' => $dataProviderweb,
                    'dateserv' => $date_serv,
                    'cc' => $cc,
                    'sbp' => $sbp,
                    'dbp' => $dbp,
                    'pr' => $pr,
                    'rr' => $rr,
                    'btemp' => $btemp,
                    'hospcode' => $hospcode,
                    'hospname' => $hospname,
                    'timeserv' => $timeserv,
                    'allergy'=> $allergy,
                    'hn'=>$hn,
                    'telephone'=>$telephone,
                    
        ]);
    }
	
}
