<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'รายละเอียดรายการซ่อม #' . $model->id;
?>

<div class="jobservice-view" style="font-family: 'Sarabun', sans-serif; padding: 20px;">
    <div class="box" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        <div class="box-header with-border">
            <h3 class="box-title" style="font-weight: bold;"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('<i class="fa fa-arrow-left"></i> กลับ', ['index'], ['class' => 'btn btn-default', 'style' => 'border-radius: 20px;']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'style' => 'border-radius: 20px;']) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped table-bordered detail-view', 'style' => 'font-size: 15px;'],
                'attributes' => [
                    'id',
                    'detail:ntext',
                    'send_by',
                    'send_at:datetime',
                    [
                        'label' => 'สถานะปัจจุบัน',
                        'format' => 'raw',
                        // ดึงค่าตรงๆ ไม่ใช้ Closure function เพื่อแก้ Error
                        'value' => $model->jstatus 
                            ? '<span class="badge" style="background: transparent; border: 1px solid '.$model->jstatus->color.'; color: '.$model->jstatus->color.'; padding: 8px 15px; border-radius: 20px;">'.$model->jstatus->status.'</span>' 
                            : '-',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>