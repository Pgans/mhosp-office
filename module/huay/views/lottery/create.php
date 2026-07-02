<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\module\huay\models\Sells;
use kartik\editable\Editable;
use app\models\Bon;
use app\models\Belows;

/* @var $this yii\web\View */
/* @var $model app\module\huay\models\Lottery */

//$this->title = 'Create Lottery';
//$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
///$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> Create HUAY</div>
                        <div class="panel-body">
                      
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<div>
<div class="col-md-4">
<div class="panel panel-primary">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>Lists</div>
                        <div class="panel-body">
                        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

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
                [
                'class'=>'kartik\grid\EditableColumn',
                'header'=>'ล่าง',
                'attribute'=>'below_id',
                'value' => function($model) {
                    return $model->below->below_price;
                    },
                ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                    </div>
                 </div>
           </div>
         </div>
    </div>


<div class="col-md-4">
<div class="panel panel-primary">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> ตัดยอดเกิน</div>
                        <div class="panel-body">
 
                    <?php
                                
                                 echo GridView::widget([
                                    'dataProvider' => $copdData,
                                   
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'label' => 'เลข',
                                            'attribute' => 'numbers'
                                        ],
                                        [
                                            'label' => 'ตัวบน',
                                            'attribute' => 'bons'
                                        ],
                                        [
                                            'label' => 'ตัวล่าง',
                                            'attribute' => 'belows'
                                        ],
                                    ],
                                ]);

        ?>
                    </div>
                 </div>
           </div>
<div class="col-md-4">
<div class="panel panel-primary">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> ยอดทั้งหมด</div>
                         <div class="panel-body">
 
                    <?php
                                
                                 echo GridView::widget([
                                    'dataProvider' => $iData,
                                   
                                    'columns' => [
                                        //['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'ตัวบน',
                                            'attribute' => 'bons',
                                            'format'=>'raw',
                                            'value' => function ($model) {
                                             return '<span class="badge" style="background-color:#A020F0">' . $model['bons'] . '</span>';
                                            }

                                        ],
                                        [
                                            'label' => 'ตัวล่าง',
                                            'attribute' => 'belows',
                                            'format'=>'raw',
                                            'value' => function ($model) {
                                             return '<span class="badge" style="background-color:#C71585">' . $model['belows'] . '</span>';
                                            }
                                        ],
                                      [
                                          'label'=>'ยอดรวม',
                                          'attribute'=>'total',
                                          'format'=>'raw',
                                          'value' => function ($model) {
                                             return '<span class="badge" style="background-color:#0000FF">' . $model['total'] . '</span>';
                                            }
                                      ],
                                      [
                                          'label'=>'20%',
                                          'attribute'=>'20%',
                                          'format'=>'raw',
                                          'value' => function ($model) {
                                             return '<span class="badge" style="background-color:#0000FF">' . $model['20%'] . '</span>';
                                            }
                                      ],
                                      [
                                          'label'=>'เหลือ',
                                          'attribute'=>'amount',
                                          'format'=>'raw',
                                          'value' => function ($model) {
                                             return '<span class="badge" style="background-color:#0000FF">' . $model['amount'] . '</span>';
                                            }
                                      ],
                                    ],
                                ]);

?>
                                </div>
                                </div>
                                </div>