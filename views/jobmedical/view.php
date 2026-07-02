<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'เครื่องมือทางการแพทย์'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jobcom-view">
<div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <p>
        <?= Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'ลบ'), ['delete', 'id' => $model->id], [
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
            'detail',
            'dateline',
            'send_by',
            'send_at',
            'repair_by',
            'repair_at',
            'repair_service',
            'repair_cost',
            'device_id',
            'jstatus_id',
            'type_id',
            'dep_id',
        ],
    ]) ?>
<p>
             <?= Html::a('<class="box-title"><i class="glyphicon glyphicon-print"></i> พิมพ์เอกสาร', ['print', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
			
            <a  class='btn btn-primary btn-ms' href="localhost/mhosp-office/web/index.php?r=jobmedical/index"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าหลัก</a>
            </p>
</div>
