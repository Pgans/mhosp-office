<?php

namespace app\controllers;
use yii;
use app\models\RepSearch;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

class RepmonthController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	###########################################
	###แยกดึงข้อมูลรายเดือน ลดการคีย์เลือกREP#####################
	public function actionRepmonth(){
		//$data = Yii::$app->request->post();
        //$rep= Yii::$app->session['REP'];
        $subfund = isset($data['SUB_FUND']) ? $data['SUB_FUND'] : 'null';
		$rep = isset($data['REP']) ? $data['REP'] : 'null';
        # $sex = isset($data['sex']) ? $data['sex'] : '1,2';
           // $idsubfund = isset($data['rep1']) ? $data['rep1'] : '';
            //$date2 = isset($data['date2']) ? $data['date2'] : '';
        
        $sql = "
			SELECT k.rep , k.sub_fund, count(k.rep) as amount
FROM (
SELECT DISTINCT r.REP, r.HN ,r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
FROM m_registerdata r
INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id 
WHERE r.notedate BETWEEN '25630901' AND '25630931' )  as k
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
                   'sql'=>$sql,
                   'subfund'=>$subfund,
				   'rep'=>$rep,
                
       ]);   
   }
     public function actionRepmonth_list($subfund){
    
    $sql = "SELECT DISTINCT r.REP, r.HN,r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
    REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM,s.SUB_FUND,s.TOTL_AMT, s.ACT_AMT
    FROM m_registerdata r
    INNER JOIN m_sum_subfund s ON r.tran_id = s.tran_id  AND s.SUB_FUND = '$subfund'
    WHERE r.notedate BETWEEN '25630901' AND '25630931' 
     ";
    $rawData = \yii::$app->db->createCommand($sql)->queryAll();
   try {
       $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
   } catch (\yii\db2\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
 
   Yii::$app->session['subfund']=$subfund;
   
   $dataProvider = new \yii\data\ArrayDataProvider([
       'allModels' => $rawData,
       'pagination' => [
        'pageSize' => 200,
        ],
   ]);
   return $this->render('repmonth_list', [
              // 'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'subfund'=>$subfund,
             
   ]);   
	}
	/*public function actionRep2($rep){
   // $rep= Yii::$app->session['REP'];
  
    $sql = "SELECT r.REP, r.HN, r.PID, r.DATEADM ,CONCAT(SUBSTR(r.TITLES,2),'',r.FNAME,' ',r.LNAME) AS FULLNAME, r.MAININSCL,
    REPLACE(r.SUMS_SERVICEITEM,',','') as SUMS_SERVICEITEM
    FROM m_registerdata r
	WHERE r.rep = '$rep'
	";
    $rawData = \yii::$app->db->createCommand($sql)->queryAll();
   try {
       $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
   } catch (\yii\db2\Exception $e) {
       throw new \yii\web\ConflictHttpException('sql error');
   }
   Yii::$app->session['rep']=$rep;
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
   return $this->render('rep2', [
              // 'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'sql'=>$sql,
               //'rep'=>$rep,
   ]);   
}*/
}