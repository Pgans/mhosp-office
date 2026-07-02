<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'rep E-Claim';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<b>rep</b>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim แยกตามREP</a>',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'REP',
                       // 'header' => 'rep',
                    ],
                    [
                        'attribute' => 'HN',
                       // 'header' => 'เดือน',
                    ],
                    [
                        'attribute' => 'PID',
                       // 'header' => 'เดือน',
                    ],
                    [
                        'attribute'=>'DATEADM',
                    ],
                    [
                        'attribute' => 'FULLNAME',
                       // 'header' => 'เดือน',
                    ],
                    [
                        'attribute' => 'MAININSCL',
                       // 'header' => 'เดือน',
                    ],
                    
                    [
                        'attribute' => 'SUMS_SERVICEITEM',
                       // 'header' => 'เดือน',
                    ],
                       ]
                  ]);
                    ?>
                    
                    <div class="alert alert-danger"><?=$sql?> </div>
