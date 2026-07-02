<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Permits */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ยืมเวชระเบียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permits-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'AN',
            'HN',
            'fullname',
            'treatments_id',
            'day_want',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'status_id',
        ],
    ]) ?>

</div>
