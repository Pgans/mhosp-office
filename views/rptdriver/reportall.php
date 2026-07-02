<?php

use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

$this->title = 'สรุปงานพนักงานขับรถ ในปีพ.ศ.'.$y;
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
                    'title' => ['text' => 'สรุปงานพนักงานขับรถ'],
                    'xAxis' => [
                        'categories' => [
                            'ม.ค.',
                            'ก.พ.',
                            'มี.ค.',
                            'เม.ย.',
                            'พ.ค.',
                            'มิ.ย.',
                            'ก.ค.',
                            'ส.ค',
                            'ก.ย.',
                            'ต.ค.',
                            'พ.ย.',
                            'ธ.ค.',
                        ]
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
                        'attribute' => 'driver_name',
                        'label' => 'พนักงานขับรถ'
                    ],
                    [
                        'attribute' => 'r1',
                        'label' => 'มกราคม'
                    ],
                    [
                        'attribute' => 'r2',
                        'label' => 'กุมภาพันธ์'
                    ],
                    [
                        'attribute' => 'r3',
                        'label' => 'มีนาคม'
                    ],
                    [
                        'attribute' => 'r4',
                        'label' => 'เมษายน'
                    ],
                    [
                        'attribute' => 'r5',
                        'label' => 'พฤษภาคม'
                    ],
                    [
                        'attribute' => 'r6',
                        'label' => 'มิถุนายน'
                    ],[
                        'attribute' => 'r7',
                        'label' => 'กรกฎาคม'
                    ],
                    [
                        'attribute' => 'r8',
                        'label' => 'สิงหาคม'
                    ],
                    [
                        'attribute' => 'r9',
                        'label' => 'กันยายน'
                    ],
                    [
                        'attribute' => 'r10',
                        'label' => 'ตุลาคม'
                    ],
                    [
                        'attribute' => 'r11',
                        'label' => 'พฤศจิกายน'
                    ],
                    [
                        'attribute' => 'r12',
                        'label' => 'ธันวาคม'
                    ],
                ],
            ])
            ?>
            <p>***หมายเหตุ พาหนะที่ไม่ถูกจองจะไม่แสดงผลภายในกราฟและตารางสรุปผล</p>
        </div>
    </div>