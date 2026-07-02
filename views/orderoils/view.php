<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orderoils */

$this->title = $model->oilorder_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orderoils'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orderoils-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->oilorder_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->oilorder_id], [
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
            'oilorder_id',
            'created_at',
            'fullname',
            'spray_id',
            'oils',
            'diagnosis',
            'province_id',
            'amphur_id',
            'district_id',
            'mooban_id',
            'anamai_id',
            'created_by',
            'updated_by',
            'd_update',
        ],
    ]) ?>

</div>
