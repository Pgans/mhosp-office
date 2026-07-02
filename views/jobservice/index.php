<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('app', 'รายการส่งซ่อมหน่วยซ่อมบำรุง');
?>

<div class="jobservice-index" style="font-family: 'Sarabun', sans-serif; background-color: #f8fafb; padding: 20px;">

    <div style="margin-bottom: 20px;">
        <?= Html::button('<i class="fa fa-plus-circle"></i> เพิ่มรายการส่งซ่อมใหม่', [
            'class' => 'btn btn-success',
            'id' => 'createButton',
            'style' => 'border-radius: 25px; padding: 10px 25px; font-weight: bold; background-color: #28a745; border: none; box-shadow: 0 4px 10px rgba(40,167,69,0.2); font-size: 15px;'
        ]) ?>
    </div>

    <div class="box" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; background: #fff;">
        <div class="box-header with-border" style="padding: 15px 20px; background: #fff;">
            <h3 class="box-title" style="font-size: 16px; font-weight: bold; color: #444;">
                <i class="fa fa-list-alt text-primary"></i> รายการส่งซ่อมทั้งหมด
            </h3>
        </div>

        <div class="box-body" style="padding: 0;">
            <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summary' => "<div style='padding: 10px 20px; color: #999; font-size: 13px;'>Showing {begin}-{end} of {totalCount} items.</div>",
    'tableOptions' => ['class' => 'table table-hover', 'style' => 'margin-bottom: 0; vertical-align: middle;'],
    
    // บังคับสลับสีแถวด้วย PHP (Row Options)
    'rowOptions' => function($model, $key, $index, $grid) {
        if ($index % 2 == 0) {
            return ['style' => 'background-color: #ffffff;']; // แถวคี่สีขาว
        } else {
            return ['style' => 'background-color: #f0f9fa;']; // แถวคู่สีเทาจาง (สลับสี)
        }
    },
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '#',
                        'headerOptions' => ['style' => 'width: 50px; text-align: center; background: #f9fbfd; color: #95a5a6;'],
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
                            // สไตล์ Badge แบบเส้นขอบ (Outline) เหมือนในรูปตัวอย่าง
                            return '<span class="badge" style="background: transparent; border: 1px solid '.$color.'; color: '.$color.'; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    <i class="fa fa-clock-o"></i> ' . ($model->jstatus->status ?? '-') . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'detail',
                        'label' => 'รายละเอียด / อาการ',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap; max-width: 300px; overflow: hidden; text-overflow: ellipsis;'],
                        'value' => function($model) {
                            return '<b>ID: ' . $model->id . '</b><br>' .
                                   '<small style="color: #888;">' . $model->detail . '</small>';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'send_at',
                        'label' => 'วันเวลาที่ส่ง',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap; color: #666; font-size: 14px;'],
                        'value' => function($model) {
                            return '<i class="fa fa-calendar-o text-info"></i> ' . date('d/m/Y H:i', strtotime($model->send_at));
                        },
                        'format' => 'raw',
                    ],
					'dateline',
					
                    [
                        'attribute' => 'send_by',
                        'label' => 'ผู้ส่งซ่อม',
                        'headerOptions' => ['style' => 'background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap;'],
                    ],
					
					'repair_by',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '',
                        'template' => '{view}', // เน้นปุ่มดูอย่างเดียวตามสไตล์รูปภาพ
                        'headerOptions' => ['style' => 'width: 80px; background: #f9fbfd;'],
                        'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i> ดู', $url, [
                                    'class' => 'btn btn-default btn-xs',
                                    'style' => 'border-radius: 5px; color: #4a90e2; border: 1px solid #4a90e2; padding: 4px 12px; font-size: 13px; background: #fff;'
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<style>
    /* CSS ตกแต่งให้เหมือนรูปภาพ */
    .table-hover tbody tr:hover {
        background-color: #f2f8ff !important;
        transition: 0.2s;
    }
    .table thead th {
        border-top: none !important;
        border-bottom: 1px solid #eee !important;
        font-weight: 500;
        color: #7f8c8d;
    }
    .table td {
        border-top: 1px solid #f4f4f4 !important;
        padding: 12px 15px !important;
    }
    /* ปรับแต่งช่อง Search */
    .grid-view .filters input {
        border-radius: 6px;
        border: 1px solid #e1e8ee;
        box-shadow: none;
        height: 34px;
    }
</style>

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
    $('#createButton').click(function() {
        $('#createModal').modal('show').find('#createContent').load('" . Url::to(['create']) . "');
    });
");
?>