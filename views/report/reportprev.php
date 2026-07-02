<?php

use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

$this->title = 'สรุปยอดการจองพาหนะในเดือน' . $month;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rental-view"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-bar-chart"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('กลับ', ['report'], ['class' => 'btn btn-warning']) ?>
            </p>
            <?=
            Highcharts::widget([
                'options' => [
                    'title' => ['text' => 'สรุปยอดการจองพาหนะ'],
                    'xAxis' => [
                        'categories' => ['ทะเบียนรถ']
                    ],
                    'yAxis' => [
                        'title' => ['text' => 'จำนวน(ครั้ง)']
                    ],
                    'series' => $graph,
                ]
            ])
            ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'license',
                        'label' => 'เลขทะเบียน'
                    ],
                    [
                        'attribute' => 'counter',
                        'label' => 'จำนวน(ครั้ง)'
                    ],
                ],
            ])
            ?>   
         
           </div>
    </div>