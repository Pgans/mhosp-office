<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุปกรณ์ชนิดต่างๆ';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานจำนวนอุปกรณ์แยกประเภท',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ]]
        )

        ?>