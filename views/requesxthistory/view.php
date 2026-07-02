<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Requesxthistory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ขอประวัติ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="requesxthistory-view">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'no',
            'cid',
            'hn',
            [
                'attribute' => 'fullname',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'full_name',
                'label'=>'ผู้บันทึก',
                'value' => function ($model) {
                    return $model->createdBy ? $model->createdBy->firstname . ' ' . $model->createdBy->lastname : '';
                },
            ],
            [
                'attribute' => 'assemble.assemble_name',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',
            ],
            
            'created_at',
            [
                'attribute' => 'updated_by',
               // 'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'label' => 'ผู้พิมพ์',
                'value' => function ($model) {
                    return $model->updater ? $model->updater->firstname . ' ' . $model->updater->lastname : '';
                },
            ],
            'updated_at',
			'start_date',
			'end_date',
			'orther',
            [
                //'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'status.status',
                'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->status->color . '">' . $model->status->status . '</span>';
                },
            ],
            'day_want',
        ],
    ]) ?>

</div>
