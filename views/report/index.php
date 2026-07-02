<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'ตารางการจองพาหนะในเดือน'.$month;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rental-view">
    <div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-calendar"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('เดือนถัดไป', ['next'], ['class' => 'btn btn-warning'])?>
            </p>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' =>[
                    [
                        'attribute' => 'license',
                        'label' => 'เลขทะเบียน'
                    ],
                    [
                        'attribute' => 'start',
                        'label' => 'วันและเวลาออกเดินทาง'
                    ],
                    [
                        'attribute' => 'end',
                        'label' => 'วันและเวลากลับ'
                    ],
                    [
                        'attribute' => 'fn',
                        'label' => 'ชื่อผู้จอง'
                    ],
                    [
                        'attribute' => 'ln',
                        'label' => 'นามสกุลผู้จอง'
                    ],
                ]
            ])
            ?>
        </div>
    </div>
</div>