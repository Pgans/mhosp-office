<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Repimport */

$this->title = $model->auto_id;
$this->params['breadcrumbs'][] = ['label' => 'Repimports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repimport-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->auto_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->auto_id], [
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
            'auto_id',
            'rep',
            'id',
            'train_id',
            'hn',
            'an',
            'pid',
            'fullname',
            'main',
            'regdate',
            'discharge',
            'ins',
            'pp',
            'errorcode',
            'sub',
        ],
    ]) ?>

</div>
