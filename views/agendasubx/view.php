<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Agendasubx */

$this->title = $model->sub_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendasubxes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agendasubx-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sub_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sub_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
   <!-- ปุ่มสำหรับดูไฟล์ -->
<?= Html::a('View File', ['view-file', 'id' => $model->sub_id], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>

<!-- ปุ่มสำหรับดาวน์โหลดไฟล์ -->
<?= Html::a('Download File', ['download-file', 'id' => $model->sub_id], ['class' => 'btn btn-success']) ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sub_id',
            'meeting_id',
            'agenda_id',
            'sub_topic',
            'sub_description:ntext',
            'department',
            'filename',
            'path',
            'create_date',
        ],
        
    ]) ?>

</div>
