<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'DEATH-List';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/index']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ป่วยเสียชีวิตในโรงพยาบาล';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'รายงานผู้ป่วยเสียชีวิตในโรงพยาบาล '.date('Y-m-d')
            ],
    ]
  );

        ?>
        <div class="alert alert-danger">
            <?=$sql?>
        </div>
