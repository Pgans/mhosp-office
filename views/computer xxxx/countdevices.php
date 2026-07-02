
<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุกรณ์ชนิดต่างๆ';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานจำนวนอุกรณ์แยกประเภท',
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
                return Html::a(Html::encode($name), ['computer/devicelist', 'cateid' => $cateid]);
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
        