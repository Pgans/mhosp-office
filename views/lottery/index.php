<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\editable\Editable;
use app\models\Bon;
use app\models\Belows;


/* @var $this yii\web\View */
/* @var $searchModel app\models\LotterySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lotteries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lottery', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'class' => 'kartik\grid\EditableColumn',
        'responsive'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lottery_id',
            'numbers',
            [
                'class' => 'kartik\grid\EditableColumn',
                'header' => 'บน',
                'attribute' => 'bon_id',
                'value' => function($model) {
                return $model->bon->bon_price;
                },
                ],
            
            'below.below_name',
            //'d_update',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
