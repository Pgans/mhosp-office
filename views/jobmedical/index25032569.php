<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('app', 'รายการส่งซ่อมเครื่องมือทางการแพทย์');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="jobcom-index" style="font-family: 'Sarabun', sans-serif; font-size: 15px; background-color: #f8fafb; padding: 10px;">
    
    <div class="box" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
        <div class="box-header with-border" style="background: #fff; padding: 20px;">
            <h3 class="box-title" style="font-weight: bold; color: #333;">
                <i class="fa fa-heartbeat text-danger"></i> <?= Html::encode($this->title) ?>
            </h3>
            <div class="pull-right">
                <?= Html::button('<i class="fa fa-plus-circle"></i> ' . Yii::t('app', 'เพิ่มรายการส่งซ่อม'), [
                    'class' => 'btn btn-success',
                    'id' => 'createButton',
                    'style' => 'border-radius: 25px; padding: 8px 20px; font-weight: bold; box-shadow: 0 4px 10px rgba(40,167,69,0.2); border: none;'
                ]) ?>
            </div>
        </div>

        <div class="box-body" style="padding: 0;">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => "<div style='padding: 10px 20px; color: #999; font-size: 13px;'>Showing {begin}-{end} of {totalCount} items.</div>",
                'tableOptions' => ['class' => 'table table-hover', 'style' => 'margin-bottom: 0; vertical-align: middle;'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '#',
                        'headerOptions' => ['style' => 'width: 50px; text-align: center; background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; color: #bbb;'],
                    ],
                    [
                        'attribute' => 'jstatus.status',
                        'label' => 'สถานะ',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 130px; background: #f9fbfd; text-align: center;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; text-align: center;'],
                        'value' => function ($model) {
                            $color = $model->jstatus->color ?? '#999';
                            return '<span class="badge" style="background: transparent; border: 1px solid '.$color.'; color: '.$color.'; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    <i class="fa fa-clock-o"></i> ' . ($model->jstatus->status ?? '-') . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'detail',
                        'label' => 'รายละเอียดการแจ้งซ่อม',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'],
                        'value' => function($model) {
                            return '<b>ID: ' . $model->id . '</b><br><small style="color: #888;">' . $model->detail . '</small>';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'send_by',
                        'label' => 'ผู้แจ้งซ่อม',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap;'],
                    ],
                    [
                        'attribute' => 'updater.firstname', 
                        'label' => 'ผู้แก้ไขล่าสุด',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; color: #3c8dbc; font-weight: 500;'],
                        'filter' => false
                    ],
                    [
                        'label' => 'ระยะเวลาใช้ไป',
                        'headerOptions' => ['style' => 'background: #f9fbfd; text-align: center;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; text-align: center; color: #e67e22; font-size: 13px; font-weight: bold;'],
                        'value' => function ($model) {
                            if ($model->repair_at && $model->send_at) {
                                $repairAt = new DateTime($model->repair_at);
                                $sendAt = new DateTime($model->send_at);
                                $interval = $repairAt->diff($sendAt);
                                return $interval->format('%d วัน %h ชม.');
                            }
                            return '-';
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'จัดการ',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center; background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; white-space: nowrap;'],
                        'template' => '{view} {update}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i> ดู', $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'style' => 'border-radius: 5px; color: #4a90e2; border: 1px solid #4a90e2; padding: 4px 10px; font-size: 12px; background: #fff;'
                                ]);
                            },
                            'update' => function($url, $model) {
                                return Html::a('<i class="fa fa-pencil"></i> แก้ไข', $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'style' => 'border-radius: 5px; color: #28a745; border: 1px solid #28a745; padding: 4px 10px; font-size: 12px; background: #fff; margin-left: 3px;'
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'header' => '<h4 style="font-weight: bold;"><i class="fa fa-plus text-success"></i> เพิ่มรายการส่งซ่อม</h4>',
    'id' => 'createModal',
    'size' => 'modal-lg',
    'options' => ['style' => 'border-radius: 15px;'],
]);
echo "<div id='createContent' style='padding: 10px;'></div>";
Modal::end();

$this->registerJs("
    $(function() {
        $('#createButton').click(function() {
            $('#createModal').modal('show')
                .find('#createContent')
                .load('" . Url::to(['create']) . "');
        });
    });
");
?>

<style>
    .table thead th {
        border-top: none !important;
        border-bottom: 1px solid #eee !important;
        color: #7f8c8d !important;
        font-weight: 500 !important;
        padding: 15px !important;
    }
    .table td {
        padding: 12px 15px !important;
        border-top: 1px solid #f4f4f4 !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f2f8ff !important;
        transition: 0.2s;
    }
    .badge i {
        margin-right: 4px;
    }
</style>