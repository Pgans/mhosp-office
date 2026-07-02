
<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ='DEVICE_LIST';

//$this->params['breadcrumbs'][] = ['label' => 'รายงานอุปกรณ์', 'url' => ['computer/device59']];
$this->params['breadcrumbs'][] = 'รายงานจำนวนอุปกรณ์ซื้อใหม่';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานจำนวนอุปกรณ์ซื้อใหม่',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ]]
        )
        ?>