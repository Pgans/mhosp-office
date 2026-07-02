<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = 'ใบส่งซ่อมหมายเลข: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ส่งซ่อมคอมพิวเตอร์'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="jobcom-view" style="font-family: 'Sarabun', sans-serif;">

    <div class="box box-info" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #00c0ef;">
        
        <div class="box-header with-border" style="background-color: #f9f9f9; padding: 15px;">
            <h3 class="box-title" style="font-weight: bold; color: #333;">
                <i class="fa fa-wrench text-info"></i> <?= Html::encode($this->title) ?>
            </h3>
            <div class="box-tools pull-right">
                <?= Html::a('<i class="fa fa-pencil"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm', 'style' => 'border-radius: 20px;']) ?>
                <?= Html::a('<i class="fa fa-trash"></i> ลบ', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'style' => 'border-radius: 20px;',
                    'data' => [
                        'confirm' => Yii::t('app', 'ยืนยันการลบรายการนี้?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="box-body" style="padding: 25px;">
            
            <div class="row">
                <div class="col-md-12">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered detail-view', 'style' => 'border-collapse: separate; border-spacing: 0; border-radius: 10px;'],
                        'attributes' => [
                            [
                                'attribute' => 'id',
                                'label' => 'เลขที่ใบสั่งซ่อม',
                                'contentOptions' => ['style' => 'width: 70%; font-weight: bold; color: #0073b7;'],
                            ],
                            [
                                'attribute' => 'detail',
                                'label' => 'รายละเอียดอาการเสีย',
                                'format' => 'ntext',
                            ],
                            [
                                'attribute' => 'dateline',
                                'label' => 'กำหนดเสร็จ',
                                'value' => function($model){ return $model->dateline ? date('d/m/Y', strtotime($model->dateline)) : '-'; }
                            ],
                            [
                                'label' => 'ข้อมูลผู้ส่งซ่อม',
                                'format' => 'raw',
                                'value' => function($model){
                                    return '<i class="fa fa-user"></i> ' . Html::encode($model->send_by) . 
                                           ' <br><small class="text-muted"><i class="fa fa-clock-o"></i> ' . $model->send_at . '</small>';
                                }
                            ],
                            [
                                'attribute' => 'updater.firstname',
                                'label' => 'ช่างผู้ดำเนินการ',
                                'value' => $model->updater ? $model->updater->firstname : '-',
                            ],
                            [
                                'attribute' => 'repair_at',
                                'label' => 'วันที่ซ่อมเสร็จ',
                            ],
                            [
                                'attribute' => 'repair_service',
                                'label' => 'การดำเนินการของช่าง',
                            ],
                            [
                                'attribute' => 'repair_cost',
                                'label' => 'ค่าใช้จ่าย',
                                'value' => number_format($model->repair_cost, 2) . ' บาท',
                                'contentOptions' => ['style' => 'color: #dd4b39; font-weight: bold;'],
                            ],
                            [
                                'attribute' => 'jstatus.status',
                                'label' => 'สถานะการดำเนินงาน',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<span class="badge" style="padding: 8px 15px; border-radius: 5px; font-size: 13px; background-color:' . ($model->jstatus->color ?? '#999') . ';">' . 
                                           ($model->jstatus->status ?? 'ไม่ระบุ') . '</span>'; 
                                },
                            ],
                            [
                                'attribute' => 'type.type',
                                'label' => 'ประเภทอุปกรณ์',
                            ],
                            [
                                'attribute' => 'Department.dep_name',
                                'label' => 'หน่วยงานที่ส่งซ่อม',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <hr style="border-top: 1px solid #eee;">

            <div class="row">
                <div class="col-md-12 text-center">
                    <?= Html::a('<i class="fa fa-print"></i> พิมพ์ใบรับซ่อม', ['print', 'id' => $model->id], [
                        'class' => 'btn btn-success btn-lg', 
                        'style' => 'border-radius: 30px; padding: 10px 30px; box-shadow: 0 4px 10px rgba(0,166,90,0.3);',
                        'target' => '_blank'
                    ]) ?>
                    
                    <?= Html::a('<i class="fa fa-arrow-left"></i> กลับหน้าหลัก', ['index'], [
                        'class' => 'btn btn-default btn-lg',
                        'style' => 'border-radius: 30px; padding: 10px 30px; margin-left: 10px;'
                    ]) ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* ปรับแต่ง Table Header ของ DetailView ให้ดูสะอาด */
    .detail-view th {
        background-color: #fcfcfc !important;
        width: 30%;
        color: #555;
        vertical-align: middle !important;
    }
    .detail-view td {
        vertical-align: middle !important;
    }
</style>