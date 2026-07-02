<?php

namespace app\controllers;
use yii;
use app\models\RepSearch;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

class RepController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	###########################################
	###แยกดึงข้อมูลรายเดือน ลดการคีย์เลือกREP#####################
	public function actionRepmonth(){
		//$data = Yii::$app->request->post();
       // $rep= Yii::$app->session['REP'];
       // $subfund = isset($data['SUB_FUND']) ? $data['SUB_FUND'] : 'null';
        # $sex = isset($data['sex']) ? $data['sex'] : '1,2';
           // $idsubfund = isset($data['rep1']) ? $data['rep1'] : '';
            //$date2 = isset($data['date2']) ? $data['date2'] : '';
        
        $sql = "
			SELECT k.SUB_FUND,COUNT(k.pid) as AMOUNT
			FROM 
			(SELECT DISTINCT r.REP, r.HN, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
			REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT, r.NOTEDATE
			FROM m_registerdata r
			INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id  
			WHERE r.notedate BETWEEN '25630901' AND '25630931' 
			AND r.maininscl = 'UCS') as k
			GROUP BY k.SUB_FUND  ORDER BY amount DESC       
         ";
        $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
       } catch (\yii\db\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('repmonth', [
                  // 'searchModel' => $searchModel,
                   'dataProvider' => $dataProvider,
                  // 'sql'=>$sql,
                   //'subfund'=>$subfund,
                
       ]);   
   }
		
	
	###########################################
    public function actionRep1(){

        $data = Yii::$app->request->post();
        $rep= Yii::$app->session['REP'];
        $subfund = isset($data['SUB_FUND']) ? $data['SUB_FUND'] : 'null';
        # $sex = isset($data['sex']) ? $data['sex'] : '1,2';

            $rep1 = isset($data['rep1']) ? $data['rep1'] : '';
            $rep2 = isset($data['rep2']) ? $data['rep2'] : '';
            $rep3 = isset($data['rep3']) ? $data['rep3'] : '';
            $rep4 = isset($data['rep4']) ? $data['rep4'] : '';
            $rep5 = isset($data['rep5']) ? $data['rep5'] : '';
            $rep6 = isset($data['rep6']) ? $data['rep6'] : '';
            $rep7 = isset($data['rep7']) ? $data['rep7'] : '';
            $rep8 = isset($data['rep8']) ? $data['rep8'] : '';
            $rep9 = isset($data['rep9']) ? $data['rep9'] : '';
           // $idsubfund = isset($data['rep1']) ? $data['rep1'] : '';
            //$date2 = isset($data['date2']) ? $data['date2'] : '';
        
        $sql = "SELECT k.rep , k.sub_fund, count(k.rep) as amount
        FROM (
        SELECT DISTINCT r.REP, r.HN, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
                REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
                FROM m_registerdata r
                INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id  
                WHERE (r.REP = '$rep1' OR r.REP = '$rep2' OR r.REP = '$rep3' OR r.REP = '$rep3'OR r.REP = '$rep4'
                OR r.REP = '$rep5'OR r.REP = '$rep6' OR r.REP = '$rep7' OR r.REP = '$rep8' OR r.REP = '$rep9' ) ) as k
        GROUP BY k.sub_fund
                
         ";
        $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
       } catch (\yii\db\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       Yii::$app->session['rep1']=$rep1;
       Yii::$app->session['rep2']=$rep2;
       Yii::$app->session['rep3']=$rep3;
       Yii::$app->session['rep4']=$rep4;
       Yii::$app->session['rep5']=$rep5;
       Yii::$app->session['rep6']=$rep6;
       Yii::$app->session['rep7']=$rep7;
       Yii::$app->session['rep8']=$rep8;
       Yii::$app->session['rep9']=$rep9;
       Yii::$app->session['subfund']=$subfund;
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    
       return $this->render('rep1', [
                  // 'searchModel' => $searchModel,
                   'dataProvider' => $dataProvider,
                   'sql'=>$sql,
                   'rep1'=>$rep1,
                   'rep2'=>$rep2,
                   'rep3'=>$rep3,   
                   'rep4'=>$rep4,   
                   'rep5'=>$rep5,   
                   'rep6'=>$rep6,   
                   'rep7'=>$rep7,   
                   'rep8'=>$rep8,                  
                   'rep9'=>$rep9,
                   'subfund'=>$subfund,
                
       ]);   
   }
	 public function actionRep(){

        $data = Yii::$app->request->post();
        $rep= Yii::$app->session['REP'];
        $subfund = isset($data['SUB_FUND']) ? $data['SUB_FUND'] : 'null';
        # $sex = isset($data['sex']) ? $data['sex'] : '1,2';

            $rep1 = isset($data['rep1']) ? $data['rep1'] : '';
            $rep2 = isset($data['rep2']) ? $data['rep2'] : '';
            $rep3 = isset($data['rep3']) ? $data['rep3'] : '';
            $rep4 = isset($data['rep4']) ? $data['rep4'] : '';
            $rep5 = isset($data['rep5']) ? $data['rep5'] : '';
            $rep6 = isset($data['rep6']) ? $data['rep6'] : '';
            $rep7 = isset($data['rep7']) ? $data['rep7'] : '';
            $rep8 = isset($data['rep8']) ? $data['rep8'] : '';
            $rep9 = isset($data['rep9']) ? $data['rep9'] : '';
           // $idsubfund = isset($data['rep1']) ? $data['rep1'] : '';
            //$date2 = isset($data['date2']) ? $data['date2'] : '';
        
        $sql = "SELECT DISTINCT r.REP, r.HN, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
        REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
        FROM m_registerdata r
        INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id  AND s.SUB_FUND = '$subfund'
        WHERE (r.REP = '$rep1' OR r.REP = '$rep2' OR r.REP = '$rep3' OR r.REP = '$rep3'OR r.REP = '$rep4'
        OR r.REP = '$rep5'OR r.REP = '$rep6' OR r.REP = '$rep7' OR r.REP = '$rep8' OR r.REP = '$rep9' ) 
         ";
        $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
       try {
           $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
       } catch (\yii\db\Exception $e) {
           throw new \yii\web\ConflictHttpException('sql error');
       }
       Yii::$app->session['rep1']=$rep1;
       Yii::$app->session['rep2']=$rep2;
       Yii::$app->session['rep3']=$rep3;
       Yii::$app->session['rep4']=$rep4;
       Yii::$app->session['rep5']=$rep5;
       Yii::$app->session['rep6']=$rep6;
       Yii::$app->session['rep7']=$rep7;
       Yii::$app->session['rep8']=$rep8;
       Yii::$app->session['rep9']=$rep9;
       Yii::$app->session['subfund']=$subfund;
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $rawData,
           'pagination' => [
            'pageSize' => 200,
            ],
       ]);
    //    $dataProvider->sort->attributes['DATEADM'] = [
    //     'asc' => ['DATEADM' => SORT_ASC],
    //     'desc'=>['DATEADM' => SORT_DESC],
    //     //'label' => 'วันมารับบริการ'
    // ];
    // $dataProvider->sort->attributes['HN'] = [
    //     'asc' => ['HN' => SORT_ASC],
    //     'desc'=>['HN' => SORT_DESC],
    //     //'label' => 'วันมารับบริการ'
    // ];
       return $this->render('rep', [
                  // 'searchModel' => $searchModel,
                   'dataProvider' => $dataProvider,
                   'subfund'=>$subfund,
                   'sql'=>$sql,
                   'rep1'=>$rep1,
                   'rep2'=>$rep2,
                   'rep3'=>$rep3,   
                   'rep4'=>$rep4,   
                   'rep5'=>$rep5,   
                   'rep6'=>$rep6,   
                   'rep7'=>$rep7,   
                   'rep8'=>$rep8,                  
                   'rep9'=>$rep9,
                   'subfund'=>$subfund,


       ]);   
   }
   public function actionRep_list($subfund){
        $rep1 = Yii::$app->session['rep1'];
        $rep2 = Yii::$app->session['rep2'];
        $rep3 = Yii::$app->session['rep3'];
        $rep4 = Yii::$app->session['rep4'];
        $rep5 = Yii::$app->session['rep5'];
        $rep6 = Yii::$app->session['rep6'];
        $rep7 = Yii::$app->session['rep7'];
        $rep8 = Yii::$app->session['rep8'];
        $rep9 = Yii::$app->session['rep9'];
       
    $sql = "SELECT DISTINCT r.REP, r.HN,r.TRAN_ID, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
    REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
    FROM m_registerdata r
    INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id  AND s.SUB_FUND = '$subfund'
    WHERE (r.REP = '$rep1' OR r.REP = '$rep2' OR r.REP = '$rep3' OR r.REP = '$rep3'OR r.REP = '$rep4'
    OR r.REP = '$rep5'OR r.REP = '$rep6' OR r.REP = '$rep7' OR r.REP = '$rep8' OR r.REP = '$rep9' ) 
     ";
    $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
   try {
       $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
   } catch (\yii\db\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   Yii::$app->session['rep1']=$rep1;
   Yii::$app->session['rep2']=$rep2;
   Yii::$app->session['rep3']=$rep3;
   Yii::$app->session['rep4']=$rep4;
   Yii::$app->session['rep5']=$rep5;
   Yii::$app->session['rep6']=$rep6;
   Yii::$app->session['rep7']=$rep7;
   Yii::$app->session['rep8']=$rep8;
   Yii::$app->session['rep9']=$rep9;
   Yii::$app->session['subfund']=$subfund;
   Yii::$app->session['TRAN_ID']=$tran_id;
   
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => [
        'pageSize' => 200,
        ],
   ]);
   return $this->render('rep_list', [
              // 'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'subfund'=>$subfund,
               'sql'=>$sql,
               'rep1'=>$rep1,
               'rep2'=>$rep2,
               'rep3'=>$rep3,   
               'rep4'=>$rep4,   
               'rep5'=>$rep5,   
               'rep6'=>$rep6,   
               'rep7'=>$rep7,   
               'rep8'=>$rep8,                  
               'rep9'=>$rep9,
			   'tranid' => $tranid,
              

   ]);   
}
   public function actionRep2(){
    $rep1= Yii::$app->session['rep1'];
    $rep2= Yii::$app->session['rep2'];
    $rep3= Yii::$app->session['rep3'];
    $rep4= Yii::$app->session['rep4'];
    $rep5= Yii::$app->session['rep5'];
    $rep6= Yii::$app->session['rep6'];
    $rep7= Yii::$app->session['rep7'];
    $rep8= Yii::$app->session['rep8'];
    $rep9= Yii::$app->session['rep9'];
    //$subfund= Yii::$app->session['subfund'];
    $sql = "SELECT r.REP, r.HN, r.TRAN_ID,r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
    REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM
    FROM m_registerdata r
    WHERE (r.REP = '$rep1' OR r.REP = '$rep2' OR r.REP = '$rep3' OR r.REP = '$rep3'OR r.REP = '$rep4'
    OR r.REP = '$rep5'OR r.REP = '$rep6' OR r.REP = '$rep7' OR r.REP = '$rep8' OR r.REP = '$rep9'
	AND r.TRAN_ID ='$tranid'
	) ";
    $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
   try {
       $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
   } catch (\yii\db\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => [
        'pageSize' => 600,
        ],
   ]);
//    $dataProvider->sort->attributes['DATEADM'] = [
//     'asc' => ['DATEADM' => SORT_ASC],
//     'desc'=>['DATEADM' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
// $dataProvider->sort->attributes['HN'] = [
//     'asc' => ['HN' => SORT_ASC],
//     'desc'=>['HN' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
// $dataProvider->sort->attributes['SUB_FUND'] = [
//     'asc' => ['SUB_FUND' => SORT_ASC],
//     'desc'=>['SUB_FUND' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
   return $this->render('rep2', [
              // 'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
                          
   ]);   
}
public function actionRep3(){
    $rep1= Yii::$app->session['rep1'];
    $rep2= Yii::$app->session['rep2'];
    $rep3= Yii::$app->session['rep3'];
    $rep4= Yii::$app->session['rep4'];
    $rep5= Yii::$app->session['rep5'];
    $rep6= Yii::$app->session['rep6'];
    $rep7= Yii::$app->session['rep7'];
    $rep8= Yii::$app->session['rep8'];
    $rep9= Yii::$app->session['rep9'];
    $subfund= Yii::$app->session['subfund'];
    $sql = "SELECT DISTINCT r.REP, r.HN, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
    REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
    FROM m_registerdata r
    INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id AND s.SUB_FUND = '$subfund'
    WHERE (r.REP = '$rep1' OR r.REP = '$rep2' OR r.REP = '$rep3' OR r.REP = '$rep3'OR r.REP = '$rep4'
    OR r.REP = '$rep5'OR r.REP = '$rep6' OR r.REP = '$rep7' OR r.REP = '$rep8' OR r.REP = '$rep9') ";
    $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
   try {
       $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
   } catch (\yii\db1\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => [
        'pageSize' => 200,
        ],
   ]);
//    $dataProvider->sort->attributes['DATEADM'] = [
//     'asc' => ['DATEADM' => SORT_ASC],
//     'desc'=>['DATEADM' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
// $dataProvider->sort->attributes['HN'] = [
//     'asc' => ['HN' => SORT_ASC],
//     'desc'=>['HN' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
// $dataProvider->sort->attributes['SUB_FUND'] = [
//     'asc' => ['SUB_FUND' => SORT_ASC],
//     'desc'=>['SUB_FUND' => SORT_DESC],
//     //'label' => 'วันมารับบริการ'
// ];
   return $this->render('rep3', [
              // 'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
                          
   ]);   
}
public function actionIpcs() {
    $sql = "
    SELECT DISTINCT m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
    s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT, m.DRG_NHSO,
    m.ADJRW_NHSO, m.DRG_NHSO
    FROM m_registerdata m
    INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.SUB_FUND NOT IN ('OP_HMAIN' , 'OPAE-DRUG')
    WHERE m.DATEADM BETWEEN '25630101' AND '25630131' AND m.ADJRW_NHSO <> ''
    ";
    $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
try {
   $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
} catch (\yii\db1\Exception $e) {
   throw new \yii\web\ConflictHttpException('sql error');
}
$dataProvider = new \yii\data\ArrayDataProvider([
   'allModels' => $rawData,
   'pagination' => [
    'pageSize' => 200,
    ],
]);
    return $this->render('ipcs', [
       // 'searchModel'=> $searchModel,
        'dataProvider'=> $dataProvider,
        'sql'=> $sql,

    ]);
}
public function actionAdjrw(){
    $sql = "      
SELECT k.HCODE, k.INSCL_NAME,k.MAININSCL,k.SUB_FUND, COUNT(k.sub_fund) AS AMOUNT ,SUM(k.ADJRW_NHSO) AS ADJRW
FROM (
SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME
FROM m_registerdata m
INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.SUB_FUND NOT IN ('OP_HMAIN' , 'OPAE-DRUG')
INNER JOIN l_inscl i ON i.MAININSCL = m.MAININSCL
WHERE m.DATEADM BETWEEN '25631001' AND '25640930' 
#AND m.ADJRW_NHSO <> '' 
)as k
GROUP BY k.SUB_FUND
            ";
            $rawData = \yii::$app->db1->createCommand($sql)->queryAll();
            try {
                $rawData = \Yii::$app->db1->createCommand($sql)->queryAll();
            } catch (\yii\db1\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }
            $dataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData,
                'pagination' => [
                 'pageSize' => 200,
                 ],
            ]);
                 return $this->render('adjrw_nhso', [
                   
                     'dataProvider'=> $dataProvider,
                     'sql'=> $sql,
         
                 ]);
             }
             public function actionTrimas(){
                 $connnection = \yii::$app->db1;
                 $trimas1 = $connnection->createCommand(" 
        SELECT DISTINCT  i.INSCL_NAME, s.SUB_FUND,m.MAININSCL,COUNT(s.SUB_FUND) AS AMOUNT, SUM(m.ADJRW_NHSO ) AS ADJRW
        FROM m_registerdata m
        INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.SUB_FUND NOT IN ('OP_HMAIN' , 'OPAE-DRUG')
        INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
        WHERE m.DATEADM BETWEEN '25621001' AND '25630131' AND m.ADJRW_NHSO <> ''
        GROUP BY s.SUB_FUND
                 ")->queryAll(); 
                 $t1dataProvider = new ArrayDataProvider([
                     'allModels'=> $trimas1,
                     'sort'=>[
                        'attributes'=>['NAME','AMOUNT','ADJRW']
                    ],
                ]);
            //เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
            for($i=0;$i<sizeof($trimas1);$i++){
                $name[] = $trimas1[$i]['INSCL_NAME'];        
                $amount[] =intval($trimas1[$i]['AMOUNT']);
                $adjrw[] = (int) $trimas1[$i]['ADJRW']*1;
               # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
            }
     ############################################################# 
     $connnection = \yii::$app->db1;
     $trimas2 = $connnection->createCommand(" 
    SELECT DISTINCT  i.INSCL_NAME, s.SUB_FUND,m.MAININSCL,COUNT(s.SUB_FUND) AS AMOUNT, SUM(m.ADJRW_NHSO ) AS ADJRW
        FROM m_registerdata m
        INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.SUB_FUND NOT IN ('OP_HMAIN' , 'OPAE-DRUG')
        INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
        WHERE m.DATEADM BETWEEN '25630201' AND '25630531' AND m.ADJRW_NHSO <> ''
        GROUP BY s.SUB_FUND
     ")->queryAll(); 
     $t2dataProvider = new ArrayDataProvider([
         'allModels'=> $trimas2,
         'sort'=>[
            'attributes'=>['NAME','AMOUNT','ADJRW']
        ],
    ]);
//เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
for($i=0;$i<sizeof($trimas2);$i++){
    $name2[] = $trimas2[$i]['NAME'];        
    $amount2[] =intval($trimas2[$i]['AMOUNT']);
    $adjrw2[] = (int) $trimas2[$i]['ADJRW']*1;
   # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
}
     ############################################################# 
     $connnection = \yii::$app->db1;
     $trimas3 = $connnection->createCommand(" 
    SELECT DISTINCT  i.INSCL_NAME, s.SUB_FUND,m.MAININSCL,COUNT(s.SUB_FUND) AS AMOUNT, SUM(m.ADJRW_NHSO ) AS ADJRW
    FROM m_registerdata m
    INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.SUB_FUND NOT IN ('OP_HMAIN' , 'OPAE-DRUG')
    INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
    WHERE m.DATEADM BETWEEN '25630601' AND '25630930' AND m.ADJRW_NHSO <> ''
    GROUP BY s.SUB_FUND
     ")->queryAll(); 
     $t3dataProvider = new ArrayDataProvider([
         'allModels'=> $trimas3,
         'sort'=>[
            'attributes'=>['NAME','AMOUNT','ADJRW']
        ],
    ]);
//เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
for($i=0;$i<sizeof($trimas3);$i++){
    $name3[] = $trimas3[$i]['NAME'];        
    $amount3[] =intval($trimas3[$i]['AMOUNT']);
    $adjrw3[] = (int) $trimas3[$i]['ADJRW']*1;
   # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
}
     ############################################################# 
       
    
     ############################################################# 
       $connnection = \yii::$app->db1;
       $fiscal = $connnection->createCommand(" 
      SELECT DISTINCT  i.INSCL_NAME, s.SUB_FUND,m.MAININSCL,COUNT(s.SUB_FUND) AS AMOUNT, SUM(m.ADJRW_NHSO ) AS ADJRW
      FROM m_registerdata m
      INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
      INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
      WHERE m.DATEADM BETWEEN '25630601' AND '25630930' AND m.ADJRW_NHSO <> ''
      GROUP BY s.SUB_FUND
       ")->queryAll(); 
       $t3dataProvider = new ArrayDataProvider([
           'allModels'=> $fiscal,
           'sort'=>[
              'attributes'=>['NAME','AMOUNT','ADJRW']
          ],
      ]);
  //เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
  for($i=0;$i<sizeof($fiscal);$i++){
      $name3[] = $fiscal[$i]['NAME'];        
      $amount3[] =intval($fiscal[$i]['AMOUNT']);
      $adjrw3[] = (int) $fiscal[$i]['ADJRW']*1;
     # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
  }
       #############################################################           
                $sql1 = "      
        SELECT k.HCODE, k.INSCL_NAME,k.MAININSCL,k.SUB_FUND, COUNT(k.sub_fund) AS AMOUNT ,SUM(k.ADJRW_NHSO) AS ADJRW
        FROM (
        SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
        s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
        m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME
        FROM m_registerdata m
        INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
        INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
        WHERE m.DATEADM BETWEEN '25621001' AND '25630131' AND m.ADJRW_NHSO <> '')as k
        GROUP BY k.SUB_FUND
                        ";
                        $rawData1 = \yii::$app->db1->createCommand($sql1)->queryAll();
                        try {
                            $rawData1 = \Yii::$app->db1->createCommand($sql1)->queryAll();
                        } catch (\yii\db1\Exception $e) {
                            throw new \yii\web\ConflictHttpException('sql error');
                        }
                        $dataProvider1 = new \yii\data\ArrayDataProvider([
                            'allModels' => $rawData1,
                            'pagination' => [
                             'pageSize' => 200,
                             ],
                        ]);                

    $sql2 = "      
        SELECT k.HCODE, k.INSCL_NAME,k.MAININSCL,k.SUB_FUND, COUNT(k.sub_fund) AS AMOUNT ,SUM(k.ADJRW_NHSO) AS ADJRW
        FROM (
        SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
        s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
        m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME
        FROM m_registerdata m
        INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
        INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
        WHERE m.DATEADM BETWEEN '25630201' AND '25630631' AND m.ADJRW_NHSO <> '' )as k
        GROUP BY k.SUB_FUND
                        ";
            $rawData2 = \yii::$app->db1->createCommand($sql2)->queryAll();
            try {
                $rawData2 = \Yii::$app->db1->createCommand($sql2)->queryAll();
            } catch (\yii\db1\Exception $e) {
                throw new \yii\web\ConflictHttpException('sql error');
            }
            $dataProvider2 = new \yii\data\ArrayDataProvider([
                'allModels' => $rawData2,
                'pagination' => [
                 'pageSize' => 200,
                 ],
            ]);
                
                $sql3 = "      
        SELECT k.HCODE, k.INSCL_NAME,k.MAININSCL,k.SUB_FUND, COUNT(k.sub_fund) AS AMOUNT ,SUM(k.ADJRW_NHSO) AS ADJRW
        FROM (
        SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
        s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
        m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME
        FROM m_registerdata m
        INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
        INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
        WHERE m.DATEADM BETWEEN '25630601' AND '25630930' AND m.ADJRW_NHSO <> '')as k
        GROUP BY k.SUB_FUND
                        ";
                        $rawData3 = \yii::$app->db1->createCommand($sql3)->queryAll();
                        try {
                            $rawData3 = \Yii::$app->db1->createCommand($sql3)->queryAll();
                        } catch (\yii\db1\Exception $e) {
                            throw new \yii\web\ConflictHttpException('sql error');
                        }
                        $dataProvider3 = new \yii\data\ArrayDataProvider([
                            'allModels' => $rawData3,
                            'pagination' => [
                             'pageSize' => 200,
                             ],
                        ]);
                             return $this->render('trimas', [
                                 //'searchModel'=> $searchModel,
                                 'dataProvider1'=> $dataProvider1,
                                 'dataProvider2'=> $dataProvider2,
                                 'dataProvider3'=> $dataProvider3,
                                 't1dataProvider'=> $t1dataProvider,
                                 't2dataProvider'=> $t2dataProvider,
                                 't3dataProvider'=> $t3dataProvider,
                       
                                 'sql1'=> $sql1,
                                 'name'=> $name ,
                                 'amount'=>$amount ,
                                 'adjrw'=>$adjrw ,
                                 'name2'=> $name2 ,
                                 'amount2'=>$amount2 ,
                                 'adjrw2'=>$adjrw2,
                                 'name3'=> $name3 ,
                                 'amount3'=>$amount3 ,
                                 'adjrw3'=>$adjrw3 ,
                                 
                             ]);
                         }

    public function actionFiscal(){
        $connnection = \yii::$app->db1;
        $fiscal1 = $connnection->createCommand(" 
        SELECT k.FISCAL, k.INSCL_NAME , COUNT(k.sub_fund) as AMOUNT, SUM(k.adjrw_nhso) as ADJRW, GROUP_CONCAT(DISTINCT k.sub_fund) as SUBFUND
        FROM (
        SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
                    s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
                    m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME, IF(MONTH(m.dateadm) >9,YEAR(m.dateadm),YEAR(m.dateadm)) AS FISCAL
                    FROM m_registerdata m
                    INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
                    INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
                    WHERE m.DATEADM BETWEEN '25601001' AND '25640930'			
                  ) as k 
                GROUP BY k.FISCAL
                ")->queryAll(); 
        $f1dataProvider = new ArrayDataProvider([
            'allModels'=> $fiscal1,
            'sort'=>[
               'attributes'=>['FISCAL','INSCL_NAME','AMOUNT','ADJRW']
           ],
       ]);
   //เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
   for($i=0;$i<sizeof($fiscal1);$i++){
       $fiscal4[] = $fiscal1[$i]['FISCAL']*1;
       //$name4[] = $fiscal1[$i]['NAME'];        
       $amount4[] =intval($fiscal1[$i]['AMOUNT']);
       $adjrw4[] = intval($fiscal1[$i]['ADJRW']);
       //$adjrw4[] = (int) $fiscal1[$i]['ADJRW'];
      # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
   }
      #############################################################  
         $connnection = \yii::$app->db1;
          $fiscal2 = $connnection->createCommand(" 
          SELECT k.FISCAL, k.INSCL_NAME ,k.SUB_FUND, COUNT(k.sub_fund) as AMOUNT, SUM(k.adjrw_nhso) as ADJRW
          FROM (
          SELECT DISTINCT m.HCODE,m.DATEADM, m.PID, m.HN, CONCAT(SUBSTR(m.TITLES,2),'',m.FNAME,' ',m.LNAME) AS FULLNAME , m.MAININSCL,
                      s.SUB_FUND, m.REP, REPLACE(m.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM, s.TOTL_AMT,
                      m.ADJRW_NHSO, m.DRG_NHSO, i.INSCL_NAME, IF(MONTH(m.dateadm) >9,YEAR(m.dateadm),YEAR(m.dateadm)) AS FISCAL
                      FROM m_registerdata m
                      INNER JOIN m_sum_subfund s ON s.tran_id = m.tran_id AND s.ITEM_SOURCE = 'M_REGISTER'
                      INNER JOIN l_inscl2 i ON i.MAININSCL = m.MAININSCL
                      WHERE m.DATEADM BETWEEN '25601001' AND '25640930'			
           ) as k 
          GROUP BY k.FISCAL, k.SUB_FUND
          ")->queryAll(); 
          $f2dataProvider = new ArrayDataProvider([
              'allModels'=> $fiscal2,
              'sort'=>[
                 'attributes'=>['FISCAL','INSCL_NAME','AMOUNT','ADJRW']
             ],
         ]);
     //เตรียมข้อมูลส่งให้กราฟ ไตรมาส1
     for($i=0;$i<sizeof($fiscal2);$i++){
         $fiscal5[] = $fiscal2[$i]['FISCAL'];
         $name5[] = $fiscal2[$i]['SUM_FUND'];        
         $amount5[] =intval($fiscal2[$i]['AMOUNT']);
         $adjrw5[] = (int) $fiscal2[$i]['ADJRW']*1;
        # $cnt[] = $d['cnt']*1;//นำไปคูณ 1 ป้องกันเป็น string
     }
    ##################################################################
                    return $this->render('fiscal', [
                       // 'searchModel'=> $searchModel,
                        'f1dataProvider'=> $f1dataProvider,
                        'f2dataProvider'=> $f2dataProvider,
                        'sql1'=> $sql1,
                        'fiscal4'=> $fiscal4,
                        'name4'=> $name4 ,
                        'amount4'=>$amount4 ,
                        'adjrw4'=>$adjrw4 ,
                        'fiscal5'=> $fiscal5,
                        'name5'=> $name5 ,
                        'amount5'=>$amount5 ,
                        'adjrw5'=>$adjrw5 ,
    ]);
}
    
}
