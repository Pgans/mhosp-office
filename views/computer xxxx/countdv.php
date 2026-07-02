<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานอุปกรณ์คอมพิวเตอร์ทั้งหมด';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานอุปกรณ์คอมพิวเตอร์ทั้งหมด',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'cateid',
            'header' => 'รหัสประเภท',
        ],
        [
            'attribute' => 'ประเภท',
            'format' => 'raw',
            'value' => function($model) {
                $cateid = $model['cateid'];
                $name = $model['ประเภท'];
                return Html::a(Html::encode($name), ['report/report01', 'cateid' => $cateid]);
            }
                ],
                        [
                    'attribute' => 'จำนวน',
                    'header' => 'จำนวน(เครื่อง)'
                ],
                        ]
       ] )


        ?>
            <div class="alert alert-danger">
                <?=$sql?>

            </div>
        