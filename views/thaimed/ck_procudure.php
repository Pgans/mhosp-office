<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'ck_procudure';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/index']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ป่วยเสียชีวิตในโรงพยาบาล';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'ตรวจสอบกับรหัสหัตถการมาตรฐาน 42แฟ้ม V2.3 '.date('Y-m-d')
            ],
    ]
  );

        ?>
        <div class="alert alert-danger">
            <?=$sql?>
        </div>
