<?php

namespace app\module\huay\controllers;

use Yii;
use app\module\huay\models\Lottery;
use app\module\huay\models\LotterySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\Json; 


/**
 * LotteryController implements the CRUD actions for Lottery model.
 */
class LotteryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lottery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotterySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['lottery_id' => SORT_DESC];

        if(Yii::$app->request->post('hasEditable'))
        {
        $_id=$_POST['editableKey'];
        $model = $this->findModel($_id);
        $post = [];
        $posted = current($_POST['Lottery']);
        $post['Lottery'] = $posted;
        if ($model->load($post)) {
        $model->save();
        if (isset($posted['bon_id']))
        {
        $output = $model->bon->bon_price;
        }
        $out = Json::encode(['output'=>$output, 'message'=>'']);

        }
        echo $out;
        return ;
        }

        if(Yii::$app->request->post('hasEditable'))
        {
        $_id=$_POST['editableKey'];
        $model = $this->findModel($_id);
        $post = [];
        $posted = current($_POST['Lottery']);
        $post['Lottery'] = $posted;
        if ($model->load($post)) {
        $model->save();
        if (isset($posted['below_id']))
        {
        $output = $model->below->below_price;
        }
        $out = Json::encode(['output'=>$output, 'message'=>'']);

        }
        echo $out;
        return ;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lottery model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lottery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lottery();
        $model->bon_id ='2';
        $model->below_id = '2';

        $searchModel = new LotterySearch();
        $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider1->sort->defaultOrder = ['lottery_id' => SORT_DESC];
       // $dataProvider2->pagination->pageSize=300;
       if(Yii::$app->request->post('hasEditable'))
        {
        $_id=$_POST['editableKey'];
        $model = $this->findModel($_id);
        $post = [];
        $posted = current($_POST['Lottery']);
        $post['Lottery'] = $posted;
        if ($model->load($post)) {
        $model->save();
        if (isset($posted['bon_id']))
        {
        $output = $model->bon->bon_price;
        }
        $out = Json::encode(['output'=>$output, 'message'=>'']);

        }
        echo $out;
        return ;
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create', 'id' => $model->lottery_id]);
        }
        //ตัดเลขที่เกิน30
     $connection = \Yii::$app->db;
     $datao = $connection->createCommand("
     SELECT a.numbers,sum(b.bon_price)as bons, SUM(c.below_price) as belows
             FROM lottery_bills a
             INNER JOIN bon b ON a.bon_id = b.bon_id
             INNER JOIN belows c ON a.below_id = c.below_id
             GROUP BY a.numbers
             HAVING SUM(b.bon_price) >=20
     ")->queryAll();

     $odataProvider = new ArrayDataProvider([
         'allModels' => $datao,
     ]);

     //ยอดรวม
  $connection = \Yii::$app->db;
     $datai = $connection->createCommand("
     SELECT 'รวม' as ยอด,sum(b.bon_price)as bons, SUM(c.below_price) as belows, SUM(b.bon_price+c.below_price) as total, 
SUM(b.bon_price+c.below_price)*0.20 as '20%',SUM(b.bon_price+c.below_price)- SUM(b.bon_price+c.below_price)*0.20 as 'amount'
FROM lottery_bills a
INNER JOIN bon b ON a.bon_id = b.bon_id
INNER JOIN belows c ON a.below_id = c.below_id

     ")->queryAll();

     $idataProvider = new ArrayDataProvider([
         'allModels' => $datai,
     ]);

      //ป้า
  $connection = \Yii::$app->db;
     $datap = $connection->createCommand("
     SELECT d.sell_name,sum(b.bon_price)as bons, SUM(c.below_price) as belows, SUM(b.bon_price+c.below_price) as total, 
SUM(b.bon_price+c.below_price)*0.20 as '20%',SUM(b.bon_price+c.below_price)- SUM(b.bon_price+c.below_price)*0.20 as 'amount'
FROM lottery_bills a
INNER JOIN bon b ON a.bon_id = b.bon_id
INNER JOIN belows c ON a.below_id = c.below_id
INNER JOIN sells d ON a.sell_id = d.sell_id AND a.sell_id= 1
     ")->queryAll();

     $padataProvider = new ArrayDataProvider([
         'allModels' => $datap,
     ]);

 //ตี๋
  $connection = \Yii::$app->db;
     $datate = $connection->createCommand("
     SELECT d.sell_name,sum(b.bon_price)as bons, SUM(c.below_price) as belows, SUM(b.bon_price+c.below_price) as total, 
SUM(b.bon_price+c.below_price)*0.20 as '20%',SUM(b.bon_price+c.below_price)- SUM(b.bon_price+c.below_price)*0.20 as 'amount'
FROM lottery_bills a
INNER JOIN bon b ON a.bon_id = b.bon_id
INNER JOIN belows c ON a.below_id = c.below_id
INNER JOIN sells d ON a.sell_id = d.sell_id AND a.sell_id= 2
     ")->queryAll();

        return $this->render('create', [
             'model' => $model,
             'dataProvider' => $dataProvider1,
             'copdData' => $odataProvider,
             'iData' => $idataProvider,
            'paData' => $padataProvider,
            'teData' => $tdataProvider,
        ]);
    }

    /**
     * Updates an existing Lottery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lottery_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lottery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lottery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Lottery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lottery::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
