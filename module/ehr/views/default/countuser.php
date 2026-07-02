
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Guest';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-3">
            <div class="box box-info">
                <div class="box-heading"><i class="fa fa-calendar-check-o"></i>&nbsp;&nbsp;จำนวนผู้ใช้บริการ</div>
    <?= GridView::widget([
        'dataProvider' => $dataProviderweb,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            [
            'attribute' => 'regdate',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'วันที่'
			],
			[
            'attribute' => 'users',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'ผู้ใช้งาน'
			],
           [
            'attribute' => 'cid',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'คนไข้'
			], 
           
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
			</div>
		</div>
</div>
