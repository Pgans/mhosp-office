<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title = '📅 บันทึกการสูตรคำนวณยาและติดตามรายเดือน';
?>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading"><h4><?= Html::encode($this->title) ?></h4></div>
        <div class="panel-body">

            <p>
                <?= Html::button('➕ เพิ่มข้อมูล', [
                    'value' => \yii\helpers\Url::to(['create']),
                    'class' => 'btn btn-success',
                    'id' => 'modalButton'
                ]) ?>
            </p>

            <?php Pjax::begin(['id' => 'pjax-grid']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => false,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'hn',
                    'start_month',
                    'month2',
                    'month3',
					'month4',
					'month5',
					'month6',
					'month7',
                    //'treatment_detail',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javascript:void(0)', [
                                    'class' => 'btn btn-xs btn-warning modalUpdate',
                                    'data-url' => $url,
                                ]);
                            }
                        ]
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4><i class="glyphicon glyphicon-edit"></i> สูตรคำนวนยา</h4>', // ใช้ header แทน title
]);
echo '<div id="modalContent"></div>';
Modal::end();
?>

<?php
$this->registerJs(<<<JS
// เปิด modal เพิ่ม
$('#modalButton').on('click', function() {
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});

// เปิด modal แก้ไข
$(document).on('click', '.modalUpdate', function() {
    var url = $(this).data('url');
    $('#modal').modal('show')
        .find('#modalContent')
        .load(url);
});

// ส่งฟอร์มผ่าน Ajax
$(document).on('beforeSubmit', '#tbmonthlytreatment-form', function() {
    var form = $(this);
    $.post(form.attr('action'), form.serialize())
        .done(function(result) {
            if (result.success) {
                $('#modal').modal('hide');
                $.pjax.reload({container: '#pjax-grid'});
            } else {
                $('#modalContent').html(result);
            }
        }).fail(function() {
           // alert('เกิดข้อผิดพลาด');
        });
    return false;
});
JS
);
?>
