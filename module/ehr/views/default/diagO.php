
<?php

use kartik\grid\GridView;
use yii\i18n\Formatter;
$formatter = new Formatter();
?>

<div class="row">
    <div class="col-lg-2">
        <p class="text-right text-green"><a>วันที่รับบริการ :</a> </p>
        <p class="text-right text-green"><a>อาการสำคัญ : </a></p>
        <p class="text-right text-green"><a>สัญญาณชีพ :</a> </p>
    </div>
     <div class="col-lg-9">
        <p class="text-left text-red"><?=': '.$formatter->asDate($dateserv)?> เวลา : <?=$timeserv?> </p>
        <p class="text-left text-red">: BP = <?=$sbp.'/'.$dbp.' ,T='.$btemp.' ,P='.$pr.' ,R='.$rr?> </p>
    </div>
     <div class="col-lg-6">
        <p class="text-left "> <?= '<a>สถานที่รับบริการ /</a>'.$hospcode.' '.$hospname?> </p>
    </div> 
</div>
<div class="row">
    <div class="col-lg-12">
    <?php
    $gridColumns = [
            [
            'attribute' => 'diagcode',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'รหัสโรค'
        ],
            [
            'attribute' => 'diagename',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ชื่อโรค'
        ],
            [
            'attribute' => 'diagtype',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ประเภทวินิจฉัย'
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'autoXlFormat' => true,
        'export' => [
            'fontAwesome' => true,
            'showConfirmAlert' => false,
            'target' => GridView::TARGET_BLANK
        ],
        'columns' => $gridColumns,
        'resizableColumns' => true,
            //'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
    ]);
    ?>
    </div>
</div>