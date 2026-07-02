<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Jobstatus;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\departmentjob;//

$this->title = 'รายการส่งซ่อมคอมพิวเตอร์';
?>

<div class="jobcom-index" style="font-family: 'Sarabun', sans-serif;">
    <div class="box box-info" style="border-radius: 15px; border-top: 5px solid #00c0ef;">
        <div class="box-header with-border">
            <h3 class="box-title" style="font-weight: bold; font-size: 18px;"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body" style="padding: 15px;">
            <p>
                <?= Html::button('<i class="fa fa-plus"></i> เพิ่มรายการส่งซ่อม', ['class' => 'btn btn-success', 'id' => 'createButton', 'style' => 'border-radius: 20px; font-size: 15px;']) ?>
                <span class="pull-right">
                    <?= Html::a('<i class="fa fa-calendar"></i> ปฏิทิน', ['calendar'], ['class' => 'btn btn-warning', 'style' => 'border-radius: 20px; font-size: 15px;']) ?>
                </span>
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-hover', 'style' => 'font-size: 15px;'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'width: 60px; white-space: nowrap;'],
                        'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; font-weight: bold;'],
                    ],
                    [
                        'attribute' => 'detail',
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap; max-width: 250px; overflow: hidden; text-overflow: ellipsis;'],
                    ],
                    [
                        'attribute' => 'send_by',
                        'contentOptions' => ['style' => 'vertical-align: middle; white-space: nowrap;'],
                    ],
					'repair_by',
					[
                'attribute' => 'dep_id',
                'label' => 'แผนกที่แจ้ง',
                'value' => function($model) {
                        return empty($model->department) ? null : $model->department->dep_name;
                 },
                'filter' => ArrayHelper::map(departmentjob::find()->asArray()->all(), 'dep_id', 'dep_name'),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width'=>'100%'
                    ],
                ],
            ],
				
                    [
                        'attribute' => 'jstatus.status',
                        'label' => 'สถานะ',
                        'format' => 'raw',
                        'filter' => ArrayHelper::map(Jobstatus::find()->all(), 'id', 'status'),
                        'contentOptions' => ['style' => 'vertical-align: middle; text-align: center; white-space: nowrap;'],
                        'value' => function ($model) {
                            return '<span class="badge" style="padding: 5px 10px; background-color:' . ($model->jstatus->color ?? '#999') . '">' . ($model->jstatus->status ?? '-') . '</span>';
                        },
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => 'จัดการ',
                        'template' => '{view} {update}', // ไม่มีปุ่มลบ
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; white-space: nowrap;'],
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-info btn-xs', 'style' => 'border-radius: 10px; padding: 4px 8px;']);
                            },
                            'update' => function($url, $model) {
                                return Html::a('<i class="fa fa-pencil"></i>', $url, ['class' => 'btn btn-success btn-xs', 'style' => 'border-radius: 10px; padding: 4px 8px;']);
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
    'header' => '<h4>เพิ่มรายการส่งซ่อม</h4>',
    'id' => 'createModal',
    'size' => 'modal-lg',
]);
echo "<div id='createContent'></div>";
Modal::end();

$this->registerJs("
    $('#createButton').click(function() {
        $('#createModal').modal('show').find('#createContent').load('" . Url::to(['create']) . "');
    });
");
?>